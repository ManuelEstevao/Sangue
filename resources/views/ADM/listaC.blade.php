@extends('ADM.main')

@section('title', 'Centros de Doação')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .table-donors {
            table-layout: fixed;
            width: 100%;
            margin-bottom: 0;
        }
        .table-donors th {
            background-color: #f8f9fa;
            font-weight: 500;
            padding: .75rem;
            white-space: nowrap;
        }
        .table-donors td {
            padding: .75rem;
            vertical-align: middle;
            white-space: nowrap;
        }
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        @media (max-width: 768px) {
            .mobile-hidden { display: none; }
            .table-donors th,
            .table-donors td {
                font-size: .9rem;
                padding: .6rem;
                white-space: normal;
            }
            .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            .btn-custom { width: 100%; text-align: center; }
        }
        .btn-custom {
            background-color: rgba(198,66,66,.9);
            color: #fff;
            border: none;
            padding: .5rem 1rem;
        }
        .dropdown-toggle { padding: .3rem .6rem; }
        .text-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
      
        .table-donors th:nth-child(1),
        .table-donors td:nth-child(1) {
        width: 25%;             
        white-space: normal; 
        }
        .table-donors td:not(:nth-child(1)) {
        white-space: nowrap;
        }



    </style>
@endpush

@section('conteudo')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Centros de Doação</h4>
            <div class="d-flex gap-2">
                
                <a href="#" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalNovoCentro">
                    <i class="fas fa-map-marker-alt me-2"></i> Novo Centro
                </a>
                <a href="{{ route('centros.pdf', request()->only('search')) }}"
                   class="btn btn-custom">
                    <i class="fas fa-file-pdf me-2"></i> Exportar PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- filtro e busca --}}
            <form method="GET" action="" class="mb-3">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div class="input-group" style="max-width:300px;">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Buscar por nome ou endereço..."
                               value="{{ request('search') }}">
                        <button class="btn btn-custom"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>

            
            <div class="table-responsive">
                <table class="table table-hover table-donors">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th class="mobile-hidden">Endereço</th>
                            <th>Telefone</th>
                            {{--<th>Estoque</th>--}}
                            <th class="small-text">Cadastrado em</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($centros as $centro)
                        <tr>
                            <td class="text-wrap" >{{ $centro->nome }}</td>
                            <td class="mobile-hidden text-truncate">{{ $centro->endereco }}</td>
                            <td>{{ $centro->telefone }}</td>
                            {{--  <td>
    @forelse($centro->estoque as $estoque)
        <div class="stock-item">
            <span class="blood-type">{{ $estoque->tipo_sanguineo }}</span>
            <div class="progress">
                <div class="progress-bar" 
                     role="progressbar" 
                     style="width: {{ ($estoque->quantidade / $centro->capacidade_maxima) * 100 }}%"
                     aria-valuenow="{{ $estoque->quantidade }}" 
                     aria-valuemin="0" 
                     aria-valuemax="{{ $centro->capacidade_maxima }}">
                    {{ $estoque->quantidade }} unidades
                </div>
            </div>
        </div>
    @empty
        <span class="text-danger">--</span>
    @endforelse
</td>--}}
                            <td>{{ \Carbon\Carbon::parse($centro->data_cadastro)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle"
                                            style="background-color:rgba(198,66,66,.95);color:#fff;"
                                            type="button"
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item text-info"
                                               href="">
                                                <i class="fas fa-eye me-2"></i>Visualizar
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-primary"
                                               href="">
                                                <i class="fas fa-pen me-2"></i>Editar
                                            </a>
                                        </li>
                                        <li>
                                            <form id="delete-form-{{ $centro->id_centro }}"
                                                  action=""
                                                  method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#"
                                                   class="dropdown-item text-danger"
                                                   onclick="confirmarExclusao(event, {{ $centro->id_centro }})">
                                                    <i class="fas fa-trash me-2"></i>Excluir
                                                </a>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-info mb-0">
                                    Nenhum centro encontrado.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

          
            @if($centros->hasPages())
                <nav class="mt-3">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item {{ $centros->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $centros->previousPageUrl() }}">&laquo;</a>
                        </li>
                        @foreach($centros->getUrlRange(1, $centros->lastPage()) as $page => $url)
                            <li class="page-item {{ $page==$centros->currentPage()?'active':'' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ $centros->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $centros->nextPageUrl() }}">&raquo;</a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    </div>
</div>

{{-- Modal de cadastro de novo Centro --}}
<div class="modal fade" id="modalNovoCentro" tabindex="-1" aria-labelledby="modalNovoCentroLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow">
      <form action="{{ route('centros.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-danger text-white rounded-top-4">
          <h5 class="modal-title" id="modalNovoCentroLabel">Cadastrar Novo Centro</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            {{-- Nome --}}
            <div class="col-md-6">
              <label class="form-label">Nome</label>
              <input type="text" name="nome" class="form-control" required>
            </div>
            {{-- E-mail --}}
            <div class="col-md-6">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control"  data-unique-url="{{ route('check.email.unique') }}" required>
            </div>
            {{-- Endereço --}}
            <div class="col-md-6">
              <label class="form-label">Endereço</label>
              <input type="text" name="endereco" class="form-control" required>
            </div>
            {{-- Telefone --}}
            <div class="col-md-6">
              <label class="form-label">Telefone</label>
              <input type="tel" name="telefone" class="form-control" required>
            </div>
            {{-- Latitude --}}
            <div class="col-md-3">
              <label class="form-label">Latitude</label>
              <input type="text" name="latitude" class="form-control" placeholder="-8.550520" required>
            </div>
            {{-- Longitude --}}
            <div class="col-md-3">
              <label class="form-label">Longitude</label>
              <input type="text" name="longitude" class="form-control" placeholder="-13.633308" required>
            </div>
            {{-- Senha de Acesso --}}
            <div class="col-md-6">
              <label class="form-label">Senha de Acesso</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            {{-- Capacidade Máx. --}}
            <div class="col-md-3">
              <label class="form-label">Capacidade Máx.</label>
              <input type="number" name="capacidade_maxima" class="form-control" min="1" required>
            </div>
            {{-- Horário Abertura --}}
            <div class="col-md-3">
              <label class="form-label">Abertura</label>
              <input type="time" name="horario_abertura" class="form-control" value="08:00" required>
            </div>
            {{-- Horário Fechamento --}}
            <div class="col-md-3">
              <label class="form-label">Fechamento</label>
              <input type="time" name="horario_fechamento" class="form-control" value="17:30" required>
            </div>
            {{-- Foto --}}
            <div class="col-md-3">
              <label class="form-label">Foto (opcional)</label>
              <input type="file" name="foto" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer p-3">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Cancelar
          </button>
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-save me-1"></i>Salvar Centro
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmarExclusao(e, id) {
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c62828',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, excluir!'
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK'
    });
    @endif
    document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('#modalNovoCentro form');
  if (!form) return;
  form.setAttribute('novalidate', true);

  // Regex básicos
  const regexEmail  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const regexTel    = /^(?:\+244)?9[1-7]\d{7}$/;
  const regexCoord  = /^-?\d+(\.\d+)?$/;

  // Rotas de verificação de e-mail e token CSRF
  const urlEmail  = form.querySelector('[name="email"]').dataset.uniqueUrl;
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  // Mensagens de erro
  const msgs = {
    nome: {
      required: 'Nome é obrigatório',
      invalid:  'Nome inválido'
    },
    email: {
      required: 'E-mail é obrigatório',
      invalid:  'Formato de e-mail inválido',
      unique:   'Este e-mail já está registrado'
    },
    endereco:         { required: 'Endereço é obrigatório' },
    telefone:         {
      required: 'Telefone é obrigatório',
      invalid:  'Formato inválido (+2449XXXXXXX ou 9XXXXXXX)'
    },
    latitude:         {
      required: 'Latitude é obrigatória',
      invalid:  'Latitude inválida'
    },
    longitude:        {
      required: 'Longitude é obrigatória',
      invalid:  'Longitude inválida'
    },
    password:         {
      required: 'Senha é obrigatória',
      invalid:  'Senha deve ter no mínimo 8 caracteres'
    },
    capacidade_maxima:{
      required: 'Capacidade é obrigatória',
      invalid:  'Capacidade deve ser ≥1'
    },
    horario_abertura:  { required: 'Horário de abertura é obrigatório' },
    horario_fechamento:{ required: 'Horário de fechamento é obrigatório' }
  };

  // Helpers para limpar/exibir erros
  function clearError(field) {
    field.classList.remove('is-invalid');
    const fb = field.nextElementSibling;
    if (fb?.classList.contains('invalid-feedback')) fb.textContent = '';
  }
  function showError(field, message) {
    field.classList.add('is-invalid');
    let fb = field.nextElementSibling;
    if (!fb || !fb.classList.contains('invalid-feedback')) {
      fb = document.createElement('div');
      fb.className = 'invalid-feedback';
      field.parentNode.appendChild(fb);
    }
    fb.textContent = message;
  }

  // Validação síncrona (formato, obrigatoriedade e lógica de horários)
  function validateSync() {
    const fd  = new FormData(form),
          e   = {},
          get = n => (fd.get(n)||'').toString().trim();

    // Nome
    const nome = get('nome');
    if (!nome) {
      e.nome = msgs.nome.required;
    } else if (nome.length < 5) {
      e.nome = msgs.nome.invalid;
    }

    // E-mail
    const email = get('email');
    if (!email) {
      e.email = msgs.email.required;
    } else if (!regexEmail.test(email)) {
      e.email = msgs.email.invalid;
    }

    // Endereço
    if (!get('endereco')) {
      e.endereco = msgs.endereco.required;
    }

    // Telefone
    const tel = get('telefone');
    if (!tel) {
      e.telefone = msgs.telefone.required;
    } else if (!regexTel.test(tel.replace(/\s+/g,''))) {
      e.telefone = msgs.telefone.invalid;
    }

    // Latitude / Longitude
    const lat = get('latitude'), lng = get('longitude');
    if (!lat) {
      e.latitude = msgs.latitude.required;
    } else if (!regexCoord.test(lat)) {
      e.latitude = msgs.latitude.invalid;
    }
    if (!lng) {
      e.longitude = msgs.longitude.required;
    } else if (!regexCoord.test(lng)) {
      e.longitude = msgs.longitude.invalid;
    }

    // Senha
    const pw = get('password');
    if (!pw) {
      e.password = msgs.password.required;
    } else if (pw.length < 8) {
      e.password = msgs.password.invalid;
    }

    // Capacidade máxima
    const cap = get('capacidade_maxima');
    if (!cap) {
      e.capacidade_maxima = msgs.capacidade_maxima.required;
    } else if (isNaN(cap) || Number(cap) < 1) {
      e.capacidade_maxima = msgs.capacidade_maxima.invalid;
    }

    // Horários
    const open  = get('horario_abertura'),
          close = get('horario_fechamento');
    if (!open) {
      e.horario_abertura = msgs.horario_abertura.required;
    }
    if (!close) {
      e.horario_fechamento = msgs.horario_fechamento.required;
    }
    // validação de lógica de horários
    if (open && close && open > close) {
       e.horario_abertura = 'Horário de abertura não pode ser maior que o de fechamento';
    e.horario_fechamento = 'Horário de fechamento deve ser maior que o de abertura';
    }

    return e;
  }

  // Validação assíncrona para e-mail único
  async function validateAsync() {
    const errors = {},
          fd     = new FormData(form);

    const email = fd.get('email').trim();
    if (email && regexEmail.test(email)) {
      try {
        const res = await fetch(urlEmail, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Accept':        'application/json',
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':  csrfToken
          },
          body: JSON.stringify({ email })
        });
        if (res.ok) {
          const json = await res.json();
          if (json.exists) errors.email = msgs.email.unique;
        } else {
          errors.email = 'Erro ao verificar e-mail';
        }
      } catch {
        errors.email = 'Falha de rede ao verificar e-mail';
      }
    }

    return errors;
  }

  // Bind em tempo real
  form.querySelectorAll('input, select').forEach(field => {
    field.addEventListener('blur', () => {
      clearError(field);
      const errs = validateSync();
      if (errs[field.name]) showError(field, errs[field.name]);
    });
    field.addEventListener('input', () => clearError(field));
  });

  // Submit final
  form.addEventListener('submit', async e => {
    e.preventDefault();
    form.querySelectorAll('.is-invalid').forEach(clearError);

    const syncErr  = validateSync();
    const asyncErr = await validateAsync();
    const allErr   = { ...syncErr, ...asyncErr };

    if (Object.keys(allErr).length) {
      Object.entries(allErr).forEach(([name, msg]) => {
        const field = form.querySelector(`[name="${name}"]`);
        if (field) showError(field, msg);
      });
      return;
    }
    form.submit();
  });
});
</script>
@endsection