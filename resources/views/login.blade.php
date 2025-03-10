<!DOCTYPE html>
<html lang="PT-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/login.css" rel="stylesheet">
    <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap"
    rel="stylesheet"
  />
  <link rel="stylesheet" href="{{url('assets/vendor/fontawesome/css/all.min.css')}}">
  <link href="assets/img/flavicon.png" rel="icon">

    <title>Login</title>
</head>
<body>
    <div id="form">
        <form action="{{ route('login.submit') }}" method="post">
        @csrf
            <h2>Login</h2>
            <label for="email">Email</label>
            <div class="control">
                <i class="far fa-envelope" aria-hidden="true"></i>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <label for="senha">Senha</label>
            <div class="control">
                <i class="fa fa-lock" aria-hidden="true"></i>
                <input type="password" id="senha" name="senha" placeholder="Senha" required>
            </div>
            <div class="btn">
                <button type="submit">Entrar</button>
            </div>
        </form>
        <div class="link">
            <a href="{{route('cadastro')}}">Não tem uma conta? Regista-se aqui!</a>
        </div>
        
    </div>
</body>
</html>