@extends('dador.DashbordDador')

@section('title', 'Histórico de Doação - ConectaDador')

@section('conteudo')
<div class="container-fluid">
    <h2 class="mb-4">Histórico de Doação</h2>
    <table class="table table-striped">
        <thead class="table-light">
            <tr>
            <tr>
                <th>Data</th>
                <th>Centro</th>
                <th>Peso</th>
                <!--  <th>Volume(ml)</th>-->  
                <th>Status</th>
                <th>Profissional</th>
                <th>Observações</th>
            </tr>
            </tr>
        </thead>
        <tbody>
            @forelse($doacoes as $doacao)
            <tr>
                <td>{{ date('d/m/Y H:i', strtotime($doacao->data_doacao)) }}</td>
                <td>{{ $doacao->agendamento->centro->nome ?? 'N/D' }}</td>
                <td>{{ number_format($doacao->agendamento->doador->peso, 2) }}</td>
              <!--  <td>{{ $doacao->volume_coletado }} ml</td>-->  
                <td>
                    <span class="badge bg-{{ $doacao->status == 'Aprovado' ? 'success' :  'danger' }}">
                        {{ ucfirst($doacao->status) }}
                    </span>
                </td>
                <td>{{ $doacao->nome_profissional ?? 'Não registado' }}</td>
                <td>{{ $doacao->observacoes ?? 'Sem observações' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Nenhuma doação registrada.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $doacoes->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
