<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

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

        $response = $this->post('/login', [
            'email' => 'admin@apotek.test',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertEquals('admin', session('user.role'));
        $this->assertEquals($user->id, session('user.id'));
    }
}
