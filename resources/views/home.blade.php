@extends('layout.main')
@section('title','Doação de Sangue')
@section('conteudo')
<main class="main">

<!-- Hero Section -->
<section id="hero" class="hero section">

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
          

          <h1 class="mb-4">
            Sua Doação, <br>
            <span class="accent-text">Uma vida a mais
          </h1>

          <p class="mb-4 mb-md-5">
            Cada doação conta! Junte-se a nós e faça a diferença na vida de quem precisa. Sua generosidade pode salvar vidas em momentos críticos.
          </p>

          <div class="hero-buttons">
            <a href="{{route('agendamento')}}" class="btn btn-primary me-0 me-sm-2 mx-1 ">Fazer agendamento <i class="fa-solid fa-arrow-right"></i></a>
            
            </a>
            
          </div>
        </div>
      </div>

    <div class="col-lg-6">
      <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
        <div class="carousel">
          <div class="carousel-images">
            <div class="carousel-image img-fluid" style="background-image: url('/assets/img/hero3.png');"></div>
            <div class="carousel-image img-fluid" style="background-image: url('/assets/img/10.png');"></div>
            <div class="carousel-image img-fluid" style="background-image: url('/assets/img/hero1.png');"></div>
            <div class="carousel-image img-fluid" style="background-image: url('/assets/img/hero3.png');"></div>
          </div>
          <button class="prev" onclick="moveSlide(-1)">❮</button>
          <button class="next" onclick="moveSlide(1)">❯</button>
        </div>
    </div>
  </div>
</section>

