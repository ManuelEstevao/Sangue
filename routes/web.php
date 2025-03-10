<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\navController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\cadastroController;
use App\Http\Controllers\dadorController;
use App\Http\Controllers\DashDadorController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\HistoricoDoacaoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\AgendamentoCentroController;
use App\Http\Controllers\CampanhaController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::controller(navController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/sobre', 'sobre')->name('sobre');
});

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::controller(loginController::class)->group(function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'login')->name('login.submit');
    });
    // Cadastro
    Route::controller(cadastroController::class)->group(function () {
        Route::get('/registar', 'index')->name('cadastro');
        Route::post('/registar', 'store')->name('store-cadastro');
    });
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Usuários Autenticados)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Rotas para doadores
    Route::get('/doador', [dadorController::class, 'index'])->name('dador');
    Route::get('/doador/dashboard', [DashDadorController::class, 'index'])->name('doador.Dashbord');
    Route::controller(AgendamentoController::class)->group(function () {
        Route::get('/doador/agendamento/create', 'create')->name('agendamento');
        Route::post('/agendamento', 'store')->name('agendamento.store');
    });
    Route::get('/doacoes/historico', [HistoricoDoacaoController::class, 'index'])->name('historico');
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    
    // Logout
    Route::post('/logout', [loginController::class, 'logout'])->name('logout');
    
    /*
    |--------------------------------------------------------------------------
    | Rotas para o Centro de Coleta (Protegidas por 'auth' e 'centro')
    |--------------------------------------------------------------------------
    */
    Route::middleware('centro')->group(function () {
        Route::get('/centro/dashboard', [CentroController::class, 'index'])->name('centro.Dashbord');
        Route::get('/centro/agendamentos', [AgendamentoCentroController::class, 'index'])->name('centro.agendamento');
        
    });
    
    /*
    |--------------------------------------------------------------------------
    | Rotas para Campanhas
    |--------------------------------------------------------------------------
    */
    Route::get('/campanhas/{campanha}', [CampanhaController::class, 'show'])->name('campanha.detalhe');
    
    Route::prefix('campanhas')->group(function () {
        Route::get('/', [CampanhaController::class, 'index'])->name('campanhas.index');
        Route::post('/', [CampanhaController::class, 'store'])->name('campanhas.store');
        Route::get('/{id_campanha}/edit', [CampanhaController::class, 'edit'])->name('campanhas.edit');
        Route::put('/{id_campanha}', [CampanhaController::class, 'update'])->name('campanhas.update');
        Route::delete('/{id_campanha}', [CampanhaController::class, 'destroy'])->name('campanhas.destroy');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Rotas para Cadastro do Centro
    |--------------------------------------------------------------------------
    */
    Route::get('/registro-centro', [CentroController::class, 'showRegistrationForm'])->name('centro.register');
    Route::post('/registro-centro', [CentroController::class, 'register'])->name('centro.submit');
});
