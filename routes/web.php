<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicContentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/edukasi', [PublicContentController::class, 'index'])->defaults('type', 'article')->name('education');
Route::get('/event', [PublicContentController::class, 'index'])->defaults('type', 'event')->name('events');
Route::get('/galeri', [PublicContentController::class, 'index'])->defaults('type', 'gallery')->name('gallery');
Route::get('/pembelajaran', [PublicContentController::class, 'index'])->defaults('type', 'learning')->name('learning');
Route::view('/tentang', 'pages.about')->name('about');
Route::get('/konten/{type}/{slug}', [PublicContentController::class, 'show'])->name('content.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('/konten', ContentController::class)->except(['show'])->parameters(['konten' => 'content'])->names('content');
    });
});
