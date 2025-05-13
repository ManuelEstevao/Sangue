@extends('ADM.main')

@section('title', 'Dashboard Admin')

@section('conteudo')
<div class="container-fluid">
    <!-- Cards de Métricas -->
    <div class="row g-3 mb-4">
        <!-- Doadores -->
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card card-stats card-round border-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3">
                            <div class="numbers">
                                <p class="card-category">Doadores<br>Registrados</p>
                                <h4 class="card-title">{{ number_format($metrics['total_doadores'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Centros -->
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card card-stats card-round border-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-hospital fa-lg"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3">
                            <div class="numbers">
                                <p class="card-category">Centros de<br>Coleta</p>
                                <h4 class="card-title">{{ $metrics['total_centros'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doações -->
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card card-stats card-round border-info">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-syringe fa-lg"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3">
                            <div class="numbers">
                                <p class="card-category">Total de<br>Doações</p>
                                <h4 class="card-title">{{ $metrics['total_doacoes'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agendamentos -->
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card card-stats card-round border-warning">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-calendar-check fa-lg"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3">
                            <div class="numbers">
                                <p class="card-category">Agendamentos<br>Ativos</p>
                                <h4 class="card-title">{{ $metrics['total_agendamentos'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card card-stats card-round border-purple">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-purple bubble-shadow-small">
                            <i class="fas fa-bullhorn fa-lg"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3">
                        <div class="numbers">
                            <p class="card-category">Campanhas<br>Ativas</p>
                            <h4 class="card-title">{{ $metrics['total_campanhas'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Atendidas -->
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card card-stats card-round border-dark">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-dark bubble-shadow-small">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3">
                            <div class="numbers">
                                <p class="card-category">Solicitações<br>Atendidas</p>
                                <h4 class="card-title">{{ $metrics['solicitacoes_atendidas'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Gráficos Estáticos -->
<div class="row g-4">

<!-- Gráfico de Doadores Registrados por Mês -->
<div class="col-lg-8">
    <div class="card shadow-sm card-round">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-chart-bar me-2 text-primary"></i>
                Doadores Registrados por Mês
            </h5>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="monthlyDonorsBarChart"
                    data-labels='@json($chartData["donors_per_month"]->pluck("label") ?? [])'
                    data-values='@json($chartData["donors_per_month"]->pluck("total") ?? [])'>
                </canvas>
            </div>
        </div>
    </div>
</div>

<!-- Distribuição de Tipos Sanguíneos -->
<div class="col-lg-4">
    <div class="card shadow-sm card-round">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-tint me-2 text-danger"></i>
                Distribuição por Tipo Sanguíneo
            </h5>
        </div>
        <div class="card-body">
            <canvas id="bloodTypeChart" 
                data-labels='@json($chartData["blood_types"]->pluck("tipo_sanguineo"))'
                data-values='@json($chartData["blood_types"]->pluck("total"))'>
            </canvas>
        </div>
    </div>
</div>

<!-- Gráfico de Doações por Centro -->

<div class="col-lg-8">
    <div class="card shadow-sm card-round">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-hospital me-2 text-primary"></i>
                Doações por Centro
            </h5>
        </div>
        <div class="card-body">
            <div class="chart-container" style="position: relative; height: 40vh; width: 100%;">
                <canvas id="centerDonationsChart" 
                    data-labels='@json($chartData["center_donations"]->pluck("nome"))'
                    data-values='@json($chartData["center_donations"]->pluck("doacoes_count"))'>
                </canvas>
            </div>
        </div>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Pizza - Tipos Sanguíneos
    const bloodTypeCtx = document.getElementById('bloodTypeChart');
    new Chart(bloodTypeCtx, {
        type: 'pie',
        data: {
            labels: JSON.parse(bloodTypeCtx.dataset.labels),
            datasets: [{
                data: JSON.parse(bloodTypeCtx.dataset.values),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#EB5757', '#2D9CDB'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Gráfico de Barras - Doações por Centro
    const centerCtx = document.getElementById('centerDonationsChart');
new Chart(centerCtx, {
    type: 'bar',
    data: {
        labels: JSON.parse(centerCtx.dataset.labels),
        datasets: [{
            label: 'Doações',
            data: JSON.parse(centerCtx.dataset.values),
            backgroundColor: '#4e73df',
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // Permite que o gráfico se ajuste ao tamanho do contêiner
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: { display: false }
        }
    }
});

   // Gráfico de Barras - Doadores por Mês
   const barCtx = document.getElementById('monthlyDonorsBarChart');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: JSON.parse(barCtx.dataset.labels),
            datasets: [{
                label: 'Novos Doadores',
                data: JSON.parse(barCtx.dataset.values),
                backgroundColor: '#4e73df',
                borderColor: '#2e59d9',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f8f9fa'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>

<style>
.card-round {
    border-radius: 15px;
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.icon-big {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-primary { background-color: #e3f2fd; color: #2196f3; }
.icon-success { background-color: #e8f5e9; color: #4caf50; }
.icon-info { background-color: #e0f7fa; color: #00bcd4; }
.icon-warning { background-color: #fff3e0; color: #ff9800; }
.icon-danger { background-color: #ffebee; color: #f44336; }
.icon-dark { background-color: #eceff1; color: #607d8b; }

.border-primary { border-left: 4px solid #2196f3 !important; }
.border-success { border-left: 4px solid #4caf50 !important; }
.border-info { border-left: 4px solid #00bcd4 !important; }
.border-warning { border-left: 4px solid #ff9800 !important; }
.border-danger { border-left: 4px solid #f44336 !important; }
.border-dark { border-left: 4px solid #607d8b !important; }

.card-category {
    font-size: 0.9rem;
    color: #6c757d;
    line-height: 1.2;
    margin-bottom: 0.25rem;
}

.card-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #2d3436;
}

.bubble-shadow-small {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f8f9fa;
}

.icon-purple { background-color: #f3e5f5; color: #9c27b0; }
.border-purple { border-left: 4px solid #9c27b0 !important; }
</style>
@endsection