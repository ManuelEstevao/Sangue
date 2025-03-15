@extends('dador.DashbordDador')

@section('title', 'Dashboard - ConectaDador')
<link href="assets/img/flavicon.png" rel="icon">

@section('styles')
<style>
    /* Estilos personalizados */
    :root {
        --red-theme: rgb(165, 3, 3);
        --hover-light: #ffe5e5;
        --btn-hover: #f8d7da;
    }
    .card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        justify-content: space-between;
    }
    .card-col {
        flex: 1;
        min-width: 250px;
    }
    .custom-card {
        background-color: var(--red-theme);
        color: #fff;
        border-radius: 8px;
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .custom-card:hover {
        transform: scale(1.02);
    }
    .custom-card .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .icon-box {
        font-size: 48px;
        margin-bottom: 1rem;
    }
    .btn-light {
        background-color: #fff;
        color: var(--red-theme);
        border: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .btn-light:hover {
        background-color: var(--hover-light);
    }
    .btn-sm {
        font-size: 0.875rem;
    }
    .btn-info {
        background-color: #17a2b8;
        border: none;
    }
    .btn-info:hover {
        background-color: #138496;
    }
    .btn-danger {
        background-color: #dc3545;
        border: none;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
    .pagination {
    display: flex;
    justify-content: center;
    margin-top: 3px;
    padding: 5px;
}

.pagination .page-item .page-link {
    border-radius: 5px;
    margin: 0 4px;
    border: 1px solid ;
    transition: all 0.3s ease;
}





</style>
@endsection

@section('conteudo')
    <div class="container-fluid">
        <div class="p-4">
            <!-- Linha de Cards -->
            <div class="card-container">
                <!-- Card 1: Próxima Doação -->
                <div class="card-col">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-box">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                            <h5 class="card-title">Próxima Doação</h5>
                            <p class="card-text">
    @if($proximaDoacao)
        {{ \Carbon\Carbon::parse($proximaDoacao->data_agendada . ' ' . $proximaDoacao->horario)->format('d/m/Y H:i') }}<br>
    @elseif($ultimaDoacao)
        Próxima doação disponível: 
        {{ \Carbon\Carbon::parse($ultimaDoacao->data_realizada)->addDays(90)->format('d/m/Y') }}
    @else
        Nenhuma doação agendada
    @endif
</p>

<a href="{{ route('agendamento') }}" class="btn btn-light mt-1 {{ $proximaDoacao ? 'disabled' : '' }}">
    Agendar <i class="fa-solid fa-calendar-plus ms-1"></i>
</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Doações Realizadas -->
                <div class="card-col">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-box">
                                <i class="fa-solid fa-hand-holding-medical"></i>
                            </div>
                            <h5 class="card-title">Doações Realizadas</h5>
                            <p class="card-text">
                                Total: {{ $totalDoacoes }} doações<br>
                            </p>
                            <a href="{{ route('historico') }}" class="btn btn-light mt-1">
                                Ver Histórico <i class="fa-solid fa-history ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Cartão Digital -->
                <div class="card-col">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-box">
                                <i class="fa-solid fa-id-card"></i>
                            </div>
                            <h5 class="card-title">Cartão Digital</h5>
                            <p class="card-text"></p>
                            <a href="#" class="btn btn-light mt-1">
                                Visualizar Cartão <i class="fa-solid fa-id-badge ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Últimos Agendamentos -->
            <div class="mt-5">
                <h4>Últimos Agendamentos</h4>
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Centro</th>
                                <th>Estado</th>
                                <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agendamentos as $agendamento)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($agendamento->data_agendada)->format('d/m/Y') }} 
                                </td>
                                <td>
                                {{ \Carbon\Carbon::parse($agendamento->horario)->format('H:i') }}
                                </td>
                                <td>{{ $agendamento->centro->nome }}</td>
                                <td>
                                    @switch($agendamento->status)
                                        @case('Agendado')
                                            <span class="">Agendado</span>
                                            @break
                                        @case('Concluído')
                                            <span class="badge bg-success text-white">Concluído</span>
                                            @break
                                        @case('Cancelado')
                                            <span class="badge bg-danger text-white">Cancelado</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                 <!-- Botão de Editar (abre o modal de edição) --> 
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $agendamento->id_agendamento}}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <!-- Botão de Cancelar --> 
                                <form action="{{ route('agendamento.cancelar', $agendamento->id_agendamento) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja cancelar este agendamento?')">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Nenhum agendamento encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $agendamentos->links('pagination::bootstrap-4') }}
                </div>

            </div>
        </div>
    </div>
@foreach ($agendamentos as $agendamento)
<div class="modal fade" id="editModal{{ $agendamento->id_agendamento }}" tabindex="-1" aria-labelledby="editModalLabel{{ $agendamento->id_agendamento }}" aria-hidden="true">
     <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $agendamento->id_agendamento }}">Editar Agendamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
            <form action="{{ route('agendamento.update', ['id' => $agendamento->id_agendamento]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-body">
                                    <label class="form-label">Data da Doação</label>
                                    <input type="datetime-local" class="form-control" name="data_agendada" 
                                    value="{{ \Carbon\Carbon::parse($agendamento->data_agendada . ' ' . $agendamento->horario)->format('Y-m-d\TH:i') }}">


                                    <label class="form-label mt-2">Centro de Doação</label>
                                    <select name="id_centro" class="form-control">
                                        @foreach($centros as $centro)
                                            <option value="{{ $centro->id }}" @if($agendamento->id_centro == $centro->id) selected @endif>
                                                {{ $centro->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                </div>
    </div>
</div>
@endforeach
@endsection
