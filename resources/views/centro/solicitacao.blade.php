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

    <!-- Botão Exportar PDF 
    <div class="mb-3">
        <a href="{{ route('centro.solicitacao.exportarPdf', request()->query()) }}" target="_blank" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>Exportar PDF
        </a>
    </div>-->

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
                    $respostasAceitas = $solicitacao->respostas->where('status', 'Aceito');
                    $totalAtendido = $respostasAceitas->sum('quantidade_aceita');
                    $progresso = $solicitacao->quantidade > 0 ? ($totalAtendido / $solicitacao->quantidade) * 100 : 0;
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
                                    Prazo: {{ \Carbon\Carbon::parse($solicitacao->prazo)->format('d/m/Y H:i') }}
                                </li>
                                <li>
                                    <i class="fas fa-comment me-2 text-muted"></i>
                                    Motivo: {{ Str::limit($solicitacao->motivo, 40) }}
                                </li>
                            </ul>

                            <div class="d-grid">
                                @if($isSolicitante)
                                    {{-- Botões para o Centro Solicitante --}}
                                    @if($respostasAceitas->count() > 0)
                                        <button class="btn btn-outline-success" onclick="mostrarResposta({{  $solicitacao->respostas->first()->id_resposta }})">
                                            <i class="fas fa-eye me-2"></i>Ver Respostas
                                        </button>
                                    @else
                                        <button class="btn btn-outline-warning" onclick="editarSolicitacao({{ $solicitacao->id_sol }})">
                                            <i class="fas fa-edit me-2"></i>Editar
                                        </button>
                                    @endif
                                @else
                                        <button class="btn btn-outline-primary"  onclick="abrirModalResposta({{ $solicitacao->id_sol }})">
                                            <i class="fas fa-hand-holding-medical me-2"></i>Responder
                                        </button>
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
                        <small class="d-block"><i class="fas fa-clock me-2"></i>Reserva válida por 24 horas</small>
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
            <div class="modal-header bg-info text-white py-2">
                <h6 class="modal-title fs-6" id="modalVerRespostasLabel">Detalhes da Resposta</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            
            <div class="modal-body p-3">
                <div class="row g-3">
                    <!-- Coluna Esquerda - Dados Principais -->
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
                                    
                                    <!--<dt class="col-6">Status:</dt>
                                    <dd class="col-6">
                                        <span id="respostaStatus" class="badge">-</span>
                                    </dd>-->
                                    
                                    <dt class="col-6">Endereço:</dt>
                                    <dd class="col-6" id="respostaEnderecoCentro">-</dd>

                                    <dt class="col-6">Telefone:</dt>
                                    <dd class="col-6" id="respostaTelefoneCentro">-</dd>
                                </dl>
                            </div>
                        </div>

                        <!-- Botão de Confirmação -->
                                                
                        <button class="btn btn-primary w-100 mt-3" onclick="confirmarTransferencia()" id="btnConfirmar">
                            <i class="fas fa-check-circle me-2"></i>Confirmar Recebimento
                        </button>
                        <button class="btn btn-danger" onclick="gerarPDF()" id="btnPDF">
                            <i class="fas fa-file-pdf me-2"></i>Gerar Relatório
                        </button>

                    </div>

                    <!-- Coluna Direita - Mapa -->
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
                            <input type="datetime-local" name="prazo" id="edit_prazo" class="form-control" required>
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
            zoom: 10,
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
                routingMode: 'auto' // Pode ser 'auto', 'pedestrian', 'masstransit'
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

                // Exibir informações no console ou em elementos HTML
                console.log(`Distância: ${distancia}`);
                console.log(`Duração estimada: ${duracao}`);

                // Exemplo: exibir no modal
                document.getElementById('respostaDistancia').textContent = distancia;
                document.getElementById('respostaDuracao').textContent = duracao;
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
                // Armazena o ID da resposta globalmente para uso posterior
                window.respostaIdAtual = data.id_resposta;
                
                // Preenche os campos do modal com os dados retornados
                document.getElementById('respostaTipoSanguineo').textContent = data.tipo_sanguineo || '-';
                document.getElementById('respostaQuantidade').textContent = data.quantidade_solicitada || '0';
                document.getElementById('respostaPrazo').textContent = data.prazo ? new Date(data.prazo).toLocaleDateString('pt-BR') : '-';
                document.getElementById('respostaCentroDoador').textContent = data.centro_doador.nome || '-';
                document.getElementById('respostaQuantidadeOferecida').textContent = data.quantidade_oferecida || '0';
                document.getElementById('respostaEnderecoCentro').textContent = data.centro_doador.endereco || '-';
                document.getElementById('respostaTelefoneCentro').textContent = data.centro_doador.telefone || '-';

                // (Status: se desejado, inclua aqui o tratamento)

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
    if (!window.respostaIdAtual) {
        alert("ID da resposta não encontrado.");
        return;
    }

    const btn = document.getElementById("btnConfirmar");
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Confirmando...';

    fetch(`/centro/solicitacao/respostas/${window.respostaIdAtual}/confirmar-recebimento`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
    .then(async (response) => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.details || "Erro na confirmação");
        }
        return data;
    })
    .then(data => {
        Swal.fire({
            title: 'Recebimento Confirmado!',
            text: data.success,
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Gerar Relatório',
            cancelButtonText: 'Fechar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/centro/solicitacao/respostas/${window.respostaIdAtual}/relatorio`;
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalVerRespostas'));
                modal.hide();
            }
        });

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Recebimento Confirmado';
        btn.classList.replace("btn-primary", "btn-success");
    })
    .catch(error => {
        console.error("Erro completo:", error);
        alert(`ERRO: ${error.message}`);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Confirmar Recebimento';
    });
}




</script>
@endsection
