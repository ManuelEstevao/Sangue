@extends('ADM.main')

@section('title', 'Dashboard Admin')

@section('conteudo')
<div class="container-fluid">
    <!-- Cards de Métricas -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-primary shadow">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-users"></i> Doadores
                    </h5>
                    <h2>{{ number_format($metrics['total_doadores']) }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card border-success shadow">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        <i class="fas fa-hospital"></i> Centros
                    </h5>
                    <h2>{{ $metrics['total_centros'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-info shadow">
                <div class="card-body">
                    <h5 class="card-title text-info">
                        <i class="fas fa-syringe"></i> Doações Hoje
                    </h5>
                    <h2>{{ $metrics['doacoes_hoje'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-danger shadow">
                <div class="card-body">
                    <h5 class="card-title text-danger">
                        <i class="fas fa-exclamation-triangle"></i> Urgências
                    </h5>
                    <h2>{{ $metrics['solicitacoes_urgentes'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos e Dados -->
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Histórico de Doações</h5>
                </div>
                <div class="card-body">
                    <canvas id="donationsChart" 
                        data-labels='@json($donationsHistory->pluck('label'))'
                        data-values='@json($donationsHistory->pluck('total'))'>
                    </canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Estoque Crítico</h5>
                </div>
                <div class="card-body">
                    @forelse($criticalStock as $estoque)
                    <div class="alert alert-warning mb-2">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $estoque['tipo'] }}</strong>
                            <span>{{ $estoque['quantidade'] }} bolsas</span>
                        </div>
                        <small class="text-muted">{{ $estoque['centro'] }} - {{ $estoque['cidade'] }}</small>
                    </div>
                    @empty
                    <div class="text-center text-muted p-3">
                        Nenhum estoque crítico 🎉
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Últimas Doações -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Últimas Doações</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Doador</th>
                                    <th>Tipo Sanguíneo</th>
                                    <th>Centro</th>
                                    <th>Quantidade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDonations as $doacao)
                                <tr>
                                    <td>{{ $doacao['data'] }}</td>
                                    <td>{{ $doacao['doador'] }}</td>
                                    <td>{{ $doacao['tipo_sanguineo'] }}</td>
                                    <td>{{ $doacao['centro'] }}</td>
                                    <td>{{ $doacao['quantidade'] }} bolsas</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Doações
    const ctx = document.getElementById('donationsChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: JSON.parse(ctx.dataset.labels),
            datasets: [{
                label: 'Doações por Mês',
                data: JSON.parse(ctx.dataset.values),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>

<style>
.card {
    border-radius: 0.5rem;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.alert {
    border-radius: 0.5rem;
    border-left: 4px solid #ffc107;
}
</style>
@endsection