@extends('dador.DashbordDador')

@section('title', 'Dashboard - ConectaDador')

@section('styles')
<style>
    /* Estilos personalizados */
    :root {
        --red-theme: rgb(165, 3, 3);
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
        background-color: #ffe5e5;
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
                                    {{ $proximaDoacao->data_agendada->format('d/m/Y H:i') }}<br>
                                @else
                                    Nenhuma doação agendada
                                @endif
                            </p>
                            <a href="#" class="btn btn-light mt-1">
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
                            <a href="#" class="btn btn-light mt-1">
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
                            <p class="card-text">
                               
                            </p>
                           
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
                            <th>Local</th>
                            <th>Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agendamentos as $agendamento)
                            <tr>
                                <td>{{ $agendamento->data_agendada->format('d/m/Y H:i') }}</td>
                                <td>{{ $agendamento->centro->nome }}</td>
                                <td>
                                    @switch($agendamento->status)
                                        @case('Agendado')
                                            <span class="badge bg-warning">Agendado</span>
                                            @break
                                        @case('Concluído')
                                            <span class="badge bg-success">Concluído</span>
                                            @break
                                        @case('Cancelado')
                                            <span class="badge bg-danger">Cancelado</span>
                                            @break
                                    @endswitch
                                </td>
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Nenhum agendamento encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $agendamentos->links() }}
            </div>
        </div>
    </div>
@endsection