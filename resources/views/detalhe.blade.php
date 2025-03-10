@extends('layout.main')
@section('title','Detalhe da Campanha - ConectaDador')

@section('conteudo')
<!-- service-details.html -->
<div class="container py-5">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <!-- Imagem da Campanha -->
        <div class="col-md-6">
          @if($campanha->foto)
            <img src="{{ asset('storage/' . $campanha->foto) }}" 
                 class="img-fluid rounded" 
                 alt="{{ $campanha->titulo }}">
          @else
            <img src="{{ asset('assets/img/campanha.png') }}" 
                 alt="Campanha padrão" 
                 class="img-fluid">
          @endif
        </div>
        <!-- Detalhes da Campanha e do Centro -->
        <div class="col-md-6">
          <h1 class="mb-3">{{ $campanha->titulo }}</h1>
          <div class="mb-4">
            <h4>Informações do Centro</h4>
            <ul class="list-unstyled">
              <li><strong>Nome:</strong> {{ $campanha->centro->nome }}</li>
              <li><strong>Endereço:</strong> {{ $campanha->centro->endereco }}</li>
              <li><strong>Telefone:</strong> {{ $campanha->centro->telefone }}</li>
            </ul>

            <h4 class="mt-4">Detalhes da Campanha</h4>
            <ul class="list-unstyled">
              <li><strong>Data:</strong> {{ \Carbon\Carbon::parse($campanha->data_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($campanha->data_fim)->format('d/m/Y') }}</li>
              <li><strong>Horário:</strong> {{ $campanha->hora_inicio }} às {{ $campanha->hora_fim }}</li>
            </ul>

            <p class="lead mt-4">{{ $campanha->descricao }}</p>

            <a href="#" class="btn btn-danger btn-lg mt-3">
              <i class="fas fa-hand-holding-heart me-2"></i> Participar
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
