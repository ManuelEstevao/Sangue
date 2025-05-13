<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhe da Campanha</title>
    
    <!-- CSS Essenciais -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/img/flavicon.png') }}" rel="icon">
</head>

<body>
<!-- Conteúdo Principal -->
<main class="main-content">
    <section class="section py-5">
        <div class="container" data-aos="fade-up">
            <!-- Card Principal -->
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="card-body p-4">
                    <div class="row g-5 align-items-stretch">
                        <!-- Coluna da Imagem -->
                        <div class="col-lg-6">
                            <div class="position-relative h-100">
                                @if($campanha->foto)
                                    <img src="{{ asset('storage/' . $campanha->foto) }}" 
                                         class="img-fluid rounded-3 w-100 h-100 object-fit-cover" 
                                         alt="{{ $campanha->titulo }}">
                                @else
                                    <img src="{{ asset('assets/img/campanha.png') }}" 
                                         class="img-fluid rounded-3 w-100 h-100 object-fit-cover" 
                                         alt="Campanha padrão">
                                @endif
                                <!-- Badge com Efeito AOS -->
                                <div class="badge bg-danger position-absolute top-0 start-0 m-3" data-aos="zoom-in">
                                {{ \Carbon\Carbon::parse($campanha->data_inicio)->format('d M') }} - {{ \Carbon\Carbon::parse($campanha->data_fim)->format('d M') }}
                                </div>
                            </div>
                        </div>

                        <!-- Coluna de Conteúdo -->
                        <div class="col-lg-6">
                            <div class="d-flex flex-column h-100 px-lg-4">
                                <!-- Título com Animação -->
                                <h1 class="display-5 fw-bold mb-4 text-danger" data-aos="fade-left">
                                    {{ $campanha->titulo }}
                                </h1>

                                <!-- Bloco de Informações -->
                                <div class="info-blocks">
                                    <!-- Seção do Centro -->
                                    <div class="info-block mb-5" data-aos="fade-up">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-building fs-3 text-danger me-3"></i>
                                            <h3 class="h4 mb-0">{{ $campanha->centro->nome }}</h3>
                                        </div>
                                        <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="bi bi-geo-alt-fill me-2"></i>
                                            @if(!empty($campanha->endereco))
                                                {{ $campanha->endereco }}
                                            @else
                                                {{ $campanha->centro->endereco }}
                                            @endif
                                        </li>
                                            <li class="mb-2">
                                                <i class="bi bi-clock-fill me-2"></i>
                                                {{ $campanha->hora_inicio }} - {{ $campanha->hora_fim }}
                                            </li>
                                            <li>
                                                <i class="bi bi-telephone-fill me-2"></i>
                                                {{ $campanha->centro->telefone }}
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Descrição -->
                                    <div class="info-block mb-5" data-aos="fade-up">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-info-circle fs-3 text-danger me-3"></i>
                                            <h3 class="h4 mb-0">Sobre a Campanha</h3>
                                        </div>
                                        <p class="lead text-secondary">{{ $campanha->descricao }}</p>
                                    </div>

                                    <!-- Botões de Ação -->
                                    <div class="mt-auto" data-aos="fade-up">
                                        <div class="d-grid gap-3 d-md-flex">
                                            <a href="javascript:void(0);" 
                                                class="btn btn-outline-secondary btn-lg px-4 py-3" 
                                                onclick="window.history.back();">
                                                    <i class="bi bi-arrow-left me-2"></i>Voltar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
<script>
    AOS.init({duration: 1000});
</script>

</body>
</html>