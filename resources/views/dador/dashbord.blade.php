@extends('dador.DashbordDador')

@section('title', 'Dashboard - ConectaDador')
<link href="assets/img/flavicon.png" rel="icon">

@section('styles')
<style>
    /* Estilos personalizados */
    :root {
        --red-theme: rgb(165, 3, 3);
        --hover-light: #ffe5e5;
        --btn-hover: #f8d7da;
        --vermelho-primario: #dc3545;
        --vermelho-secundario: #a00;
        --sombra: 0 4px 20px rgba(0,0,0,0.2);
    }
    .card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        justify-content: space-between;
    }
    .card-col {
        flex: 1;
        min-width: 250px;
    }
    .custom-card {
        background-color: var(--red-theme);
        color: #fff;
        border-radius: 8px;
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .custom-card:hover {
        transform: scale(1.02);
    }
    .custom-card .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .icon-box {
        font-size: 48px;
        margin-bottom: 1rem;
    }
    .btn-light {
        background-color: #fff;
        color: var(--red-theme);
        border: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .btn-light:hover {
        background-color: var(--hover-light);
    }
    .btn-sm {
        font-size: 0.875rem;
    }
    .btn-info {
        background-color: #17a2b8;
        border: none;
    }
    .btn-info:hover {
        background-color: #138496;
    }
    .btn-danger {
        background-color: #dc3545;
        border: none;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }

.custom-pagination-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    margin: 0.5rem;
    
}

.custom-pagination-wrapper .pagination {
    gap: 0.5rem;
    flex-wrap: wrap;
    margin: 0;
}

.custom-pagination-wrapper .page-link {
    border: none;
    border-radius: 8px;
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    transition: all 0.3s ease;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.custom-pagination-wrapper .page-link:hover {
    background-color: #ffe6e9;
    color: #dc3545;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220,53,69,0.1);
}

.custom-pagination-wrapper .page-item.active .page-link {
    background-color: #dc3545 !important;
    color: white !important;
    font-weight: 500;
}

.custom-pagination-wrapper .page-item.disabled .page-link {
    opacity: 0.5;
    background-color: #f8f9fa;
    box-shadow: none;
}

@media (max-width: 576px) {
    .custom-pagination-wrapper .page-link {
        min-width: 35px;
        height: 35px;
        font-size: 0.9rem;
        margin-bottom: 40px;
    }
    
    .custom-pagination-wrapper .pagination {
        gap: 0.25rem;
    }
}

@media (max-width: 576px) {
    .custom-pagination-wrapper .page-link {
        min-width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
    
    .custom-pagination-wrapper .pagination {
        gap: 0.25rem;
    }
}



/* tabela */
 .bg-gradient-danger {
        background: linear-gradient(135deg,rgb(199, 35, 51) 0%, rgb(165, 3, 3) 100%);
    }

    .badge.rounded-pill {
        border-radius: 50rem!important;
        padding: 0.5em 1em;
    }

    .dropdown-menu {
        min-width: 200px;
        border: 1px solid rgba(220, 53, 69, 0.1);
    }

    .empty-state {
        opacity: 0;
        animation: fadeIn 0.4s ease-out forwards;
        animation-delay: 0.2s;
    }
.empty-state-icon {
    animation: fadeInBounce 1s ease;
}

@keyframes fadeInBounce {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }
    60% {
        transform: translateY(10px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.text-secondary {
    color: #6c757d !important;
}

.opacity-50 {
    opacity: 0.5;
}

/* Cartão */
.cartao-wrapper {
    width: 380px;
    height: 240px;
    perspective: 1000px;
    margin: 0 auto;
    transition: transform 0.3s;
}

.cartao-frente, .cartao-verso {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    transform-style: preserve-3d;
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--sombra);
}
.verso-captura .cartao-frente {
  display: none !important;
}
.verso-captura .cartao-verso {
  transform: none !important;
  backface-visibility: visible !important;
}

/* Frente do Cartão */
.cartao-frente {
    background: linear-gradient(135deg, var(--vermelho-primario), var(--vermelho-secundario));
    transform: rotateY(0deg);
}

.cabecalho h1 {
    color: white;
    font-size: 1.4rem;
    text-transform: uppercase;
    text-align: center;
    margin-bottom: 15px;
    line-height: 1.3;
}

.cabecalho h1 span {
    font-size: 0.75rem;
    display: block;
    opacity: 0.9;
    font-weight: 400;
    margin-top: 3px;
}

.conteudo {
    display: grid;
    grid-template-columns: 110px 1fr;
    gap: 15px;
    height: 130px;
}

.foto-wrapper {
    width: 110px;
    height: 135px;
    border: 2px solid rgba(255,255,255,0.9);
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    position: relative; /* Movido para cá para simplificar */
}

.foto-doador {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.blood-type {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(255, 255, 255, 0.9);
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 1.4rem;
    font-weight: 900;
    color: var(--vermelho-primario);
    box-shadow: var(--sombra);
    border: 2px solid white;
}

.dados {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.linha {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.rotulo {
    color: rgba(255,255,255,0.85);
    font-size: 0.7rem;
    font-weight: 500;
    min-width: 80px;
}

.valor {
    color: white;
    font-size: 0.85rem;
    font-weight: 600;
    max-width: 140px;
    text-align: right;
}

.footer {
    position: absolute;
    bottom: 4px;
    left: 0;
    right: 0;
    text-align: center;
}

.selo-autenticidade {
    color: rgba(255,255,255,0.85);
    font-size: 0.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

/* Verso Corrigido */
.cartao-verso {
    background: #ffffff;
    transform: rotateY(180deg);
    display: flex;
    flex-direction: column;
    padding: 20px;
}

.cabecalho-verso {
    border-bottom: 2px solid var(--vermelho-primario);
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.logo-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logo-sistema {
    height: 50px;
    width: auto;
}

.titulo-verso h2 {
    color: var(--vermelho-primario);
    font-size: 1.4rem;
    margin: 0;
    line-height: 1.2;
}

.titulo-verso p {
    color: #666;
    font-size: 0.75rem;
    margin: 3px 0 0;
}

.qr-area {
    display: flex;
    flex-direction: row-reverse; 
    gap: 20px;
    margin-top: auto;
}

.qr-code {
    background: white;
    border-radius: 8px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    width: 120px; 
    height: 120px;
    margin-bottom: 25px;
}

.qr-info {
    flex: 1;
    font-size: 0.75rem;
}

.qr-titulo {
    color: var(--vermelho-primario);
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 8px;
}

.qr-beneficios {
    list-style: none;
    padding: 0;
    margin: 0;
}

.qr-beneficios li {
    position: relative;
    padding-left: 15px;
    margin-bottom: 5px;
    font-size: 0.75rem;
}

.qr-beneficios li::before {
    content: "•";
    color: var(--vermelho-primario);
    position: absolute;
    left: 0;
}

/* Controles */
.controles {
    position: absolute;
    bottom: -60px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 12px;
}

.btn-close,
.btn-flip,
.btn-print {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    border: none;
    box-shadow: var(--sombra);
}

.btn-close {
    background: #666;
    color: white;
}

.btn-flip,
.btn-print {
    background: var(--vermelho-primario);
    color: white;
}

.btn-flip:hover,
.btn-print:hover,
.btn-close:hover {
    transform: scale(1.1);
    background: var(--vermelho-secundario);
}

/* Animação Flip */
.cartao-wrapper.flipped .cartao-frente {
    transform: rotateY(-180deg);
}

.cartao-wrapper.flipped .cartao-verso {
    transform: rotateY(0deg);
}

/* Media Queries Essenciais */
@media (max-width: 400px) {
    .cartao-wrapper {
        width: 95%;
        height: auto;
        aspect-ratio: 1.586;
    }
    
    .conteudo {
        grid-template-columns: 1fr;
    }
    
    .foto-wrapper {
        width: 100%;
        height: 150px;
    }
}

.verso-captura {
    position: fixed;
    left: -9999px;
    top: 0;
    transform: none !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.verso-captura .cartao-verso {
    transform: none !important;
    box-shadow: none;
}

@media print {
    .controles {
        display: none;
    }
    
    .cartao-frente,
    .cartao-verso {
        box-shadow: none;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

</style>
@endsection

@section('conteudo')

    <div class="container-fluid">
        <div >
            <!-- Linha de Cards -->
            <div class="card-container">
                <!-- Card 1: Próxima Doação -->
                <div class="card-col">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-box">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                            <h5 class="card-title">Próxima Doação</h5>
                            <p class="card-text">
                            @if($proximaDoacao)
                                <?php 
                                    // Extrai a data sem o horário extra (garante apenas a parte de data)
                                    $data = \Carbon\Carbon::parse($proximaDoacao->data_agendada)->format('Y-m-d');
                                    // Extrai somente a parte do horário
                                    $hora = \Carbon\Carbon::parse($proximaDoacao->horario)->format('H:i:s');
                                    // Junta os dois e cria um novo objeto Carbon
                                    $dataHora = \Carbon\Carbon::parse("{$data} {$hora}")->format('d/m/Y H:i');
                                ?>
                                {{ $dataHora }}<br>
                            @elseif($proximaDataPermitida)
                            Próxima doação disponível apartir de: {{ $proximaDataPermitida->format('d/m/Y') }}
                            @else
                                Nenhuma doação agendada
                            @endif
                        </p>

                        <a href="{{ route('agendamento') }}" class="btn btn-light mt-1 {{ ($proximaDoacao || $proximaDataPermitida) ? 'disabled' : '' }}">
                            Agendar <i class="fa-solid fa-calendar-plus ms-1"></i>
                        </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Doações Realizadas -->
                <div class="card-col">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-box">
                                <i class="fa-solid fa-hand-holding-medical"></i>
                            </div>
                            <h5 class="card-title">Doações Realizadas</h5>
                            <p class="card-text">
                                Total: {{ $totalDoacoes }} doações<br>
                            </p>
                            <a href="{{ route('historico') }}" class="btn btn-light mt-1">
                                Ver Histórico <i class="fa-solid fa-history ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Cartão Digital -->
                <div class="card-col">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-box">
                                <i class="fa-solid fa-id-card"></i>
                            </div>
                            <h5 class="card-title">Cartão Digital</h5>
                            <p class="card-text"></p>
                            <button class="btn btn-light mt-1" data-bs-toggle="modal" data-bs-target="#cartaoModal">
                                Visualizar Cartão <i class="fa-solid fa-id-badge ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

           <!-- Tabela de Últimos Agendamentos -->
            <div class="mt-5">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="card-header bg-gradient-danger text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0"><i class="fas fa-tint me-2"></i>Meus Agendamentos</h3>
                            
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive rounded-bottom">
                            <table class="table table-hover mb-0">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="ps-4 text-uppercase text-danger fw-medium small border-bottom-0">Data</th>
                                        <th class="text-uppercase text-danger fw-medium small border-bottom-0">Detalhes</th>
                                        <th class="text-uppercase text-danger fw-medium small border-bottom-0">Estado</th>
                                        <th class="pe-4 text-end text-uppercase text-danger fw-medium small border-bottom-0">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @forelse($agendamentos as $agendamento)
                                        <tr class="align-middle position-relative hover-scale">
                                            <td class="ps-4">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($agendamento->data_agendada)->isoFormat('DD MMM YYYY') }}</span>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($agendamento->horario)->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-shape bg-light-danger text-danger rounded-circle me-3">
                                                        <i class="fas fa-hospital"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $agendamento->centro->nome }}</h6>
                                                        <small class="text-muted">{{ $agendamento->centro->endereco }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                @php
                                                    $statusConfig = [
                                                        'Agendado' => ['color' => 'black', 'icon' => 'fas fa-clock'],
                                                        'Comparecido' => ['color' => 'info', 'icon' => 'fas fa-user-check'],
                                                        'Concluido' => ['color' => 'success', 'icon' => 'fas fa-check-double'],
                                                        'Cancelado' => ['color' => 'danger', 'icon' => 'fas fa-times-circle']
                                                    ];
                                                    $config = $statusConfig[$agendamento->status];
                                                @endphp
                                                
                                                <span class="badge bg-{{ $config['color'] }}-subtle text-{{ $config['color'] }} rounded-pill py-2 px-3">
                                                    <i class="{{ $config['icon'] }} me-2"></i>
                                                    {{ $agendamento->status }}
                                                </span>
                                            </td>
                                            
                                            <td class="pe-4 text-end">
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <!-- Botão de Direções -->
                                                    <a href="{{ route('direcoes', $agendamento->id_agendamento) }}" 
                                                    class="btn btn-icon btn-danger btn-sm rounded-circle"
                                                    data-bs-toggle="tooltip"
                                                    title="Ver direções">
                                                        <i class="fas fa-route"></i>
                                                    </a>

                                                    <!-- Menu de Ações -->
                                                    <div class="dropdown">
                                                        <button class="btn btn-icon btn-light btn-sm rounded-circle" 
                                                                type="button" 
                                                                data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow">
                                                            @if ($agendamento->status === 'Agendado')
                                                                <li>
                                                                    <a class="dropdown-item" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#editModal{{ $agendamento->id_agendamento }}">
                                                                        <i class="fas fa-edit me-2"></i>Editar
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('agendamento.cancelar', $agendamento->id_agendamento) }}" 
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" 
                                                                                class="dropdown-item text-danger" 
                                                                                onclick="return confirm('Confirmar cancelamento?')">
                                                                            <i class="fas fa-ban me-2"></i>Cancelar
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                            <li>
                                                                <a class="dropdown-item" 
                                                                href="{{ route('doador.questionario.comprovativo', $agendamento->id_agendamento) }}">
                                                                    <i class="fas fa-file-pdf me-2"></i>Comprovativo
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                   @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <div class="py-4">
                                                    <div class="empty-state-icon mb-2">
                                                        <i class="fas fa-calendar-times fa-2x text-danger opacity-50"></i>
                                                    </div>
                                                    <h5 class="text-secondary mb-1">Nenhum agendamento</h5>
                                                    <p class="text-muted small mb-0">Comece agendando sua primeira doação</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

                <div class="custom-pagination-wrapper mt-5">
                    {{ $agendamentos->links('pagination::bootstrap-4') }}
                </div>

            </div>
        </div>
    </div>
    @php
    $doador = Auth::user()->doador; // Obtendo dados do doador
@endphp
<div class="modal fade" id="cartaoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0">
                <!-- Container Flip -->
                <div class="cartao-wrapper" id="cartaoWrapper">
                    <!-- Frente -->
                    <div class="cartao-frente">
                        <div class="cabecalho">
                            <h1>Doador de Sangue<br><span>Cartão do doador</span></h1>
                        </div>

                        <div class="conteudo">
                            <!-- Coluna Esquerda - Foto -->
                            <div class="foto-coluna">
                                <div class="foto-wrapper">
                                    <img src="{{ $doador->foto ? asset('storage/fotos/' . $doador->foto) : asset('assets/img/profile.png') }}"
                                         class="foto-doador"
                                         alt="Foto do doador">
                                </div>
                                <div class="blood-type">
                                    {{ $doador->tipo_sanguineo ?? '-' }}
                                </div>
                            </div>

                            <!-- Coluna Direita - Dados -->
                            <div class="dados">
                                <div class="linha">
                                    <span class="rotulo">Nome Completo</span>
                                    <span class="valor">@php
                                            $nomes = explode(' ', $doador->nome);
                                            echo count($nomes) > 1 
                                                ? $nomes[0].' '.end($nomes) 
                                                : $doador->nome;
                                        @endphp</span>
                                </div>
                                <div class="linha">
                                    <span class="rotulo">Nº Identificação</span>
                                    <span class="valor">{{ $doador->numero_bilhete }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Fixo -->
                        <div class="footer">
                            <div class="selo-autenticidade ">
                            <i class="fas fa-tint"></i>
                                ConectaDador
                            </div>
                        </div>
                    </div>

                    <!-- Verso -->
           <div class="cartao-verso corrigir-inversao">
                <!-- Cabeçalho Institucional -->
                <div class="cabecalho-verso">
                    <div class="logo-wrapper">
                        <img src="{{ asset('assets/img/flavicon.png') }}" 
                            class="logo-sistema" 
                            alt="Sistema Nacional de Doação">
                        <div class="titulo-verso">
                            <h2>Doador de Sangue</h2>
                            <p>Ajudando a salvar vidas</p>
                        </div>
                    </div>
                </div>

                <!-- Área Principal -->
                <div class="conteudo-verso">
                    <!-- QR Code Interativo -->
                    <div class="qr-area">
                        <div class="qr-code">
                             <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" style="width: 120px;  height: 120px;">
                        </div>
                        <div class="qr-info">
                            <p class="qr-titulo">Acesso Digital ao Histórico</p>
                            <p class="qr-descricao">Digitalize com seu smartphone para:</p>
                            <ul class="qr-beneficios">
                                <li>Verificar doações registradas</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
                <!-- Controles -->
                <div class="controles">
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Fechar">
                        <i class="fas fa-times"></i>
                    </button>
                    <button class="btn-flip" onclick="flipCard()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button class="btn-print"  onclick="downloadPNG()">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal de editar -->
@foreach ($agendamentos as $agendamento)
<div class="modal fade" id="editModal{{ $agendamento->id_agendamento }}" tabindex="-1" aria-labelledby="editModalLabel{{ $agendamento->id_agendamento }}" aria-hidden="true">
     <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $agendamento->id_agendamento }}">Editar Agendamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
            <form action="{{ route('agendamento.update', ['id' => $agendamento->id_agendamento]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-body">
                                    <label class="form-label">Data da Doação</label>
                                    <input type="datetime-local" class="form-control" name="data_agendada" 
                                        value="{{ \Carbon\Carbon::parse(
                                            \Carbon\Carbon::parse($agendamento->data_agendada)->format('Y-m-d')
                                            . ' ' .
                                            \Carbon\Carbon::parse($agendamento->horario)->format('H:i:s')
                                        )->format('Y-m-d\TH:i') }}">


                                    <label class="form-label mt-2">Centro de Doação</label>
                                    <select name="id_centro" class="form-control">
                                        @foreach($centros as $centro)
                                            <option value="{{ $centro->id }}" @if($agendamento->id_centro == $centro->id) selected @endif>
                                                {{ $centro->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                </div>
    </div>
</div>
@endforeach
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
//Cartão
 async function downloadPNG() {
  const options = {
    scale: 3,
    useCORS: true,
    logging: true,
    backgroundColor: null
  };

  try {
    // 1) Captura a frente normalmente
    const frente = await html2canvas(
      document.querySelector('.cartao-frente'),
      options
    );

    // 2) Antes de capturar o verso, adiciona uma classe que exibe
    //    o verso “de frente” (sem rotações nem espelhamentos).
    const wrapper = document.querySelector('#cartaoWrapper');
    wrapper.classList.add('verso-captura');

    // 3) Captura TODO o wrapper (que agora só mostra o verso)
    const verso = await html2canvas(wrapper, options);

    // 4) Remove a classe pra não bagunçar o modal original
    wrapper.classList.remove('verso-captura');

    // 5) Faz o download das duas imagens
    downloadImage(frente, 'cartao-frente.png');
    setTimeout(() => downloadImage(verso, 'cartao-verso.png'), 500);

  } catch (error) {
    console.error('Erro:', error);
    alert('Erro ao gerar imagens!');
  }

  // Função auxiliar
  function downloadImage(canvas, fileName) {
    const link = document.createElement('a');
    link.download = fileName;
    link.href = canvas.toDataURL('image/png');
    link.click();
  }
}
//controlles
function flipCard() {
    const wrapper = document.getElementById('cartaoWrapper');
    wrapper.classList.toggle('flipped');
    void wrapper.offsetWidth; // Trigger reflow para suavizar animação
}

    // Também exibimos quaisquer erros de elegibilidade vindos do controller->create()
    @if(session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Atenção',
        text: "{{ session('error') }}",
        confirmButtonText: 'OK'
      });
    @endif
  
</script>
@endsection
