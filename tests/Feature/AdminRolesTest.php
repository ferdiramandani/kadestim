<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_specialist_admin_can_sign_in(): void
    {
        User::create([
            'name' => 'Admin Edukasi',
            'email' => 'education@example.test',
            'password' => Hash::make('a-very-long-test-password'),
            'role' => 'admin_education',
            'is_active' => true,
        ]);

        $this->post('/admin/login', [
            'email' => 'education@example.test',
            'password' => 'a-very-long-test-password',
        ])->assertRedirect('/admin');

        $this->get('/admin')->assertOk()->assertSee('Admin Edukasi');
    }

    public function test_only_super_admin_can_open_admin_management(): void
    {
        $admin = User::create([
            'name' => 'Admin Umum',
            'email' => 'general@example.test',
            'password' => Hash::make('a-very-long-test-password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->get('/admin/admins')->assertForbidden();
    }

    public function test_super_admin_can_open_admin_management(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@example.test',
            'password' => Hash::make('a-very-long-test-password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->get('/admin/admins')->assertOk()->assertSee('Tambah admin');
    }
}
