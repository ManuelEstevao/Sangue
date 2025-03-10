<!DOCTYPE html>
<html lang="Pt-Pt">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title')</title>
  <meta name="description" content="">


  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{url('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{url('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{url('assets/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{url('assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
  <link href="{{url('assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="{{url('assets/vendor/fontawesome/css/all.min.css')}}">
  <link href="assets/img/flavicon.png" rel="icon">
  
  <!-- Mapa-->
  <script src="https://api-maps.yandex.ru/2.1/?lang=pt_PT&apikey=db51d640-6b39-495c-b35b-c2ec8a719fc9" type="text/javascript"></script>

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Name: Plataforma de doação de sangue
  * Updated: 10 de Dezembro 2024
  * Author: Manuel Estevão & Cátia Diogo
  ======================================================== -->
</head>

<body class="index-page">

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="{{route('home')}}" class="logo d-flex align-items-center me-auto me-xl-0">
        <h1 class="sitename"><strong>ConectaDador</strong></h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
        <!--  <li><a href="#about">Doador</a></li> -->
          <li><a href="#informacoes">Informações</a></li>
          

          @auth
          <li><a href="#Campanha">Campanha</a></li>
          <li><a href="#faq">FAQ</a></li>
          
        </ul>
         <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
         </nav>

         <a class="btn-getstarted" href="{{route('doador.Dashbord')}}">Painel do doador</a>
          @endauth
          @guest
          <li><a href="#Campanha">Campanha</a></li>
          <li><a href="#faq">FAQ</a></li>
          <li><a href="#footer">Contactos</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="{{route('login')}}">Conecta-se </a>
      @endguest
    </div>
  </header>

  @yield('conteudo')

  <footer id="footer" class="footer bg-dark text-light">
    <div class="container footer-top py-5">
      <div class="row gy-4">
        
        <!-- Coluna 1: Sobre -->
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="/" class="logo d-flex align-items-center text-light text-decoration-none mb-3">
            <h3 class="sitename fw-bold mb-0">ConectaDador</h3>
          </a>
          <div class="footer-contact pt-3">
            <p><i class="bi bi-geo-alt-fill me-2"></i>Angola, Luanda</p>
            <p class="mt-3">
              <i class="bi bi-telephone-fill me-2"></i><strong>Telefone:</strong> <span>+244 5589 558 55</span>
            </p>
            <p>
              <i class="bi bi-envelope-fill me-2"></i><strong>Email:</strong>
              <a href="mailto:manuelestevao92@gmailcom" class="text-light text-decoration-none">manuelestevao92@gmailcom</a>
            </p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href="#" class="me-3"><i class="bi bi-twitter text-light"></i></a>
            <a href="#" class="me-3"><i class="bi bi-facebook text-light"></i></a>
            <a href="#" class="me-3"><i class="bi bi-instagram text-light"></i></a>
            <a href="#"><i class="bi bi-linkedin text-light"></i></a>
          </div>
        </div>
        
        <!-- Coluna 2: Links Úteis -->
        <div class="col-lg-2 col-md-3 footer-links ms-auto">
          <h5 class="fw-bold mb-3">Links Úteis</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-light text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Início</a></li>
            <li><a href="{{route('sobre')}}" class="text-light text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Sobre nós</a></li>
            <li><a href="#Campanha" class="text-light text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Campanha</a></li>
            <li><a href="#" class="text-light text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Termos de serviço</a></li>
            <li><a href="#" class="text-light text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Política de privacidade</a></li>
          </ul>
        </div>
  
        <!-- Coluna 3: Nossa Campanha -->
        <div class="col-lg-4 col-md-6 ms-lg-auto">
          <h5 class="fw-bold mb-3">Nossa Campanha</h5>
          <p>Fique por dentro das novidades e campanhas mais recentes.</p>
          <form action="#" class="d-flex">
            <input type="email" class="form-control me-2" placeholder="Insira seu email" required>
            <button type="submit" class="btn btn-danger">Inscrever-se</button>
          </form>
        </div>
  
      </div>
    </div>
  
    <!-- Rodapé Final -->
    <div class="container text-center py-4 border-top">
      <p class="mb-1">&copy; <strong>ConectaDador</strong> Todos os direitos reservados</p>
      <p class="credits">Desenvolvido em 2024</p>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{url('assets/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{url('assets/vendor/aos/aos.js')}}"></script>
  <script src="{{url('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{url('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{url('assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>

  <!-- Main JS File -->
  <script src="{{url('assets/js/main.js')}}"></script>
</body>
</html>