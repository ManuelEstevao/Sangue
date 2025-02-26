@extends('dador.DashbordDador')

@section('title', 'Meu Perfil - ConectaDador')

@section('contedo')
<div class="container-fluid">
    <h2 class="mb-4">Meu Perfil</h2>

    <div class="row">
        <!-- Informações Pessoais -->
        <div class="col-md-6">
            <div class="mb-4 p-3 border rounded bg-light">
                <h4 class="mb-3">Informações Pessoais</h4>
                <p><strong>Nome:</strong> {{ $doador->nome }}</p>
                <p><strong>Gênero:</strong> {{ ucfirst($doador->genero) }}</p>
                <p><strong>Data de Nascimento:</strong> {{ date('d/m/Y', strtotime($doador->data_nascimento)) }}</p>
                <p><strong>Tipo Sanguíneo:</strong> {{ $doador->tipo_sanguineo }}</p>
                <p><strong>Telefone:</strong> {{ $doador->telefone }}</p>
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            </div>
        </div>

        <!-- Endereço -->
        <div class="col-md-6">
            <div class="mb-4 p-3 border rounded bg-light">
                <h4 class="mb-3">Endereço</h4>
                <p><strong>Rua:</strong> {{ $doador->endereco_rua ?? 'Não informado' }}</p>
                <p><strong>Cidade:</strong> {{ $doador->cidade ?? 'Não informado' }}</p>
                <p><strong>Província:</strong> {{ $doador->provincia ?? 'Não informado' }}</p>
            </div>
        </div>
    </div>

    <!-- Histórico de Doações -->
    <div class="mb-4 p-3 border rounded bg-light">
        <h4 class="mb-3">Histórico de Doações</h4>
        <p><strong>Total de Doações:</strong> {{ $totalDoacoes }} </p>
        <p><strong>Última Doação:</strong> 
            {{ $ultimaDoacao ? date('d/m/Y', strtotime($ultimaDoacao->data_doacao)) : 'Nenhuma doação registrada' }}
        </p>
    </div>

    <!-- Cartão Digital -->
    <div class="card text-white bg-danger mt-4">
        <div class="card-body text-center">
            <h5 class="card-title">Cartão Digital de Doador</h5>
            <p class="mb-1"><strong>Nome:</strong> {{ $doador->nome }}</p>
            <p class="mb-1"><strong>Tipo Sanguíneo:</strong> {{ $doador->tipo_sanguineo }}</p>
            <p class="mb-1"><strong>Telefone:</strong> {{ $doador->telefone }}</p>
            <p class="mb-0"><strong>Email:</strong> {{ Auth::user()->email }}</p>
        </div>
    </div>
</div>
@endsection
