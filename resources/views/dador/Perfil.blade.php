@extends('dador.DashbordDador')

@section('title', 'Meu Perfil')

@section('styles')
<style>
    .profile-card {
        background: linear-gradient(135deg, #ffffff, #fff5f5);
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(140, 0, 0, 0.1);
    }

    .blood-type {
        width: 100px;
        height: 100px;
        border: 3px solid #d10000;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
    }

    .donation-badge {
        background: #d10000;
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .info-card {
        background: #ffffff;
        border-left: 4px solid #d10000;
        border-radius: 8px;
        padding: 1.5rem;
    }
</style>
@endsection

@section('conteudo')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="profile-card p-4 mb-4">
                <!-- Cabeçalho -->
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="blood-type text-danger">
                        {{ $doador->tipo_sanguineo ?? '-' }}
                    </div>
                    <div>
                        <h2 class="mb-1">{{ $doador->nome }}</h2>
                        <p class="text-muted mb-0">Bilhete de identidade: {{ $doador->numero_bilhete }}</p>
                        <div class="donation-badge mt-2">
                            {{ $doador->doacoes_count }} Doações Realizadas
                        </div>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="info-card">
                            <h5 class="text-danger mb-3">📅 Histórico de Doações</h5>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-1">Primeira Doação</p>
                                    <strong>{{ $primeiraDoacao ?? '-' }}</strong>
                                </div>
                                <div>
                                    <p class="mb-1">Última Doação</p>
                                    <strong>{{ $ultimaDoacao ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <h5 class="text-danger mb-3">🏆 Conquistas</h5>
                            <div class="d-flex gap-3">
                                <div class="text-center">
                                    <div class="fs-4">🥇</div>
                                    <small>Doador Regular</small>
                                </div>
                                <div class="text-center">
                                    <div class="fs-4">🎯</div>
                                    <small>Meta 2024</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="d-grid gap-3">
                    <a href="#" class="btn btn-outline-danger">
                        <i class="fas fa-edit me-2"></i>Editar Perfil
                    </a>
                    <a href="{{ route('historico') }}" class="btn btn-outline-danger">
                        <i class="fas fa-history me-2"></i>Ver Histórico Completo
                    </a>
                </div>
            </div>

            <!-- QR Code de Identificação -->
            <div class="text-center mt-4">
                <div class="mb-2">
                    <i class="fas fa-qrcode fa-3x text-danger"></i>
                </div>
                <p class="small text-muted">Use este QR code para identificação em centros de doação</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Script para geração de QR Code dinâmico
    window.addEventListener('DOMContentLoaded', (event) => {
        new QRCode(document.getElementById("qrcode"), {
            text: "DADOR:{{ $doador->id }}",
            width: 120,
            height: 120,
            colorDark : "#d10000",
        });
    });
</script>
@endsection
