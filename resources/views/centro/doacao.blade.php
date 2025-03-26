@extends('centro.main')

@section('title', 'Doações - Centro de Coleta')
@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* Mantenha todos os estilos anteriores e adicione estes */
    .table-donations td, .table-donations th {
        vertical-align: middle;
    }
    .badge-status {
        min-width: 90px;
    }
</style>
@endsection

@section('conteudo')
<div class="container">
    <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Registo de Doações</h4>
    <div class="d-flex gap-2">
        <a href="" class="btn btn-custom">
            <i class="fas fa-file-pdf me-2"></i> Exportar PDF
        </a>
        <a href="" class="btn btn-custom">
            <i class="fas fa-plus me-2"></i> Nova Doação
        </a>
    </div>
</div>
        
        <div class="card-body">
            <!-- Filtro por Status -->
            <form method="GET" action="" class="mb-3">
                <div class="d-flex align-items-center gap-2">
                    <label class="fw-bold">Filtrar por:</label>
                    <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Todos status</option>
                        <option value="Aprovado" {{ request('status') == 'Aprovado' ? 'selected' : '' }}>Aprovado</option>
                        <option value="Reprovado" {{ request('status') == 'Reprovado' ? 'selected' : '' }}>Reprovado</option>
        
                    </select>
                </div>
            </form>

            <!-- Tabela de Doações -->
            <div class="table-responsive">
                <table class="table table-hover table-donations">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Doador</th>
                            <th>Volume (ml)</th>
                            <th>Hemoglobina</th>
                            <th>Pressão</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doacoes as $doacao)
                            <tr>
                                <td>{{ $doacao->data_doacao}}</td>
                                <td>{{ $doacao->doador->nome }}</td>
                                <td>{{ $doacao->Volume_coletado }} ml</td>
                                <td>{{ $doacao->hemoglobina }} g/dL</td>
                                <td>{{ $doacao->pressao_arterial }}</td>
                                <td>
                                    <span class="badge badge-status bg-@switch($doacao->status)
                                        @case('Aprovado')success @break
                                        @case('Pendente')warning @break
                                        @default('danger')
                                    @endswitch">
                                        {{ $doacao->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle" 
                                                style="background-color: rgba(198, 66, 66, 0.95); color:white;" 
                                                data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="">
                                                    <i class="fas fa-edit me-2"></i>Editar
                                                </a>
                                            </li>
                                            <li>
                                                <form id="delete-form-{{ $doacao->id_doacao }}" 
                                                    action="" 
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <a class="dropdown-item text-danger" href="#" 
                                                    onclick="confirmarExclusao(event, {{ $doacao->id_doacao }})">
                                                    <i class="fas fa-trash me-2"></i>Excluir
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        Nenhuma doação registrada.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if ($doacoes->hasPages())
                <nav aria-label="Page navigation" class="mt-3">
                    <ul class="pagination justify-content-center mb-0">
                        {{-- Botão Anterior --}}
                        @if ($doacoes->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $doacoes->previousPageUrl() }}">&laquo;</a>
                            </li>
                        @endif

                        {{-- Números das Páginas --}}
                        @foreach ($doacoes->getUrlRange(1, $doacoes->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $doacoes->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        {{-- Botão Próximo --}}
                        @if ($doacoes->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $doacoes->nextPageUrl() }}">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmarExclusao(event, doacaoId) {
        event.preventDefault();
        Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c62828',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, excluir!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${doacaoId}`).submit();
            }
        });
    }
</script>
@endsection