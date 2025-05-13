<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="assets/img/flavicon.png" rel="icon">
    

    <script src="{{ asset('assets/Centro/assets/js/plugin/webfont/webfont.min.js') }}"></script>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="{{ url('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/Centro/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/Centro/assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/vendor/fontawesome/css/all.min.css') }}">
    <!--<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">-->
    <!-- CSS Just for demo purpose -->
    <link rel="stylesheet" href="{{ url('assets/Centro/assets/css/demo.css') }}">

    <style>

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}

/* Estilo para os ícones de notificação */
.icon-circle {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Melhorias no dropdown */
.notif-box {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.list-group-item {
    transition: background-color 0.2s;
    border-left: 3px solid transparent;
}

.list-group-item.unread {
    border-left-color: #0d6efd;
    background-color: #f8f9fa;
}

.list-group-item:hover {
    background-color: #f1f1f1;
}
    </style>
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

            <span class="sidebar-title">Gestão de Estoque</span>
            <li class="nav-item {{ request()->routeIs('estoque.*') ? 'active' : '' }}">
              <a href="{{ route('estoque.index') }}">
                <i class="fas fa-tint"></i>
                <p>Estoque</p>
              </a>
            </li>

              <br>
              <!-- Seção Emergências -->
              <span class="sidebar-title">Emergências</span>
              <li class="nav-item {{ request()->routeIs('solicitacao.*') ? 'active' : '' }}">
                  <a href="{{ route('centro.solicitacao.index') }}" class="nav-link">
                  <i class="fas fa-ambulance"></i>
                  <span>Solicitações de Sangue</span>
                </a>
              </li>
              <!--<br><br>
              <li class="sidebar-item btn-logout">
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">Terminar Sessão</button>
                  </form>
              </li>-->
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
                @php
                  use Carbon\Carbon;
                @endphp
               <li class="nav-item topbar-icon dropdown hidden-caret">
    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button"
       data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-bell"></i>
        @if($naoLidas > 0)
            <span class="notification pulse">{{ $naoLidas }}</span>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end notif-box animated fadeIn py-0" 
        aria-labelledby="notifDropdown"
        style="width: 350px; max-height: 70vh; overflow-y: auto;">
        
        <li class="dropdown-header bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-bell me-2"></i>
                    Notificações ({{ $naoLidas }})
                </h6>
                @if($naoLidas > 0)
                    <button id="mark-all-read-btn" 
                        class="btn btn-sm btn-link text-danger"
                        onclick="markAllAsRead()">
                    Marcar  como lidas
                </button>
                @endif
            </div>
        </li>

        <li>
            <div class="notif-scroll scrollbar-outer">
                <div class="list-group list-group-flush">
                    @forelse($notificacoes as $notif)
                        <a href="{{ $notif->link }}" 
                           class="list-group-item list-group-item-action border-bottom py-3"
                           onclick="markNotificationAsRead({{ $notif->id_notificacao }})">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <div class="icon-circle bg-{{ $notif->tipo === 'urgente' ? 'danger' : 'primary' }}">
                                        <i class="fas fa-{{ $notif->tipo === 'agendamento' ? 'calendar' : 'exclamation' }} text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="mb-1">
                                        <span class="fw-bold">{{ $notif->titulo }}</span>
                                        <span class="text-muted float-end small">
                                            {{ Carbon::parse($notif->data_envio)->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="small mb-0 text-muted">
                                        {{ $notif->mensagem }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-2x text-muted mb-3"></i>
                            <p class="text-muted small mb-0">Nenhuma nova notificação</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </li>

        <li class="dropdown-footer bg-light py-2">
            <a href="#" 
               class="text-center d-block small text-decoration-none">
                Ver histórico completo
            </a>
        </li>
    </ul>
</li>

                              <li class="nav-item topbar-user dropdown hidden-caret">
                  <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                      <img src="{{ Auth::user()->centro->foto ? asset('storage/centros/' . Auth::user()->centro->foto) : asset('assets/img/profile.png') }}" alt="..." class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                      <span class="fw-bold">{{ Auth::user()->centro->nome }}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                          <img src="{{ Auth::user()->centro->foto ? asset('storage/centros/' . Auth::user()->centro->foto) : asset('assets/img/profile.png') }}" alt="perfil" class="avatar-img rounded" />
                          </div>
                          <div class="u-text">
                            <h4>{{ Auth::user()->centro->nome }}</h4>
                            <p class="text-muted">{{ Auth::user()->email }}</p>
                          </div>
                        </div>
                      </li>
                      <li>
                      
                      <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('centro.perfil') }}">Meu Perfil</a>
                        <div class="dropdown-divider"></div>
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
              © 2024 ConectaDador, made with <i class="fa fa-heart heart text-danger"></i> by <a href="#">ManuelEstevao & CatiaDiogo</a>  
            </div>
          </div>
        </footer>
      </div>
    </div>
<!-- Adicione isto antes do fechamento do body -->
<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Sistema</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>
</div>
    <script>
   function markAllAsRead() {
    const counter = document.querySelector('.notification');
    const notificationList = document.querySelector('.notif-scroll');
    const button = document.getElementById('mark-all-read-btn');

    if (!counter || !notificationList || !button) {
        console.error('Elementos necessários não encontrados');
        return;
    }

    // Bloqueia e mostra loading
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Processando...';

    fetch('/notificacoes/marcar-todas-lidas', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Falha na requisição');
        
        // Atualizações visuais
        counter.remove();
        
        // Remove todas as notificações
        notificationList.querySelectorAll('.list-group-item').forEach(item => {
            item.style.opacity = '0';
            setTimeout(() => item.remove(), 300);
        });

        // Atualiza o botão
        button.outerHTML = '<small class="text-muted">Todas marcadas como lidas</small>';
        
        // Mostra feedback
        showToast('Todas as notificações foram marcadas como lidas!', 'success');
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao marcar notificações: ' + error.message, 'danger');
        button.disabled = false;
        button.innerHTML = originalContent;
    });
}

// Função de toast corrigida
function showToast(message, type = 'success') {
    const toastElement = document.getElementById('liveToast');
    const toastBody = document.getElementById('toast-message');
    
    // Remove classes anteriores
    toastElement.classList.remove('text-bg-success', 'text-bg-danger');
    
    // Adiciona classes conforme o tipo
    toastElement.classList.add(`text-bg-${type}`);
    toastBody.textContent = message;
    
    // Inicializa e mostra o toast
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}
    </script>

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
    <script src="{{ asset('assets/Centro/assets/js/plugin/sweetalert/sweetalert2.min.js') }}"></script>
  

    @yield('scripts')
  </body>
</html>
