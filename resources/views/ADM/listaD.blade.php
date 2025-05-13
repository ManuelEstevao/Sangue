@extends('ADM.main')

@section('title', 'Doadores Cadastrados')

@section('styles')
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
        .btn-custom {
            background-color: rgba(198,66,66,.9);
            color: #fff;
            border: none;
            padding: .5rem 1rem;
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
        
        .dropdown-toggle { padding: .2rem .5rem; }
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
            <h4 class="mb-0">Doadores Cadastrados</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('listaD') }}" class="btn btn-custom"  data-bs-toggle="modal" data-bs-target="#modalNovoDoador" style=" background-color: rgba(198, 66, 66, 0.9) !important;
        color: white; ">
                    <i class="fas fa-user-plus me-2"></i> Novo Doador
                </a>
                <a  href="{{ route('doador.Pdf', request()->only('search','tipo_sanguineo')) }}" class="btn btn-custom" style=" background-color: rgba(198, 66, 66, 0.9) !important;
        color: white; ">
                    <i class="fas fa-file-pdf me-2"></i> Exportar PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="mb-3">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div class="input-group" style="max-width:300px;">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Buscar por nome ou e-mail..."
                               value="{{ request('search') }}">
                        <button class="btn btn-custom" style=" background-color: rgba(198, 66, 66, 0.9) !important; color: white; padding: 0.372rem 0.72rem;"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <label class="fw-bold mb-0">Tipo Sanguíneo:</label>
                        <select name="tipo_sanguineo"
                                class="form-select w-auto"
                                onchange="this.form.submit()">
                            <option value="">Todos</option>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $tipo)
                                <option value="{{ $tipo }}"
                                        {{ request('tipo_sanguineo')== $tipo?'selected':'' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-donors">
                    <thead>
                        <tr>
                          <th>Nome</th>
                          <th>Email</th>
                          <th>Tipo Sanguíneo</th>
                          <th>Telefone</th>
                          <th>Cadastrado em</th>
                          <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($doadores as $doador)
                            <tr>
                                <td >
                                    {{ collect(explode(' ', $doador->nome))->when(count($names = explode(' ', $doador->nome)) > 1, fn($c) => $c->only([0, count($names) - 1]))->implode(' ') }}
                                </td>

                                <td>{{ $doador->email ?? '-' }}</td>
                                <td class="text-center">{{ $doador->tipo_sanguineo }}</td>
                                <td>{{ $doador->telefone }}</td>
                                <td >{{ \Carbon\Carbon::parse($doador->data_cadastro)->format('d/m/Y') }}</td>

           
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
                                                    href="{{ route('doadores.edit', $doador->id_doador) }}">
                                                    <i class="fas fa-pen me-2"></i>Editar
                                                </a>
                                            </li>
                                            <li>
                                                <form id="delete-form-{{ $doador->id_doador }}"
                                                      action=""
                                                      method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#"
                                                       class="dropdown-item text-danger"
                                                       onclick="confirmarExclusao(event, {{ $doador->id_doador }})">
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

            @if($doadores->hasPages())
                <nav class="mt-3">
                    <ul class="pagination justify-content-center mb-0">
                        {{-- Previous --}}
                        <li class="page-item {{ $doadores->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $doadores->previousPageUrl() }}">&laquo;</a>
                        </li>
                        {{-- Numbers --}}
                        @foreach($doadores->getUrlRange(1, $doadores->lastPage()) as $page => $url)
                            <li class="page-item {{ $page==$doadores->currentPage()?'active':'' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        {{-- Next --}}
                        <li class="page-item {{ $doadores->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $doadores->nextPageUrl() }}">&raquo;</a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Cadastro -->
<div class="modal fade" id="modalNovoDoador" tabindex="-1" aria-labelledby="modalNovoDoadorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <form id="formNovoDoador" action="{{ route('doadores.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title" id="modalNovoDoadorLabel">Cadastrar Novo Doador</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        
                        <!-- Campos do formulário -->
                        <div class="col-md-6">
                            <label class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">E-mail <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required  data-unique-url="{{ route('check.email.unique') }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nº Bilhete <span class="text-danger">*</span></label>
                            <input type="text" name="numero_bilhete" class="form-control" 
                                   required
                                   data-unique-url="{{ route('check.bilhete.unique') }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Data Nascimento <span class="text-danger">*</span></label>
                            <input type="date" name="data_nascimento" class="form-control" 
                                   required
                                   max="{{ now()->subYears(18)->format('Y-m-d') }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Gênero <span class="text-danger">*</span></label>
                            <select name="genero" class="form-select" required>
                                <option value="">Selecione</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tipo Sanguíneo <span class="text-danger">*</span></label>
                            <select name="tipo_sanguineo" class="form-select" required>
                                <option value="">Selecione</option>
                                @foreach(['Desconhecido','A+','A-','B+','B-','O+','O-','AB+','AB-'] as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Telefone <span class="text-danger">*</span></label>
                            <input type="tel" name="telefone" class="form-control" 
                                   required
                                   placeholder="+244 900 000 000">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Peso (kg)</label>
                            <input type="number" name="peso" class="form-control" 
                                   step="0.1" min="45" max="200">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Endereço</label>
                            <input type="text" name="endereco" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Senha <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Foto (opcional)</label>
                            <input type="file" name="foto" class="form-control" 
                                   accept="image/jpeg, image/png">
                            <div class="invalid-feedback"></div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-1"></i>Salvar Doador
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
            text: "Essa ação não pode ser desfeita!",
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
        text: @json(session('success')),
        confirmButtonText: 'OK'
        });
  @endif

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formNovoDoador');
  if (!form) return;
  form.setAttribute('novalidate', true);

  // Regex para BI: 9 dígitos + 2 letras + 3 dígitos
  const regexBI     = /^\d{9}[A-Z]{2}\d{3}$/;
  // Regex para e-mail
  const regexEmail  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Rotas de verificação e CSRF
  const urlBI     = form.querySelector('[name="numero_bilhete"]').dataset.uniqueUrl;
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
  numero_bilhete: {
    required: 'BI obrigatório',
    invalid:  'Formato inválido: 9 dígitos + 2 letras + 3 dígitos',
    unique:   'Este BI já está cadastrado'
  },
  data_nascimento: {
    required: 'Data de nascimento é obrigatória',
    invalid:  'A idade deve estar entre 18 e 69 anos'
  },
  genero: {
    required: 'Gênero é obrigatório'
  },
  tipo_sanguineo: {
    required: 'Tipo sanguíneo é obrigatório'
  },
  telefone: {
    required: 'Telefone é obrigatório',
    invalid:  'Formato inválido (+2449XXXXXXX ou 9XXXXXXX)'
  },
  password: {
    required: 'Senha é obrigatória',
    invalid:  'A senha deve ter no mínimo 8 caracteres'
  },
  peso: {
    invalid: 'Peso deve ser entre 45kg e 200kg'
  }
};

  // Limpa erro visual
  function clearError(field) {
    field.classList.remove('is-invalid');
    const fb = field.nextElementSibling;
    if (fb?.classList.contains('invalid-feedback')) fb.textContent = '';
  }
  // Exibe mensagem de erro
  function showError(field, text) {
    field.classList.add('is-invalid');
    let fb = field.nextElementSibling;
    if (!fb || !fb.classList.contains('invalid-feedback')) {
      fb = document.createElement('div');
      fb.className = 'invalid-feedback';
      field.parentNode.appendChild(fb);
    }
    fb.textContent = text;
  }

  // 1) Validação síncrona (formato e obrigatoriedade)
  function validateSync() {
    const data = new FormData(form),
        e    = {},
        get  = name => (data.get(name)||'').toString().trim();

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

  // BI
  const bi = get('numero_bilhete');
  if (!bi) {
    e.numero_bilhete = msgs.numero_bilhete.required;
  } else if (!regexBI.test(bi)) {
    e.numero_bilhete = msgs.numero_bilhete.invalid;
  }

  // Data de nascimento
  const dn = get('data_nascimento');
  if (!dn) {
    e.data_nascimento = msgs.data_nascimento.required;
  } else {
    const birth = new Date(dn),
          today = new Date(),
          age   = today.getFullYear() - birth.getFullYear()
                  - ((today.getMonth()<birth.getMonth() ||
                     (today.getMonth()===birth.getMonth() && today.getDate()<birth.getDate()))
                     ?1:0);
    if (age < 18 || age > 69) {
      e.data_nascimento = msgs.data_nascimento.invalid;
    }
  }

  // Gênero
  const genero = get('genero');
  if (!genero) {
    e.genero = msgs.genero.required;
  }

  // Tipo sanguíneo
  const tipo = get('tipo_sanguineo');
  if (!tipo) {
    e.tipo_sanguineo = msgs.tipo_sanguineo.required;
  }

  // Telefone
  const tel = get('telefone');
  if (!tel) {
    e.telefone = msgs.telefone.required;
  } else if (!/^(?:\+244)?9[1-7]\d{7}$/.test(tel.replace(/\s+/g,''))) {
    e.telefone = msgs.telefone.invalid;
  }

  // Senha
  const pw = get('password');
  if (!pw) {
    e.password = msgs.password.required;
  } else if (pw.length < 8) {
    e.password = msgs.password.invalid;
  }

  // Peso (opcional)
  const pesoVal = get('peso');
  if (pesoVal) {
    const peso = parseFloat(pesoVal);
    if (isNaN(peso) || peso < 45 || peso > 200) {
      e.peso = msgs.peso.invalid;
    }
  }
    return e;
  }

  // 2) Validação assíncrona (unicidade)
  async function validateAsync() {
    const errors = {},
          data   = new FormData(form);

    // BI único
    const bi = data.get('numero_bilhete').toString().trim();
    if (regexBI.test(bi)) {
      try {
        const res = await fetch(urlBI, {
          method: 'POST',
          headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken },
          body: JSON.stringify({ numero_bilhete: bi })
        });
        const json = await res.json();
        if (json.exists) errors.numero_bilhete = msgs.numero_bilhete.unique;
      } catch {
        errors.numero_bilhete = 'Erro ao verificar BI';
      }
    }

    // E-mail único
     const email = data.get('email').trim();
    if (regexEmail.test(email)) {
      try {
        const res = await fetch(urlEmail, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Accept':        'application/json',
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':   csrfToken
          },
          body: JSON.stringify({ email })
        });
        if (!res.ok) {
          errors.email = 'Erro ao verificar e-mail';
        } else {
          const result = await res.json();
          if (result.exists) errors.email = msgs.email.unique;
        }
      } catch {
        errors.email = 'Falha de rede ao verificar e-mail';
      }
    }

    return errors;
  }

  // 3) Eventos em tempo real (blur + input)
  form.querySelectorAll('input[name="numero_bilhete"], input[name="email"]').forEach(field => {
    field.addEventListener('blur', () => {
      clearError(field);
      const errs = validateSync();
      if (errs[field.name]) showError(field, errs[field.name]);
    });
    field.addEventListener('input', () => clearError(field));
  });

  // 4) Submissão final
  form.addEventListener('submit', async e => {
    e.preventDefault();
    form.querySelectorAll('.is-invalid').forEach(f => clearError(f));

    const syncErr  = validateSync();
    const asyncErr = await validateAsync();
    const allErr   = {...syncErr, ...asyncErr};

    if (Object.keys(allErr).length) {
      Object.entries(allErr).forEach(([name, msg]) => {
        const f = form.querySelector(`[name="${name}"]`);
        if (f) showError(f, msg);
      });
      return;
    }
    form.submit();
  });
});

</script>
@endsection
