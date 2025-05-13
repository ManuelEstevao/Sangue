@extends('ADM.main')

@section('title', 'Todos os Agendamentos')

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
        width: 120px;
    }
    @media (max-width: 768px) {
        .mobile-hidden { display: none; }
        .table-donations td, .table-donations th {
            white-space: normal;
            padding: 0.5rem;
        }
        .dropdown-menu { min-width: 140px; }
    }
    .btn-custom {
        background-color: rgba(198, 66, 66, 0.9) !important;
        color: white;
        border: none;
        padding: 0.375rem 0.75rem;
    }
    .btn-custom i {
        color: white !important;
    }
    /* Custom dropdown toggle icon */
    .dropdown-toggle {
        background-color: rgba(198, 66, 66, 0.9) !important;
        color: white !important;
        border: none;
        padding: 0.25rem 0.5rem;
    }
    .dropdown-toggle i {
        color: white !important;
    }
    /* Status badge spacing */
    .badge { font-size: 0.85rem; }
</style>
@endsection

@section('conteudo')
<div class="container">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Todos os Agendamentos</h4>
    </div>
    <div class="card-body">

      {{-- Filtros --}}
      <form method="GET" action="{{ route('agendamentos.todos') }}" class="mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
          {{-- Busca por nome do doador --}}
          <div class="input-group" style="max-width: 300px;">
            <input
              type="text"
              name="doador"
              class="form-control"
              placeholder="Nome do doador..."
              value="{{ request('doador') }}"
            >
            <button class="btn btn-custom" style=" background-color: rgba(198, 66, 66, 0.9) !important;
        color: white; padding: 0.372rem 0.72rem;" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>

          {{-- Intervalo de datas --}}
          <div class="d-flex align-items-center gap-2">
            <span class="fw-bold small">De:</span>
            <input
              type="date"
              name="data_inicio"
              class="form-control"
              style="max-width:150px;"
              value="{{ request('data_inicio') }}"
            >
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="fw-bold small">Até:</span>
            <input
              type="date"
              name="data_fim"
              class="form-control"
              style="max-width:150px;"
              value="{{ request('data_fim') }}"
            >
          </div>
          <button class="btn btn-custom" style=" background-color: rgba(198, 66, 66, 0.9) !important;
        color: white; padding: 0.375rem 0.75rem;" type="submit">
            <i class="fas fa-filter"></i>
          </button>

          {{-- Filtro de status --}}
          <div class="d-flex align-items-center gap-2">
            <label class="fw-bold mb-0">Status:</label>
            <select
              name="status"
              class="form-select"
              style="max-width: 160px;"
              onchange="this.form.submit()"
            >
              <option value="">Todos</option>
              @foreach(['Agendado','Comparecido','Não Compareceu','Cancelado','Concluido'] as $st)
                <option
                  value="{{ $st }}"
                  {{ request('status')==$st ? 'selected' : '' }}
                >{{ $st }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </form>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="table-responsive">
        <table class="table table-hover table-donations mb-0">
          <thead>
            <tr>
              <th>Doador</th>
              <th class="mobile-hidden">Centro</th>
              <th class="compact-column">Data</th>
              <th class="compact-column">Horário</th>
              <th class="status-column">Status</th>
              <th class="text-center">Ações</th>
            </tr>
          </thead>
          <tbody>
            @forelse($agendamentos as $item)
              <tr>
                <td>{{ Str::limit($item->doador->nome, 26) }}</td>
                <td class="mobile-hidden">{{ Str::limit($item->centro->nome, 20) }}</td>
                <td>{{ \Carbon\Carbon::parse($item->data_agendada)->format('d/m/Y') }}</td>
                <td>{{ $item->horario }}</td>
                <td>
                  <span class="badge 
                    @switch($item->status)
                      @case('Agendado') bg-secondary @break
                      @case('Comparecido') bg-success @break
                      @case('Não Compareceu') bg-warning @break
                      @case('Cancelado') bg-danger @break
                      @case('Concluido') bg-primary @break
                    @endswitch
                  ">
                    {{ $item->status }}
                  </span>
                </td>
                <td class="text-center">
                  <div class="dropdown">
                    <button
                      class="btn btn-sm  dropdown-toggle"
                      style=" background-color: rgba(198, 66, 66, 0.9) !important;
                      color: white; padding: 0.375rem 0.75rem;"
                      type="button"
                      id="acoesDropdown{{ $item->id_agendamento }}"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                    >
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end"
                      aria-labelledby="acoesDropdown{{ $item->id_agendamento }}"
                    >
                      @foreach(['Agendado','Comparecido','Não Compareceu','Cancelado','Concluido'] as $st)
                        <li>
                          <form method="POST" action="{{ route('agendamentos.updateStatus', $item) }}" class="m-0">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $st }}">
                            <button type="submit" class="dropdown-item">{{ $st }}</button>
                          </form>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-4">
                  <div class="alert alert-info mb-0">
                    Nenhum agendamento encontrado
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($agendamentos->hasPages())
        <nav class="mt-3">
          {{ $agendamentos->links() }}
        </nav>
      @endif

    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
