<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\navController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\cadastroController;
use App\Http\Controllers\dadorController;
use App\Http\Controllers\DashDadorController;

// Rotas Públicas
Route::controller(navController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/sobre', 'sobre')->name('sobre');
});

// Rotas de Autenticação
Route::middleware('guest')->group(function () {
    Route::controller(loginController::class)->group(function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'login')->name('login.submit');
    });
    
    Route::controller(cadastroController::class)->group(function () {
        Route::get('/registar', 'index')->name('cadastro');
        Route::post('/registar', 'store')->name('store-cadastro'); 
    });
});


// Rotas Protegidas (após login)
Route::middleware('auth')->group(function () {
    Route::get('/doador', [dadorController::class, 'index'])->name('dador');
    Route::get('/dashboard', [DashDadorController::class, 'index'])->name('dash');
    Route::post('/logout', [loginController::class, 'logout'])->name('logout');
});