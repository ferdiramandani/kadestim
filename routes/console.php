<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('kadestim:create-admin', function () {
    $name = $this->ask('Nama admin');
    $email = $this->ask('Email admin');
    $password = $this->secret('Kata sandi admin (minimal 12 karakter)');

    if (strlen($password) < 12) {
        $this->error('Kata sandi harus minimal 12 karakter.');
        return 1;
    }

    if (User::where('email', $email)->exists()) {
        $this->error('Email tersebut sudah terdaftar.');
        return 1;
    }

    User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'admin',
        'is_active' => true,
    ]);

    $this->info('Akun admin berhasil dibuat. Simpan kredensial dengan aman.');
})->purpose('Membuat akun admin KADESTIM melalui terminal.');

Artisan::command('kadestim:about', function () {
    $this->info('KADESTIM — Platform Digital Kader Kesehatan Jawa Timur');
})->purpose('Menampilkan informasi singkat KADESTIM.');
