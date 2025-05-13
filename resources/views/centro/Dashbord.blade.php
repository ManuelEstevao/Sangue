@extends('centro.main')
@section('title', 'Dashboard ')
<link href="assets/img/flavicon.png" rel="icon">
@section('styles')
<style>
.fc-day-blocked {
    background: repeating-linear-gradient(
        45deg,
        rgba(220, 53, 69, 0.1),
        rgba(220, 53, 69, 0.1) 10px,
        rgba(220, 53, 69, 0.05) 10px,
        rgba(220, 53, 69, 0.05) 20px
    );
}

.fc-blocked-day {
    text-align: center;
    padding:5px;
   
}
.fc-blocked-container {
    cursor: pointer;
    height: 100%;
    width: 100%;
}

.fc-blocked-day i {
    color: #dc3545;
    font-size: 10px;
}

.fc-blocked-day small {
    display: block;
    font-size: 0.75em;
    color: #666;
}
.fc-day-blocked {
    position: relative;
    cursor: default;
}

.custom-tooltip {
    pointer-events: none;
    white-space: nowrap;
}
</style>
@endsection
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
                                <p class="card-category">Solicitações atendidas</p>
                                <h4 class="card-title">{{ $totalSolicitacoesAtendidas }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
        <!-- Calendário -->
        <div class="col-md-6 mb-4">
            <div class="card h-70">
                <div class="card-header">
                    <h4 class="card-title">Dias Bloqueados</h4>
                </div>
                <div class="card-body" >
                    <div id="calendar" style="height: 280px; " ></div>
                </div>
            </div>
        </div>

        <!-- Distribuição de Tipos Sanguíneos -->
        <div class="col-md-6 mb-4">
            <div class="card h-70">
                <div class="card-header">
                    <h4 class="card-title">Distribuição de Tipos Sanguíneos</h4>
                </div>
                <div class="card-body" style="position: relative; height: 54vh">
                    <canvas id="bloodTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda Linha: Doações Recentes e Status -->
    <div class="row">
        <!-- Doações nos Últimos 6 Meses -->
        <div class="col-md-6 mb-4">
            <div class="card h-70">
                <div class="card-header">
                    <h4 class="card-title">Doações nos Últimos 6 Meses</h4>
                </div>
                <div class="card-body">
                    <canvas id="donationTrendChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Status dos Agendamentos -->
        <div class="col-md-6 mb-4">
            <div class="card h-70">
                <div class="card-header">
                    <h4 class="card-title">Status dos Agendamentos</h4>
                </div>
                <div class="card-body">
                    <canvas id="appointmentStatusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.6/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/Centro/assets/js/plugin/chart-circle/circles.min.js') }}"></script>
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

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            contentHeight: 280,
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: ''
            },
            events: {
                url: '/centro/dias-bloqueados/' + {{ Auth::user()->centro->id_centro }},
                method: 'GET',
                failure: function() {
                    console.error('Erro ao carregar dias bloqueados!');
                }
            },
            dayHeaderFormat: { weekday: 'short' },
            eventDidMount: function(info) {
                try {
                    // Cria estrutura base
                    const container = document.createElement('div');
                    container.className = 'fc-blocked-container';
                    container.style.position = 'relative';
                    container.style.height = '100%';

                    // Conteúdo principal
                    container.innerHTML = `
                        <div class="fc-blocked-day">
                            <i class="fas fa-ban"></i>
                        </div>
                    `;
                    // Dentro do eventDidMount
                    let motivo = info.event.title;
        
                    // Verifica valores nulos, undefined ou string "null"
                    if (motivo === null || 
                        motivo === undefined || 
                        (typeof motivo === 'string' && motivo.toLowerCase() === 'null')) {
                        motivo = 'Sem motivo especificado';
                    }

                    // Tooltip
                    const tooltip = document.createElement('div');
                    tooltip.className = 'fc-blocked-tooltip';
                    tooltip.textContent = motivo;
                    
                    // Estilos do tooltip
                    Object.assign(tooltip.style, {
                        position: 'absolute',
                        background: 'rgba(0,0,0,0.9)',
                        color: 'white',
                        padding: '8px 12px',
                        borderRadius: '4px',
                        fontSize: '0.8em',
                        display: 'none',
                        zIndex: '1000',
                        pointerEvents: 'none',
                        whiteSpace: 'nowrap',
                        top: '0',
                        left: '0',
                        transition: 'opacity 0.2s'
                    });

                    // Eventos de mouse
                    container.addEventListener('mouseenter', (e) => {
                        const rect = container.getBoundingClientRect();
                        tooltip.style.display = 'block';
                        tooltip.style.top = `${e.clientY - rect.top + 15}px`;
                        tooltip.style.left = `${e.clientX - rect.left + 10}px`;
                    });

                    container.addEventListener('mousemove', (e) => {
                        const rect = container.getBoundingClientRect();
                        tooltip.style.top = `${e.clientY - rect.top + 15}px`;
                        tooltip.style.left = `${e.clientX - rect.left + 10}px`;
                    });

                    container.addEventListener('mouseleave', () => {
                        tooltip.style.display = 'none';
                    });

                    // Monta estrutura
                    container.appendChild(tooltip);
                    info.el.innerHTML = '';
                    info.el.appendChild(container);
                    info.el.classList.add('fc-day-blocked');

                } catch (error) {
                    console.error('Erro ao renderizar dia bloqueado:', error);
                }
            }
        });
        calendar.render();
    } else {
        console.error('Elemento do calendário não encontrado!');
    }
});
</script>
@endsection