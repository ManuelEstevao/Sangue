<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\navController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\CadastroController;
use App\Http\Controllers\dadorController;
use App\Http\Controllers\DashDadorController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\HistoricoDoacaoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\PerfilCentroController;
use App\Http\Controllers\AgendamentoCentroController;
use App\Http\Controllers\CampanhaController;
use App\Http\Controllers\DoacaoController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\Adm\DashboardController;

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
    Route::controller(CadastroController::class)->group(function () {
        Route::get('/registar', 'index')->name('cadastro');
        Route::post('/registar', 'store')->name('store-cadastro');
    });
});

/*
|--------------------------------------------------------------------------
| Rotas de Campanhas
|--------------------------------------------------------------------------
*/
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

    // Agendamentos
    Route::controller(AgendamentoController::class)->group(function () {
        Route::get('/doador/agendamento/create', 'create')->name('agendamento');
        Route::post('/agendamento', 'store')->name('agendamento.store');
        Route::post('/agendamento/questionario', 'storeQuestionario')->name('agendamento.questionario.store');
        Route::get('/doador/agendamento/{id}/edit', 'edit')->name('agendamento.edit');
        Route::patch('/doador/agendamento/{id}', 'update')->name('agendamento.update');
        Route::patch('/doador/agendamento/{id}/cancelar', 'cancelar')->name('agendamento.cancelar');
    });

    // Histórico de Doações
    Route::get('/doacoes/historico', [HistoricoDoacaoController::class, 'index'])->name('historico');
    Route::get('/doador/historico/{doador}', [HistoricoDoacaoController::class, 'show'])->name('doador.historico');

    // Perfil do Doador
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
        Route::get('/listar/doadores', [CentroController::class, 'listarDoadores'])->name('listar.doador');
        Route::get('/centro/doador/pdf', [CentroController::class, 'exportarPdf'])->name('centro.doador.pdf');

        //Estoque do centro
        Route::controller(EstoqueController::class)->group(function () {
        Route::get('/estoque',  'index')->name('estoque.index');
        Route::post('/estoque/ajustar', 'ajuste')->name('estoque.ajustar');
        
        });

        // Agendamentos do Centro
        Route::controller(AgendamentoCentroController::class)->group(function () {
            Route::get('/agendamentos', 'index')->name('centro.agendamento');
            Route::patch('/agendamentos/{agendamento}/concluir', 'concluir')->name('agendamento.concluir');
            Route::patch('/agendamentos/{agendamento}/confirmar', 'confirmar')->name('agendamentos.confirmar');
            Route::patch('/centro/agendamentos/{id}/cancelar', 'cancelar')->name('centro.agendamento.cancelar');
            Route::patch('/agendamentos/{id}/comparecido', 'marcarComparecido')->name('centro.agendamentos.comparecido');
        });
        //Perfil centro
        Route::get('/perfil', [PerfilCentroController::class, 'index'])->name('centro.perfil');
        Route::put('/perfil', [PerfilCentroController::class, 'update'])->name('centro.profile.update');

        // Rotas para doações
        Route::controller(DoacaoController::class)->group(function () {
            Route::get('/doacoes', 'index')->name('centro.doacao');
            Route::post('/doacoes', 'store')->name('doacoes.store');
            Route::get('/doacao/{id}/edit', 'edit')->name('centro.doacao.edit');
            Route::put('/doacao/{id}', 'update')->name('centro.doacao.update');
            Route::delete('/doacao/{id}', 'destroy')->name('centro.doacao.destroy');
            Route::get('/centro/doacoes/exportar-pdf', 'exportarPdf')->name('centro.exportarPdf');

        });
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
        Route::delete('/{campanha}', [CampanhaController::class, 'destroy'])->name('campanhas.destroy');
    });

    // Rotas para Solicitações


    Route::prefix('centro/solicitacao')->name('centro.solicitacao.')->group(function () {
        Route::get('/', [SolicitacaoController::class, 'index'])->name('index');
        Route::get('/create', [SolicitacaoController::class, 'create'])->name('create');
        Route::post('/store', [SolicitacaoController::class, 'store'])->name('store');
        Route::get('/{id}', [SolicitacaoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SolicitacaoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SolicitacaoController::class, 'update'])->name('update');
        Route::get('/exportar-pdf', [SolicitacaoController::class, 'exportarPdf'])->name('exportarPdf');
    });


});

/*
|--------------------------------------------------------------------------
| Rotas para Cadastro do Centro
|--------------------------------------------------------------------------
*/
Route::get('/registro-centro', [CentroController::class, 'showRegistrationForm'])->name('centro.register');
Route::post('/registro-centro', [CentroController::class, 'register'])->name('centro.submit');



Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

