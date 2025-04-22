@extends('ADM.main')
@section('title', 'Lista de Doadores')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/Centro/assets/css/plugin/datatables/datatables.min.css') }}">
@endpush

@section('conteudo')
<div class="page-header">
  <h4 class="page-title">Doadores Cadastrados</h4>
  <div class="ml-auto">
    <a href="{{ route('doadores.create') }}" class="btn btn-danger btn-sm">
      <i class="fas fa-user-plus me-1"></i> Novo Doador
    </a>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <table id="tabela-doadores" class="table table-striped table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>Email</th>
          <th>Tipo Sanguíneo</th>
          <th>Telefone</th>
          <th>Criado em</th>
          <th class="text-center">Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach($doadores as $doador)
        <tr>
          <td>{{ $doador->id }}</td>
          <td>{{ $doador->nome }}</td>
          <td>{{ $doador->email }}</td>
          <td>{{ $doador->tipo_sanguineo }}</td>
          <td>{{ $doador->telefone }}</td>
          <td>{{ $doador->created_at->format('d/m/Y') }}</td>
          <td class="text-center">
            <a href="{{ route('doadores.show',    $doador->id) }}" class="btn btn-sm btn-outline-primary" title="Ver">
              <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('doadores.edit',    $doador->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
              <i class="fas fa-pen"></i>
            </a>
            <button onclick="excluirDoador({{ $doador->id }})"
                    class="btn btn-sm btn-outline-danger" title="Excluir">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
  <!-- DataTables JS -->
  <script src="{{ asset('assets/Centro/assets/js/plugin/datatables/datatables.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('#tabela-doadores').DataTable({
        responsive: true,
        language: {
          url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
        }
      });
    });

    function excluirDoador(id) {
      Swal.fire({
        title: 'Confirmar exclusão?',
        text: "O doador será removido permanentemente!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`/admin/doadores/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json'
            },
          })
          .then(res => res.json())
          .then(json => {
            if (json.success) {
              Swal.fire('Excluído!', json.message, 'success');
              // Remover linha da tabela sem recarregar
              $('#tabela-doadores').DataTable()
                .row( $('button[onclick="excluirDoador('+id+')"]').parents('tr') )
                .remove()
                .draw();
            } else {
              Swal.fire('Erro!', json.message, 'error');
            }
          })
          .catch(() => {
            Swal.fire('Erro!', 'Não foi possível excluir.', 'error');
          });
        }
      });
    }
  </script>
@endpush