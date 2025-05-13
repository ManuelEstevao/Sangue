@extends('centro.main')

@section('title', 'Histórico de Notificações')
@section('conteudo')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5><i class="fas fa-cogs me-2"></i>Configurações de Notificações</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            @csrf
            
            <div class="mb-4">
                <h6 class="mb-3"><i class="fas fa-bell me-2"></i>Preferências Gerais</h6>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" 
                        name="receber_email" id="receber_email" 
                        >
                    <label class="form-check-label" for="receber_email">
                        Receber notificações por e-mail
                    </label>
                </div>
                
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" 
                        name="alertas_estoque" id="alertas_estoque"
                        >
                    <label class="form-check-label" for="alertas_estoque">
                        Alertas de estoque crítico
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3"><i class="fas fa-clock me-2"></i>Horário de Notificações</h6>
                <input type="time" name="horario_notificacoes" 
                    value=""
                    class="form-control w-auto">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Salvar Configurações
            </button>
        </form>
    </div>
</div>
@endsection
