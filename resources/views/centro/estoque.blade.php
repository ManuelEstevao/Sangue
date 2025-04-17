@extends('centro.main') 

@section('title', 'Gestão de Estoque')

@section('styles')
<style>
    .stock-header {
        background: linear-gradient(135deg, #ffe3e3, #ffffff);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .blood-type-badge {
        font-size: 0.9em;
        padding: 8px 15px;
        border-radius: 25px;
        min-width: 70px;
        text-align: center;
    }
    .stock-progress {
        height: 25px;
        border-radius: 15px;
        overflow: hidden;
    }
    .stock-alert {
        border-left: 4px solid #dc3545;
        padding-left: 1rem;
    }
    .stock-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .stock-card:hover {
        transform: translateY(-3px);
    }
</style>
@endsection

@section('conteudo')
<div class="page-inner">
    <div class="stock-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0 text-danger"><i class="fas fa-tint me-2"></i>Gestão de Estoque</h2>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#ajusteEstoqueModal">
                <i class="fas fa-exchange-alt me-2"></i>Ajustar Estoque
            </button>
        </div>
        
        <!-- Resumo Rápido -->
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="stock-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Total em Estoque</small>
                            <h3 class="mb-0">{{ $totalEstoque }}</h3>
                        </div>
                        <i class="fas fa-boxes text-danger fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stock-card p-3 stock-alert"
                    style="cursor: pointer;"
                    data-bs-toggle="modal"
                    data-bs-target="#movimentacoesModal"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Abrir histórico">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Últimas Movimentações</small>
                            <h3 class="mb-0">{{ $movimentacoes->count() }}</h3>
                        </div>
                        <i class="fas fa-history text-danger fs-4"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="stock-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Próximas Expirações</small>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="fas fa-clock text-danger fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela e Gráfico -->
    <div class="row">
        <div class="col-md-8">
            <div class="stock-card p-3">
                <h5 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Distribuição por Tipo Sanguíneo</h5>
                <canvas id="stockChart" height="160"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stock-card p-3">
                <h5 class="mb-3"><i class="fas fa-list-ul me-2"></i>Estoque Detalhado</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tbody>
                            @foreach($estoque as $item)
                            <tr>
                                <td>
                                    <span class="blood-type-badge bg-danger text-white">
                                        {{ $item->tipo_sanguineo }}
                                    </span>
                                </td>
                                <td>
                                    <div class="progress stock-progress">
                                        <div class="progress-bar bg-danger" 
                                            >
                                            {{ $item->quantidade }}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <small class="text-muted">{{ $item->ultima_atualizacao->diffForHumans() }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Ajustes (Atualizado) -->
<div class="modal fade" id="ajusteEstoqueModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form action="{{ route('estoque.ajustar') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title"><i class="fas fa-sliders me-2"></i>Ajuste de Estoque</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo Sanguíneo</label>
                            <select name="tipo_sanguineo" class="form-select" required>
                                @foreach($tiposSanguineos as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Operação</label>
                            <select name="operacao" class="form-select" id="operacaoSelect">
                                <option  value="+">Adicionar</option>
                                <option value="-">Remover</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Quantidade</label>
                            <div class="input-group">
                                <span class="input-group-text" id="operacaoSimbolo">+</span>
                                <input type="number" name="quantidade" 
                                       class="form-control" 
                                       placeholder="Quantidade de unidades" 
                                       min="1" 
                                       required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Motivo</label>
                            <select name="motivo" class="form-select" required>
                                <option value="doacao">Doação Recebida</option>
                                <option value="expiracao">Expiração</option>
                                <option value="perda">Perda/Descarte</option>
                                <option value="Transfusão">Transfusão</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observações</label>
                            <textarea name="observacao" class="form-control" rows="2" 
                                      placeholder="Detalhes do ajuste"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Ajuste</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Últimas Movimentações -->
<div class="modal fade" id="movimentacoesModal" tabindex="-1" aria-labelledby="movimentacoesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="fas fa-history me-2 text-danger"></i>Últimas Movimentações
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="max-height: 70vh; overflow-y: auto;"> <!-- Limita altura -->
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tipo</th>
                                <th>Qtd</th>
                                <th>Operação</th>
                                <th>Motivo</th>
                                <th>Obs</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movimentacoes as $mov)
                            <tr>
                                <td><span class="badge bg-danger">{{ $mov->tipo_sanguineo }}</span></td>
                                <td>{{ $mov->quantidade }}</td>
                                <td>
                                    @if($mov->operacao === '+')
                                        <span class="text-success fw-bold">+ Adição</span>
                                    @else
                                        <span class="text-danger fw-bold">- Remoção</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($mov->motivo) }}</td>
                                <td>{{ $mov->observacao ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($mov->created_at)->format("d/m/Y H:i") }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Nenhuma movimentação recente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ session('error') }}'
    });
</script>
@endif

@endsection

@section('scripts')
<script>
    // Controle do símbolo da operação
    document.getElementById('operacaoSelect').addEventListener('change', function() {
        document.getElementById('operacaoSimbolo').textContent = this.value;
    });
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Filtro para excluir tipos com quantidade 0
    const estoqueData = @json($estoque);

    const labels = [];
    const data = [];

    estoqueData.forEach(item => {
        if (item.quantidade > 0) {
            labels.push(item.tipo_sanguineo);
            data.push(item.quantidade);
        }
    });

    // Configuração do gráfico
    const ctx = document.getElementById('stockChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Unidades Disponíveis',
                data: data,
                backgroundColor: '#dc3545',
                borderWidth: 0,
                borderRadius: 5,
                barThickness: 25
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ` ${context.dataset.label}: ${context.raw} unidades`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value + ' un';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection