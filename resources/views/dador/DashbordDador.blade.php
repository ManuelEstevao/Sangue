<!DOCTYPE html> 
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ConectaDador')</title>
    <!-- Bootstrap CSS -->
    <link href="{{ url('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/vendor/fontawesome/css/all.min.css') }}">
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
                    <span class="sidebar-title">Meu registro</span>
                    <li class="sidebar-item">
                        <a href="{{ route('agendamento') }}" class="sidebar-link text-decoration-none">
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
                            <i class="fa-solid fa-user pe-2"></i> Perfil
                        </a>
                    </li>
                    <br><br><br><br><br>
                    <li class="sidebar-item .btn-logout">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">Terminar Sessão</button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main flex-grow-1">
            <!-- Navbar Superior -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-3 align-items-center gap-2">
                <button class="btn" id="sidebar-toggle" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="ms-auto d-flex align-items-center">
                    <ul class="navbar-nav me-3">
                        <!-- Ícone de Notificações na Navbar -->
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-solid fa-bell"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                    <a class="nav-link" href="#" aria-expanded="false">
                        <img src="{{ asset('assets/img/profile.png') }}" class="rounded-circle" alt="Avatar" style="width:40px;">
                        @php
                            $nomeCompleto = Auth::user()->doador->nome;
                            $partes = explode(' ', $nomeCompleto);
                            $primeiroNome = $partes[0];
                            $ultimoNome = count($partes) > 1 ? $partes[count($partes)-1] : '';
                            $nomeExibido = $primeiroNome . ($ultimoNome ? ' ' . $ultimoNome : '');
                        @endphp
                        <span class="ms-2">{{ $nomeExibido }}</span>
                    </a>
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
    <script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Custom Script -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @yield('scripts')
</body>
</html>
