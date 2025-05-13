<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ConectaDador Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <link href="assets/img/flavicon.png" rel="icon">

    <!-- Fonts e Ícones -->
    <script src="{{ asset('assets/Centro/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons"
          ],
          urls: ["{{ asset('assets/Centro/assets/css/fonts.min.css') }}"]
        },
        active: function () {
          sessionStorage.fonts = true;
        }
      });
    </script>

    <!-- CSS Files -->
     
    <link href="{{ url('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/Centro/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/Centro/assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/vendor/fontawesome/css/all.min.css') }}">
    <!--<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">-->
    <!-- CSS Just for demo purpose -->
    <link rel="stylesheet" href="{{ url('assets/Centro/assets/css/demo.css') }}">
    <link href="assets/img/flavicon.png" rel="icon">

    @stack('styles')
  </head>
  <body>
    <div class="wrapper">
      
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <div class="logo-header" data-background-color="dark">
          <i class="fas fa-tint  logo text-white h3" style="margin-right: 10px;"></i> 
            <a href="" class="logo text-white h3">
              ConectaDador
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                  <a href="{{ route('admin.dashboard') }}">
                      <i class="fas fa-home"></i>
                      <p>Dashboard</p>
                  </a>
              </li>

              <span class="sidebar-title">Doadores</span>
              <li class="nav-item {{ request()->routeIs('listaD') ? 'active' : '' }}">
                  <a href="{{ route('listaD') }}">
                      <i class="fas fa-user-check"></i>
                      <p>Lista de Doadores</p>
                  </a>
              </li>
              <span class="sidebar-title">centro</span>
              <li class="nav-item {{ request()->routeIs('centros.lista') ? 'active' : '' }}">
                <a href="{{ route('centros.lista') }}">
                    <i class="fas fa-building"></i>
                    <p>Lista de Centros</p>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('centro.mapa') ? 'active' : '' }}">
                <a href="{{ route('centro.mapa') }}">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>Mapa de Centros</p>
                </a>
            </li>

              <span class="sidebar-title">Agendamentos</span>
              <li class="nav-item {{ request()->routeIs('agendamentos.todos') ? 'active' : '' }}">
                  <a href="{{ route('agendamentos.todos') }}">
                      <i class="fas fa-calendar-check"></i>
                      <p>Todos os Agendamentos</p>
                  </a>
              </li>

              <span class="sidebar-title">Campanhas </span>
              <li class="nav-item {{ request()->routeIs('campanhas.gerenciar') ? 'active' : '' }}">
                  <a href="">
                      <i class="fas fa-bullhorn"></i>
                      <p>Gerenciar Campanhas</p>
                  </a>
              </li>

              <span class="sidebar-title">Relatórios</span>
              <li class="nav-item {{ request()->routeIs('relatorios.index') ? 'active' : '' }}">
                  <a data-bs-toggle="collapse" href="#relatorios" aria-expanded="false">
                      <i class="fas fa-chart-bar"></i>
                      <p>Relatórios</p>
                      <span class="caret"></span>
                  </a>
                  <div class="collapse" id="relatorios">
                      <ul class="nav nav-collapse">
                          <li><a href=""><span class="sub-item">Diário</span></a></li>
                          <li><a href=""><span class="sub-item">Mensal</span></a></li>
                          <li><a href=""><span class="sub-item">Por Centro</span></a></li>
                      </ul>
                  </div>
              </li>

              <span class="sidebar-title">Notificações</span>
              <li class="nav-item {{ request()->routeIs('notificacoes.index') ? 'active' : '' }}">
                  <a href="">
                      <i class="fas fa-bell"></i>
                      <p>Notificações</p>
                      <span class="badge badge-danger">4</span>
                  </a>
              </li>
              <li class="nav-item {{ request()->routeIs('emergencias.index') ? 'active' : '' }}">
                  <a href="">
                      <i class="fas fa-ambulance"></i>
                      <p>Emergências</p>
                  </a>
              </li>

              <span class="sidebar-title">Usuários</span>
              <li class="nav-item {{ request()->routeIs('admin.usuarios.index') ? 'active' : '' }}">
                  <a href="{{ route('admin.usuarios.index') }}">
                      <i class="fas fa-users-cog"></i>
                      <p>Gerenciar Usuários</p>
                  </a>
              </li>

              <span class="sidebar-title">Configurações</span>
              <li class="nav-item {{ request()->routeIs('configuracoes.index') ? 'active' : '' }}">
                  <a href="">
                      <i class="fas fa-cogs"></i>
                      <p>Opções do Sistema</p>
                  </a>
              </li>
              
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <div class="logo-header" data-background-color="dark">
              <a href="" class="logo">
                <img src="{{ asset('assets/img/profile.png')}}" alt="Logo" class="navbar-brand" height="20" />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
          </div>
          <!-- Navbar -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
              <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input type="text" placeholder="Pesquisar..." class="form-control" />
                </div>
              </nav>
              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                      <img src="{{ asset('assets/img/profile.png') }}" alt="avatar" class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                    <span class="fw-bold">Admin</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img src="{{ asset('assets/img/profile.png') }}" alt="perfil" class="avatar-img rounded" />
                          </div>
                          <div class="u-text">
                            <h4>Admin</h4>
                            <p class="text-muted">admin@conectadador.com</p>
                            <a href="" class="btn btn-xs btn-secondary btn-sm">Ver Perfil</a>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="">Meu Perfil</a>
                        <a class="dropdown-item" href="">Configurações</a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                          @csrf
                          <button class="dropdown-item" type="submit">Sair</button>
                        </form>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <!-- Conteúdo Principal -->
        <div class="container">
          <div class="page-inner">
            <div class="content">
              @yield('conteudo')
            </div>
          </div>
        </div>

        <!-- Rodapé -->
        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <div class="copyright">
              © {{ date('Y') }} ConectaDador, feito com <i class="fa fa-heart heart text-danger"></i> por
              <a href="#">ManuelEstevao & CatiaDiogo</a> 
            </div>
          </div>
        </footer>
      </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/Centro/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/jsvectormap/world.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/gmaps/gmaps.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('scripts')
  </body>
</html>
