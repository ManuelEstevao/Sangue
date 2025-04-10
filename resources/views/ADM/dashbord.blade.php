@extends('ADM.main')

@section('title', 'Dashboard - ConectaDador Admin')

@section('conteudo')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Dashboard</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="fa fa-chevron-right"></i>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}">Início</a>
            </li>
        </ul>
    </div>
    <div class="page-category">Visão geral dos indicadores do sistema</div>
    <div class="row">
        <!-- Card: Total de Doadores -->
        <div class="col-md-3">
            <div class="card card-stats card-primary">
                <div class="card-body">
                    <h5 class="card-title">Total de Doadores</h5>
                    <p class="card-text">{{ $totalDoadores ?? '0' }}</p>
                </div>
            </div>
        </div>

        <!-- Card: Agendamentos -->
        <div class="col-md-3">
            <div class="card card-stats card-warning">
                <div class="card-body">
                    <h5 class="card-title">Agendamentos</h5>
                    <p class="card-text">{{ $totalAgendamentos ?? '0' }}</p>
                </div>
            </div>
        </div>

        <!-- Card: Campanhas Ativas -->
        <div class="col-md-3">
            <div class="card card-stats card-success">
                <div class="card-body">
                    <h5 class="card-title">Campanhas Ativas</h5>
                    <p class="card-text"></p>
                </div>
            </div>
        </div>

        <!-- Card: Notificações -->
        <div class="col-md-3">
            <div class="card card-stats card-danger">
                <div class="card-body">
                    <h5 class="card-title">Notificações</h5>
                    <p class="card-text">{{ $totalNotificacoes ?? '0' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
