<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_requires_authentication(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_sign_in_and_open_dashboard(): void
    {
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@example.test',
            'password' => Hash::make('a-very-long-test-password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@example.test',
            'password' => 'a-very-long-test-password',
        ])->assertRedirect('/admin');

        $this->get('/admin')->assertOk()->assertSee('Admin Test');
    }
}
