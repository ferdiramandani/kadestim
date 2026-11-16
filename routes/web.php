<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/edukasi', 'pages.education')->name('education');
Route::view('/event', 'pages.events')->name('events');
Route::view('/galeri', 'pages.gallery')->name('gallery');
Route::view('/pembelajaran', 'pages.learning')->name('learning');
Route::view('/tentang', 'pages.about')->name('about');

// Login admin dan dashboard akan diaktifkan setelah autentikasi backend dipasang.
Route::view('/admin/login', 'admin.login')->name('admin.login');