<!-- Requisitos -->
<section id="donation-requirements" class="about section">
  <div class="container"  >
    <div class="row gy-4 align-items-center justify-content-between">
      
      <div class="col-xl-5" >
        <span class="about-meta">REQUISITOS PARA DOAR</span>
        <h2 class="about-title">Quem pode doar sangue?</h2>
        <p class="about-description">
          Para garantir a segurança de todos, é necessário atender a alguns requisitos antes de realizar a doação de sangue. Confira abaixo os critérios para se tornar um doador.
        </p>

        <div class="row feature-list-wrapper">
          <div class="col-md-6">
            <ul class="feature-list">
              <li><i class="bi bi-check-circle-fill"></i> Idade entre 18 e 69 anos</li>
              <li><i class="bi bi-check-circle-fill"></i> Peso superior a 50 kg</li>
              <li><i class="bi bi-check-circle-fill"></i> Boa saúde geral</li>
              <li><i class="bi bi-check-circle-fill"></i> Não ter feito tatuagem ou piercing nos últimos 12 meses</li>
            </ul>
          </div>
          <div class="col-md-6">
            <ul class="feature-list">
              <li><i class="bi bi-check-circle-fill"></i> Intervalo de 3 meses entre as doações</li>
              <li><i class="bi bi-check-circle-fill"></i> Não ter doenças transmissíveis pelo sangue</li>
              <li><i class="bi bi-check-circle-fill"></i> Não estar grávida ou amamentando</li>
              
            </ul>
          </div>
        </div>

        
      </div>

      <div class="col-xl-6" >
        <div class="image-wrapper">
          <div class=" position-relative" >
            <img src="assets/img/requisitos1.jpg" alt="Coleta de Sangue" class="img-fluid ">
            
          </div>
          
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Sessão instrução -->
<section id="informacoes" id="features" class="features section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Passos Simples para Salvar Vidas</h2>
    
  </div>

  <div class="container">

    <div class="d-flex justify-content-center">

      <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">

        <li class="nav-item">
          <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#features-tab-1">
            <h4>Como Funciona</h4>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-2">
            <h4>Benefício</h4>
          </a>

        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-3">
           <h4>Quando Não Doar</h4>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-4">
              <h4>Curiosidades</h4>
          </a>
        </li>

      </ul>

    </div>

    <div class="tab-content" data-aos="fade-up" data-aos-delay="200">

      <div class="tab-pane fade active show" id="features-tab-1">
        <div class="row">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
          <h3>Seu Guia para Doar Sangue</h3>
            <p class="fst-italic">
            Tudo o que você precisa saber para doar sangue de forma segura e eficiente:
            </p>
            <ul>
              <li><i class="bi bi-clipboard2-pulse"></i> <span><strong>Pré-Doação:</strong> Durma bem, hidrate-se e evipe alimentos gordurosos 3 horas antes.</span></li>
              <li><i class="bi bi-geo-alt"></i> <span><strong>Localização:</strong> Encontre o centro mais próximo com nosso mapa interativo.</span></li>
              <li><i class="bi bi-calendar2-check"></i> <span><strong>Agendamento:</strong> Escolha o horário que melhor se encaixa na sua rotina.</span></li>
              <li><i class="bi bi-file-medical"></i> <span><strong>Triagem Rápida:</strong> Passo a passo do processo no dia (documentos, questionário de saúde).</span></li>
              <li><i class="bi bi-cup-hot"></i> <span><strong>Pós-Doação:</strong> Repouso breve, lanche reforçado e evitar esforços físicos por 12h.</span></li>
            </ul>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img src="assets/img/como funciona.jpeg" alt="" class="img-fluid" style="width: 80%;;">
          </div>
        </div>
      </div><!-- End tab content item -->

      <div class="tab-pane fade" id="features-tab-2">
        <div class="row">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
          <h3>Por Que Doar Sangue?</h3>
            <p class="fst-italic">
            Além de salvar vidas, sua doação traz benefícios para você e toda a comunidade:
            </p>
            <ul>
              <li><i class="bi bi-heart-pulse"></i> <span><strong>Check-up Gratuito:</strong> Testes de HIV, hepatite, sífilis e tipagem sanguínea.</span></li>
              <li><i class="bi bi-people"></i> <span><strong>Impacto Coletivo:</strong> 1 doação beneficia até 3 pessoas.</span></li>
              <li><i class="bi bi-shield-check"></i> <span><strong>Segurança Garantida:</strong> Todo material é descartável e esterilizado.</span></li>
              <li><i class="bi bi-graph-up"></i> <span><strong>Saúde em Dia:</strong> Doar regularmente ajuda a reduzir risco de doenças cardíacas.</span></li>
             
            </ul>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img src="assets/img/intrucao3.png" alt="" class="img-fluid" style="width: 80%;">
            
          </div>
        </div>
      </div><!-- End tab content item -->

      <div class="tab-pane fade" id="features-tab-3">
        <div class="row">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
            <h3>Não Doe Sangue, Se...</h3>
            <p class="fst-italic">
            Sua segurança e a dos pacientes são prioridade:
            </p>
            <ul>
                <li><i class="bi bi-x-circle text-danger"></i> 
                <span><strong>Portador de VIH/SIDA:</strong> Ou teste positivo recente</span></li>

              <li><i class="bi bi-slash-circle text-danger"></i> 
                <span><strong>Histórico de Sífilis:</strong> Sem tratamento completo nos últimos 12 meses</span></li>

              <li><i class="bi bi-exclamation-triangle text-danger"></i> 
                <span><strong>Receptor de Sangue:</strong> Esperar 1 ano após última transfusão</span></li>
              <li><i class="bi bi-capsule text-danger"></i> 
                <span><strong>Medicação Contínua:</strong> Antibióticos, anticoagulantes ou quimioterapia</span></li>
                <li><i class="bi bi-droplet text-danger"></i> 
              <span><strong>Anemia Grave:</strong> Taxa de hemoglobina abaixo de 12,5g/dL </span></li>

              <li><i class="bi bi-moon-stars text-danger"></i> 
               <span><strong>Grávidas:</strong> Aguardar 6 meses pós-parto para doar</span></li>
            </ul>
            
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
             <img src="assets/img/11.png" alt="" class="img-fluid" style="width: 75%;">
          </div>
        </div>
      </div><!-- End tab content item -->

      <div class="tab-pane fade" id="features-tab-4">
        <div class="row">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
            <h3>Sabia Que...</h3>
            <p class="fst-italic">
              Fatos surpreendentes sobre doação de sangue:
            </p>
            <ul>
              <li><i class="bi bi-lightning-charge text-danger"></i> 
                <span><strong>Velocidade Sanguínea:</strong> Seu corpo repõe o plasma em 24h!</span></li>
              
              <li><i class="bi bi-globe-americas text-danger"></i> 
                <span><strong>Universalidade:</strong> Tipo O- é doador universal</span></li>
              
              <li><i class="bi bi-heartbreak text-danger"></i>
                <span><strong>Vitalidade:</strong> Sangue transporta oxigênio, nutrientes e defesas - nenhum substituto completo existe</span></li>
              <li><i class="bi bi-clock-history text-danger"></i> 
                <span><strong>Frequência:</strong> Homens podem doar a cada 2 meses</span></li>
              
              <li><i class="bi bi-thermometer-sun text-danger"></i> 
                <span><strong>Emergências:</strong> 70% das doações são para cirurgias</span></li>
            </ul>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img src="assets/img/11.png" alt="" class="img-fluid" style="width: 75%;">
          </div>
        </div>
      </div><!-- End tab content item -->

    </div>
  </div>
</section>

