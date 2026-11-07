<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $kasir;

    private User $pelanggan;

    protected function setUp(): void
    {
        parent::setUp();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $kasirRole = Role::create(['name' => 'kasir', 'guard_name' => 'web']);
        $pelangganRole = Role::create(['name' => 'pelanggan', 'guard_name' => 'web']);

        $this->admin = User::factory()->create([
            'name' => 'Admin QA',
            'email' => 'admin-setting@apotek.test',
            'username' => 'admin-setting',
            'role' => 'admin',
        ]);
        $this->admin->assignRole($adminRole);

        $this->kasir = User::factory()->create([
            'name' => 'Kasir QA',
            'email' => 'kasir-setting@apotek.test',
            'username' => 'kasir-setting',
            'role' => 'kasir',
        ]);
        $this->kasir->assignRole($kasirRole);

        $this->pelanggan = User::factory()->create([
            'name' => 'Pelanggan QA',
            'email' => 'pelanggan-setting@apotek.test',
            'username' => 'pelanggan-setting',
            'role' => 'pelanggan',
        ]);
        $this->pelanggan->assignRole($pelangganRole);
    }

    public function test_admin_can_save_pharmacy_name_vat_and_packaging_fee(): void
    {
        $this->actingAs($this->admin)
            ->get(route('settings.edit'))
            ->assertOk()
            ->assertSee('Profil Apotek')
            ->assertSee('Pajak & Biaya');

        $this->put(route('settings.update'), [
            'pharmacy_name' => 'Apotek QA Utama',
            'address' => 'Jl. Pengujian No. 1',
            'phone' => '0215550101',
            'official_email' => 'info@apotek-qa.test',
            'receipt_footer' => 'Terima kasih.',
            'show_pharmacist_name' => '1',
            'vat_percent' => '11.50',
            'default_packaging_fee' => '3000',
        ])->assertRedirect(route('settings.edit'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('settings', [
            'id' => 1,
            'pharmacy_name' => 'Apotek QA Utama',
            'vat_percent' => '11.50',
            'default_packaging_fee' => 3000,
        ]);
    }

    public function test_kasir_and_pelanggan_receive_forbidden_when_updating_settings(): void
    {
        $payload = [
            'pharmacy_name' => 'Unauthorized Update',
            'vat_percent' => '11',
            'default_packaging_fee' => '2500',
        ];

        $this->actingAs($this->kasir)
            ->put(route('settings.update'), $payload)
            ->assertForbidden();

        $this->actingAs($this->pelanggan)
            ->put(route('settings.update'), $payload)
            ->assertForbidden();

        $this->assertDatabaseCount('settings', 0);
    }

    public function test_invalid_settings_are_rejected_without_overwriting_saved_values(): void
    {
        $this->actingAs($this->admin)->get(route('settings.edit'));

        $this->from(route('settings.edit'))->put(route('settings.update'), [
            'pharmacy_name' => 'Invalid Update',
            'official_email' => 'not-an-email',
            'vat_percent' => '101',
            'default_packaging_fee' => '-1',
        ])->assertRedirect(route('settings.edit'))
            ->assertSessionHasErrors(['official_email', 'vat_percent', 'default_packaging_fee']);

        $this->assertDatabaseHas('settings', [
            'id' => 1,
            'pharmacy_name' => 'MediFlow Farma Utama',
        ]);
    }

    public function test_guest_is_redirected_from_settings(): void
    {
        $this->get(route('settings.edit'))->assertRedirect(route('login'));
        $this->put(route('settings.update'), [
            'pharmacy_name' => 'Guest Update',
            'vat_percent' => 11,
            'default_packaging_fee' => 100,
        ])->assertRedirect(route('login'));
    }
}