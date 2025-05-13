@extends('centro.main')

@section('title', 'Agendamentos - Centro de Coleta')
@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .badge-status {
        min-width: 90px;
        transition: all 0.3s ease;
    }
 
.swal-wide-modal {
    max-width: 800px;
    border-radius: 15px;
}

.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    padding-left: 30px;
    border-left: 3px solid #dee2e6;
}

.timeline-item.approved {
    border-left-color: #28a745;
}

.timeline-item.rejected {
    border-left-color: #dc3545;
}

.timeline-date {
    font-weight: 500;
    color: #6c757d;
    margin-bottom: 5px;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    position: relative;
}

.timeline-content:before {
    content: '';
    position: absolute;
    left: -33px;
    top: 15px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid;
}

.timeline-item.approved .timeline-content:before {
    border-color: #28a745;
}

.timeline-item.rejected .timeline-content:before {
    border-color: #dc3545;
}

.timeline-item.approved .timeline-content:before {
    border-color: #28a745;
}

.timeline-item.rejected .timeline-content:before {
    border-color: #dc3545;
}


.pagination .page-item.active .page-link {
    background-color: rgba(198, 66, 66, 0.95);
    border-color: rgba(198, 66, 66, 0.95);;
    color: white;
}


.pagination .page-link {
    color: silver;
    margin: 0 5px;
    border-radius: 5px;
}

