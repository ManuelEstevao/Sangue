@extends('centro.main')

@section('title', 'Doadores - Centro de Coleta')
@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .table-donations {
        table-layout: fixed;
        width: 100%;
        margin-bottom: 0;
    }
    
    .table-donations th {
        background-color: #f8f9fa;
        font-weight: 500;
        padding: 0.75rem;
        white-space: nowrap;
    }

    .table-donations td {
        padding: 0.75rem;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 768px) {
        .mobile-hidden {
            display: none;
        }
        
        .table-donations th,
        .table-donations td {
            font-size: 0.9rem;
            padding: 0.6rem;
            white-space: normal;
        }

        .card-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .btn-custom {
            width: 100%;
            text-align: center;
        }
    }

    .btn-custom {
        background-color: rgba(198, 66, 66, 0.9);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
    }

    .dropdown-toggle {
        padding: 0.3rem 0.6rem;
    }

    .text-truncate {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endsection

@section('conteudo')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Lista de Doadores</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('centro.doador.pdf', request()->query()) }}" class="btn btn-custom">
                    <i class="fas fa-file-pdf me-2"></i> Exportar PDF
                </a>
               <!--  <a href="#" class="btn btn-custom">
                    <i class="fas fa-plus me-2"></i> Novo Doador
                </a>-->
            </div>
        </div>
        
        <div class="card-body">
        <form method="GET" action="{{ route('listar.doador') }}" class="mb-3">
            <div class="d-flex justify-content-between align-items-center gap-3">


            <div class="d-flex align-items-center gap-2" style="max-width: 300px;">
                    <div class="input-group">
                        <input type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Nome do doador..." 
                            value="{{ request('search') }}"
                            aria-label="Buscar doador">
                        <button type="submit" class="btn btn-custom ">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Filtro de Status (Esquerda) -->
                <div class="d-flex align-items-center gap-2">
                    <label class="fw-bold">Filtrar por:</label>
                    <select name="tipo_sanguineo" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Todos tipos</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $tipo)
                            <option value="{{ $tipo }}" {{ request('tipo_sanguineo') == $tipo ? 'selected' : '' }}>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Barra de Pesquisa (Direita) -->
               
            </div>
        </form>

            <!-- Tabela de Doadores -->
            <div class="table-responsive">
                <table class="table table-hover table-donors">
                <thead>
                        <tr>
                            <th >Nome</th>
                            <th class="mobile-hidden">Idade</th>
                            <th >Gênero</th>
                            <th class="mobile-hidden">Tipo </th>
                            <th >Telefone</th>
                            <th >E-mail</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doadores as $doador)
                        <tr>
                            <td>{{ $doador->nome }}</td>
                            <td>{{ \Carbon\Carbon::parse($doador->data_nascimento)->age }} anos</td>
                            <td>{{ ucfirst($doador->genero) }}</td>
                            <td>{{ $doador->tipo_sanguineo }}</td>
                            <td>{{ $doador->telefone }}</td>
                            <td>{{ $doador->user->email }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle" style="background-color: rgba(198, 66, 66, 0.95); color: white;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item text-primary" href="">
                                                <i class="fas fa-edit me-2"></i>Editar
                                            </a>
                                        </li>
                                        <li>
                                            <form id="delete-form-{{ $doador->id }}" action="" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <a class="dropdown-item text-danger" href="#" onclick="confirmarExclusao(event, {{ $doador->id }})">
                                                    <i class="fas fa-trash me-2"></i>Excluir
                                                </a>
                                            </form>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-info" href="">
                                                <i class="fas fa-eye me-2"></i>Visualizar
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
                                    Nenhum doador encontrado.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if ($doadores->hasPages())
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination justify-content-center mb-0">
                    {{-- Botão Anterior --}}
                    @if ($doadores->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">&laquo;</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $doadores->previousPageUrl() }}">&laquo;</a>
                    </li>
                    @endif

                    {{-- Números das Páginas --}}
                    @foreach ($doadores->getUrlRange(1, $doadores->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $doadores->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach

                    {{-- Botão Próximo --}}
                    @if ($doadores->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $doadores->nextPageUrl() }}">&raquo;</a>
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
document.querySelectorAll('select[name="tipo_sanguineo"]').forEach(select => {
    select.addEventListener('change', function() {
        this.form.submit();
    });
});
    function confirmarExclusao(event, doadorId) {
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
                document.getElementById(`delete-form-${doadorId}`).submit();
            }
        });
    }

</script>
@endsection
