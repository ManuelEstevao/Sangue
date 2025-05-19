@extends('centro.main')

@section('title', 'Doações - Centro de Coleta')
@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    
   
   .table-donations {
        table-layout: auto;
        margin-bottom: 0;
    }
    
    .table-donations th {
        font-weight: 500;
        background-color: #f8f9fa;
        padding: 0.78rem;
    }

    .table-donations td {
        padding: 0.78rem;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-responsive {
        overflow-x: auto;
        max-height: none;
        border-radius: 8px;
    }

    .compact-column {
        max-width: 125px;
        min-width: 100px;
    }

    .status-column {
        width: 100px;
    }

    @media (max-width: 768px) {
        .mobile-hidden {
            display: none;
        }
        
        .table-donations td, .table-donations th {
            white-space: normal;
            padding: 0.5rem;
        }
        
        .dropdown-menu {
            min-width: 120px;
        }
    }

    .btn-custom {
        background-color: rgba(198, 66, 66, 0.9);
        color: white;
        border: none;
        padding: 0.375rem 0.75rem;
    }

    .dropdown-toggle {
        padding: 0.25rem 0.5rem;
    }
</style>
@endsection

@section('conteudo')
<div class="container">
    <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Registo de Doações</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('centro.exportarPdf', request()->query()) }}" class="btn btn-custom">
            <i class="fas fa-file-pdf me-2"></i> Exportar PDF
        </a>
       <!--  <a href="" class="btn btn-custom">
            <i class="fas fa-plus me-2"></i> Nova Doação
         </a>-->
    </div>
</div>
        
        <div class="card-body">
        <form method="GET" action="" class="mb-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        
        <!-- Campo de Busca por Nome -->
        <div class="d-flex align-items-center gap-2" style="max-width: 300px;">
            <div class="input-group">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Nome do doador..." 
                       value="{{ request('search') }}"
                       aria-label="Buscar doador">
                <button type="submit" class="btn btn-custom">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- Filtros por Data (Centralizado) -->
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <!-- Data de Início com rótulo inline -->
            <div class="d-flex align-items-center gap-1">
                <span class="fw-bold small" style="white-space: nowrap;">De:</span>
                <input type="date" id="data_inicio" name="data_inicio" class="form-control" style="max-width: 150px;"
                       value="{{ request('data_inicio') }}">
            </div>
            <!-- Data de Fim com rótulo inline -->
            <div class="d-flex align-items-center gap-1">
                <span class="fw-bold small" style="white-space: nowrap;">Até:</span>
                <input type="date" id="data_fim" name="data_fim" class="form-control" style="max-width: 150px;"
                       value="{{ request('data_fim') }}">
            </div>
            <!-- Botão para aplicar os filtros -->
            <div>
                <button type="submit" class="btn btn-custom">
                    <i class="fas fa-filter me-1"></i>
                </button>
            </div>
        </div>

        <!-- Filtro de Status -->
        <div class="d-flex align-items-center gap-2">
            <label class="fw-bold">Filtrar por:</label>
            <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                <option value="">Todos status</option>
                <option value="Aprovado" {{ request('status') == 'Aprovado' ? 'selected' : '' }}>Aprovado</option>
                <option value="Reprovado" {{ request('status') == 'Reprovado' ? 'selected' : '' }}>Reprovado</option>
            </select>
        </div>
    </div>
</form>



 <!-- Tabela de Doações -->
 <div class="table-responsive">
    <table class="table table-hover ">
                    <thead>
                        <tr>
                            <th>Doador</th>
                            <th class="mobile-hidden">Tipo</th>
                            <th class="mobile-hidden">Peso</th>
                            <th>Pressão</th>
                            <th class="mobile-hidden">Hemog.</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doacoes as $doacao)
                        <tr>
                            <td>{{ Str::limit($doacao->agendamento->doador->nome, 26) }}</td>
                            <td class="mobile-hidden">{{ $doacao->agendamento->doador->tipo_sanguineo }}</td>
                            <td class="mobile-hidden">{{ $doacao->agendamento->doador->peso }} kg</td>
                            <td>{{ $doacao->pressao_arterial }}</td>
                            <td class="mobile-hidden">{{ $doacao->hemoglobina }}g/dL</td>
                            <td>{{ \Carbon\Carbon::parse($doacao->data_doacao)->format('d/m/y') }}</td>
                            <td>
                                <span class="badge bg-{{ $doacao->status == 'Aprovado' ? 'success' : 'danger' }}">
                                    {{ ucfirst($doacao->status) }}
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
                                            <a class="dropdown-item text-primary" href="#"
                                            data-id="{{ $doacao->id_doacao }}"
                                            data-hemoglobina="{{ $doacao->hemoglobina }}"
                                            data-pressao="{{ $doacao->pressao_arterial }}"
                                            data-volume="{{ $doacao->volume_coletado }}"
                                            data-peso="{{ $doacao->agendamento->doador->peso }}"
                                            data-profissional="{{ $doacao->nome_profissional }}"
                                            data-status="{{ $doacao->status }}"
                                            data-observacoes="{{ $doacao->observacoes }}"
                                            onclick="abrirModalEdicao(this)">
                                            <i class="fas fa-edit me-2"></i>Editar
                                            </a>
                                        </li>
                                        <li>
                                            <form id="delete-form-{{ $doacao->id_doacao }}" action="{{ route('centro.doacao.destroy', $doacao->id_doacao) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <a class="dropdown-item text-danger" href="#" onclick="confirmarExclusao(event, {{ $doacao->id_doacao }})">
                                                    <i class="fas fa-trash me-2"></i>Excluir
                                                </a>
                                            </form>
                                        </li>
                                        <li><a class="dropdown-item"  href="{{ route('relatorio.doador.pdf', $doacao->agendamento->doador->id_doador) }}"><i class="fas fa-file-alt me-2"></i>Relatório</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="alert alert-info mb-0">
                                    Nenhuma doação encontrada
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
<!-- Modal de Edição de Doação -->
<div class="modal fade" id="editarDoacaoModal" tabindex="-1" aria-labelledby="editarDoacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <form id="editarDoacaoForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title" id="editarDoacaoModalLabel">Editar Doação</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Coluna Esquerda -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hemoglobina (g/dL)</label>
                                <input type="number" step="0.1" name="hemoglobina" id="editHemoglobina" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Pressão Arterial</label>
                                <input type="text" name="pressao_arterial" id="editPressao" class="form-control" placeholder="Ex: 120/80" pattern="\d{2,3}/\d{2,3}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Profissional Responsável</label>
                                <input type="text" name="nome_profissional" id="editProfissional" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status da Doação</label>
                                <select name="status" id="editStatus" class="form-select" required>
                                    <option value="Aprovado">Aprovado</option>
                                    <option value="Reprovado">Reprovado</option>
                                </select>
                            </div>
                        </div>

                        <!-- Coluna Direita -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Volume Coletado (ml)</label>
                                <input type="number" name="volume_coletado" id="editVolume" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Peso (kg)</label>
                                <input type="number" step="0.1" name="peso" id="editPeso" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Observações</label>
                                <textarea name="observacoes" id="editObservacoes" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit"  class="btn btn-danger">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

