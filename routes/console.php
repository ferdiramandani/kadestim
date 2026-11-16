<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

Artisan::command('kadestim:create-admin', function () {
    $name = trim((string) $this->ask('Nama admin'));
    $email = strtolower(trim((string) $this->ask('Email admin')));

    $validator = Validator::make(
        ['name' => $name, 'email' => $email],
        ['name' => ['required', 'string', 'max:120'], 'email' => ['required', 'email', 'max:255', 'unique:users,email']]
    );

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return 1;
    }

    $password = (string) $this->secret('Kata sandi (minimal 12 karakter)');
    $confirmation = (string) $this->secret('Ulangi kata sandi');

    if (strlen($password) < 12 || ! hash_equals($password, $confirmation)) {
        $this->error('Kata sandi harus minimal 12 karakter dan kedua isian harus sama.');

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

    return 0;
})->purpose('Membuat akun admin KADESTIM melalui terminal.');

Artisan::command('kadestim:about', function () {
    $this->info('KADESTIM — Platform Digital Kader Kesehatan Jawa Timur');
})->purpose('Menampilkan informasi singkat KADESTIM.');
