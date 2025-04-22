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
    .btn-action {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.bg-edit {
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
}

.bg-delete {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0,0,0,0.12);
}

.bg-edit:hover {
    background: #6366f1;
    color: white;
}

.bg-delete:hover {
    background: #ef4444;
    color: white;
}

.actions-container {
    border-top: 1px solid rgba(0,0,0,0.05);
    padding-top: 1rem;
}

.btn-navigation {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(13, 110, 253, 0.05);
    border: 1px solid rgba(13, 110, 253, 0.15);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    color: rgba(13, 110, 253, 0.8);
}

.btn-navigation:hover {
    background: rgba(13, 110, 253, 0.1);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.btn-navigation:active {
    transform: translateY(0);
    box-shadow: none;
}
.btn-link.text-white:hover {
        opacity: 0.8;
        transform: scale(1.1);
        transition: all 0.2s;
    }

    .btn-status {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-status:disabled {
    opacity: 0.8;
    cursor: not-allowed;
}


</style>
@endsection

@section('conteudo')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
    <!-- Lista de Solicitações Dinâmicas -->
    <div class="card border-0 shadow">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Solicitações Recentes</h5>
        </div>
        <div class="card-body">
        <div class="row g-4">
                @forelse($solicitacoes as $solicitacao)
                @php
                    $isSolicitante = $solicitacao->id_centro == auth()->user()->centro->id_centro;
                    $respostasAceitas = $solicitacao->respostas->whereIn('status', ['Aceito', 'Concluido']);
                    $totalAtendido = $respostasAceitas->sum('quantidade_aceita');
                    $progresso = $solicitacao->quantidade > 0 ? ($totalAtendido / $solicitacao->quantidade) * 100 : 0;
                    
                    $corProgresso = $progresso >= 100 ? 'bg-success' : 'bg-danger';
                    
                    $estoqueCentro = !$isSolicitante 
                        ? Estoque::where('id_centro', auth()->user()->centro->id_centro)
                            ->where('tipo_sanguineo', $solicitacao->tipo_sanguineo)
                            ->first()
                        : null;
                    $respostaAtual = $solicitacao->respostas->where('id_centro', auth()->user()->centro->id_centro)->first();
                
                @endphp


                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 border-0 hover-shadow">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    @if($solicitacao->urgencia == 'Emergencia')
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
                                <div class="progress-bar {{ $corProgresso }}" style="width: {{ $progresso }}%;"></div>
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
                                    Prazo: {{ \Carbon\Carbon::parse($solicitacao->prazo)->format('d/m/Y') }}
                                </li>
                                <li>
                                    <i class="fas fa-comment me-2 text-muted"></i>
                                    Motivo: {{ Str::limit($solicitacao->motivo, 40) }}
                                </li>
                            </ul>

                            <div class="d-grid">
                                @if($isSolicitante)
                                    {{-- Botões para o Centro Solicitante --}}
                                    @if($respostasAceitas->count() > 0 )
                                        <button class="btn btn-outline-success" 
                                                onclick="carregarRespostas({{ $solicitacao->id_sol }})">
                                            <i class="fas fa-eye me-2"></i>Ver Respostas ({{ $respostasAceitas->count() }}</span>)
                                        </button>
                                        @else
                                    <div class="d-flex justify-content-end gap-1">
                                        <button class="btn btn-action bg-edit" 
                                                onclick="editarSolicitacao({{ $solicitacao->id_sol }})"
                                                data-bs-toggle="tooltip" 
                                                title="Editar Solicitação">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        
                                        <button class="btn btn-action bg-delete" 
                                                onclick="excluirSolicitacao({{ $solicitacao->id_sol }})"
                                                data-bs-toggle="tooltip"
                                                title="Excluir Solicitação">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    @endif
                                @else
                                    @if($respostaAtual)
                                        @if($respostaAtual->status == 'Aceito')
                                            <button class="btn btn-outline-secondary w-100" disabled>
                                                <i class="fas fa-clock me-2"></i>Aguardando confirmação...
                                            </button>
                                        @elseif($respostaAtual->status == 'Concluido')
                                            
                                            <a href="/respostas/{{ $respostaAtual->id_resposta }}/relatorio-doador" 
                                            class="btn btn-outline-success w-100"
                                            target="_blank">
                                            <i class="fas fa-file-pdf me-2"></i>Relatório
                                            </a>
                                        @endif
                                    @else
                                        <button class="btn btn-outline-primary w-100" 
                                                onclick="abrirModalResposta({{ $solicitacao->id_sol }})">
                                            <i class="fas fa-hand-holding-medical me-2"></i>Responder
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
                            <input type="date" name="prazo" class="form-control" required>
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


<!-- Modal de Resposta à Solicitação -->
<div class="modal fade" id="modalResponder" tabindex="-1" aria-labelledby="modalResponderLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
           
            <form id="formResponder" method="POST" action="{{ route('centro.solicitacao.responder', ['id' => 'REPLACE_ID']) }}" data-base-action="{{ route('centro.solicitacao.responder', ['id' => 'REPLACE_ID']) }}">
                @csrf
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="modalResponderLabel">Oferecer Sangue</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Cabeçalho Informativo -->
                    <div class="alert alert-info py-2 mb-4">
                        <small class="d-block mb-1"><i class="fas fa-info-circle me-2"></i>A quantidade oferecida será reservada temporariamente</small>
                        <!--<small class="d-block"><i class="fas fa-clock me-2"></i>Reserva válida por 24 horas</small>-->
                    </div>

                    <!-- Dados da Solicitação -->
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border p-2 rounded">
                                <p class="small text-muted mb-1">Tipo Necessário:</p>
                                <h4 class="text-danger mb-0" id="modalTipoSanguineo">-</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border p-2 rounded">
                                <p class="small text-muted mb-1">Seu Estoque:</p>
                                <h4 class="text-success mb-0" id="modalEstoqueDisponivel">-</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Campo de Quantidade -->
                    <div class="mt-4">
                        <label for="quantidade" class="form-label">
                            Quantidade a Oferecer 
                            <small class="text-muted">(máx. <span id="maxDisponivel">0</span> bolsas)</small>
                        </label>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary" onclick="ajustarQuantidade(-1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="form-control text-center" id="quantidade" name="quantidade" value="1" min="1" required oninput="validarQuantidade(this)">
                            <button type="button" class="btn btn-outline-secondary" onclick="ajustarQuantidade(1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="form-text">Esta ação reservará imediatamente o estoque</div>
                        <div class="invalid-feedback" id="quantidadeFeedback"></div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Confirmar Oferta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Visualização de Respostas  -->
<div class="modal fade" id="modalVerRespostas" tabindex="-1" aria-labelledby="modalVerRespostasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2 position-relative">
                <h6 class="modal-title fs-6" id="modalVerRespostasLabel">Detalhes da Resposta</h6>
                
                
                <div class="position-absolute end-0 top-50 translate-middle-y me-5">
                    <button type="button" 
                            class="btn btn-link text-white p-0" 
                            title="Gerar Relatório PDF"
                            id="btnPdf"
                            
                            data-bs-toggle="tooltip">
                        <i class="fas fa-file-pdf fa-fw"></i>
                    </button>
                </div>
                
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            
                   
            <div class="modal-body p-3">
                <input type="hidden" id="respostaIdAtual" value="">
                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body p-3">
                                <h6 class="text-danger mb-3"><i class="fas fa-tint me-2"></i>Solicitação</h6>
                                <dl class="row small mb-0">
                                    <dt class="col-6">Tipo Sanguíneo:</dt>
                                    <dd class="col-6" id="respostaTipoSanguineo">-</dd>
                                    
                                    <dt class="col-6">Quantidade:</dt>
                                    <dd class="col-6" id="respostaQuantidade">-</dd>
                                    
                                    <dt class="col-6">Prazo:</dt>
                                    <dd class="col-6" id="respostaPrazo">-</dd>
                                </dl>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3">
                                <h6 class="text-primary mb-3"><i class="fas fa-hand-holding-medical me-2"></i>Colaborador</h6>
                                <dl class="row small mb-0">
                                    <dt class="col-6">Centro:</dt>
                                    <dd class="col-6" id="respostaCentroDoador">-</dd>
                                    
                                    <dt class="col-6">Oferecido:</dt>
                                    <dd class="col-6" id="respostaQuantidadeOferecida">-</dd>
                                    
                                    <dt class="col-6">Endereço:</dt>
                                    <dd class="col-6" id="respostaEnderecoCentro">-</dd>

                                    <dt class="col-6">Telefone:</dt>
                                    <dd class="col-6" id="respostaTelefoneCentro">-</dd>
                                </dl>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 mt-3" 
                                onclick="confirmarTransferencia()" 
                                id="btnConfirmar"
                                data-status-url="{{ route('centro.solicitacao.respostas.status', ['id' => 'REPLACE_ID']) }}">
                            <i class="fas fa-check-circle me-2"></i>Confirmar Recebimento
                        </button>
                    </div>

                    <!-- Coluna Direita - Mantida Integralmente -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-2 position-relative">
                                <div id="mapaResposta" style="height: 400px; border-radius: 6px; background: #f8f9fa;">
                                    <div class="spinner-border spinner-border-sm text-primary mapa-spinner"></div>
                                </div>
                                <div class="text-center mt-2 small text-muted">
                                    <i class="fas fa-map-marker-alt text-danger"></i> Solicitante
                                    <i class="fas fa-map-marker-alt text-primary ms-2"></i> Colaborador
                                    <div class="mt-1">
                                        <i class="fas fa-route me-1"></i>
                                        Distância: <span id="respostaDistancia">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer " id="footerNavegacao" style="display: none;">
                <div class="me-auto">
                    <span id="respostaContador" class="badge bg-primary"></span>
                </div>
                <button class="btn btn-navigation shadow-sm" id="btnAnterior" 
                        onclick="respostaAnterior()"> 
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-navigation shadow-sm" id="btnProximo" 
                        onclick="proximaResposta()"> 
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal de Edição de Solicitação -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form id="formEditarSolicitacao" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title" id="modalEditarLabel">Editar Solicitação de Sangue</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo Sanguíneo</label>
                            <select class="form-select" name="tipo_sanguineo" id="edit_tipo_sanguineo" required>
                                <option value="">Selecione</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quantidade (bolsas)</label>
                            <input type="number" name="quantidade" id="edit_quantidade" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Urgência</label>
                            <select class="form-select" name="urgencia" id="edit_urgencia" required>
                                <option value="normal">Normal</option>
                                <option value="emergencia">Emergência</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prazo</label>
                            <input type="date" name="prazo" id="edit_prazo" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Motivo da Solicitação</label>
                            <textarea name="motivo" id="edit_motivo" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>




@endsection
@section('scripts')
<script src="https://api-maps.yandex.ru/2.1/?lang=pt_PT&apikey=db51d640-6b39-495c-b35b-c2ec8a719fc9" type="text/javascript"></script>
<script>

function editarSolicitacao(id) {
    fetch(`/centro/solicitacao/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            // Preencher formulário
            document.getElementById('edit_tipo_sanguineo').value = data.tipo_sanguineo;
            document.getElementById('edit_quantidade').value = data.quantidade;
            document.getElementById('edit_urgencia').value = data.urgencia.toLowerCase();
            document.getElementById('edit_prazo').value = data.prazo.replace(' ', 'T').slice(0, 16);
            document.getElementById('edit_motivo').value = data.motivo;

            // Atualizar ação do formulário
            document.getElementById('formEditarSolicitacao').action = `/centro/solicitacao/${id}`;

            // Exibir modal
            new bootstrap.Modal(document.getElementById('modalEditar')).show();
        })
        .catch(error => console.error('Erro:', error));
}
let mapaResposta = null;
function inicializarMapa(centroSolicitante, centroDoador) {
    if (!centroSolicitante || !centroDoador) {
        console.error('Coordenadas inválidas');
        return;
    }

    // Destruir mapa existente
    if (mapaResposta) {
        mapaResposta.destroy();
    }

    ymaps.ready(function () {
        // Esconder spinner
        document.querySelector('.mapa-spinner').style.display = 'none';

        mapaResposta = new ymaps.Map('mapaResposta', {
            center: [centroSolicitante.latitude, centroSolicitante.longitude],
            zoom: 11,
            controls: ['zoomControl', 'typeSelector']
        });

        // Criar pontos de rota
        const pontosRota = [
            [centroSolicitante.latitude, centroSolicitante.longitude],
            [centroDoador.latitude, centroDoador.longitude]
        ];

        // Configurar a rota
        const rota = new ymaps.multiRouter.MultiRoute({
            referencePoints: pontosRota,
            params: {
                routingMode: 'auto' 
            }
        }, {
            boundsAutoApply: true
        });

        // Adicionar a rota ao mapa
        mapaResposta.geoObjects.add(rota);

        // Obter informações da rota após o carregamento
        rota.model.events.add('requestsuccess', function () {
            const rotaAtiva = rota.getActiveRoute();
            if (rotaAtiva) {
                const distancia = rotaAtiva.properties.get('distance').text;
                const duracao = rotaAtiva.properties.get('duration').text;

                // Exemplo: exibir no modal
                document.getElementById('respostaDistancia').textContent = distancia;
               
            }
        });
    });
}

// Função mostrarResposta atualizada
document.addEventListener('DOMContentLoaded', function() {
    window.mostrarResposta = function(idResposta) {
        
        // Exibe o spinner
        const spinner = document.querySelector('.mapa-spinner');
        if (spinner) spinner.style.display = 'block';

        fetch(`/centro/solicitacao/respostas/${idResposta}/detalhes`)
            .then(response => {
                if (!response.ok) throw new Error('Erro na requisição: ' + response.status);
                return response.json();
            })
            .then(data => {
                
                
                
                // Preenche os campos do modal com os dados retornados
                document.getElementById('respostaTipoSanguineo').textContent = data.tipo_sanguineo || '-';
                document.getElementById('respostaQuantidade').textContent = data.quantidade_solicitada || '0';
                document.getElementById('respostaPrazo').textContent = data.prazo ? new Date(data.prazo).toLocaleDateString('pt-BR') : '-';
                document.getElementById('respostaCentroDoador').textContent = data.centro_doador.nome || '-';
                document.getElementById('respostaQuantidadeOferecida').textContent = data.quantidade_oferecida || '0';
                document.getElementById('respostaEnderecoCentro').textContent = data.centro_doador.endereco || '-';
                document.getElementById('respostaTelefoneCentro').textContent = data.centro_doador.telefone || '-';

                   

                
                // Inicializa o mapa com os dados dos centros (solicitante e doador)
                inicializarMapa(data.centro_solicitante, data.centro_doador);

                // Abre o modal de detalhes da resposta
                new bootstrap.Modal(document.getElementById('modalVerRespostas')).show();
            })
            .catch(error => {
                console.error('Erro ao carregar dados da resposta:', error);
                if (spinner) spinner.style.display = 'none';
                alert('Erro ao carregar os detalhes da resposta.');
            });
    };
});
function abrirModalResposta(idSolicitacao) {
    // Restaurar o action do formulário com a URL base (incluindo o placeholder)
    const form = document.getElementById('formResponder');
    form.action = form.getAttribute('data-base-action');
   
    

    fetch(`/centro/solicitacao/${idSolicitacao}/dados-oferta`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTipoSanguineo').textContent = data.tipo_sanguineo;
            document.getElementById('modalEstoqueDisponivel').textContent = `${data.estoque_disponivel} bolsas`;
            document.getElementById('maxDisponivel').textContent = data.estoque_disponivel;

            const inputQuantidade = document.getElementById('quantidade');
            inputQuantidade.max = data.estoque_disponivel;
            inputQuantidade.value = Math.min(1, data.estoque_disponivel);
            maxDisponivelGlobal = data.estoque_disponivel;

            // Atualizar o action utilizando a URL base e substituindo o placeholder
            form.action = form.getAttribute('data-base-action').replace('REPLACE_ID', idSolicitacao);

            new bootstrap.Modal(document.getElementById('modalResponder')).show();
        })
        .catch(error => console.error('Erro ao carregar dados da solicitação:', error));
}

function ajustarQuantidade(valor) {
    const input = document.getElementById('quantidade');
    const novoValor = parseInt(input.value) + valor;
    if (novoValor >= 1 && novoValor <= maxDisponivelGlobal) {
        input.value = novoValor;
        validarQuantidade(input);
    }
}

function validarQuantidade(input) {
    const feedback = document.getElementById('quantidadeFeedback');
    if (parseInt(input.value) > maxDisponivelGlobal) {
        input.classList.add('is-invalid');
        feedback.textContent = `Máximo disponível: ${maxDisponivelGlobal} bolsas`;
        return false;
    }
    input.classList.remove('is-invalid');
    feedback.textContent = '';
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formResponder');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validarQuantidade(document.getElementById('quantidade'))) {
                e.preventDefault();
                document.getElementById('quantidade').focus();
            }
        });
    }
});
function confirmarTransferencia() {
    const idResposta = document.getElementById('respostaIdAtual').value;

    if (!idResposta) {
        Swal.fire('Erro!', 'ID da resposta não identificado.', 'error');
        return;
    }

    const btn = document.getElementById("btnConfirmar");
    const btnPdf = document.getElementById('btnPdf');
    

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Confirmando...';

    fetch(`/centro/solicitacao/respostas/${idResposta}/confirmar-recebimento`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
    .then(async (response) => {
        const data = await response.json();
        
        // Tratar conflito de confirmação duplicada
        if (response.status === 409) {
            throw new Error(data.message || 'Recebimento já confirmado anteriormente');
        }
        if (!response.ok) {
            throw new Error(data.details || "Erro na confirmação");
        }

        // SweetAlert de sucesso
        Swal.fire({
            icon: 'success',
            title: 'Recebimento Confirmado!',
            text: 'Recebimento registrado com sucesso',
            showConfirmButton: true,
            willClose: () => {
                // Atualizar o botão do PDF e recarregar
                btnPdf.removeAttribute('disabled');
                btnPdf.onclick = () => {
                    window.open(`/respostas/${idResposta}/relatorio`, '_blank');
                };
                window.location.reload(true);
            }
        });

        
    })
    .catch(error => {
        console.error("Erro completo:", error);
        Swal.fire({
            icon: 'error',
            title: 'Erro na confirmação',
            text: error.message,
            willClose: () => {
                window.location.reload();
            }
        });
    });
}

function mostrarRespostaAtual() {
    if (!window.respostasData?.length || window.currentRespostaIndex < 0) return;

    const resposta = window.respostasData[window.currentRespostaIndex];
    const btnConfirmar = document.getElementById("btnConfirmar");
    
    // Estado inicial de loading
    btnConfirmar.disabled = true;
    btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Carregando...';
    btnConfirmar.classList.remove("btn-success", "btn-danger");
    btnConfirmar.classList.add("btn-primary");

    // Verificação de status no servidor
    fetch(`/centro/solicitacao/${resposta.id_resposta}/status`)
    .then(response => {
        if (!response.ok) throw new Error('Falha ao verificar status');
        return response.json();
    })
    .then(status => {
        if (status.confirmado) {
            btnConfirmar.disabled = true;
            btnConfirmar.classList.replace("btn-primary", "btn-success");
            btnConfirmar.innerHTML = '<i class="fas fa-check-circle me-2"></i>Confirmado';
            
                    btnPdf.removeAttribute('disabled');
                    btnPdf.onclick = () => {
                        window.open(`/respostas/${resposta.id_resposta}/relatorio`, '_blank');
                    };
               
        } else {
            btnConfirmar.disabled = false;
            btnConfirmar.classList.replace("btn-success", "btn-primary");
            btnConfirmar.innerHTML = '<i class="fas fa-check-circle me-2"></i>Confirmar Recebimento';
            btnPdf.setAttribute('disabled', true);
            btnPdf.onclick = null;
        }
    })
    .catch(error => {
        console.error("Erro ao verificar status:", error);
        btnConfirmar.disabled = true;
        btnConfirmar.innerHTML = '<i class="fas fa-times-circle me-2"></i>Erro ao verificar status';
    });

    // Restante da lógica de exibição
    document.getElementById('respostaIdAtual').value = resposta.id_resposta;
    const footer = document.getElementById('footerNavegacao');
    footer.style.display = window.respostasData.length > 1 ? 'flex' : 'none';

    // Atualizar dados da resposta
    document.getElementById('respostaTipoSanguineo').textContent = resposta.solicitacao.tipo_sanguineo || '-';
    document.getElementById('respostaQuantidade').textContent = resposta.solicitacao.quantidade || '0';
    document.getElementById('respostaPrazo').textContent = resposta.solicitacao.prazo ? 
        new Date(resposta.solicitacao.prazo).toLocaleDateString('pt-BR') : '-';
    
    const centroDoador = resposta?.centro_doador || {};
    document.getElementById('respostaCentroDoador').textContent = centroDoador.nome || 'Centro desconhecido';
    document.getElementById('respostaEnderecoCentro').textContent = centroDoador.endereco || 'Endereço não cadastrado';
    document.getElementById('respostaTelefoneCentro').textContent = centroDoador.telefone || 'N/D';
    document.getElementById('respostaQuantidadeOferecida').textContent = resposta?.quantidade_aceita || '0';

    // Controles de navegação
    document.getElementById('respostaContador').textContent = 
        `${window.currentRespostaIndex + 1} de ${window.respostasData.length}`;
    
    document.getElementById('btnAnterior').disabled = window.currentRespostaIndex === 0;
    document.getElementById('btnProximo').disabled = 
        window.currentRespostaIndex === window.respostasData.length - 1;

    // Mapa
    if (resposta.solicitacao.centro_solicitante && resposta.centro_doador) {
        inicializarMapa(
            resposta.solicitacao.centro_solicitante,
            resposta.centro_doador
        );
    }
}




function excluirSolicitacao(idSol) {
    Swal.fire({
        title: 'Confirmar exclusão?',
        text: "Esta ação não poderá ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX para exclusão
            fetch(`/centro/solicitacao/${idSol}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(response.statusText);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Solicitação excluída com sucesso!'
                    });
                    // Atualizar a lista ou recarregar a página
                    setTimeout(() => window.location.reload(), 1500);
                }
            })
            .catch(error => {
                Swal.fire('Erro!', 'Não foi possível excluir a solicitação', 'error');
                console.error('Error:', error);
            });
        }
    });
}

// Inicializar o SweetAlert
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});



window.respostasData = [];
window.currentRespostaIndex = 0;

window.carregarRespostas = function(idSol) {
    fetch(`/centro/solicitacao/${idSol}/respostas`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (!contentType?.includes('application/json')) {
            const text = await response.text();
            throw new Error(`Resposta inválida: ${text.slice(0, 100)}...`);
        }
        return response.json();
    })
    .then(data => {
        if (!data?.success || !data.data?.length) {
            throw new Error(data?.message || 'Nenhuma resposta encontrada');
        }
        
        // Atualize as variáveis GLOBAIS
        window.respostasData = data.data;
        window.currentRespostaIndex = 0;

        const footer = document.getElementById('footerNavegacao');
        footer.style.display = (window.respostasData.length > 1) ? 'flex' : 'none';
        
        mostrarRespostaAtual();
        new bootstrap.Modal(document.getElementById('modalVerRespostas')).show();
    })
    .catch(error => {
        console.error('Erro:', error);
        Swal.fire('Erro!', error.message, 'error');
    });
};


// 4. Controles de navegação corrigidos
window.proximaResposta = function() {
    if (window.currentRespostaIndex < window.respostasData.length - 1) {
        window.currentRespostaIndex++;
        mostrarRespostaAtual();
    }
};

window.respostaAnterior = function() {
    if (window.currentRespostaIndex > 0) {
        window.currentRespostaIndex--;
        mostrarRespostaAtual();
    }
};

</script>
@endsection