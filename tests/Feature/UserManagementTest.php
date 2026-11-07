<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $kasir;

    protected function setUp(): void
    {
        parent::setUp();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $kasirRole = Role::create(['name' => 'kasir', 'guard_name' => 'web']);

        $this->admin = User::factory()->create([
            'name' => 'Admin QA',
            'email' => 'admin-users@apotek.test',
            'username' => 'admin-users',
            'role' => 'admin',
            'is_active' => true,
        ]);
        $this->admin->assignRole($adminRole);

        $this->kasir = User::factory()->create([
            'name' => 'Kasir QA',
            'email' => 'kasir-users@apotek.test',
            'username' => 'kasir-users',
            'role' => 'kasir',
            'is_active' => true,
        ]);
        $this->kasir->assignRole($kasirRole);
    }

    public function test_admin_can_view_staff_list_and_active_access_statistics(): void
    {
        $inactiveAdmin = User::factory()->create([
            'name' => 'Admin Nonaktif',
            'email' => 'inactive-admin@apotek.test',
            'username' => 'inactive-admin',
            'role' => 'admin',
            'is_active' => false,
        ]);
        $inactiveAdmin->assignRole('admin');

        $inactiveCashier = User::factory()->create([
            'name' => 'Kasir Nonaktif',
            'email' => 'inactive-cashier@apotek.test',
            'username' => 'inactive-cashier',
            'role' => 'kasir',
            'is_active' => false,
        ]);
        $inactiveCashier->assignRole('kasir');

        $this->actingAs($this->admin)
            ->get(route('users.index'))
            ->assertOk()
            ->assertSee('Total Akses Aktif')
            ->assertSee('Admin QA')
            ->assertSee('Kasir QA')
            ->assertSee('Admin Nonaktif')
            ->assertSee('Kasir Nonaktif')
            ->assertSee('Nonaktif')
            ->assertViewHas('activeAdmins', 1)
            ->assertViewHas('activeCashiers', 1);
    }

    public function test_admin_can_add_staff_user_with_spatie_role_and_hashed_password(): void
    {
        $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'Siti Kasir',
            'username' => 'siti.kasir',
            'email' => 'siti@apotek.test',
            'role' => 'kasir',
            'password' => 'strong-pass-123',
            'password_confirmation' => 'strong-pass-123',
            'is_active' => '1',
        ])->assertRedirect(route('users.index'));

        $user = User::where('email', 'siti@apotek.test')->firstOrFail();
        $this->assertSame('siti.kasir', $user->username);
        $this->assertTrue($user->hasRole('kasir'));
        $this->assertTrue(Hash::check('strong-pass-123', $user->password));
        $this->assertTrue($user->is_active);
    }

    public function test_admin_can_search_edit_role_and_change_user_status(): void
    {
        $this->actingAs($this->admin)
            ->get(route('users.index', ['search' => 'kasir-users']))
            ->assertOk()
            ->assertSee('Kasir QA')
            ->assertSee('Menampilkan 1-1 dari 1 pengguna');

        $this->put(route('users.update', $this->kasir), [
            'name' => 'Kasir QA Updated',
            'username' => 'kasir-users',
            'email' => 'kasir-users@apotek.test',
            'role' => 'admin',
            'is_active' => '0',
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect(route('users.index'));

        $this->kasir->refresh();
        $this->assertTrue($this->kasir->hasRole('admin'));
        $this->assertFalse($this->kasir->is_active);
        $this->assertSame('Kasir QA Updated', $this->kasir->name);
    }

    public function test_last_active_admin_cannot_be_demoted_or_deactivated(): void
    {
        $this->actingAs($this->admin)
            ->from(route('users.edit', $this->admin))
            ->put(route('users.update', $this->admin), [
                'name' => $this->admin->name,
                'username' => $this->admin->username,
                'email' => $this->admin->email,
                'role' => 'kasir',
                'is_active' => '1',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertRedirect(route('users.edit', $this->admin))
            ->assertSessionHasErrors('role');

        $this->actingAs($this->admin)
            ->from(route('users.edit', $this->admin))
            ->put(route('users.update', $this->admin), [
                'name' => $this->admin->name,
                'username' => $this->admin->username,
                'email' => $this->admin->email,
                'role' => 'admin',
                'is_active' => '0',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertRedirect(route('users.edit', $this->admin))
            ->assertSessionHasErrors('role');

        $this->assertTrue($this->admin->fresh()->hasRole('admin'));
        $this->assertTrue($this->admin->fresh()->is_active);
    }

    public function test_invalid_role_duplicate_username_and_malformed_username_are_rejected(): void
    {
        $basePayload = [
            'name' => 'Staff Invalid',
            'username' => 'admin-users',
            'email' => 'staff-invalid@apotek.test',
            'role' => 'pelanggan',
            'password' => 'strong-pass-123',
            'password_confirmation' => 'strong-pass-123',
        ];

        $this->actingAs($this->admin)
            ->from(route('users.create'))
            ->post(route('users.store'), $basePayload)
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['username', 'role']);

        $basePayload['username'] = 'staff name';
        $basePayload['role'] = 'kasir';

        $this->from(route('users.create'))
            ->post(route('users.store'), $basePayload)
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors('username');
    }

    public function test_only_admin_can_manage_users(): void
    {
        $this->get(route('users.index'))->assertRedirect(route('login'));
        $this->actingAs($this->kasir)->get(route('users.index'))->assertForbidden();
        $this->actingAs($this->kasir)->post(route('users.store'), [])->assertForbidden();
    }

    public function test_inactive_staff_cannot_log_in_or_continue_using_an_existing_session(): void
    {
        $this->kasir->update(['is_active' => false]);

        $this->from(route('login'))->post(route('login.post'), [
            'email' => $this->kasir->email,
            'password' => 'password',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();

        $this->actingAs($this->kasir)
            ->get(route('dashboard'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}