function abrirModalEdicao(element) {
        // Obter os dados a partir dos data attributes
        const id = element.getAttribute('data-id');
        const hemoglobina = element.getAttribute('data-hemoglobina');
        const pressao = element.getAttribute('data-pressao');
        const volume = element.getAttribute('data-volume');
        const peso = element.getAttribute('data-peso');
        const profissional = element.getAttribute('data-profissional');
        const status = element.getAttribute('data-status');
        const observacoes = element.getAttribute('data-observacoes');

        // Preencher os campos do modal
        document.getElementById('editHemoglobina').value = hemoglobina;
        document.getElementById('editPressao').value = pressao;
        document.getElementById('editVolume').value = volume;
        document.getElementById('editPeso').value = peso;
        document.getElementById('editProfissional').value = profissional;
        document.getElementById('editStatus').value = status;
        document.getElementById('editObservacoes').value = observacoes;

        // Atualizar a action do formulário para a rota de atualização
        const form = document.getElementById('editarDoacaoForm');
        form.action = `/centro/doacao/${id}`;

        // Abrir o modal usando Bootstrap
        const modal = new bootstrap.Modal(document.getElementById('editarDoacaoModal'));
        modal.show();
    }
    //validação
    $(document).ready(function() {
    $('#editarDoacaoForm').on('submit', function(e) {
        e.preventDefault();
        let isValid = true;
        const errorMessages = [];
        const $form = $(this);

        // Resetar erros
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Validações atualizadas
        const validations = [
            {
                id: '#editHemoglobina',
                test: (v) => v >= 12.5 && v <= 18.0,
                msg: 'Hemoglobina deve ser entre 12.5-18.0 g/dL'
            },
            {
                id: '#editPressao',
                test: (v) => {
                    const partes = v.split('/');
                    if (partes.length !== 2) return false;
                    const sistolica = parseInt(partes[0]);
                    const diastolica = parseInt(partes[1]);
                    return (sistolica >= 90 && sistolica <= 200) && 
                           (diastolica >= 60 && diastolica <= 120);
                },
                msg: 'Pressão arterial inválida (ex: 120/80)'
            },
            {
                id: '#editVolume',
                test: (v) => v >= 300 && v <= 500,
                msg: 'Volume deve ser 300-500 ml'
            },
            {
                id: '#editPeso',
                test: (v) => v >= 45 && v <= 200,
                msg: 'Peso deve ser 45-200 kg'
            },
            {
                id: '#editProfissional',
                test: (v) => v.trim().length >= 5 && /^[a-zA-Z\u00C0-\u017F\s]+$/.test(v),
                msg: 'Nome profissional inválido'
            },
            {
                id: '#editStatus',
                test: (v) => ['Aprovado', 'Reprovado'].includes(v),
                msg: 'Selecione um status válido'
            }
        ];

        // Executar validações
        validations.forEach(({id, test, msg}) => {
            const $element = $(id);
            const value = $element.val();
            
            if (!test(value)) {
                isValid = false;
                errorMessages.push(msg);
                $element.addClass('is-invalid')
                       .parent().append(`<div class="invalid-feedback">${msg}</div>`);
            }
        });

        // Tratar resultado
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Corrija os seguintes erros:',
                html: `<ul class="text-start">${errorMessages.map(m => `<li>${m}</li>`).join('')}</ul>`
            });
        } else {
            // Enviar dados
            fetch($form.attr('action'), {
                method: 'POST',
                body: new FormData($form[0]),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw { ...data, status: response.status };
                
                Swal.fire({
                    icon: 'success',
                    title: 'Alterações salvas!',
                    text: data.message,
                    willClose: () => window.location.reload()
                });
            })
            .catch(error => {
                const msg = error.status === 422 
                    ? Object.values(error.errors).join('\n')
                    : error.message || 'Erro no servidor';
                
                Swal.fire('Erro!', msg, 'error');
            });
        }
    });

    // Resetar validações ao abrir modal
    $('#editarDoacaoModal').on('show.bs.modal', function() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    });
});
</script>
@endsection