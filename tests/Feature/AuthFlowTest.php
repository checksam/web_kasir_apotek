<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Customer;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'kasir', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'pelanggan', 'guard_name' => 'web']);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_login(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin Apotek',
            'email' => 'admin@apotek.test',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        $user->assignRole('admin');

        $response = $this->post('/login', [
            'email' => 'admin@apotek.test',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertEquals('admin', session('user.role'));
        $this->assertEquals($user->id, session('user.id'));
    }

    public function test_kasir_can_login_to_cashier_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'kasir@apotek.test',
            'password' => 'kasir123',
            'role' => 'kasir',
        ]);
        $user->assignRole('kasir');

        $this->post('/login', [
            'email' => 'kasir@apotek.test',
            'password' => 'kasir123',
        ])->assertRedirect('/kasir/dashboard');
    }

    public function test_pelanggan_can_login_to_customer_dashboard(): void
    {
        $customer = Customer::create([
            'name' => 'Pelanggan Login',
            'phone' => '081111111111',
            'address' => 'Alamat login test',
        ]);
        $user = User::factory()->create([
            'email' => 'pelanggan@apotek.test',
            'password' => 'pelanggan123',
            'role' => 'pelanggan',
            'customer_id' => $customer->id,
        ]);
        $user->assignRole('pelanggan');

        $this->post('/login', [
            'email' => 'pelanggan@apotek.test',
            'password' => 'pelanggan123',
        ])->assertRedirect('/pelanggan/dashboard');

        $this->assertAuthenticatedAs($user);
        $this->assertSame($customer->id, $user->customer->id);
    }

    public function test_logout_requires_post_and_ends_the_session(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/logout')
            ->assertMethodNotAllowed();

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_user_without_spatie_role_cannot_login_using_legacy_role_column(): void
    {
        User::factory()->create([
            'email' => 'legacy-admin@apotek.test',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        $this->from('/login')->post('/login', [
            'email' => 'legacy-admin@apotek.test',
            'password' => 'admin123',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_role_seeder_stores_hashed_passwords_and_assigns_roles(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::where('email', 'admin@apotek.test')->firstOrFail();
        $kasir = User::where('email', 'kasir@apotek.test')->firstOrFail();

        $this->assertTrue(Hash::check('123098', $admin->password));
        $this->assertTrue(Hash::check('098123', $kasir->password));
        $pelanggan = User::where('email', 'pelanggan@apotek.test')->firstOrFail();
        $this->assertTrue(Hash::check('123456', $pelanggan->password));
        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue($kasir->hasRole('kasir'));
        $this->assertTrue($pelanggan->hasRole('pelanggan'));
        $this->assertNotNull($pelanggan->customer);
    }
}
