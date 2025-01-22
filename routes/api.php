<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\TrainerController;
use App\Http\Middleware\JwtAuthorize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Configuração das rotas

// no auth
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/signin', [AuthController::class, 'login']);

Route::middleware(JwtAuthorize::class)->group(function () {
    // auth
    Route::get('/logout', [AuthController::class, 'logout']);
    
    // pokemon
    Route::post('/pokemon/read', [PokemonController::class, 'read']);
    Route::get('/pokemon/list', [PokemonController::class, 'list']);
    Route::post('/pokemon/view', [PokemonController::class, 'view']);
    
    // trainer
    Route::get('/trainer/data', [TrainerController::class, 'data']);
});
