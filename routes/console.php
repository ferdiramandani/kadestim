<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

Artisan::command('kadestim:about', function () {
    $this->info('KADESTIM — Platform Digital Kader Kesehatan Jawa Timur');
})->purpose('Menampilkan informasi singkat KADESTIM.');
