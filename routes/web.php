<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SkinController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

// Публічні сторінки
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/world', [PageController::class, 'world'])->name('world');
Route::get('/guide', [PageController::class, 'guide'])->name('guide');
Route::get('/rules', [PageController::class, 'rules'])->name('rules');
Route::get('/support', [PageController::class, 'support'])->name('support');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// Старі адреси
Route::permanentRedirect('/about', '/world');
Route::permanentRedirect('/store', '/support');

// Новини
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    Route::post('/profile/username', [ProfileController::class, 'updateUsername'])->name('profile.username.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->middleware('throttle:10,1')->name('profile.password.update');

    Route::post('/profile/discord/unlink', [ProfileController::class, 'unlinkDiscord'])->name('profile.discord.unlink');
    Route::post('/profile/telegram/unlink', [ProfileController::class, 'unlinkTelegram'])->name('profile.telegram.unlink');

    Route::post('/profile/minecraft/code', [ProfileController::class, 'minecraftCode'])->middleware('throttle:10,1')->name('profile.minecraft.code');
    Route::post('/profile/minecraft/unlink', [ProfileController::class, 'minecraftUnlink'])->name('profile.minecraft.unlink');

    Route::get('/profile/skin', [SkinController::class, 'show'])->name('profile.skin');
    Route::post('/profile/skin', [SkinController::class, 'upload'])->middleware('throttle:10,1')->name('profile.skin.upload');
    Route::delete('/profile/skin', [SkinController::class, 'destroy'])->name('profile.skin.destroy');
    Route::get('/skins/preview/{skin}', [SkinController::class, 'preview'])->name('skins.preview');
});

// PNG скіна за ніком (для клієнтського мода й аватарів на сайті)
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
