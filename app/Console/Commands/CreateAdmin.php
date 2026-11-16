<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'kadestim:create-admin';

    protected $description = 'Create an administrator account for KADESTIM from the server terminal';

    public function handle(): int
    {
        $name = trim((string) $this->ask('Nama admin'));
        $email = strtolower(trim((string) $this->ask('Email admin')));

        $validator = Validator::make(
            ['name' => $name, 'email' => $email],
            ['name' => ['required', 'string', 'max:120'], 'email' => ['required', 'email', 'max:255', 'unique:users,email']]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        $password = (string) $this->secret('Kata sandi (minimal 12 karakter)');
        $confirmation = (string) $this->secret('Ulangi kata sandi');

        if (strlen($password) < 12 || ! hash_equals($password, $confirmation)) {
            $this->components->error('Kata sandi harus minimal 12 karakter dan kedua isian harus sama.');

            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->components->info('Akun admin berhasil dibuat. Simpan kredensial dengan aman.');

        return self::SUCCESS;
    }
}