<!-- Card -->
<section id="features-cards" class="features-cards section">
  <div class="container">
    <div class="row gy-4">

      <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
        <div class="feature-box orange">
          <i class="bi bi-geo-alt"></i>
          <h4>Encontre Centros Próximos</h4>
          <p>Use nossa plataforma para localizar o centro de doação mais próximo </p>
        </div>
      </div><!-- End Feature Box -->

      <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
        <div class="feature-box blue">
          <i class="bi bi-calendar-check"></i>
          <h4>Agende em Minutos</h4>
          <p>Escolha horários convenientes para doar sangue com nosso sistema de agendamento rápido.</p>
        </div>
      </div><!-- End Feature Box -->

      <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
        <div class="feature-box green">
          <i class="bi bi-heart-pulse"></i>
          <h4>Salve Vidas</h4>
          <p>Cada doação pode salvar até 3 vidas. Veja como seu gesto impacta pessoas reais.</p>
        </div>
      </div><!-- End Feature Box -->

      <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
        <div class="feature-box red">
          <i class="bi bi-shield-check"></i>
          <h4>Sangue Saudável é Sangue Doado</h4>
          <p>Diga não a quem vende sangue!</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Stats Section 
<section id="stats" class="stats section">

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row gy-4">

      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
          <p>Clients</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
          <p>Projects</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="1453" data-purecounter-duration="1" class="purecounter"></span>
          <p>Hours Of Support</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="32" data-purecounter-duration="1" class="purecounter"></span>
          <p>Workers</p>
        </div>
      </div>
    </div>
  </div>
</section>-->


<!-- Campanha Section -->
<section id="Campanha" class="our_department_area">
    <div class="container">
        <div class="row text-center mb-5">
            <h2>Campanha de Coleta de Sangue</h2>
            <p class="text-muted">Participe e ajude a salvar vidas!</p>
        </div>
        <div class="row">
            @forelse($campanhas as $campanha)
            <div class="col-xl-4 col-md-6 col-lg-4">
                <div class="single_department">
                    <div class="department_thumb">
                        @if($campanha->foto)
                            <img src="{{ asset('storage/' . $campanha->foto) }}" 
                                 alt="{{ $campanha->titulo }}" 
                                 class="img-fluid">
                        @else
                            <img src="{{ asset('assets/img/campanha.jpg') }}" 
                                 alt="Campanha padrão" 
                                 class="img-fluid">
                        @endif
                    </div>
                    <div class="ms-3">
                        <h3>{{ $campanha->titulo }}</h3>
                        <ul class="list-unstyled small text-muted mb-2">
                            <li><strong>Data:</strong>  {{ \Carbon\Carbon::parse($campanha->data_inicio)->format('d/m/Y') }}</li>
                            <li><strong>Hora:</strong> {{ $campanha->hora_inicio }} - {{ $campanha->hora_fim }}</li>
                            <li><strong>Local:</strong> {{ $campanha->centro->nome }}</li>
                            <li><strong>Endereço:</strong> 
                                @if(!empty($campanha->endereco))
                                    {{ $campanha->endereco }}
                                @else
                                    {{ $campanha->centro->endereco }}
                                @endif
                            </li>
                        </ul>
                        <p class="mb-2">{{ Str::limit($campanha->descricao, 100) }}</p>
                        <a href="{{ route('campanha.detalhe', $campanha->id_campanha) }}" 
                           class="read-more text-decoration-none fw-bold">
                            Saiba Mais <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    Nenhuma campanha activa no momento
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>



<!-- Faq Section -->
<section class="faq-9 faq section light-background" id="faq">
<div class="container">
  <div class="row">

    <div class="col-lg-5" data-aos="fade-up">
      <h2 class="faq-title">Tem alguma pergunta? Confira o FAQ</h2>
      <p class="faq-description">Encontre respostas rápidas para as dúvidas mais comuns sobre o processo de doação de sangue.</p>
      <div class="faq-arrow d-none d-lg-block" data-aos="fade-up" data-aos-delay="200">
        <svg class="faq-arrow" width="200" height="211" viewBox="0 0 200 211" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M198.804 194.488C189.279 189.596 179.529 185.52 169.407 182.07L169.384 182.049C169.227 181.994 169.07 181.939 168.912 181.884C166.669 181.139 165.906 184.546 167.669 185.615C174.053 189.473 182.761 191.837 189.146 195.695C156.603 195.912 119.781 196.591 91.266 179.049C62.5221 161.368 48.1094 130.695 56.934 98.891C84.5539 98.7247 112.556 84.0176 129.508 62.667C136.396 53.9724 146.193 35.1448 129.773 30.2717C114.292 25.6624 93.7109 41.8875 83.1971 51.3147C70.1109 63.039 59.63 78.433 54.2039 95.0087C52.1221 94.9842 50.0776 94.8683 48.0703 94.6608C30.1803 92.8027 11.2197 83.6338 5.44902 65.1074C-1.88449 41.5699 14.4994 19.0183 27.9202 1.56641C28.6411 0.625793 27.2862 -0.561638 26.5419 0.358501C13.4588 16.4098 -0.221091 34.5242 0.896608 56.5659C1.8218 74.6941 14.221 87.9401 30.4121 94.2058C37.7076 97.0203 45.3454 98.5003 53.0334 98.8449C47.8679 117.532 49.2961 137.487 60.7729 155.283C87.7615 197.081 139.616 201.147 184.786 201.155L174.332 206.827C172.119 208.033 174.345 211.287 176.537 210.105C182.06 207.125 187.582 204.122 193.084 201.144C193.346 201.147 195.161 199.887 195.423 199.868C197.08 198.548 193.084 201.144 195.528 199.81C196.688 199.192 197.846 198.552 199.006 197.935C200.397 197.167 200.007 195.087 198.804 194.488ZM60.8213 88.0427C67.6894 72.648 78.8538 59.1566 92.1207 49.0388C98.8475 43.9065 106.334 39.2953 114.188 36.1439C117.295 34.8947 120.798 33.6609 124.168 33.635C134.365 33.5511 136.354 42.9911 132.638 51.031C120.47 77.4222 86.8639 93.9837 58.0983 94.9666C58.8971 92.6666 59.783 90.3603 60.8213 88.0427Z" fill="currentColor"></path>
        </svg>
      </div>
    </div>

    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="300">
      <div class="faq-container">

        <div class="faq-item ">
          <h3>Como posso agendar uma doação de sangue?</h3>
          <div class="faq-content">
            <p>Você pode agendar uma doação através da nossa plataforma online. Basta criar uma conta, escolher um centro de coleta próximo e selecionar um horário conveniente.</p>
          </div>
          <i class="faq-toggle bi bi-chevron-right"></i>
        </div>

        <div class="faq-item">
          <h3>Qual é o intervalo seguro entre as doações?</h3>
          <div class="faq-content">
            <p> Homens podem doar de 3 em 3 meses.</p>
            <p> Mulheres podem doar de 4 em 4 meses.</p>
          </div>
          <i class="faq-toggle bi bi-chevron-right"></i>
        </div>

        <div class="faq-item">
          <h3>Como posso encontrar o centro de coleta mais próximo?</h3>
          <div class="faq-content">
            <p>Utilize nossa ferramenta de geolocalização na plataforma. Basta inserir seu endereço ou ativar a localização para visualizar os centros mais próximos.</p>
          </div>
          <i class="faq-toggle bi bi-chevron-right"></i>
        </div>

        <div class="faq-item">
          <h3>É seguro doar sangue durante a pandemia?</h3>
          <div class="faq-content">
            <p>Sim, todos os centros seguem rigorosos protocolos de segurança e higiene, como uso de máscaras, distanciamento social e esterilização constante dos materiais.</p>
          </div>
          <i class="faq-toggle bi bi-chevron-right"></i>
        </div>

        <div class="faq-item">
          <h3>Quantas vidas posso salvar com uma doação?</h3>
          <div class="faq-content">
            <p>Cada doação de sangue pode salvar até 3 vidas. Componentes como hemácias, plaquetas e plasma podem ser usados para diferentes pacientes.</p>
          </div>
          <i class="faq-toggle bi bi-chevron-right"></i>
        </div>

        <div class="faq-item">
          <h3>Posso doar sangue se tive Covid-19 recentemente?</h3>
          <div class="faq-content">
            <p>Se você teve Covid-19, deve aguardar pelo menos 30 dias após a recuperação completa antes de doar sangue.</p>
          </div>
          <i class="faq-toggle bi bi-chevron-right"></i>
        </div>

      </div>
    </div>

  </div>
</div>
</section>
</main>
<!--Section 
<section id="clients" class="clients section">

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="swiper init-swiper">
      <script type="application/json" class="swiper-config">
        {
          "loop": true,
          "speed": 600,
          "autoplay": {
            "delay": 5000
          },
          "slidesPerView": "auto",
          "pagination": {
            "el": ".swiper-pagination",
            "type": "bullets",
            "clickable": true
          },
          "breakpoints": {
            "320": {
              "slidesPerView": 2,
              "spaceBetween": 40
            },
            "480": {
              "slidesPerView": 3,
              "spaceBetween": 60
            },
            "640": {
              "slidesPerView": 4,
              "spaceBetween": 80
            },
            "992": {
              "slidesPerView": 6,
              "spaceBetween": 120
            }
          }
        }
      </script>
      <div class="swiper-wrapper align-items-center">
        <div class="swiper-slide"><img src="assets/img/Apoio/itel oficial.png" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="assets/img/Apoio/INS.png " class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="assets/img/Apoio/minsa.png" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="assets/img/Apoio/minttics.png" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="assets/img/clients/client-5.png" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="assets/img/clients/client-6.png" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="assets/img/clients/client-7.png" class="img-fluid" alt=""></div>
      </div>
      <div class="swiper-pagination"></div>
    </div>

  </div>

</section>
 -->
@endsection