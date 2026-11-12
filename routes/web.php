<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SkinController;
use App\Http\Controllers\SocialAuthController;
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

    Route::post('/profile/discord/unlink', [ProfileController::class, 'unlinkDiscord'])->name('profile.discord.unlink');
    Route::post('/profile/telegram/unlink', [ProfileController::class, 'unlinkTelegram'])->name('profile.telegram.unlink');

    Route::post('/profile/minecraft/code', [ProfileController::class, 'minecraftCode'])->middleware('throttle:10,1')->name('profile.minecraft.code');
    Route::post('/profile/minecraft/unlink', [ProfileController::class, 'minecraftUnlink'])->name('profile.minecraft.unlink');

    Route::get('/profile/skin', [SkinController::class, 'show'])->name('profile.skin');
    Route::post('/profile/skin', [SkinController::class, 'upload'])->middleware('throttle:10,1')->name('profile.skin.upload');
    Route::delete('/profile/skin', [SkinController::class, 'destroy'])->name('profile.skin.destroy');
    Route::get('/skins/preview/{skin}', [SkinController::class, 'preview'])->name('skins.preview');

    Route::post('/profile/top-up', [ProfileController::class, 'topUp'])->name('profile.top-up');
    Route::post('/profile/inventory/{item}/refund', [ProfileController::class, 'refund'])->name('profile.inventory.refund');
    Route::post('/profile/inventory/{item}/activate', [ProfileController::class, 'activateInventoryItem'])->name('profile.inventory.activate');
    Route::post('/store/buy-now', [StoreController::class, 'buyNow'])->name('store.buy-now');
});

// Новини
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

// PNG скіна за ніком (для клієнтського мода)
Route::get('/skins/{name}.png', [SkinController::class, 'png'])
    ->where('name', '[A-Za-z0-9_]{3,16}')
    ->name('skins.png');

// Discord / Telegram: привʼязка (у профілі) і вхід (для гостей)
Route::get('/auth/discord/redirect', [SocialAuthController::class, 'discordRedirect'])->name('auth.discord.redirect');
Route::get('/auth/discord/callback', [SocialAuthController::class, 'discordCallback'])->name('auth.discord.callback');
Route::get('/auth/telegram/callback', [SocialAuthController::class, 'telegramCallback'])->name('auth.telegram.callback');

// Адмін-панель: модератори — гравці, бани, скіни; адміни — ще ролі й новини
Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/role', [Admin\UserController::class, 'updateRole'])->name('users.role');
    Route::post('/users/{user}/ban', [Admin\UserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [Admin\UserController::class, 'unban'])->name('users.unban');

    Route::get('/skins', [Admin\SkinController::class, 'index'])->name('skins.index');
    Route::post('/skins/{skin}/approve', [Admin\SkinController::class, 'approve'])->name('skins.approve');
    Route::post('/skins/{skin}/reject', [Admin\SkinController::class, 'reject'])->name('skins.reject');
    Route::post('/skins/{skin}/resign', [Admin\SkinController::class, 'resign'])->name('skins.resign');

    Route::middleware('staff:admin')->group(function () {
        Route::resource('news', Admin\NewsController::class)->except('show');
    });
});
