@extends('dador.DashbordDador')

@section('title', 'Histórico de Doação - ConectaDador')

@section('conteudo')
<div class="container-fluid">
    <h2 class="mb-4">Histórico de Doação</h2>
    <table class="table table-striped">
        <thead class="table-light">
            <tr>
                <th>Data da Doação</th>
                <th>Centro</th>
                <th>Quantidade (ml)</th>
                <th>Status</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($doacoes as $doacao)
            <tr>
                <td>{{ date('d/m/Y H:i', strtotime($doacao->data_doacao)) }}</td>
                <td>{{ $doacao->centro->nome ?? 'N/D' }}</td>
                <td>{{ $doacao->quantidade_ml }} ml</td>
                <td>
                    <span class="badge bg-{{ $doacao->status == 'concluido' ? 'success' : ($doacao->status == 'pendente' ? 'warning' : 'danger') }}">
                        {{ ucfirst($doacao->status) }}
                    </span>
                </td>
                <td>{{ $doacao->observacoes ?? 'Sem observações' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">Nenhuma doação registrada.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $doacoes->links() }}
    </div>
</div>
@endsection
