<?php

namespace Tests\Feature;

use App\Models\BatchObat;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Penjualan;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PenjualanTest extends TestCase
{
    use RefreshDatabase;

    private User $kasir;

    private User $pelanggan;

    protected function setUp(): void
    {
        parent::setUp();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $kasirRole = Role::create(['name' => 'kasir', 'guard_name' => 'web']);
        $pelangganRole = Role::create(['name' => 'pelanggan', 'guard_name' => 'web']);

        $this->kasir = User::factory()->create(['role' => 'kasir']);
        $this->kasir->assignRole($kasirRole);

        $this->pelanggan = User::factory()->create(['role' => 'pelanggan']);
        $this->pelanggan->assignRole($pelangganRole);
    }

    public function test_kasir_can_checkout_and_stock_is_taken_from_batches_in_fifo_order(): void
    {
        $item = Item::create([
            'item_code' => 'MED-FIFO',
            'barcode' => '899000000001',
            'name' => 'Obat FIFO',
            'category' => 'Tablet',
            'selling_price' => '10000.00',
            'stock' => 7,
        ]);
        $olderBatch = BatchObat::create([
            'item_id' => $item->id,
            'batch_number' => 'BATCH-OLDER',
            'quantity' => 2,
            'received_at' => today()->subDays(10),
            'expiration_date' => today()->addYear(),
        ]);
        $newerBatch = BatchObat::create([
            'item_id' => $item->id,
            'batch_number' => 'BATCH-NEWER',
            'quantity' => 5,
            'received_at' => today()->subDays(2),
            'expiration_date' => today()->addYear(),
        ]);
        $customer = Customer::create(['name' => 'Pelanggan POS', 'phone' => '081111000001']);
        Setting::create([
            'pharmacy_name' => 'Apotek Test',
            'vat_percent' => '11.00',
            'default_packaging_fee' => 1500,
        ]);

        $this->actingAs($this->kasir)
            ->get(route('kasir.penjualan', ['search' => '899000000001']))
            ->assertOk()
            ->assertSee('Obat FIFO');

        $this->actingAs($this->kasir)
            ->post(route('kasir.penjualan.checkout'), [
                'items' => [['item_id' => $item->id, 'quantity' => 4]],
                'customer_id' => $customer->id,
                'discount' => '2000',
                'amount_paid' => '50000',
            ])
            ->assertRedirect(route('kasir.penjualan'))
            ->assertSessionHas('status');

        $sale = Penjualan::with('details')->firstOrFail();
        $this->assertSame($this->kasir->id, $sale->user_id);
        $this->assertSame($customer->id, $sale->customer_id);
        $this->assertSame('completed', $sale->status);
        $this->assertSame('40000.00', $sale->subtotal);
        $this->assertSame('2000.00', $sale->discount);
        $this->assertSame('4180.00', $sale->tax);
        $this->assertSame('1500.00', $sale->packaging_fee);
        $this->assertSame('43680.00', $sale->total);
        $this->assertSame('6320.00', $sale->change);
        $this->assertSame(2, $sale->details->count());
        $this->assertSame($olderBatch->id, $sale->details[0]->batch_obat_id);
        $this->assertSame(2, $sale->details[0]->quantity);
        $this->assertSame($newerBatch->id, $sale->details[1]->batch_obat_id);
        $this->assertSame(2, $sale->details[1]->quantity);
        $this->assertSame(0, $olderBatch->fresh()->quantity);
        $this->assertSame(3, $newerBatch->fresh()->quantity);
        $this->assertSame(3, $item->fresh()->stock);
    }

    public function test_checkout_is_rejected_if_stock_is_missing_or_insufficient_without_partial_writes(): void
    {
        $item = Item::create([
            'item_code' => 'MED-LOW',
            'name' => 'Obat Stok Rendah',
            'category' => 'Tablet',
            'selling_price' => '5000.00',
            'stock' => 2,
        ]);
        $batch = BatchObat::create([
            'item_id' => $item->id,
            'batch_number' => 'BATCH-LOW',
            'quantity' => 2,
            'received_at' => today(),
            'expiration_date' => today()->addYear(),
        ]);

        $this->actingAs($this->kasir)
            ->from(route('kasir.penjualan'))
            ->post(route('kasir.penjualan.checkout'), [
                'items' => [['item_id' => $item->id, 'quantity' => 3]],
                'discount' => '0',
                'amount_paid' => '20000',
            ])
            ->assertRedirect(route('kasir.penjualan'))
            ->assertSessionHasErrors('items');

        $this->assertDatabaseCount('penjualan', 0);
        $this->assertDatabaseCount('detail_penjualan', 0);
        $this->assertSame(2, $batch->fresh()->quantity);
        $this->assertSame(2, $item->fresh()->stock);
    }

    public function test_checkout_is_rejected_when_item_has_no_batch_stock(): void
    {
        $item = Item::create([
            'item_code' => 'MED-EMPTY',
            'name' => 'Obat Habis',
            'category' => 'Tablet',
            'selling_price' => '5000.00',
            'stock' => 0,
        ]);

        $this->actingAs($this->kasir)
            ->from(route('kasir.penjualan'))
            ->post(route('kasir.penjualan.checkout'), [
                'items' => [['item_id' => $item->id, 'quantity' => 1]],
                'discount' => '0',
                'amount_paid' => '10000',
            ])
            ->assertRedirect(route('kasir.penjualan'))
            ->assertSessionHasErrors('items');

        $this->assertDatabaseCount('penjualan', 0);
        $this->assertDatabaseCount('detail_penjualan', 0);
        $this->assertSame(0, $item->fresh()->stock);
    }

    public function test_expired_batches_are_not_counted_as_sellable_stock(): void
    {
        $item = Item::create([
            'item_code' => 'MED-EXPIRED',
            'name' => 'Obat Kedaluwarsa',
            'category' => 'Tablet',
            'selling_price' => '5000.00',
            'stock' => 1,
        ]);
        BatchObat::create([
            'item_id' => $item->id,
            'batch_number' => 'BATCH-EXPIRED',
            'quantity' => 1,
            'received_at' => today()->subYear(),
            'expiration_date' => today()->subDay(),
        ]);

        $this->actingAs($this->kasir)
            ->from(route('kasir.penjualan'))
            ->post(route('kasir.penjualan.checkout'), [
                'items' => [['item_id' => $item->id, 'quantity' => 1]],
                'discount' => '0',
                'amount_paid' => '10000',
            ])
            ->assertRedirect(route('kasir.penjualan'))
            ->assertSessionHasErrors('items');

        $this->assertDatabaseCount('penjualan', 0);
    }

    public function test_pelanggan_is_forbidden_from_accessing_cashier_pos_and_checkout(): void
    {
        $this->actingAs($this->pelanggan)
            ->get(route('kasir.penjualan'))
            ->assertForbidden();

        $this->actingAs($this->pelanggan)
            ->post(route('kasir.penjualan.checkout'), [])
            ->assertForbidden();

        $this->assertDatabaseCount('penjualan', 0);
    }

    public function test_payment_shortfall_and_discount_over_subtotal_are_rejected(): void
    {
        $item = Item::create([
            'item_code' => 'MED-PAY',
            'name' => 'Obat Pembayaran',
            'category' => 'Tablet',
            'selling_price' => '5000.00',
            'stock' => 2,
        ]);
        BatchObat::create([
            'item_id' => $item->id,
            'batch_number' => 'BATCH-PAY',
            'quantity' => 2,
            'received_at' => today(),
            'expiration_date' => today()->addYear(),
        ]);
        Setting::create([
            'pharmacy_name' => 'Apotek Test',
            'vat_percent' => '0.00',
            'default_packaging_fee' => 0,
        ]);

        $payload = [
            'items' => [['item_id' => $item->id, 'quantity' => 1]],
            'discount' => '0',
            'amount_paid' => '4999',
        ];
        $this->actingAs($this->kasir)
            ->from(route('kasir.penjualan'))
            ->post(route('kasir.penjualan.checkout'), $payload)
            ->assertSessionHasErrors('amount_paid');

        $payload['discount'] = '5001';
        $payload['amount_paid'] = '10000';
        $this->from(route('kasir.penjualan'))
            ->post(route('kasir.penjualan.checkout'), $payload)
            ->assertSessionHasErrors('discount');

        $this->assertDatabaseCount('penjualan', 0);
        $this->assertSame(2, $item->batches()->first()->fresh()->quantity);
    }
}
