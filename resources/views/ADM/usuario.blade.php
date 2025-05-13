@extends('ADM.main')

@section('title', 'Gerenciar Usuários')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .table-users { table-layout: auto; margin-bottom: 0; }
    .table-users th { font-weight: 500; background-color: #f8f9fa; padding: 0.78rem; }
    .table-users td { padding: 0.78rem; vertical-align: middle; white-space: nowrap; }
    .table-responsive { overflow-x: auto; border-radius: 8px; }
    .btn-custom { background-color: rgba(198, 66, 66, 0.9) !important; color: white !important; border: none !important; padding: 0.375rem 0.75rem; }
    .btn-custom i { color: white !important; }
</style>
@endsection

@section('conteudo')
<div class="container">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Gerenciar Usuários</h4>
      <a href="{{ route('admin.usuarios.create') }}" class="btn btn-custom">
        <i class="fas fa-plus"></i> Novo Usuário
      </a>
    </div>
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      <div class="table-responsive">
        <table class="table table-hover table-users mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Email</th>
              <th>Tipo</th>
              <th>Verificado</th>
              <th>Criado em</th>
              <th class="text-center">Ações</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
              <tr>
                <td>{{ $user->id_user }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->tipo_usuario) }}</td>
                <td>
                  @if($user->email_verified_at)
                    <span class="badge bg-success">Sim</span>
                  @else
                    <span class="badge bg-warning">Não</span>
                  @endif
                </td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="text-center">
                  <a href="{{ route('admin.usuarios.edit', $user) }}" class="btn btn-sm btn-custom">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form action="{{ route('admin.usuarios.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-custom" onclick="return confirm('Confirma exclusão?')">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-4">Nenhum usuário encontrado</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-3">{{ $users->links() }}</div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
