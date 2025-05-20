<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\navController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\CadastroController;
use App\Http\Controllers\dadorController;
use App\Http\Controllers\DashDadorController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\QuestionarioController;
use App\Http\Controllers\HistoricoDoacaoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\PerfilCentroController;
use App\Http\Controllers\AgendamentoCentroController;
use App\Http\Controllers\CampanhaController;
use App\Http\Controllers\DoacaoController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\RespostasController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\DiaBloqueadoController;
use App\Http\Controllers\Adm\DashboardController;
use App\Http\Controllers\Adm\DoadoresController;
use App\Http\Controllers\Adm\CentrosController;
use App\Http\Controllers\Adm\AgendamentosController;
use App\Http\Controllers\Adm\UsuariosController;

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
Route::post('/check-bilhete-unique', [DoadoresController::class, 'checkBilheteUnique'])->name('check.bilhete.unique');
Route::post('/check-email-unique', [DoadoresController::class, 'checkEmailUnique'])->name('check.email.unique');
/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Usuários Autenticados)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    // Rotas para doadores
    Route::get('/doador', [dadorController::class, 'index'])->name('dador');
    Route::get('/doador/dashboard', [DashDadorController::class, 'index'])->name('doador.Dashbord');
    //QR
    Route::get('/doador/{doador}/verificar', [DashDadorController::class,'verificarQr'])
     ->name('doador.qr.verificar');
    Route::get('/verificar-doacao/{codigo}', [DashDadorController::class, 'verificarDoacao'])
     ->name('doador.verificar');

    // Agendamentos
    Route::controller(AgendamentoController::class)->group(function () {
        Route::get('/doador/agendamento/create', 'create')->name('agendamento');
        Route::post('/agendamento', 'store')->name('agendamento.store');
        Route::get('/doador/agendamento/{id}/edit', 'edit')->name('agendamento.edit');
        Route::patch('/doador/agendamento/{id}', 'update')->name('agendamento.update');
        Route::patch('/doador/agendamento/{id}/cancelar', 'cancelar')->name('agendamento.cancelar');
      

    });

    Route::post('/doador/agendamento/{agendamento}/questionario',[QuestionarioController::class, 'store'])->name('doador.questionario.store');
    Route::get('/doador/agendamento/{agendamento}/questionario/comprovativo', [QuestionarioController::class, 'comprovativo'])->name('doador.questionario.comprovativo');
    Route::get('/direcoes/{agendamento}', [AgendamentoController::class, 'showDirections'])
    ->name('direcoes');
    // Histórico de Doações
    Route::get('/doacoes/historico', [HistoricoDoacaoController::class, 'index'])->name('historico');
    Route::get('/doador/historico/{doador}', [HistoricoDoacaoController::class, 'show'])->name('doador.historico');

    // Perfil do Doador
    Route::get('/doador/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::put('/doador/perfil', [PerfilController::class, 'update'])->name('perfil.update');

    
    // Alteração de senha
    Route::get('/alterar-senha', [PerfilController::class, 'showChangePasswordForm'])->name('password.show');
    Route::put('/alterar-senha', [PerfilController::class, 'updatePassword'])->name('password.update');
    Route::post('/validate-current-password', function(Request $request) {
        return response()->json([
            'valid' => Hash::check($request->current_password, Auth::user()->password)
        ]);
    });

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
        Route::get('/agendamentos/{id}/questionario', [CentroController::class, 'getQuestionario'])->name('centro.questionario');
        Route::get('/centro/notificacao', [NotificacaoController::class, 'index'])->name('centro.notify');
        Route::get('/questionario/{id}/pdf', [CentroController::class, 'generateQuestionarioPDF'])->name('centro.questionario.pdf');
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
        Route::put('/password/update', [PerfilCentroController::class, 'updatePassword'])->name('centro.password.update');

        Route::prefix('dias-bloqueados')->group(function () {
            // POST - Criar novo bloqueio
            Route::post('/', [DiaBloqueadoController::class, 'store'])
                ->name('dias-bloqueados.store');
        
            // GET - Listar bloqueios
            Route::get('/{centro}', [DiaBloqueadoController::class, 'index'])
                ->name('dias-bloqueados.index');
        
            // DELETE - Remover bloqueio
            Route::delete('/{id}', [DiaBloqueadoController::class, 'destroy'])
                ->name('dias-bloqueados.destroy');
        });
        Route::get('/verificar-data-bloqueada', [DiaBloqueadoController::class, 'verificarDataBloqueada'])
                ->name('verificar.data.bloqueada');

        // Rotas para doações
        Route::controller(DoacaoController::class)->group(function () {
            Route::get('/doacoes', 'index')->name('centro.doacao');
            Route::post('/doacoes', 'store')->name('doacoes.store');
            Route::get('/doacao/{id}/edit', 'edit')->name('centro.doacao.edit');
            Route::put('/doacao/{id}', 'update')->name('centro.doacao.update');
            Route::delete('/doacao/{id}', 'destroy')->name('centro.doacao.destroy');
            Route::get('/centro/doacoes/exportar-pdf', 'exportarPdf')->name('centro.exportarPdf');
            Route::get('/relatorios/doador/{doador}/pdf', 'relatorio')->name('relatorio.doador.pdf');

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
        Route::delete('/{id}', [SolicitacaoController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/responder', [SolicitacaoController::class, 'responder'])->name('responder');
        Route::get('/{id}/dados-oferta', [SolicitacaoController::class, 'dadosOferta'])->name('dados-oferta');
        Route::get('respostas/{idResposta}/detalhes', [SolicitacaoController::class, 'detalhesResposta'])->name('detalhes');
        Route::post('/respostas/{idResposta}/confirmar-recebimento', [SolicitacaoController::class, 'confirmarRecebimento'])->name('confirmar.recebimento');
        Route::get('{id}/status', [SolicitacaoController::class, 'verificarStatusResposta'])->name('respostas.status');
        Route::get('/{id}/respostas', [SolicitacaoController::class, 'listarRespostas']) ->name('solicitacao.respostas');
        Route::delete('/{id}', [SolicitacaoController::class, 'destroy']) ->name('destroy');
    });

    Route::get('/respostas/{id}/relatorio', [RespostasController::class, 'gerarRelatorio'])
     ->name('solicitante.relatorio');
     
    Route::get('/respostas/{id}/relatorio-doador', [RespostasController::class, 'gerarRelatorioDoador'])
    ->name('doador.relatorio');


    /*
    |--------------------------------------------------------------------------
    | Rotas para o ADM
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin/')->group(function() {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('doador', [DoadoresController::class, 'store'])->name('doadores.store');
    Route::put('doadores/{id}', [DashboardController::class, 'update'])->name('doadores.update');
    Route::get('agendamentos', [AgendamentosController::class, 'index'])
    ->name('agendamentos.todos');

    Route::patch('agendamentos/{agendamento}/status', [AgendamentosController::class, 'updateStatus'])
    ->name('agendamentos.updateStatus');
    

    });


    // Listagem de Centros
    Route::get('/admin/centros', [CentrosController::class, 'index'])
    ->name('centros.lista');
    Route::get('admin/centros/pdf', [CentrosController::class, 'exportarPdf'])
    ->name('centros.pdf');
    Route::get('/admin/centros/mapa', [CentrosController::class, 'mapa'])->name('centro.mapa');
    Route::post('/admin/centros', [CentrosController::class, 'store'])->name('centros.store');

    // Listagem de doador
    Route::get('admin/doadores', [DoadoresController::class, 'index'])->name('listaD');
    Route::get('admin/doadores/exportar-pdf', [DoadoresController::class, 'exportarPdf'])
         ->name('doador.Pdf');
    Route::get('doadores/{id}/perfil', [DoadoresController::class, 'perfil'])->name('doadores.perfil');
      Route::get('doadores/{id}/edit', [DoadoresController::class, 'edit'])->name('doadores.edit');

   


    Route::get('/admin/centros/{centro}', [CentrosController::class, 'show'])
    ->name('centros.show');
    Route::get('/admin/centros/{centro}/edit', [CentrosController::class, 'edit'])
    ->name('centros.edit');
    Route::put('/admin/centros/{centro}', [CentrosController::class, 'update'])
    ->name('centros.update');
    Route::delete('/admin/centros/{centro}', [CentrosController::class, 'destroy'])
    ->name('centros.destroy');

    Route::prefix('admin')
     ->name('admin.')
     ->group(function () {
         // Resourceful routes para usuários
         Route::resource('usuarios', UsuariosController::class)
              ->parameters(['usuarios' => 'user'])
              ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']); 
     });

     /*
|--------------------------------------------------------------------------
| Notificação
|--------------------------------------------------------------------------
*/


     Route::post('/notificacoes/{notificacao}/marcar-lida', [NotificacaoController::class, 'marcarComoLida'])
    ->name('notificacoes.marcar-lida');
    Route::post('/notificacoes/marcar-todas-lidas', [NotificacaoController::class, 'marcarTodasComoLidas'])
    ->name('notificacoes.marcar-todas-lidas');

            // routes/web.php
    Route::get('/notificacoes/historico', [NotificacaoController::class, 'historico'])
    ->name('notificacoes.historico');


    });



/*
|--------------------------------------------------------------------------
| Rotas para Cadastro do Centro
|--------------------------------------------------------------------------
*/
