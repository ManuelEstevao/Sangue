@extends('centro.main')

@section('title', 'Agendamentos - Centro de Coleta')
@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .badge-status {
        min-width: 90px;
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('conteudo')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Agendamentos</h4>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Novo Agendamento
            </a>
        </div>
        <div class="card-body">
            <!-- Filtro por Tipo Sanguíneo -->
            <form method="GET" action="{{ route('centro.agendamento') }}" class="mb-3">
                <div class="d-flex align-items-center gap-2">
                    <label class="fw-bold">Filtrar por:</label>
                    <select name="tipo_sanguineo" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Todos tipos</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $tipo)
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
                            <td> {{ \Carbon\Carbon::parse($agendamento->data_agendada)->format('d/m/Y') }}</td>
                            <td> {{ \Carbon\Carbon::parse($agendamento->horario)->format('H:i') }}</td>
                                <td>{{ $agendamento->doador->nome }}</td>
                                <td>{{ $agendamento->doador->tipo_sanguineo }}</td>
                                <td>
                                    <span class="badge badge-status bg-{{ $agendamento->status === 'Agendado' ? 'primary' : ($agendamento->status === 'confirmado' ? 'success' : 'danger') }}">
                                        {{ ucfirst($agendamento->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" 
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($agendamento->status !== 'confirmado')
                                            <li>
                                                <button class="dropdown-item text-success" 
                                                        onclick="iniciarTriagem({{ $agendamento->id_agendamento }})">
                                                    <i class="fas fa-check me-2"></i>Confirmar Doação
                                                </button>
                                            </li>
                                            @endif
                                            
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
                                            
                                            <li><hr class="dropdown-divider"></li>
                                            
                                            <li>
                                                <button class="dropdown-item text-primary" 
                                                        onclick="verHistorico({{ $agendamento->doador->id }})">
                                                    <i class="fas fa-history me-2"></i>Histórico
                                                </button>
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

            <!-- Paginação -->
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function handleAgendamentoAction(url, agendamentoId, successMessage) {
        Swal.fire({
            title: 'Tem certeza?',
            text: successMessage,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Erro na operação');
                    return data;
                })
                .then(data => {
                    const statusBadge = document.querySelector(`tr:has(button[onclick*="${agendamentoId}"]) .badge`);
                    if(statusBadge && data.success) {
                        statusBadge.textContent = data.new_status;
                        statusBadge.className = `badge badge-status bg-${data.new_color}`;
                    }
                    Swal.fire('Sucesso!', data.message || 'Operação realizada com sucesso', 'success');
                })
                .catch(error => {
                    Swal.fire('Erro!', error.message || 'Falha na operação', 'error');
                });
            }
        });
    }


    function confirmarCancelamento(event, agendamentoId) {
        event.preventDefault(); 

        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja cancelar este agendamento?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, cancelar',
            cancelButtonText: 'Não'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById(`cancelar-form-${agendamentoId}`);
                if (form) {
                    form.submit();
                } else {
                    console.error(`Formulário não encontrado para o agendamento ${agendamentoId}`);
                }
            }
        });
    }
    function verHistorico(doadorId) {
        fetch(`/doador/historico/${doadorId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const historicoFormatado = data.map(item => `
                        <div class="historico-item">
                            <strong>Data:</strong> ${new Date(item.data_agendamento).toLocaleDateString('pt-BR')}<br>
                            <strong>Tipo:</strong> ${item.tipo_doacao}<br>
                            <strong>Status:</strong> ${item.status}
                        </div>
                        <hr>
                    `).join('');

                    Swal.fire({
                        title: `Histórico do Doador`,
                        html: historicoFormatado,
                        icon: 'info',
                        confirmButtonText: 'Fechar'
                    });
                } else {
                    Swal.fire({
                        title: 'Histórico do Doador',
                        text: 'Nenhum histórico encontrado.',
                        icon: 'warning',
                        confirmButtonText: 'Fechar'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Erro',
                    text: 'Não foi possível carregar o histórico.',
                    icon: 'error',
                    confirmButtonText: 'Fechar'
                });
            });
    }
</script>
@endsection