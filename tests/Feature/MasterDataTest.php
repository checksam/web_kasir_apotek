<?php

namespace Tests\Feature;

use App\Models\BatchObat;
use App\Models\Item;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $kasir;

    protected function setUp(): void
    {
        parent::setUp();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $cashierRole = Role::create(['name' => 'kasir', 'guard_name' => 'web']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->admin->assignRole($adminRole);
        $this->kasir = User::factory()->create(['role' => 'kasir']);
        $this->kasir->assignRole($cashierRole);
    }

    public function test_admin_can_create_update_search_and_delete_supplier(): void
    {
        $this->actingAs($this->admin)->post(route('suppliers.store'), [
            'name' => 'PBF Sehat',
            'contact_person' => 'Dewi',
            'phone' => '081234567890',
            'address' => 'Jl. Sehat No. 1',
            'is_active' => '1',
        ])->assertRedirect(route('master.supplier.index'));

        $supplier = Supplier::firstOrFail();
        $this->assertTrue($supplier->is_active);

        $this->actingAs($this->admin)
            ->get(route('master.supplier.index', ['search' => 'Dewi']))
            ->assertOk()
            ->assertSee('PBF Sehat');

        $this->put(route('suppliers.update', $supplier), [
            'name' => 'PBF Sehat Baru',
            'contact_person' => 'Dewi Updated',
            'phone' => '081234567891',
            'address' => 'Jl. Sehat No. 2',
            'is_active' => '0',
        ])->assertRedirect(route('master.supplier.index'));

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => 'PBF Sehat Baru',
            'is_active' => false,
        ]);

        $this->delete(route('suppliers.destroy', $supplier))
            ->assertRedirect(route('master.supplier.index'));
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }

    public function test_supplier_with_purchase_history_cannot_be_deleted(): void
    {
        $supplier = $this->createSupplier();
        Pembelian::create([
            'supplier_id' => $supplier->id,
            'user_id' => $this->admin->id,
            'purchase_number' => 'INV-SUPPLIER-PROTECTED',
            'purchase_date' => today(),
            'total' => 1000,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('suppliers.destroy', $supplier))
            ->assertSessionHasErrors('supplier');
        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);
    }

    public function test_admin_can_create_update_and_delete_item_without_history(): void
    {
        $payload = [
            'item_code' => 'MED-NEW',
            'barcode' => '8991000000001',
            'name' => 'Obat Baru',
            'category' => 'Tablet',
            'unit' => 'Tablet',
            'selling_price' => '12500.00',
            'harga_beli' => '8000.00',
            'minimum_stock' => 8,
            'is_controlled' => '1',
            'is_active' => '1',
        ];
        $this->actingAs($this->admin)
            ->post(route('items.store'), $payload)
            ->assertRedirect(route('barang.index'));

        $item = Item::where('item_code', 'MED-NEW')->firstOrFail();
        $this->assertSame(0, $item->stock);
        $this->assertTrue($item->is_controlled);
        $this->assertSame('8000.00', $item->harga_beli);
        $this->actingAs($this->admin)
            ->get(route('barang.index'))
            ->assertOk()
            ->assertSee('Harga Beli')
            ->assertSee('8.000');

        $payload['name'] = 'Obat Diperbarui';
        $payload['minimum_stock'] = 10;
        $this->put(route('items.update', $item), $payload)
            ->assertRedirect(route('barang.index'));
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'Obat Diperbarui',
            'minimum_stock' => 10,
        ]);

        $this->delete(route('items.destroy', $item))->assertRedirect(route('barang.index'));
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    public function test_item_with_batch_or_transaction_history_cannot_be_deleted(): void
    {
        $item = $this->createItem();
        BatchObat::create([
            'item_id' => $item->id,
            'batch_number' => 'BATCH-DELETE-PROTECTED',
            'quantity' => 5,
            'received_at' => today(),
            'expiration_date' => today()->addYear(),
        ]);

        $this->actingAs($this->admin)
            ->delete(route('items.destroy', $item))
            ->assertSessionHasErrors('item');
        $this->assertDatabaseHas('items', ['id' => $item->id]);
    }

    public function test_completed_purchase_creates_batch_and_increments_item_stock(): void
    {
        $supplier = $this->createSupplier();
        $item = $this->createItem();

        $this->actingAs($this->admin)->post(route('purchases.store'), [
            'supplier_id' => $supplier->id,
            'purchase_date' => today()->toDateString(),
            'purchase_number' => 'INV-PURCHASE-001',
            'status' => 'completed',
            'items' => [[
                'item_id' => $item->id,
                'quantity' => 12,
                'unit_price' => '2000.00',
                'discount' => '1500.00',
                'batch_number' => 'BATCH-PURCHASE-001',
                'expiration_date' => today()->addYear()->toDateString(),
            ]],
        ])->assertRedirect(route('transactions.pembelian.index'));

        $purchase = Pembelian::with('details')->firstOrFail();
        $this->assertSame('22500.00', $purchase->total);
        $this->assertDatabaseHas('batch_obat', [
            'item_id' => $item->id,
            'batch_number' => 'BATCH-PURCHASE-001',
            'quantity' => 12,
            'received_at' => today()->toDateString().' 00:00:00',
        ]);
        $this->assertSame(12, $item->fresh()->stock);
        $this->assertSame('2000.00', $item->fresh()->harga_beli);
        $this->assertSame('22500.00', $purchase->details->first()->subtotal);

        $this->actingAs($this->admin)
            ->get(route('purchases.show', $purchase))
            ->assertOk()
            ->assertSee('INV-PURCHASE-001')
            ->assertSee('Obat QA')
            ->assertSee('BATCH-PURCHASE-001')
            ->assertSee('Harga Beli')
            ->assertSee('22.500,00');
    }

    public function test_pending_purchase_does_not_add_inventory_and_invalid_discount_is_rejected(): void
    {
        $supplier = $this->createSupplier();
        $item = $this->createItem();
        $payload = [
            'supplier_id' => $supplier->id,
            'purchase_date' => today()->toDateString(),
            'purchase_number' => 'INV-PURCHASE-PENDING',
            'status' => 'pending',
            'items' => [[
                'item_id' => $item->id,
                'quantity' => 3,
                'unit_price' => '10000.00',
                'discount' => '0',
                'batch_number' => 'BATCH-PENDING-001',
                'expiration_date' => today()->addYear()->toDateString(),
            ]],
        ];

        $this->actingAs($this->admin)->post(route('purchases.store'), $payload)
            ->assertRedirect(route('transactions.pembelian.index'));
        $this->assertSame(0, $item->fresh()->stock);
        $this->assertDatabaseMissing('batch_obat', ['batch_number' => 'BATCH-PENDING-001']);

        $payload['purchase_number'] = 'INV-PURCHASE-INVALID';
        $payload['items'][0]['discount'] = '30001.00';
        $payload['items'][0]['batch_number'] = 'BATCH-INVALID';
        $this->from(route('purchases.create'))
            ->post(route('purchases.store'), $payload)
            ->assertSessionHasErrors('items.0.discount');
        $this->assertDatabaseMissing('pembelian', ['purchase_number' => 'INV-PURCHASE-INVALID']);
    }

    public function test_only_admin_can_write_supplier_item_and_purchase_data(): void
    {
        $supplier = $this->createSupplier();
        $item = $this->createItem();
        $purchase = Pembelian::create([
            'supplier_id' => $supplier->id,
            'user_id' => $this->admin->id,
            'purchase_number' => 'INV-DETAIL-FORBIDDEN',
            'purchase_date' => today(),
            'total' => 0,
            'status' => 'pending',
        ]);
        $this->actingAs($this->kasir)
            ->post(route('suppliers.store'), [])
            ->assertForbidden();
        $this->actingAs($this->kasir)
            ->put(route('suppliers.update', $supplier), [])
            ->assertForbidden();
        $this->actingAs($this->kasir)
            ->delete(route('suppliers.destroy', $supplier))
            ->assertForbidden();
        $this->actingAs($this->kasir)
            ->post(route('items.store'), [])
            ->assertForbidden();
        $this->actingAs($this->kasir)
            ->put(route('items.update', $item), [])
            ->assertForbidden();
        $this->actingAs($this->kasir)
            ->delete(route('items.destroy', $item))
            ->assertForbidden();
        $this->actingAs($this->kasir)
            ->post(route('purchases.store'), [])
            ->assertForbidden();
        $this->actingAs($this->kasir)
            ->get(route('purchases.show', $purchase))
            ->assertForbidden();
        $this->assertDatabaseCount('suppliers', 1);
        $this->assertDatabaseCount('items', 1);
        $this->assertDatabaseCount('pembelian', 1);
    }

    public function test_admin_forms_render_and_cashier_cannot_open_them(): void
    {
        $supplier = $this->createSupplier();
        $item = $this->createItem();

        $this->actingAs($this->admin)
            ->get(route('suppliers.create'))
            ->assertOk()
            ->assertSee('Kontak Person');
        $this->actingAs($this->admin)
            ->get(route('items.create'))
            ->assertOk()
            ->assertSee('Status Obat Keras');
        $this->actingAs($this->admin)
            ->get(route('purchases.create'))
            ->assertOk()
            ->assertSee('Tanggal Pembelian')
            ->assertSee($supplier->name)
            ->assertSee($item->name);

        $this->actingAs($this->kasir)->get(route('suppliers.create'))->assertForbidden();
        $this->actingAs($this->kasir)->get(route('items.create'))->assertForbidden();
        $this->actingAs($this->kasir)->get(route('purchases.create'))->assertForbidden();
    }

    private function createSupplier(): Supplier
    {
        return Supplier::create([
            'name' => 'Supplier QA',
            'contact_person' => 'Kontak QA',
            'phone' => '081234567890',
            'address' => 'Alamat QA',
            'is_active' => true,
        ]);
    }

    private function createItem(): Item
    {
        return Item::create([
            'item_code' => 'MED-QA',
            'name' => 'Obat QA',
            'category' => 'Tablet',
            'unit' => 'Tablet',
            'selling_price' => '5000.00',
            'stock' => 0,
            'minimum_stock' => 2,
            'is_controlled' => false,
            'is_active' => true,
        ]);
    }
}
