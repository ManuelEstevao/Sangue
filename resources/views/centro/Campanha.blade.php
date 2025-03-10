@extends('centro.main')

@section('title', 'Campanhas - Centro de Coleta')
@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .campaign-status {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .campaign-card {
        transition: transform 0.3s ease;
        border-left: 4px solid #dc3545;
    }
    .campaign-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('conteudo')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Gestão de Campanhas</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('centro.Dashbord') }}">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="fas fa-chevron-right"></i>
            </li>
            <li class="nav-item">Campanhas</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Campanhas Ativas</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCampanha">
                        <i class="fas fa-plus me-2"></i>Nova Campanha
                    </button>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Período</th>
                                    <th>Status</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campanhas as $campanha)
                                <tr class="campaign-card">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($campanha->foto)
                                            <img src="{{ Storage::url($campanha->foto) }}" 
                                                 alt="{{ $campanha->titulo }}"
                                                 class="rounded me-3"
                                                 style="width: 60px; height: 40px; object-fit: cover">
                                            @endif
                                            {{ $campanha->titulo }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ $campanha->data_inicio}} - 
                                        {{ $campanha->data_fim}}
                                    </td>
                                    <td>
                                        @php
                                            $hoje = now();
                                            $status = $hoje > $campanha->data_fim ? 'Encerrada' : 
                                                    ($hoje < $campanha->data_inicio ? 'Programada' : 'Ativa');
                                        @endphp
                                        <span class="badge campaign-status bg-{{ 
                                            $status == 'Ativa' ? 'success' : 
                                            ($status == 'Programada' ? 'warning' : 'secondary') 
                                        }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-link text-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalCampanha"
                                                    onclick="carregarEdicao({{ $campanha->id_campanha }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('campanhas.destroy', $campanha->id_campanha) }}" 
                                                  method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger"
                                                        onclick="return confirm('Tem certeza que deseja excluir?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="alert alert-info mb-0">
                                            Nenhuma campanha cadastrada
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $campanhas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('centro.modalCampanha')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
<script>
    // Configuração do DatePicker
    flatpickr.localize(flatpickr.l10ns.pt);
    const configDate = {
        dateFormat: 'd/m/Y',
        locale: 'pt'
    };
    
    flatpickr('#data_inicio', configDate);
    flatpickr('#data_fim', configDate);

    // Função para carregar dados para edição
    function carregarEdicao(id) {
        fetch(`/campanhas/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.querySelector('#modalCampanha [name="titulo"]').value = data.titulo;
                document.querySelector('#modalCampanha [name="descricao"]').value = data.descricao;
                document.querySelector('#modalCampanha [name="data_inicio"]').value = 
                    new Date(data.data_inicio).toLocaleDateString('pt-BR');
                document.querySelector('#modalCampanha [name="data_fim"]').value = 
                    new Date(data.data_fim).toLocaleDateString('pt-BR');
                
                const form = document.querySelector('#modalCampanha form');
                form.action = `/campanhas/${data.id_campanha}`;
                form.method = 'POST';
                form.innerHTML += '<input type="hidden" name="_method" value="PUT">';
                
                new bootstrap.Modal(document.getElementById('modalCampanha')).show();
            });
    }

    // Resetar formulário ao abrir modal para nova campanha
    document.getElementById('modalCampanha').addEventListener('hidden.bs.modal', function () {
        const form = this.querySelector('form');
        form.reset();
        form.action = "{{ route('campanhas.store') }}";
        form.method = 'POST';
        const methodInput = form.querySelector('[name="_method"]');
        if(methodInput) methodInput.remove();
    });
</script>
@endsection