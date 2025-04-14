@extends('centro.main')
@php use App\Models\Estoque; @endphp
@section('title', 'Gestão de Solicitações de Sangue')

@section('styles')
<style>
    .hover-shadow {
        transition: box-shadow 0.2s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 4px 16px rgba(220,53,69,0.15) !important;
    }
    .thin-progress {
        height: 6px;
        border-radius: 3px;
    }
    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.08);
    }
    .hover-scale {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-scale:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    .border-start {
        border-left-width: 4px !important;
    }
</style>
@endsection

@section('conteudo')
<div class="container-fluid py-4">
    <!-- Cabeçalho com título e botão para nova solicitação -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-0 fw-bold">Solicitações de Sangue</h1>
        </div>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalNovaSolicitacao">
            <i class="fas fa-plus me-2"></i>Nova Solicitação
        </button>
    </div>

    <!-- Botão Exportar PDF -->
    <div class="mb-3">
        <a href="{{ route('centro.solicitacao.exportarPdf', request()->query()) }}" target="_blank" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>Exportar PDF
        </a>
    </div>

    <!-- Lista de Solicitações Dinâmicas -->
    <div class="card border-0 shadow">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Solicitações Recentes</h5>
        </div>
        <div class="card-body">
        <div class="row g-4">
                @forelse($solicitacoes as $solicitacao)
                @php
                    $isSolicitante = $solicitacao->id_centro_solicitante == auth()->user()->centro->id_centro;
                    $respostasAceitas = $solicitacao->respostas->where('status', 'aceito');
                    $totalAtendido = $respostasAceitas->sum('quantidade_aceita');
                    $progresso = $solicitacao->quantidade > 0 ? ($totalAtendido / $solicitacao->quantidade) * 100 : 0;
                    $estoqueCentro = !$isSolicitante 
                        ? Estoque::where('id_centro', auth()->user()->centro->id_centro)
                            ->where('tipo_sanguineo', $solicitacao->tipo_sanguineo)
                            ->first()
                        : null;
                @endphp

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 border-0 hover-shadow">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    @if($solicitacao->urgencia == 'emergencia')
                                        <span class="badge bg-danger">URGENTE</span>
                                    @else
                                        <span class="badge bg-secondary">NORMAL</span>
                                    @endif
                                    <h5 class="mt-2 mb-1">
                                        Tipo: <span class="text-danger">{{ $solicitacao->tipo_sanguineo }}</span>
                                    </h5>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">#{{ $solicitacao->id_sol }}</small>
                                    <div class="text-success small">{{ ucfirst($solicitacao->status) }}</div>
                                </div>
                            </div>

                            <div class="progress thin-progress mb-2">
                                <div class="progress-bar bg-danger" style="width: {{ $progresso }}%;"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted mb-3">
                                <span>Solicitado: {{ $solicitacao->quantidade }}</span>
                                <span>Atendido: {{ $totalAtendido }}</span>
                            </div>

                            <ul class="list-unstyled mb-4">
                                <li class="mb-2">
                                    <i class="fas fa-hospital me-2 text-muted"></i>
                                    {{ $solicitacao->centroSolicitante->nome }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-clock me-2 text-muted"></i>
                                    Prazo: 1
                                </li>
                                <li>
                                    <i class="fas fa-comment me-2 text-muted"></i>
                                    Motivo: {{ Str::limit($solicitacao->motivo, 40) }}
                                </li>
                            </ul>

                            <div class="d-grid">
                                @if($isSolicitante)
                                    @if($respostasAceitas->count() > 0)
                                        <button class="btn btn-outline-success" onclick="mostrarDetalhes({{ $solicitacao->id_sol }})">
                                            <i class="fas fa-eye me-2"></i>Ver Respostas
                                        </button>
                                    @else
                                        <button class="btn btn-outline-warning" onclick="editarSolicitacao({{ $solicitacao->id_sol }})">
                                            <i class="fas fa-edit me-2"></i>Editar
                                        </button>
                                    @endif
                                @else
                                    @if($estoqueCentro && $estoqueCentro->quantidade > 0)
                                        <button class="btn btn-outline-primary" onclick="responderSolicitacao({{ $solicitacao->id_sol }})">
                                            <i class="fas fa-hand-holding-medical me-2"></i>Responder
                                        </button>
                                    @else
                                        <button class="btn btn-outline-info" onclick="mostrarDetalhes({{ $solicitacao->id_sol }})">
                                            <i class="fas fa-info-circle me-2"></i>Ver Detalhes
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0 text-center">Nenhuma solicitação encontrada.</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $solicitacoes->links() }}
    </div>
</div>

<!-- Modal de Nova Solicitação -->
<div class="modal fade" id="modalNovaSolicitacao" tabindex="-1" aria-labelledby="modalNovaSolicitacaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('centro.solicitacao.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title" id="modalNovaSolicitacaoLabel">Nova Solicitação de Sangue</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo Sanguíneo</label>
                            <select class="form-select" name="tipo_sanguineo" required>
                                <option value="">Selecione</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quantidade (bolsas)</label>
                            <input type="number" name="quantidade" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Urgência</label>
                            <select class="form-select" name="urgencia" required>
                                <option value="Normal">Normal</option>
                                <option value="Emergencia">Emergência</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prazo</label>
                            <input type="datetime-local" name="prazo" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Motivo da Solicitação</label>
                            <textarea name="motivo" class="form-control" rows="3" placeholder="Descreva o motivo"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Salvar Solicitação</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Detalhes da Solicitação -->
<div class="modal fade" id="modalDetalhes" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetalhesLabel">Detalhes da Solicitação</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
         <div id="detalhesSolicitacaoContainer">
             <!-- Os detalhes serão carregados via Ajax --> 
         </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Edição da Solicitação -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="formEditarSolicitacao" method="POST" action=""> <!-- O atributo action será atualizado via JS --> 
        @csrf
        @method('PUT')
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title" id="modalEditarLabel">Editar Solicitação de Sangue</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body" id="conteudoEditarSolicitacao">
            <!-- Conteúdo do formulário de edição será carregado via Ajax --> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
function mostrarDetalhes(id_sol) {
    $.get('/centro/solicitacao/' + id_sol, function(data) {
        $('#detalhesSolicitacaoContainer').html(data);
        $('#modalDetalhes').modal('show');
    }).fail(function() {
        alert('Não foi possível carregar os detalhes da solicitação.');
    });
}

function editarSolicitacao(id_sol) {
    $.get('/centro/solicitacao/' + id_sol + '/edit', function(data) {
        $('#conteudoEditarSolicitacao').html(data);
        $('#formEditarSolicitacao').attr('action', '/centro/solicitacao/' + id_sol);
        $('#modalEditar').modal('show');
    }).fail(function() {
        alert('Não foi possível carregar o formulário de edição.');
    });
}
</script>
@endsection
