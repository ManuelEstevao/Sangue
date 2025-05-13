@extends('dador.DashbordDador')

@section('title', 'Histórico de Doação - ConectaDador')

@section('conteudo')
<div class="container-fluid" style="overflow-x: hidden;"> <!-- Adicionei overflow-x: hidden -->
    <!-- Cabeçalho -->
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
        <h2 class="h4 mb-0">
            <i class="fas fa-history me-2 text-danger"></i>
            Histórico de Doação
        </h2>
    </div>

    <!-- Tabela -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;"> <!-- Ajuste no scroll -->
                <table class="table table-hover mb-0" style="min-width: 992px;"> <!-- Largura mínima garantida -->
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4" style="min-width: 150px;">Data</th>
                            <th class="py-3" style="min-width: 200px;">Centro</th>
                            <th class="py-3" style="min-width: 100px;">Peso</th>
                            <th class="py-3" style="min-width: 120px;">Status</th>
                            <th class="py-3" style="min-width: 180px;">Profissional</th>
                            <th class="py-3 pe-4" style="min-width: 200px;">Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doacoes as $doacao)
                        <tr class="position-relative">
                            <td class="ps-4 align-middle text-nowrap">{{ date('d/m/Y H:i', strtotime($doacao->data_doacao)) }}</td>
                            <td class="align-middle">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                <span class="d-inline-block text-truncate" style="max-width: 180px;">
                                    {{ $doacao->agendamento->centro->nome ?? 'N/D' }}
                                </span>
                            </td>
                            <td class="align-middle text-nowrap">{{ number_format($doacao->agendamento->doador->peso, 2) }} kg</td>
                            <td class="align-middle">
                                <span class="badge bg-{{ $doacao->status == 'Aprovado' ? 'success' : 'danger' }} rounded-pill px-3 py-2">
                                    {{ ucfirst($doacao->status) }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <i class="fas fa-user-md text-muted me-2"></i>
                                <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                    {{ $doacao->nome_profissional ?? 'Não registado' }}
                                </span>
                            </td>
                            <td class="pe-4 align-middle">
                                <div class="text-truncate" style="max-width: 180px;" 
                                     data-bs-toggle="tooltip" 
                                     title="{{ $doacao->observacoes ?? 'Sem observações' }}">
                                    {{ $doacao->observacoes ?? 'Sem observações' }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p class="mb-0">Nenhuma doação registrada</p>
                                    <small>Seu histórico aparecerá aqui</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($doacoes->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-center">
                    {{ $doacoes->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Garante que o container principal não cause overflow */
    .container-fluid {
        max-width: 100vw;
    }
    
    /* Ajustes específicos para tabela */
    .table-responsive {
        scrollbar-width: thin;
        scrollbar-color: #dee2e6 transparent;
    }

    .table-responsive::-webkit-scrollbar {
        height: 8px;
        background: transparent;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 4px;
    }

    /* Mantém alinhamento vertical */
    .align-middle {
        vertical-align: middle !important;
    }

    /* Otimização para mobile */
    @media (max-width: 768px) {
        .table-responsive {
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
        }
        
        .text-truncate {
            max-width: 120px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ativar tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        tooltips.forEach(t => new bootstrap.Tooltip(t))
    })
</script>
@endsection