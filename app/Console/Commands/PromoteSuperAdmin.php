<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteSuperAdmin extends Command
{
    protected $signature = 'kadestim:promote-super-admin {email}';

    protected $description = 'Promote an existing KADESTIM admin account to Super Admin';

    public function handle(): int
    {
        $user = User::where('email', strtolower(trim((string) $this->argument('email'))))->first();

        if (! $user) {
            $this->components->error('Akun dengan email tersebut tidak ditemukan.');

            return self::FAILURE;
        }

        if (! $this->confirm('Jadikan '.$user->email.' sebagai Super Admin?', false)) {
            $this->components->info('Perubahan dibatalkan.');

            return self::SUCCESS;
        }

        $user->forceFill(['role' => 'super_admin', 'is_active' => true])->save();

        $this->components->info('Akun berhasil menjadi Super Admin.');

        return self::SUCCESS;
    }
}