.is-invalid {
    border-color: #dc3545 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.invalid-feedback {
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

/* Modal horizontal */
.modal-horizontal .modal-dialog {
    max-width: 95%;
    height: 85vh;
}

.modal-horizontal .modal-content {
    height: 100%;
}

.modal-horizontal .modal-body {
    overflow-y: auto;
    padding: 20px;
}

.question-badge {
    min-width: 70px;
    text-align: center;
}

.sticky-section-header {
    position: sticky;
    top: 0;
    background: white;
    z-index: 1;
    padding-top: 1rem;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 2px solid #dee2e6;
}
.question-item {
    padding: 12px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.question-text {
    flex: 1;
    margin-right: 15px;
    font-size: 0.95em;
    line-height: 1.4;
}

.answer-badge {
    flex-shrink: 0;
    width: 70px;
    text-align: center;
    font-size: 0.9em;
    padding: 5px 10px;
}

#btnExportPDF {
    width: 36px;
    height: 36px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

#btnExportPDF:hover {
    background-color: #fff !important;
    color: #c64242 !important;
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

/* Tooltip custom */
.tooltip-inner {
    background-color: #c64242;
    font-size: 0.85em;
    padding: 5px 10px;
}

.bs-tooltip-end .tooltip-arrow::before {
    border-right-color: #c64242 !important;
}
</style>
@endsection

@section('conteudo')
<div class="container">
    <!-- Modal para Registro de Doação -->
<div class="modal fade" id="registrarDoacaoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="registrarDoacaoForm" method="POST" action="{{ route('doacoes.store') }}">
                @csrf
                <input type="hidden" name="id_agendamento" id="id_agendamento">
                <input type="hidden" name="doador_id" id="doador_id">
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Registro de Doação</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Coluna Esquerda -->
                        <div class="col-md-6">
                            <!-- Tipo Sanguíneo -->
                            <div class="mb-3" id="bloodTypeField" style="display: none;">
                                <label class="form-label">Tipo Sanguíneo</label>
                                <select name="tipo_sanguineo" id="tipo_sanguineo" class="form-select">
                                    <option value="" disabled selected>Selecione o tipo</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $tipo)
                                        <option value="{{ $tipo }}">{{ $tipo }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Campo Peso -->
                            <div class="mb-3">
                                <label class="form-label">Peso (kg)</label>
                                <input type="number" step="0.1" id="peso" name="peso" class="form-control" placeholder="Ex: 70.5" required>
                            </div>

                            <!-- Campo Hemoglobina -->
                            <div class="mb-3">
                                <label class="form-label">Hemoglobina (g/dL)</label>
                                <input type="number" step="0.1" id="hemoglobina" name="hemoglobina" class="form-control" placeholder="Ex: 13.5" required>
                            </div>
                            
                            <!-- Campo Pressão Arterial -->
                            <div class="mb-3">
                                <label class="form-label">Pressão Arterial</label>
                                <input type="text" name="pressao_arterial" id="pressao_arterial" class="form-control" placeholder="Ex: 120/80" pattern="\d{2,3}/\d{2,3}" required>
                            </div>
                        </div>

                        <!-- Coluna Direita -->
                        <div class="col-md-6">
                            <!-- Volume Coletado -->
                            <div class="mb-3">
                                <label class="form-label">Volume Coletado (ml)</label>
                                <input type="number" name="volume_coletado" id="volume_coletado" class="form-control" min="300" max="500" value="450" required>
                            </div>

                            <!-- Profissional Responsável -->
                            <div class="mb-3">
                                <label class="form-label">Profissional Responsável</label>
                                <input type="text" name="nome_profissional" id="nome_profissional" class="form-control" placeholder="Nome do Profissional" required>
                            </div>
                            
                            <!-- Status da Doação -->
                            <div class="mb-3">
                                <label class="form-label">Status da Doação</label>
                                <select name="status" class="form-select" required>
                                    <option value="Aprovado">Aprovado</option>
                                    <option value="Reprovado">Reprovado</option>
                                </select>
                            </div>
                            
                            <!-- Observações -->
                            <div class="mb-3">
                                <label class="form-label">Observações</label>
                                <textarea name="observacoes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Salvar Doação</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Agendamentos</h4>
            <!--<a href="#" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Novo Agendamento
            </a>-->
        </div>
        
        <div class="card-body">
            <!-- Filtro por Tipo Sanguíneo -->
            <form method="GET" action="{{ route('centro.agendamento') }}" class="mb-3">
                <div class="d-flex align-items-center gap-2">
                    <label class="fw-bold">Filtrar por:</label>
                    <select name="tipo_sanguineo" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Todos tipos</option>
                        @foreach(['Desconhecido','A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $tipo)
                            <option value="{{ $tipo }}" {{ request('tipo_sanguineo') == $tipo ? 'selected' : '' }}>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <!-- Tabela de Agendamentos -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Doador</th>
                            <th>Tipo Sanguíneo</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agendamentos as $agendamento)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($agendamento->data_agendada)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($agendamento->horario)->format('H:i') }}</td>
                                <td>{{ $agendamento->doador->nome }}</td>
                                <td>{{ $agendamento->doador->tipo_sanguineo }}</td>
                                <td>
                                  <span class="badge badge-status bg-@switch($agendamento->status)
                                        @case('Agendado')primary @break
                                        @case('Comparecido')warning @break
                                        @case('Concluido')success @break
                                        @case('Cancelado')danger @break
                                        @case('Não Compareceu')danger @break
                                         @endswitch">
                                        {{ ucfirst($agendamento->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle" style="background-color: rgba(198, 66, 66, 0.95); color:white;" type="button" 
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($agendamento->status === 'Agendado')
                                            <li>
                                                <form id="form-comparecido-{{ $agendamento->id_agendamento }}" 
                                                    method="POST" 
                                                    action="{{ route('centro.agendamentos.comparecido', $agendamento->id_agendamento) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                </form>
                                                <button class="dropdown-item text-success" 
                                                        onclick="confirmarComparecimento({{ $agendamento->id_agendamento }})">
                                                    <i class="fas fa-user-check me-2"></i>Confirmar Comparecimento
                                                </button>
                                            </li>
                                            @endif
                                            @if($agendamento->status === 'Comparecido')
                                            <li>
                                                <button class="dropdown-item text-primary" 
                                                        onclick="abrirModalDoacao({{ $agendamento->id_agendamento }},   
                                                                                     {{ $agendamento->id_doador }}, 
                                                                                     '{{ $agendamento->doador->tipo_sanguineo }}')">
                                                    <i class="fas fa-tint me-2"></i>Inserir Doação
                                                </button>
                                            </li>
                                            @endif
                                            
                                            <li>
                                                <button class="dropdown-item text-info" 
                                                        onclick="verHistorico({{ $agendamento->doador->id_doador }})">
                                                        <i class="fas fa-user me-2"></i>Perfil
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-info" 
                                                        onclick="carregarQuestionario({{ $agendamento->id_agendamento }})">
                                                    <i class="fas fa-file-medical me-2"></i>Ver Questionário
                                                </button>
                                            </li>
                                            
                                            <li><hr class="dropdown-divider"></li>
                                            
                                            <li>
                                                <form id="cancelar-form-{{ $agendamento->id_agendamento }}" method="POST"
                                                    action="{{ route('centro.agendamento.cancelar', $agendamento->id_agendamento) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                </form>
                                                <a class="dropdown-item text-danger" href="#" 
                                                    onclick="confirmarCancelamento(event, {{ $agendamento->id_agendamento }})">
                                                    <i class="fas fa-times me-2"></i>Cancelar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        Nenhum agendamento encontrado.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

                    <!-- Paginação Estilizada -->
            @if ($agendamentos->hasPages())
                <nav aria-label="Page navigation" class="mt-3">
                    <ul class="pagination justify-content-center mb-0">
                        {{-- Botão Anterior --}}
                        @if ($agendamentos->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $agendamentos->previousPageUrl() }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        {{-- Números das Páginas --}}
                        @foreach ($agendamentos->getUrlRange(1, $agendamentos->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $agendamentos->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        {{-- Botão Próximo --}}
                        @if ($agendamentos->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $agendamentos->nextPageUrl() }}" rel="next">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
    </div>
</div>

<!-- Modal do Questionário -->
<div class="modal fade" id="questionarioModal" tabindex="-1">
    <div class="modal-dialog modal-xl"> <!-- Alterado para modal-xl -->
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                 <h5 class="modal-title">Questionário Pré-Doação</h5>
                   <div class="d-flex align-items-center gap-2 ms-auto"> 
                        <button type="button" 
                                class="btn text-white " 
                                id="btnExportPDF"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="left" 
                                title="Exportar para PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button type="button" 
                                class="btn-close btn-close-white" 
                                data-bs-dismiss="modal"></button>
                   </div>
            </div>
            <div class="modal-body">
                <div id="questionarioContent" class="container-fluid">
                    <div class="row g-4">
                        <!-- Coluna Esquerda -->
                        <div class="col-md-6 border-end">
                            <!-- Conteúdo será preenchido via JS -->
                        </div>
                        
                        <!-- Coluna Direita -->
                        <div class="col-md-6">
                            <!-- Conteúdo será preenchido via JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Confirmar Comparecimento
    function confirmarComparecimento(agendamentoId) {
    Swal.fire({
        title: 'Confirmar Comparecimento',
        text: "Deseja marcar este doador como presente?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submete o formulário tradicionalmente
            document.getElementById(`form-comparecido-${agendamentoId}`).submit();
        }
    });
}
// Abrir Modal
function abrirModalDoacao(agendamentoId, doadorId, tipoSanguineo) {
    const modal = new bootstrap.Modal(document.getElementById('registrarDoacaoModal'));
    document.getElementById('id_agendamento').value = agendamentoId;
    document.getElementById('doador_id').value = doadorId;
    
    const bloodTypeField = document.getElementById('bloodTypeField');
    const selectTipoSanguineo = document.getElementById('tipo_sanguineo');

    if (tipoSanguineo === 'Desconhecido') {
        bloodTypeField.style.display = 'block';
        selectTipoSanguineo.required = true;
        selectTipoSanguineo.value = '';
    } else {
        bloodTypeField.style.display = 'none';
        selectTipoSanguineo.required = false;
        selectTipoSanguineo.value = '';
    }
    modal.show();
}
// Validação e Submissão 
$(document).ready(function () {
    $('#registrarDoacaoForm').on('submit', function (e) {
        e.preventDefault();
        let isValid = true;
        const errorMessages = [];
        const $form = $(this);

        // Resetar erros
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Novas validações
        const validations = [
            // Validação Tipo Sanguíneo (condicional)
            {
                id: '#tipo_sanguineo',
                test: (v) => $('#bloodTypeField').is(':visible') ? v !== '' : true,
                msg: 'Selecione o tipo sanguíneo'
            },
            // Validação Peso
            {
                id: '#peso',
                test: (v) => v >= 45 && v <= 200, // Intervalo realista
                msg: 'Peso deve ser entre 45-200 kg'
            },
            // Validação Hemoglobina
            {
                id: '#hemoglobina',
                test: (v) => v >= 12.5 && v <= 18.0, // Intervalo médico
                msg: 'Hemoglobina deve ser 12.5-18.0 g/dL'
            },
            // Validação Pressão Arterial
            {
                id: '#pressao_arterial',
                test: (v) => {
                    const partes = v.split('/');
                    if (partes.length !== 2) return false;
                    const sistolica = parseInt(partes[0]);
                    const diastolica = parseInt(partes[1]);
                    return (sistolica >= 90 && sistolica <= 200) && 
                           (diastolica >= 60 && diastolica <= 120);
                },
                msg: 'Valores inválidos (ex: 120/80)'
            },
            // Validação Volume
            {
                id: '#volume_coletado',
                test: (v) => v >= 300 && v <= 500,
                msg: 'Volume deve ser 300-500 ml'
            },
            // Validação Profissional
            {
                id: '#nome_profissional',
                test: (v) => v.trim().length >= 5 && /^[a-zA-Z\u00C0-\u017F\s]+$/.test(v),
                msg: 'Nome completo válido (mín. 5 letras)'
            },
            // Validação Status
            {
                id: 'select[name="status"]',
                test: (v) => ['Aprovado', 'Reprovado'].includes(v),
                msg: 'Selecione um status válido'
            }
        ];

        // Executar validações
        validations.forEach(({id, test, msg}) => {
            const $element = $(id);
            const value = $element.val();
            
            if (!test(value)) {
                isValid = false;
                errorMessages.push(msg);
                $element.addClass('is-invalid')
                       .parent().append(`<div class="invalid-feedback">${msg}</div>`);
            }
        });

        // Validação de campos ocultos
        if (!$('#id_agendamento').val() || !$('#doador_id').val()) {
            isValid = false;
            errorMessages.push('Dados do agendamento inválidos');
            Swal.fire('Erro!', 'Recarregue a página e tente novamente', 'error');
        }

        // Tratar resultado
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Corrija os seguintes erros:',
                html: `<ul class="text-start">${errorMessages.map(m => `<li>${m}</li>`).join('')}</ul>`
            });
        } else {
            // Enviar dados
            fetch($form.attr('action'), {
                method: 'POST',
                body: new FormData($form[0]),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw { ...data, status: response.status };
                
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: data.message,
                    willClose: () => window.location.reload()
                });
            })
            .catch(error => {
                const msg = error.status === 422 
                    ? Object.values(error.errors).join('\n')
                    : error.message || 'Erro no servidor';
                
                Swal.fire('Erro!', msg, 'error');
            });
        }
    });
});


   // Função para visualizar histórico com foto
   function verHistorico(doadorId) {
        Swal.fire({
            title: 'Carregando...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(`/doador/historico/${doadorId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const contentType = response.headers.get('content-type');
            
            if (!contentType.includes('application/json')) {
                throw new Error('Resposta inválida do servidor');
            }

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Erro ao carregar histórico');
            }

            Swal.close();
            
            const historicoHTML = `
<div class="container-fluid p-4">
    <div class="d-flex justify-content-end">
        <button type="button" 
                class="btn-close" 
                onclick="Swal.close()">
        </button>
    </div>

    <div class="row g-4">
        <!-- Perfil do Doador -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <img src="${data.doador.foto_url}" 
                         class="rounded-circle mb-3" 
                         style="width: 200px; height: 200px; object-fit: cover" 
                         alt="Foto"
                         onerror="this.src='{{ asset('img/default-avatar.png') }}'">

                    <h4 class="mb-2">${data.doador.nome}</h4>
                    <div class="badge bg-danger text-white mb-3">
                        ${data.doador.tipo_sanguineo}
                    </div>
                    
                    <div class="text-muted small">
                        <i class="fas fa-tint me-1"></i>
                        ${data.doador.doacoes.length} doações realizadas
                    </div>
                </div>
            </div>
        </div>

        <!-- Histórico -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4"><i class="fas fa-history me-2"></i>Histórico Completo</h5>
                    
                    ${data.doador.doacoes.length === 0 ? `
                        <div class="text-center text-muted">
                            <p>Sem registo de histórico de doação.</p>
                        </div>
                    ` : 
                    data.doador.doacoes.map((item, index) => `
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="badge ${item.status === 'Aprovado' ? 'bg-success' : 'bg-danger'} me-2">
                                        ${item.status}
                                    </span>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">
                                        ${item.data_formatada} 
                                    </small>
                                </div>
                            </div>
                            
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tint text-danger me-2"></i>
                                        <span>${item.volume_coletado}ml</span>
                                    </div>
                                </div>
                                
                                <div class="col-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-prescription-bottle text-danger me-2"></i>
                                        <span>${item.hemoglobina}g/dL</span>
                                    </div>
                                </div>
                                
                                <div class="col-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-heartbeat text-danger me-2"></i>
                                        <span>${item.pressao_arterial}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-md text-danger me-2"></i>
                                        <span>${item.nome_profissional}</span>
                                    </div>
                                </div>
                            </div>
                            
                            ${item.observacoes ? `
                            <div class="mt-2 p-2 bg-light rounded">
                                <small class="text-muted">Observações:</small>
                                <div>${item.observacoes}</div>
                            </div>` : ''}
                        </div>
                    `).join('')}
                </div>
            </div>
        </div>
    </div>
</div>`;
// Função auxiliar para validar datas
function isValidDate(d) {
  return d instanceof Date && !isNaN(d);
}

            Swal.fire({
                title: 'Perfil do doador',
                html: historicoHTML,
                width: '90%',
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-wide-modal',
                    container: 'swal2-container'
                }
            });
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: error.message,
                confirmButtonText: 'OK'
            });
            console.error('Erro detalhado:', error);
        });
    }

