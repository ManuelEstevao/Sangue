<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <link href="assets/img/flavicon.png" rel="icon">
    

    <!-- Fonts and Icons -->
    <script src="{{ url('assets/Centro/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ url('assets/Centro/assets/css/fonts.min.css') }}"]
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

    @yield('styles')
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->

          <div class="logo-header " style="  display: flex;
            align-items: center;" data-background-color="dark">
          
            <i class="fas fa-tint  logo text-white h3" style="margin-right: 10px;"></i>   
            
            <a href="#" class="logo text-white h3">
             
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
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <!-- Dashboard -->
              <li class="nav-item {{ request()->routeIs('centro.Dashbord') ? 'active' : '' }}">
                <a href="{{route('centro.Dashbord')}}">
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <br>
              <!-- Seção Doadores -->
              <span class="sidebar-title">Doadores</span>
              <li class="nav-item {{ request()->routeIs('listar.doador') ? 'active' : '' }}">
                <a href="{{route('listar.doador')}}">
                  <i class="fas fa-users"></i>
                  <p>Doadores</p>
                </a>
              </li>
              <li class="nav-item {{ request()->routeIs('centro.agendamento') ? 'active' : '' }}">
                <a href="{{route('centro.agendamento')}}">
                  <i class="fas fa-calendar-check"></i>
                  <p>Agendamentos</p>
                </a>
              </li>
              <li class="nav-item {{ request()->routeIs('centro.doacao') ? 'active' : '' }}">
                  <a href="{{route('centro.doacao')}}">
                      <i class="fas fa-hand-holding-heart"></i>
                      <p>Doações</p>
                  </a>
              </li>
             
              <li class="nav-item {{ request()->routeIs('campanhas.index') ? 'active' : '' }}">
                  <a href="{{ route('campanhas.index') }}">
                      <i class="fas fa-bullhorn"></i>
                      <p>Campanhas</p>
                  </a>
              </li>

              
              <!-- Seção Relatórios 
              <span class="sidebar-title">Relatórios</span>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#relatorios" aria-expanded="false">
                  <i class="fas fa-chart-bar"></i>
                  <p>Relatórios</p>
                  <span class="caret"></span>
                </a>
                 <div class="collapse" id="relatorios">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="#">
                        <span class="sub-item">Diário</span>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <span class="sub-item">Semanal</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>-->
              <br>
              <!-- Seção Emergências -->
              <span class="sidebar-title">Emergências</span>
              <li class="nav-item">
                <a href="#">
                  <i class="fas fa-ambulance"></i>
                  <p>Solicitação</p>
                  <span class="badge badge-danger">3</span>
                </a>
              </li>
              <br><br>
              <li class="sidebar-item btn-logout">
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">Terminar Sessão</button>
                  </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <!-- Main Panel -->
      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
             
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
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                  <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" aria-haspopup="true">
                    <i class="fa fa-search"></i>
                  </a>
                </li>
                <!--
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-envelope"></i>
                  </a>
                  Mensagens, se necessário 
                </li>-->
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    <span class="notification">4</span>
                  </a>
                  <!-- Notificações -->
                </li>
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                      <img src="{{ asset('assets/img/profile.png') }}" alt="..." class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                      <span class="op-7"></span> <span class="fw-bold"></span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img src="{{ asset('assets/img/profile.png') }}" alt="image profile" class="avatar-img rounded" />
                          </div>
                          <div class="u-text">
                            <h4></h4>
                            <p class="text-muted"></p>
                            <a href="" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="">My Profile</a>
                        <a class="dropdown-item" href="#">Settings</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="">
                          @csrf
                          <button class="dropdown-item" type="submit">Logout</button>
                        </form>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar Header -->
        </div>

        <!-- Main Content -->
        <div class="container">
          <div class="page-inner">
            @yield('conteudo')
          </div>
        </div>

        <!-- Footer -->
        <footer class="footer mt-auto py-3 bg-light">
          <div class="container-fluid d-flex justify-content-between">
            <div class="copyright">
              © 2024 ConectaDador, made with <i class="fa fa-heart heart text-danger"></i> by <a href="#">manuelestev...</a> & 
            </div>
          </div>
        </footer>
      </div>
    </div>

    <!-- Scripts -->
    <script src="{{ url('assets/Centro/assets/Centro/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ url('assets/Centro/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ url('assets/Centro/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ url('assets/Centro/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ url('assets/Centro/assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ url('assets/Centro/assets/js/kaiadmin.min.js') }}"></script>
    @yield('scripts')
  </body>
</html>
