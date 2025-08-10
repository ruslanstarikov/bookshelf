<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/game', [GameController::class, 'index'])->name('game.index');
Route::post('/game/round', [GameController::class, 'round'])->name('game.round');
Route::get('/game/reset', [GameController::class, 'reset'])->name('game.reset');
