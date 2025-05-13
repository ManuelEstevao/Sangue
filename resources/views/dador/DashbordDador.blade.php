<!DOCTYPE html> 
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                 @php
                  use Carbon\Carbon;
                @endphp
                <button class="btn" id="sidebar-toggle" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="ms-auto d-flex align-items-center">
                <ul class="navbar-nav me-3">
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
    
            <!-- Conteúdo Principal -->
            <main class="content p-3">
                @yield('conteudo')
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Custom Script -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/Centro/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
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
    @yield('scripts')
</body>
</html>
