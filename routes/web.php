<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
})->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/rules', function () {
    return view('pages.rules');
})->name('rules');

Route::get('/store', [StoreController::class, 'index'])->name('store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');


    Route::post('/profile/username', [ProfileController::class, 'updateUsername'])->name('profile.username.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/email/verify', [ProfileController::class, 'verifyEmail'])->name('profile.email.verify');

    Route::post('/profile/discord/link', [ProfileController::class, 'linkDiscord'])->name('profile.discord.link');
    Route::post('/profile/discord/unlink', [ProfileController::class, 'unlinkDiscord'])->name('profile.discord.unlink');

    Route::post('/profile/telegram/link', [ProfileController::class, 'linkTelegram'])->name('profile.telegram.link');
    Route::post('/profile/telegram/unlink', [ProfileController::class, 'unlinkTelegram'])->name('profile.telegram.unlink');

    Route::post('/profile/top-up', [ProfileController::class, 'topUp'])->name('profile.top-up');
    Route::post('/profile/inventory/{item}/refund', [ProfileController::class, 'refund'])->name('profile.inventory.refund');
    Route::post('/profile/inventory/{item}/activate', [ProfileController::class, 'activateInventoryItem'])->name('profile.inventory.activate');
    Route::post('/store/buy-now', [StoreController::class, 'buyNow'])->name('store.buy-now');
});