let currentQuestionarioData = null;
    // Função para carregar o questionário
function carregarQuestionario(agendamentoId) {
    const modal = new bootstrap.Modal(document.getElementById('questionarioModal'));
    const [leftCol, rightCol] = document.querySelectorAll('#questionarioContent .col-md-6');
     currentQuestionarioData = null;
    // Loading state
    leftCol.innerHTML = rightCol.innerHTML = `
        <div class="text-center my-5">
            <div class="spinner-border text-danger" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    fetch(`/centro/agendamentos/${agendamentoId}/questionario`)
        .then(response => {
            if (!response.ok) throw new Error('Questionário não encontrado');
            return response.json();
        })
        .then(data => {
            const questions = {
    // Primeira coluna
    'ja_doou_sangue': 'Doou sangue nos últimos 4 meses?',
    'problema_doacao_anterior': 'Teve problemas em doações anteriores?',
    'fez_tatuagem_ultimos_12_meses': 'Fez tatuagem ou piercing nos últimos 12 meses?',
    'fez_cirurgia_recente': 'Realizou cirurgia nos últimos 6 meses?',
    'recebeu_transfusao_sanguinea': 'Recebeu transfusão sanguínea nos últimos 12 meses?',
    'tem_doenca_infecciosa': 'Possui doença infecciosa?',
    'teve_febre_ultimos_30_dias': 'Teve febre nos últimos 30 dias?',
    
    // Segunda coluna
    'tem_doenca_cronica': 'Possui doença crônica?',
    'esta_gravida': 'Está grávida ou amamentando?',
    'usa_medicacao_continua': 'Faz uso contínuo de medicamentos?',
    'tem_comportamento_de_risco': 'Nos últimos 6 meses, você teve práticas de risco de infecções sexualmente transmissíveis?',
    'consumiu_alcool_ultimas_24_horas': 'Consumiu álcool nas últimas 24 horas?',
    'teve_malaria_ultimos_3meses': 'Teve malária nos últimos 3 meses?',
    'nasceu_ou_viveu_angola': 'Nasceu e sempre viveu em Angola?',
    'esteve_internado': 'Esteve internado nos últimos 12 meses?'
};
    
    currentQuestionarioData = data;
     document.getElementById('btnExportPDF').addEventListener('click', () => {
                if(currentQuestionarioData && currentQuestionarioData.id_questionario) {
                    window.open(
                        `/centro/questionario/${currentQuestionarioData.id_questionario}/pdf`, 
                        '_blank'
                    );
                }
            });

            // Inicializar tooltip
            new bootstrap.Tooltip(document.getElementById('btnExportPDF'), {
                boundary: 'window',
                trigger: 'hover'
            });
            // Dividir questões
            const primeiraParte = Object.entries(questions).slice(0,7);
            const segundaParte = Object.entries(questions).slice(7);

            const createQuestionHTML = (items) => items.map(([key, text]) => `
                <div class="d-flex justify-content-between align-items-center py-2 px-3 mb-2 bg-light rounded">
                    <span class="text-muted">${text}</span>
                    <span class="badge ${data[key] ? 'bg-danger' : 'bg-success'}">
                        ${data[key] ? 'Sim' : 'Não'}
                    </span>
                </div>
            `).join('');

            leftCol.innerHTML = `
                <div class="sticky-top pt-3" style="top: -1rem">
                    <h5 class="text-danger mb-4">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Triagem Clínica
                    </h5>
                    ${createQuestionHTML(primeiraParte)}
                </div>
            `;

            rightCol.innerHTML = `
                <div class="sticky-top pt-3" style="top: -1rem">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="text-danger mb-0">
                            <i class="fas fa-user-shield me-2"></i>
                            Saúde do Doador
                        </h5>
                        <div class="text-end">
                            <small class="text-muted">
                                ${new Date(data.data_resposta).toLocaleDateString('pt-BR')}
                            </small>
                            <div class="badge bg-danger">
                                ${data.doador.tipo_sanguineo}
                            </div>
                        </div>
                    </div>
                    ${createQuestionHTML(segundaParte)}
                </div>
            `;
        })
        .catch(error => {
            leftCol.innerHTML = rightCol.innerHTML = `
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${error.message}
                </div>
            `;
        });
}

</script>
@endsection