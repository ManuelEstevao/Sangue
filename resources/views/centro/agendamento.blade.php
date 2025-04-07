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

</style>
@endsection

@section('conteudo')
<div class="container">
    <!-- Modal para Registro de Doação -->
    
<div class="modal fade" id="registrarDoacaoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="registrarDoacaoForm" method="POST" action="{{ route('doacoes.store') }}">
                @csrf
                <input type="hidden" name="agendamento_id" id="agendamento_id">
                <input type="hidden" name="doador_id" id="doador_id">
                
                <div class="modal-header">
                    <h5 class="modal-title">Registro de Doação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <!-- Tipo Sanguíneo -->
                    <div class="mb-3" id="bloodTypeField" style="display: none;">
                        <label>Tipo Sanguíneo</label>
                        <select name="tipo_sanguineo" id="tipo_sanguineo" class="form-select">
                            <option value="Desconhecido">Selecionar Tipo Sanguíneo</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo Peso -->
                    <div class="mb-3">
                        <label>Peso (kg)</label>
                        <input type="number" step="0.1" id="peso" name="peso" class="form-control" placeholder="Ex: 70.5" required>
                    </div>

                    <!-- Campo Hemoglobina -->
                    <div class="mb-3">
                        <label>Hemoglobina (g/dL)</label>
                        <input type="number" step="0.1" id="hemoglobina" name="hemoglobina" class="form-control" placeholder="Ex: 13.5" required>
                    </div>
                    
                    <!-- Campo Pressão Arterial -->
                    <div class="mb-3">
                        <label>Pressão Arterial</label>
                        <input type="text" name="pressao_arterial" id="pressao_arterial" class="form-control" placeholder="Ex: 120/80" pattern="\d{2,3}/\d{2,3}" required>
                    </div>
                    
                    <!-- Volume Coletado -->
                    <div class="mb-3">
                        <label>Volume Coletado (ml)</label>
                        <input type="number" name="volume_coletado" id="volume_coletado" class="form-control" min="300" max="500" value="450" required>
                    </div>

                    <!-- Profissional Responsável (Input) -->
                    <div class="mb-3">
                        <label>Profissional Responsável</label>
                        <input type="text" name="nome_profissional" id="nome_profissional" class="form-control" placeholder="Nome do Profissional" required>
                    </div>
                    
                    <!-- Status da Doação -->
                    <div class="mb-3">
                        <label>Status da Doação</label>
                        <select name="status" class="form-select" required>
                            <option value="Aprovado">Aprovado</option>
                            <option value="Reprovado">Reprovado</option>
                        </select>
                    </div>
                    
                    <!-- Observações -->
                    <div class="mb-3">
                        <label>Observações</label>
                        <textarea name="observacoes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Doação</button>
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

$(document).ready(function () {
    $('#registrarDoacaoForm').on('submit', function (e) {
        let valid = true;
        let errorMessage = '';

        const tipoSanguineoField = $('#bloodTypeField');
        const tipoSanguineo = $('#tipo_sanguineo').val();

        if (tipoSanguineoField.is(':visible') && (!tipoSanguineo || tipoSanguineo === 'Desconhecido')) {
            valid = false;
            errorMessage = 'Por favor, selecione o tipo sanguíneo.';
        }

        const peso = parseFloat($('#peso').val());
        if (isNaN(peso) || peso < 45) {
            valid = false;
            errorMessage = 'O peso deve ser no mínimo 45 kg.';
        }

        const hemoglobina = parseFloat($('#hemoglobina').val());
        if (isNaN(hemoglobina) || hemoglobina < 12.5) {
            valid = false;
            errorMessage = 'Hemoglobina deve ser igual ou superior a 12.5 g/dL.';
        }

        const pressao = $('#pressao_arterial').val();
        const regexPressao = /^\d{2,3}\/\d{2,3}$/;
        if (!regexPressao.test(pressao)) {
            valid = false;
            errorMessage = 'Pressão arterial inválida. Use o formato 120/80.';
        }

        const volume = parseInt($('#volume_coletado').val());
        if (isNaN(volume) || volume < 300 || volume > 500) {
            valid = false;
            errorMessage = 'Volume coletado deve estar entre 300 e 500 ml.';
        }

        const profissional = $('#nome_profissional').val();
        if (!profissional || profissional.trim().length < 3) {
            valid = false;
            errorMessage = 'Informe o nome completo do profissional responsável.';
        }

        if (!valid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Erro de Validação',
                text: errorMessage
            });
        }
    });
});
    // Abrir Modal de Doação
function abrirModalDoacao(agendamentoId, doadorId, tipoSanguineo) {
    const modal = new bootstrap.Modal(document.getElementById('registrarDoacaoModal'));
    
    // Preenche os campos ocultos
    document.getElementById('agendamento_id').value = agendamentoId;
    document.getElementById('doador_id').value = doadorId;

    // Controle do campo de tipo sanguíneo
    const bloodTypeField = document.getElementById('bloodTypeField');
    if (tipoSanguineo === 'Desconhecido') {
        bloodTypeField.style.display = 'block';
        bloodTypeField.querySelector('select').required = true;
    } else {
        bloodTypeField.style.display = 'none';
        bloodTypeField.querySelector('select').required = false;
    }

    modal.show();
}

// Submissão do formulário com AJAX
document.getElementById('registrarDoacaoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Erro no servidor');
        }
        
        Swal.fire({
            icon: 'success',
            title: 'Doação registrada!',
            text: data.message
        }).then(() => {
            window.location.reload();
        });
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: error.message
        });
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
</script>
@endsection