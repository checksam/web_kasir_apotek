<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\RolePermissionSeeder;
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
        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue($kasir->hasRole('kasir'));
    }
}
