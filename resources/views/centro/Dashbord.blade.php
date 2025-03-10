@extends('centro.main')
@section('title', 'Dashboard ')

@section('conteudo')
<div class="container">
<h2 class="fw-bold mb-3">Visão geral</h3>
    
    <div class="row">
        <!-- Agendamentos do Dia -->
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Agendamentos do Dia</p>
                                <h4 class="card-title">{{ $agendamentosHoje }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Doações -->
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-tint"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total  de <br>Doações</p>
                                <h4 class="card-title">{{ $totalDoacoes }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campanhas -->
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total de Campanhas</p>
                                <h4 class="card-title">{{ $totalCampanhas }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solicitações -->
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                <i class="fas fa-ambulance"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total de Solicitações</p>
                                <h4 class="card-title">1</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Seção de Gráficos -->
        <div class="row mt-4">
            <!-- Gráfico 1: Distribuição de Tipos Sanguíneos -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Distribuição de Tipos Sanguíneos</h4>
                    </div>
                    <div class="card-body" style="position: relative; height: 54vh">
                        <canvas id="bloodTypeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico 2: Doações nos Últimos 6 Meses -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Doações nos Últimos 6 Meses</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="donationTrendChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico 3: Status dos Agendamentos -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Status dos Agendamentos</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="appointmentStatusChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico 1: Distribuição de Tipos Sanguíneos
    const bloodTypeChart = new Chart(document.getElementById('bloodTypeChart'), {
        type: 'pie',
        data: {
            labels: @json($distribuicaoTipos->pluck('tipo')),
            datasets: [{
                data: @json($distribuicaoTipos->pluck('total')),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#EB5757', '#2D9CDB'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 12,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.label}: ${context.raw} doações`
                    }
                }
            }
        }
    });

    // Gráfico 2: Tendência de Doações (Últimos 6 Meses)
    const donationTrendChart = new Chart(document.getElementById('donationTrendChart'), {
        type: 'line',
        data: {
            labels: @json($doacoesMensais->pluck('mes')),
            datasets: [{
                label: 'Doações',
                data: @json($doacoesMensais->pluck('total')),
                borderColor: '#FF6384',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Quantidade de Doações' }
                }
            }
        }
    });

    // Gráfico 3: Status dos Agendamentos
    const appointmentStatusChart = new Chart(document.getElementById('appointmentStatusChart'), {
        type: 'bar',
        data: {
            labels: @json($statusAgendamentos->pluck('status')),
            datasets: [{
                label: 'Quantidade',
                data: @json($statusAgendamentos->pluck('total')),
                backgroundColor: ['#4BC0C0', '#FFCE56', '#FF6384']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 10 }
                }
            }
        }
    });
});
</script>
@endsection