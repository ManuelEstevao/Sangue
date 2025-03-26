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
    .btn-edit {
        padding: 0.25rem 0.5rem;
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
                                    <th>Descrição</th>
                                    <th>Data</th>
                                    <th>Horário</th>
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
                                            <img src="{{ asset('storage/' . $campanha->foto) }}" 
                                                alt="{{ $campanha->titulo }}"
                                                class="rounded me-3"
                                                style="width: 60px; height: 40px; object-fit: cover"
                                                onerror="this.style.display='none'">
                                            @endif
                                            {{ $campanha->titulo }}
                                        </div>
                                    </td>
                                     <!-- Nova Coluna: Descrição -->
                                    <td class="text-truncate" style="max-width: 200px;">
                                        {{ $campanha->descricao }}
                                    </td>

                                    <!-- Coluna Data Atualizada -->
                                    <td>
                                        @if(\Carbon\Carbon::parse($campanha->data_inicio)->isSameDay($campanha->data_fim))
                                            {{ \Carbon\Carbon::parse($campanha->data_inicio)->format('d/m/Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($campanha->data_inicio)->format('d/m/Y') }} - 
                                            {{ \Carbon\Carbon::parse($campanha->data_fim)->format('d/m/Y') }}
                                        @endif
                                    </td>

                                    <!-- Nova Coluna: Horário -->
                                    <td>
                                        {{ \Carbon\Carbon::parse($campanha->hora_inicio)->format('H:i') }} às 
                                        {{ \Carbon\Carbon::parse($campanha->hora_fim)->format('H:i') }}
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
                                            <button class="btn btn-sm btn-link text-primary btn-edit" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalCampanha"
                                                    onclick="carregarEdicao({{ $campanha->id_campanha }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <div class="d-flex justify-content-center">
                                                <form class="delete-form" 
                                                    action="{{ route('campanhas.destroy', $campanha->id_campanha) }}" 
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-link text-danger" 
                                                            onclick="return confirmDelete(event)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
<script>
    let datepickerInicio, datepickerFim;

    // Inicialização do Flatpickr
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr.localize(flatpickr.l10ns.pt);
        
        datepickerInicio = flatpickr('#data_inicio', {
            dateFormat: 'd/m/Y',
            locale: 'pt'
        });
        
        datepickerFim = flatpickr('#data_fim', {
            dateFormat: 'd/m/Y',
            locale: 'pt'
        });
    });

    // Função de Confirmação de Exclusão
    function confirmDelete(event) {
        event.preventDefault();
        const form = event.target.closest('form');
        
        Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Função de Carregamento para Edição
    function carregarEdicao(id) {
    fetch(`/campanhas/${id}/edit`)
        .then(response => {
            if (!response.ok) throw new Error('Falha ao carregar dados');
            return response.json();
        })
        .then(data => {
            const modal = document.getElementById('modalCampanha');
            const form = modal.querySelector('form');
            
            // Preencher todos os campos
            form.querySelector('[name="titulo"]').value = data.titulo;
            form.querySelector('[name="descricao"]').value = data.descricao;
            form.querySelector('[name="hora_inicio"]').value = data.hora_inicio;
            form.querySelector('[name="hora_fim"]').value = data.hora_fim;

            // Configurar datas
            datepickerInicio.setDate(data.data_inicio);
            datepickerFim.setDate(data.data_fim);

            // Atualizar ação do formulário
            form.action = `/campanhas/${data.id_campanha}`;
            form.method = 'POST';
            
            // Gerenciar método PUT
            form.querySelector('[name="_method"]')?.remove();
            form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');

            // Atualizar título do modal
            modal.querySelector('.modal-title').textContent = 'Editar Campanha';
            
            new bootstrap.Modal(modal).show();
        })
        .catch(error => {
            console.error('Erro:', error);
            Swal.fire('Erro!', 'Não foi possível carregar os dados', 'error');
        });
}

// Gerenciamento de Eventos do Modal
document.getElementById('modalCampanha').addEventListener('show.bs.modal', function(e) {
    const isEdit = e.relatedTarget?.classList.contains('btn-edit');
    const modal = this;
    
    if (!isEdit) {
        const form = modal.querySelector('form');
        form.reset();
        form.action = "{{ route('campanhas.store') }}";
        form.method = 'POST';
        form.querySelector('[name="_method"]')?.remove();
        datepickerInicio.clear();
        datepickerFim.clear();
        modal.querySelector('.modal-title').textContent = 'Nova Campanha';
    }
});
</script>
@endsection