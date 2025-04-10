@extends('centro.main')

@section('title', 'Gestão de Solicitações de Sangue')

@section('conteudo')
<div class="container-fluid py-4">
    <!-- Cabeçalho e Estatísticas -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-0  fw-bold">
                Solicitações de Sangue
            </h1>
        </div>
        <button class="btn btn-danger">
            <i class="fas fa-plus me-2"></i>Nova Solicitação
        </button>
    </div>

    <!-- Cards de Métricas Interativas -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-start border-4 border-danger hover-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class=" bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-bell text-danger"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Solicitações Ativas</h6>
                            <h3 class="fw-bold mb-0">12</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-4 border-warning hover-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class=" bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-clock text-warning"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Urgência Média</h6>
                            <h3 class="fw-bold mb-0">Alta</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-4 border-info hover-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class=" bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-stopwatch text-info"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Tempo de Resposta</h6>
                            <h3 class="fw-bold mb-0">2h</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-4 border-success hover-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class=" bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-users text-success"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Doadores Ativos</h6>
                            <h3 class="fw-bold mb-0">36</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Solicitações Aprimorada -->
    <div class="card border-0 shadow">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Solicitações Recentes</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm">
                        <option>Todos os Tipos</option>
                        <option>A+</option>
                        <option>O-</option>
                    </select>
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row g-4">
                @foreach(range(1, 6) as $i)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 border-0 hover-shadow">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-danger">URGENTE</span>
                                    <h5 class="mt-2 mb-1">Tipo: <span class="text-danger">O+</span></h5>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">#{{ 4500 + $i }}</small>
                                    <div class="text-success small">Ativo</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="progress thin-progress">
                                    <div class="progress-bar bg-danger" style="width: {{ ($i*15) }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between small text-muted mt-1">
                                    <span>Solicitado: 5</span>
                                    <span>Atendido: {{ $i }}</span>
                                </div>
                            </div>

                            <ul class="list-unstyled mb-4">
                                <li class="mb-2">
                                    <i class="fas fa-hospital me-2 text-muted"></i>
                                    Hospital Central
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-clock me-2 text-muted"></i>
                                    Prazo: 10/04/2025
                                </li>
                                <li>
                                    <i class="fas fa-user me-2 text-muted"></i>
                                    Doadores necessários: 3
                                </li>
                            </ul>

                            <div class="d-grid">
                                <button class="btn btn-outline-danger">
                                    <i class="fas fa-notes-medical me-2"></i>Ver Detalhes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .metric-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 12px !important;
    }

    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .hover-shadow {
        transition: box-shadow 0.2s ease;
    }

    .hover-shadow:hover {
        box-shadow: 0 4px 16px rgba(220,53,69,0.15) !important;
    }

    .thin-progress {
        height: 6px;
        border-radius: 3px;
    }

    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    .hover-scale {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .hover-scale:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .border-start {
            border-left-width: 4px !important;
        }

        .bg-opacity-10 {
            background-color: rgba(var(--bs-primary-rgb), 0.1);
        }
</style>
@endsection