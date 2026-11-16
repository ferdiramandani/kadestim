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

    public function test_inactive_admin_cannot_sign_in(): void
    {
        User::create([
            'name' => 'Admin Nonaktif',
            'email' => 'inactive@example.test',
            'password' => Hash::make('a-very-long-test-password'),
            'role' => 'admin',
            'is_active' => false,
        ]);

        $this->post('/admin/login', [
            'email' => 'inactive@example.test',
            'password' => 'a-very-long-test-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_admin_user_cannot_open_admin_dashboard(): void
    {
        $user = User::create([
            'name' => 'Pengunjung',
            'email' => 'visitor@example.test',
            'password' => Hash::make('a-very-long-test-password'),
            'role' => 'visitor',
            'is_active' => true,
        ]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
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
