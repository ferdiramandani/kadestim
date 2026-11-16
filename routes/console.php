<?php

use Illuminate\\Support\\Facades\\Artisan;

Artisan::command('kadestim:about', function () {
    $this->info('KADESTIM — Platform Digital Kader Kesehatan Jawa Timur');
})->purpose('Menampilkan informasi singkat KADESTIM.');
