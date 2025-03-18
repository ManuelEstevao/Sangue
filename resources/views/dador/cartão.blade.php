@section('styles')
<style>
    /* Estilos do Cartão Moderno */
    .cartao-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(220, 53, 69, 0.15);
        overflow: hidden;
        position: relative;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
    }

    .cartao-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 2rem;
        position: relative;
        backdrop-filter: blur(10px);
    }

    .cartao-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.1);
        z-index: 1;
    }

    .logo-container {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
    }

    .logo-icon {
        font-size: 2rem;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }

    .logo-text {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .foto-wrapper {
        width: 140px;
        height: 140px;
        border: 4px solid white;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 1.5rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        position: relative;
        z-index: 2;
    }

    .foto-doador {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .blood-type {
        background: rgba(255,255,255,0.9);
        color: #dc3545;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 800;
        display: inline-block;
        backdrop-filter: blur(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        position: relative;
        z-index: 2;
    }

    .info-section {
        padding: 2rem;
        position: relative;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item {
        background: rgba(220, 53, 69, 0.03);
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid rgba(220, 53, 69, 0.1);
    }

    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
        display: block;
    }

    .info-value {
        font-weight: 600;
        color: #343a40;
        margin: 0;
    }

    .qrcode-section {
        text-align: center;
        padding: 1.5rem;
        background: rgba(220, 53, 69, 0.05);
        border-radius: 15px;
        margin-top: 1.5rem;
    }

    .copyright {
        text-align: center;
        padding: 1.5rem;
        font-size: 0.75rem;
        color: #6c757d;
        border-top: 1px solid rgba(0,0,0,0.05);
    }

    /* Estilos de Impressão */
    @media print {
        body * {
            visibility: hidden;
        }

        .cartao-container,
        .cartao-container * {
            visibility: visible;
        }

        .cartao-container {
            box-shadow: none;
            border-radius: 0;
            width: 100%;
            max-width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .cartao-header {
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }

        .qrcode-section {
            background: transparent !important;
        }

        .info-item {
            background: transparent;
            border: 1px solid #ddd;
        }
    }
</style>
@endsection

@section('conteudo')
<!-- Modal do Cartão -->
<div class="modal fade" id="cartaoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-body p-3">
                <div class="cartao-container">
                    <div class="cartao-header">
                        <div class="logo-container">
                            <i class="fas fa-tint logo-icon"></i>
                            <span class="logo-text">SangueApp</span>
                        </div>
                        
                        <div class="foto-wrapper">
                            <img src="{{ $doador->foto ? asset('storage/'.$doador->foto) : asset('assets/img/profile.png') }}" 
                                 class="foto-doador"
                                 alt="Foto do doador">
                        </div>
                        <div class="blood-type text-center">
                            Tipo Sanguíneo: {{ $doador->tipo_sanguineo }}
                        </div>
                    </div>

                    <div class="info-section">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Nome Completo</span>
                                <p class="info-value">{{ $doador->nome }}</p>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Documento</span>
                                <p class="info-value">{{ $doador->bi }}</p>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Primeira Doação</span>
                                <p class="info-value">{{ $doador->primeira_doacao ?? '--/--/----' }}</p>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Última Doação</span>
                                <p class="info-value">{{ $doador->ultima_doacao ?? '--/--/----' }}</p>
                            </div>
                        </div>

                        <div class="qrcode-section">
                            <div class="mb-2">
                                {!! QrCode::size(120)->generate($doador->id) !!}
                            </div>
                            <small class="text-muted">ID: {{ $doador->id }}</small>
                        </div>
                    </div>

                    <div class="copyright">
                        © {{ date('Y') }} SangueApp. Todos os direitos reservados.
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-center border-0 bg-white">
                <button type="button" class="btn btn-lg btn-outline-danger" data-bs-dismiss="modal">
                    Fechar
                </button>
                <button onclick="printCartao()" class="btn btn-lg btn-danger">
                    <i class="fas fa-print me-2"></i>
                    Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function printCartao() {
    const printContent = document.querySelector('.cartao-container').cloneNode(true);
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Cartão do Doador - {{ $doador->nome }}</title>
                <style>
                    ${document.querySelector('#cartao-styles').innerHTML}
                </style>
            </head>
            <body>
                ${printContent.outerHTML}
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(() => window.close(), 500);
                    }
                <\/script>
            </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
@endsection