<!DOCTYPE html>
<html lang="PT-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{url('assets/vendor/fontawesome/css/all.min.css')}}">
    <link href="assets/img/flavicon.png" rel="icon">
    <link href="assets/css/login.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, 
        rgba(198, 66, 66, 0.95) 0%, 
        #c62828 100%),
        url('assets/img/Fundo.png');" >
    <div class="particles">
        <!-- Partículas serão adicionadas via JavaScript -->
    </div>
        @if($errors->any())
    <div class="alert alert-danger">
    @foreach($errors->all() as $error)
        <p class="error-message">{{ $error }}</p>
    @endforeach
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <p class="error-message">{{ session('error') }}</p>
    </div>
    @endif

    <div id="form">
        <form action="{{ route('login.submit') }}" method="post">
            @csrf
            <h2>ConectaDador</h2>
            
            <div class="control">
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>

            <div class="control">
                <i class="fas fa-lock"></i>
                <input type="password" id="senha" name="senha" placeholder="Senha" required>
            </div>

            <div class="btn">
                <button type="submit">Entrar</button>
            </div>

            <div class="link">
                <a href="{{route('cadastro')}}">Cadastre-se e ajude a fazer a diferença!</a>
            </div>
        </form>
    </div>

    <div class="wave-container">
        <svg class="wave" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path class="shape-fill" d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path>
        </svg>
    </div>

    <script>
        // Cria partículas dinâmicas
        function createParticles() {
            const container = document.querySelector('.particles');
            const particleCount = 30;

            for(let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Posição e tamanho aleatórios
                particle.style.left = Math.random() * 100 + '%';
                particle.style.width = particle.style.height = 
                    (Math.random() * 10 + 5) + 'px';
                particle.style.animationDelay = Math.random() * 20 + 's';
                
                container.appendChild(particle);
            }
        }

        window.addEventListener('load', createParticles);
    </script>
</body>
</html>