@extends('centro.main')

@section('title', 'Relatórios')

@section('conteudo')
<div class="container mt-4">
    <h2 class="text-center">Relatórios de Doação</h2>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Doações por Tipo Sanguíneo</h5>
                </div>
                <div class="card-body">
                    <canvas id="doacoesPorSangueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Doações na Última Semana</h5>
                </div>
                <div class="card-body">
                    <canvas id="doacoesPorDiaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const doacoesPorSangue = @json($doacoesPorSangue);
        const doacoesPorDia = @json($doacoesPorDia);

        // Gráfico de Doações por Tipo Sanguíneo
        new Chart(document.getElementById('doacoesPorSangueChart'), {
            type: 'pie',
            data: {
                labels: doacoesPorSangue.map(d => d.tipo_sanguineo),
                datasets: [{
                    data: doacoesPorSangue.map(d => d.total),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#C9CBCF']
                }]
            }
        });

        // Gráfico de Doações por Dia
        new Chart(document.getElementById('doacoesPorDiaChart'), {
            type: 'line',
            data: {
                labels: doacoesPorDia.map(d => d.dia),
                datasets: [{
                    label: 'Doações',
                    data: doacoesPorDia.map(d => d.total),
                    borderColor: '#36A2EB',
                    fill: false
                }]
            }
        });
    });
</script>
@endsection
