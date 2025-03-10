@extends('centro.main')

@section('title', 'Agendamentos - Centro de Coleta')
<!-- CSS do SweetAlert -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- JavaScript do SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            <form method="GET" action="#" class="mb-3">
                <div class="d-flex align-items-center">
                    <label class="me-2 fw-bold">Filtrar por Tipo Sanguíneo:</label>
                    <select name="tipo_sanguineo" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
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
                                <td>{{ $agendamento->data_agendamento->format('d/m/Y') }}</td>
                                <td>{{ $agendamento->hora_agendamento }}</td>
                                <td>{{ $agendamento->doador->nome }}</td>
                                <td>{{ $agendamento->doador->tipo_sanguineo }}</td>
                                <td>
                                    <span class="badge bg-{{ $agendamento->status === 'pendente' ? 'primary' : ($agendamento->status === 'confirmado' ? 'success' : 'danger') }}">
                                        {{ ucfirst($agendamento->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-success" title="Confirmar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Cancelar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info" title="Ver Histórico" onclick="verHistorico({{ $agendamento->doador->id }})">
                                            <i class="fas fa-history"></i>
                                        </button>
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
<script>
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