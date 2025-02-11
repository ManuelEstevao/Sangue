<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ConectaDador')</title>
    <!-- Bootstrap CSS -->
    <link href="{{url('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{url('assets/vendor/fontawesome/css/all.min.css')}}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashbordDador.css') }}">
    @yield('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-light border-end">
            <div class="p-3">
                <div class="sidebar-logo mb-4 text-center">
                    <a href="#" class="text-decoration-none">
                        <h3 class="fw-bold">ConectaDador</h3>
                    </a>
                </div>
                <ul class="sidebar-nav list-unstyled">
                    <li class="sidebar-item active">
                        <a href="{{ route('dash') }}" class="sidebar-link text-decoration-none">
                            <i class="fa-solid fa-chart-line pe-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('dador') }}" class="sidebar-link text-decoration-none">
                            <i class="fa-solid fa-house pe-2"></i> Página Inicial
                        </a>
                    </li>

                    <span class="sidebar-title">Gerenciamento</span>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link text-decoration-none">
                            <i class="fa-solid fa-calendar-check pe-2"></i> Agendamentos
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link text-decoration-none">
                            <i class="fa-solid fa-clock-rotate-left pe-2"></i> Histórico de Doação
                        </a>
                    </li>
                    <span class="sidebar-title">Dados</span>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link text-decoration-none">
                            <i class="fa-solid fa-bell pe-2"></i> Notificações
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link text-decoration-none">
                            <i class="fa-solid fa-user pe-2"></i> Perfil
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main flex-grow-1">
            <!-- Navbar Superior -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-3">
                <button class="btn" id="sidebar-toggle" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="ms-auto">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('assets/img/profile.jpg') }}" class="rounded-circle" alt="Avatar" style="width:40px;">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Perfil</a></li>
                                <li><a class="dropdown-item" href="#">Configurações</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Sair</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Conteúdo Principal -->
            <main class="content p-3">
                @yield('conteudo')
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="{{url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- Custom Script -->
    <script src="assets/js/script.js"></script>
    @yield('scripts')
</body>
</html>