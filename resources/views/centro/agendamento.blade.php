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
                    <!-- Campo condicional para tipo sanguíneo -->
                            <div class="mb-3" id="bloodTypeField" style="display: none;">
                                <label>Tipo Sanguíneo</label>
                                <select name="tipo_sanguineo" class="form-select">
                                    <option value="Desconhecido">Selecionar Tiipo Sanguíneo</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $tipo)
                                        <option value="{{ $tipo }}">{{ $tipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                    
                    
                        <div class="mb-3">
                            <label>Hemoglobina (g/dL)</label>
                            <input type="number" step="0.1" name="hemoglobina" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Pressão Arterial</label>
                            <input type="text" name="pressao_arterial" class="form-control" placeholder="Ex: 120/80" pattern="\d{2,3}/\d{2,3}" required>
                        </div>
                        
                         <div class="mb-3">
                            <label>Volume Coletado (ml)</label>
                            <input type="number" 
                                name="volume_coletado" 
                                class="form-control" 
                                min="300" 
                                max="500" 
                                value="450" 
                                required>
                        </div>
                        
                        <div class="mb-3">
                        <label> Doação</label>
                            <select name="status" class="form-select" required>
                                <option value="Aprovado">Aprovado</option>
                                <option value="Reprovado">Reprovado</option>
                            </select>
                        </div>
                        
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
                                        @case('Reprovado')dark @break
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
                                            @if($agendamento->status === 'Agendado' || $agendamento->status === 'Comparecido')
                                            <li>
                                                <button class="dropdown-item text-info" 
                                                        onclick="verHistorico({{ $agendamento->doador->id }})">
                                                    <i class="fas fa-history me-2"></i>Histórico
                                                </button>
                                            </li>
                                            @endif
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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="${data.doador.foto_url || '/img/default-avatar.png'}" 
                                class="img-thumbnail mb-3" 
                                style="width: 200px; height: 200px; object-fit: cover;"
                                alt="Foto de ${data.doador.nome}">
                            <h4 class="mb-1">${data.doador.nome}</h4>
                            <div class="badge bg-primary">${data.doador.tipo_sanguineo}</div>
                        </div>
                        <div class="col-md-8">
                            <div class="timeline">
                                ${data.doador.doacoes.map((item, index) => `
                                    <div class="timeline-item ${item.status === 'Aprovado' ? 'approved' : 'rejected'}">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-date">
                                            ${new Date(item.data_doacao).toLocaleDateString('pt-BR')}
                                        </div>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-${item.status === 'Aprovado' ? 'success' : 'danger'}">
                                                    ${item.status}
                                                </span>
                                                <small class="text-muted">#${index + 1}</small>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="mb-1"><i class="fas fa-tint me-2"></i>${item.volume_coletado}ml</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-1"><i class="fas fa-prescription-bottle me-2"></i>${item.hemoglobina}g/dL</p>
                                                </div>
                                            </div>
                                            ${item.observacoes ? `
                                                <div class="alert alert-light mt-2 p-2">
                                                    <i class="fas fa-comment-dots me-2"></i>${item.observacoes}
                                                </div>` : ''}
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            Swal.fire({
                title: 'Histórico de Doações',
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