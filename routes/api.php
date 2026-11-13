<?php

use App\Http\Controllers\Api\GameController;
use Illuminate\Support\Facades\Route;

// API для плагінів сервера (заголовок X-Palermo-Token = PALERMO_GAME_API_TOKEN)
Route::middleware(['game.token', 'throttle:120,1'])->prefix('game')->group(function () {
    Route::get('/players/{name}', [GameController::class, 'player'])->where('name', '[A-Za-z0-9_]{3,16}');
    Route::post('/link', [GameController::class, 'link']);
});
