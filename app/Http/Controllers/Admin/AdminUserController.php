<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    private const ROLES = ['admin', 'super_admin', 'admin_event', 'admin_education', 'admin_gallery', 'admin_learning'];

    public function index(): View
    {
        return view('admin.users.index', ['users' => User::query()->orderBy('name')->paginate(20)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:'.implode(',', self::ROLES)],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        return back()->with('success', 'Akun admin berhasil ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:'.implode(',', self::ROLES)],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($user->is($request->user()) && (! $data['is_active'] || $data['role'] !== 'super_admin')) {
            return back()->withErrors(['account' => 'Kamu tidak dapat menonaktifkan atau menurunkan peran akun sendiri.']);
        }

        if ($user->role === 'super_admin' && ($data['role'] !== 'super_admin' || ! $data['is_active'])) {
            $remaining = User::where('role', 'super_admin')->where('is_active', true)->whereKeyNot($user->id)->exists();
            if (! $remaining) {
                return back()->withErrors(['account' => 'Harus tersedia minimal satu Super Admin aktif.']);
            }
        }

        $user->update($data);

        return back()->with('success', 'Data admin berhasil diperbarui.');
    }
}
