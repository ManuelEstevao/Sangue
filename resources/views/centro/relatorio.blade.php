@extends('centro.main)

@section('content')
<div class="container">
    <h2 class="mb-4">Relatório de Doadores - {{ $centro->nome }}</h2>

    <!-- Filtros -->
    <form method="GET" action="{{ route('relatorios.index', $centro->id) }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="data_inicio">Data Início:</label>
                <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
            </div>
            <div class="col-md-3">
                <label for="data_fim">Data Fim:</label>
                <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ request('data_fim') }}">
            </div>
            <div class="col-md-4">
                <label for="pesquisa_nome">Pesquisar Nome:</label>
                <input type="text" name="pesquisa_nome" id="pesquisa_nome" class="form-control" value="{{ request('pesquisa_nome') }}" placeholder="Digite o nome do doador">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabela -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Contato</th>
                <th>Última Doação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($doadores as $doador)
                <tr>
                    <td>{{ $doador->nome }}</td>
                    <td>{{ $doador->contato }}</td>
                    <td>{{ optional($doador->doacoes->first())->data_doacao ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Botão de exportação -->
    <div class="mt-3">
        <a href="{{ route('relatorios.export.pdf', $centro->id) }}" class="btn btn-danger">Exportar PDF</a>
    </div>
</div>
@endsection
