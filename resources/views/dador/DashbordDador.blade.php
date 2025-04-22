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
    <link href="assets/img/flavicon.png" rel="icon">  
    @yield('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-white border-end shadow-sm d-flex flex-column" style="min-height:100vh; width: 250px;">
  <!-- Logo -->
  <div class="p-3 text-center border-bottom">
    <a href="#" class="text-decoration-none text-dark">
      <h3 class="fw-bold mb-0">ConectaDador</h3>
    </a>
  </div>

  
  <nav class="flex-grow-1 overflow-auto ">
    <ul class="nav flex-column mt-3 px-2">
      <!-- Dashboard -->
      <li class="nav-item mb-1">
        <a href="{{ route('doador.Dashbord') }}"
           class="nav-link d-flex align-items-center gap-2 py-2 px-3 rounded
             {{ request()->routeIs('doador.Dashbord') 
                 ? 'active bg-danger text-white' 
                 : 'text-dark hover-bg-light' }}">
          <i class="fa-solid fa-chart-line"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <!-- Página Inicial -->
      <li class="nav-item mb-1">
        <a href="{{ route('dador') }}"
           class="nav-link d-flex align-items-center gap-2 py-2 px-3 rounded
             {{ request()->routeIs('dador') 
                 ? 'active bg-danger text-white' 
                 : 'text-dark hover-bg-light' }}">
          <i class="fa-solid fa-house"></i>
          <span>Página Inicial</span>
        </a>
      </li>

      <!-- Seção -->
      <li class="mt-4 mb-2 ps-3 text-uppercase small text-muted">Meu Registro</li>

      <!-- Agendar Doação -->
      <li class="nav-item mb-1">
        <a href="{{ route('agendamento') }}"
           class="nav-link d-flex align-items-center gap-2 py-2 px-3 rounded
             {{ request()->routeIs('agendamento') 
                 ? 'active bg-danger text-white' 
                 : 'text-dark hover-bg-light' }}">
          <i class="fa-solid fa-calendar-check"></i>
          <span>Agendar Doação</span>
        </a>
      </li>

      <!-- Histórico -->
      <li class="nav-item mb-1">
        <a href="{{ route('historico') }}"
           class="nav-link d-flex align-items-center gap-2 py-2 px-3 rounded
             {{ request()->routeIs('historico') 
                 ? 'active bg-danger text-white' 
                 : 'text-dark hover-bg-light' }}">
          <i class="fa-solid fa-clock-rotate-left"></i>
          <span>Histórico de Doação</span>
        </a>
      </li>

      <!-- Seção -->
      <li class="mt-4 mb-2 ps-3 text-uppercase small text-muted">Meu Perfil</li>

      <!-- Perfil -->
      <li class="nav-item mb-1">
        <a href="{{ route('perfil') }}"
           class="nav-link d-flex align-items-center gap-2 py-2 px-3 rounded
             {{ request()->routeIs('perfil') 
                 ? 'active bg-danger text-white' 
                 : 'text-dark hover-bg-light' }}">
          <i class="fa-solid fa-user"></i>
          <span>Meu Perfil</span>
        </a>
      </li>
    </ul>
  </nav>

  <!-- Logout -->
  <div class="mt-auto p-3 border-top">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit"
              class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Terminar Sessão</span>
      </button>
    </form>
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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        @if($notificacoes->count() > 0)
                        <span class="badge bg-danger">{{ $notificacoes->count() }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @forelse($notificacoes as $notificacao)
                        <li>
                            <a class="dropdown-item" href="#">
                            {{ $notificacao->titulo }} <br>
                            <small class="text-muted">{{ $notificacao->data_envio->diffForHumans() }}</small>
                            </a>
                        </li>
                        @empty
                        <li><span class="dropdown-item text-muted">Sem notificações</span></li>
                        @endforelse
                    </ul>
                </li>
                    @php
                            $doador=Auth::user()->doador;
                            $nomeCompleto = Auth::user()->doador->nome;
                            $partes = explode(' ', $nomeCompleto);
                            $primeiroNome = $partes[0];
                            $ultimoNome = count($partes) > 1 ? $partes[count($partes)-1] : '';
                            $nomeExibido = $primeiroNome . ($ultimoNome ? ' ' . $ultimoNome : '');
                        @endphp

                        <li class="nav-item dropdown">
                        <a
                            class="nav-link d-flex align-items-center"
                            href="#"
                            id="userDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                       
                        <img src="{{ $doador->foto ?  asset('storage/fotos/' . $doador->foto) : asset('assets/img/profile.png') }}" class="img-fluid rounded-circle" 
                        style="width: 40px; height: 40px; object-fit: cover">
                        <span class="ms-2">{{ $nomeExibido }}</span></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!--<li><a class="dropdown-item" href="#">Meu Perfil</a></li> -->
                            <li><hr class="dropdown-divider my-1"></li>
                               
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                            <i class="fa-solid fa-right-from-bracket"></i>
                                            <span>Terminar Sessão</span>
                                        </button>
                                    </form>
                                </li>
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
    <script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <!-- Custom Script -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    @yield('scripts')
</body>
</html>
