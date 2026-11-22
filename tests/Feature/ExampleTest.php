<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_public_landing_page_is_available_to_guests(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('PharmaPOS')
            ->assertSee('Sistem Manajemen Apotek yang')
            ->assertSee('Bersih, Cepat, dan Akurat')
            ->assertSee('Didesain Khusus untuk Standar Ketat Farmasi Indonesia')
            ->assertSee(route('login'));
    }
}
