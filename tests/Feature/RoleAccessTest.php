<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $kasir;

    private User $pelanggan;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $kasirRole = Role::create([
            'name' => 'kasir',
            'guard_name' => 'web',
        ]);

        $pelangganRole = Role::create([
            'name' => 'pelanggan',
            'guard_name' => 'web',
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin-test@apotek.test',
            'role' => 'admin',
        ]);
        $this->admin->assignRole($adminRole);

        $this->kasir = User::factory()->create([
            'name' => 'Kasir Test',
            'email' => 'kasir-test@apotek.test',
            'role' => 'kasir',
        ]);
        $this->kasir->assignRole($kasirRole);

        $customer = Customer::create([
            'name' => 'Pelanggan Test',
            'phone' => '081234567890',
            'address' => 'Alamat test',
        ]);
        $this->pelanggan = User::factory()->create([
            'name' => 'Pelanggan Test',
            'email' => 'pelanggan-test@apotek.test',
            'role' => 'pelanggan',
            'customer_id' => $customer->id,
        ]);
        $this->pelanggan->assignRole($pelangganRole);
    }

    public function test_admin_can_access_every_registered_application_page(): void
    {
        $adminPages = [
            'admin.dashboard',
            'dashboard',
            'master.customer.index',
            'master.supplier.index',
            'barang.index',
            'transactions.pembelian.index',
            'transactions.penjualan.index',
            'reports.pembelian',
            'reports.penjualan',
        ];

        foreach ($adminPages as $routeName) {
            $response = $this->actingAs($this->admin)->get(route($routeName));

            $this->assertSame(200, $response->status(), $routeName.': '.$response->exception?->getMessage());
        }
    }

    public function test_kasir_can_access_cashier_pages(): void
    {
        $cashierPages = [
            'kasir.dashboard',
            'dashboard',
            'master.customer.index',
            'barang.index',
            'transactions.penjualan.index',
            'reports.penjualan',
        ];

        foreach ($cashierPages as $routeName) {
            $response = $this->actingAs($this->kasir)->get(route($routeName));

            $this->assertSame(200, $response->status(), $routeName.': '.$response->exception?->getMessage());
        }
    }

    public function test_pelanggan_can_access_only_their_dashboard(): void
    {
        $this->actingAs($this->pelanggan)
            ->get(route('pelanggan.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Pelanggan');

        $this->assertSame('pelanggan', $this->pelanggan->getRoleNames()->first());
        $this->assertNotNull($this->pelanggan->customer);
    }

    public function test_pelanggan_is_forbidden_from_admin_and_kasir_pages(): void
    {
        $staffPages = [
            'admin.dashboard',
            'kasir.dashboard',
            'dashboard',
            'master.supplier.index',
            'master.customer.index',
            'barang.index',
            'transactions.pembelian.index',
            'transactions.penjualan.index',
            'kasir.penjualan',
            'reports.pembelian',
            'reports.penjualan',
        ];

        foreach ($staffPages as $routeName) {
            $this->actingAs($this->pelanggan)
                ->get(route($routeName))
                ->assertForbidden();
        }

        $this->actingAs($this->pelanggan)
            ->get(route('admin.dashboard'))
            ->assertSee('Akses Ditolak')
            ->assertSee('Kembali ke dashboard')
            ->assertDontSee('Stack trace');
    }

    public function test_kasir_is_forbidden_from_admin_pages(): void
    {
        $adminOnlyPages = [
            'admin.dashboard',
            'master.supplier.index',
            'transactions.pembelian.index',
            'reports.pembelian',
        ];

        foreach ($adminOnlyPages as $routeName) {
            $this->actingAs($this->kasir)
                ->get(route($routeName))
                ->assertForbidden();
        }

        $this->actingAs($this->kasir)
            ->get(route('reports.pembelian'))
            ->assertForbidden()
            ->assertSee('Akses Ditolak')
            ->assertSee('Kembali ke dashboard')
            ->assertDontSee('Stack trace');
    }

    public function test_guest_is_redirected_to_login_from_protected_pages(): void
    {
        $protectedPages = [
            'admin.dashboard',
            'kasir.dashboard',
            'pelanggan.dashboard',
            'dashboard',
            'master.customer.index',
            'master.supplier.index',
            'barang.index',
            'transactions.pembelian.index',
            'transactions.penjualan.index',
            'reports.pembelian',
            'reports.penjualan',
        ];

        foreach ($protectedPages as $routeName) {
            $this->get(route($routeName))
                ->assertRedirect(route('login'));
        }
    }

    public function test_user_can_authenticate_with_the_login_form(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'kasir-test@apotek.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('kasir.dashboard'));
        $this->assertAuthenticatedAs($this->kasir);
        $this->assertTrue(auth()->user()->hasRole('kasir'));
    }

    public function test_dashboard_profile_dropdown_contains_a_csrf_protected_post_logout_form(): void
    {
        $this->actingAs($this->kasir)
            ->get(route('kasir.dashboard'))
            ->assertOk()
            ->assertSee('aria-haspopup="true"', false)
            ->assertSee('id="profile-menu"', false)
            ->assertSee('Logout')
            ->assertSee('action="'.route('logout').'"', false)
            ->assertSee('name="_token"', false);

        $this->post(route('logout'))
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
