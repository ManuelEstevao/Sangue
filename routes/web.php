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
use App\Http\Controllers\DoacaoController;

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


Route::get('/campanhas/{campanha}', [CampanhaController::class, 'show'])->name('campanha.detalhe');
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
        Route::get('/doador/agendamento/{id}/edit', 'edit')->name('agendamento.edit');
        Route::patch('/doador/agendamento/{id}', [AgendamentoController::class, 'update'])->name('agendamento.update');
        Route::patch('/doador/agendamento/{id}/cancelar', 'cancelar')->name('agendamento.cancelar');
    });
    Route::get('/doacoes/historico', [HistoricoDoacaoController::class, 'index'])->name('historico');
    // centro
    Route::get('/doador/historico/{doador}', [HistoricoDoacaoController::class, 'show'])->name('doador.historico');
    Route::get('/doador/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::put('/doador/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    
    // Logout
    Route::post('/logout', [loginController::class, 'logout'])->name('logout');
    
    /*
    |--------------------------------------------------------------------------
    | Rotas para o Centro de Coleta 
    |--------------------------------------------------------------------------
    */
    
    Route::prefix('centro')->group(function () {
        Route::get('/dashboard', [CentroController::class, 'index'])->name('centro.Dashbord');
        Route::get('/relatorios', [CentroController::class, 'relatorio'])->name('centro.relatorio');
        Route::get('doacoes',[ DoacaoController::class, 'index'])->name('centro.doacao');
        
        Route::controller(AgendamentoCentroController::class)->group(function () {
            Route::get('/agendamentos', 'index')->name('centro.agendamento');
            Route::patch('/agendamentos/{agendamento}/concluir', 'concluir')->name('agendamento.concluir');
            Route::patch('/agendamentos/{agendamento}/confirmar', 'confirmar')->name('agendamentos.confirmar');
            Route::patch('/centro/agendamentos/{id}/cancelar',  'cancelar')->name('centro.agendamento.cancelar');

            Route::patch('/agendamentos/{id}/comparecido', 'marcarComparecido')->name('centro.agendamentos.comparecido');

        });



        Route::post('/doacoes', [DoacaoController::class, 'store'])->name('doacoes.store');

    });
    
    /*
    |--------------------------------------------------------------------------
    | Rotas para Campanhas
    |--------------------------------------------------------------------------
    */
    
    
    Route::prefix('campanhas')->group(function () {
        Route::get('/', [CampanhaController::class, 'index'])->name('campanhas.index');
        Route::post('/', [CampanhaController::class, 'store'])->name('campanhas.store');
        Route::get('/{campanha}/edit', [CampanhaController::class, 'edit'])->name('campanhas.edit');
        Route::put('/{campanha}', [CampanhaController::class, 'update'])->name('campanhas.update');
        Route::delete('/{campanha}', [CampanhaController::class, 'destroy'])->name('campanhas.destroy');});
    
    /*
    |--------------------------------------------------------------------------
    | Rotas para Cadastro do Centro
    |--------------------------------------------------------------------------
    */

});
Route::get('/registro-centro', [CentroController::class, 'showRegistrationForm'])->name('centro.register');
Route::post('/registro-centro', [CentroController::class, 'register'])->name('centro.submit');