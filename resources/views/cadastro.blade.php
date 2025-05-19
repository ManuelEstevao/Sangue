<!DOCTYPE html>
<html lang="PT-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/formulario.css') }}">
    <link href="assets/img/flavicon.png" rel="icon">
    <link rel="stylesheet" href="{{url('assets/vendor/fontawesome/css/all.min.css')}}">
    <title>Cadastro</title>
</head>
<body style="background: linear-gradient(135deg, 
        rgba(198, 66, 66, 0.95) 0%, 
        #c62828 100%),
        url('assets/img/Fundo.png');">
    <main>
        <header>
            <h2>Cadastro de Doador</h2>
        </header>

        <form id="form" class="form" action="{{ route('store-cadastro') }}" method="POST" data-check-bi-url="{{ route('check.bilhete.unique') }}"
               data-check-email-url="{{ route('check.email.unique') }}">
        @csrf

            <!-- Bilhete de Identidade -->
            <div class="form-control">
            <i class="fas fa-id-card input-icon"></i>
                <input 
                    type="text" 
                    id="bi" 
                    name="bi" 
                    value="{{ old('bi') }}" 
                    placeholder="Bilhete de Identidade (14 caracteres)" 
                    maxlength="14"
                >
                <i class="fas fa-exclamation-circle icon-sucess"></i>
                <i class="fas fa-check-circle icon-sucess"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Nome -->
            <div class="form-control">
            <i class="fas fa-user"></i>
                <input 
                    type="text" 
                    id="nome" 
                    name="nome" 
                    value="{{ old('nome') }}" 
                    placeholder="Nome Completo" 
                    readonly
                >
                <i class="fas fa-exclamation-circle icon-sucess"></i>
                <i class="fas fa-check-circle icon-sucess"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Data de Nascimento -->
            <div class="data" >
                <i class="fas fa-calendar-alt"></i>
                <input 
                    type="text" 
                    id="data" 
                    name="data" 
                    value="{{ old('data') }}" 
                    placeholder="Data de Nascimento" 
                    readonly
                >
                <i class="fas fa-check-circle icon-sucess"></i>
                <i class="fas fa-exclamation-circle icon-sucess"></i>
                <small class="error-message"></small>
                
            </div>
          <!-- Gênero -->
            <div class="form-control">
            <i class="fas fa-venus-mars"></i>
            <select id="genero" name="genero">
                <option value="">Selecione o gênero</option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino" >Feminino</option>
            </select>
            <i class="fas fa-exclamation-circle icon-sucess"></i>
            <i class="fas fa-check-circle icon-sucess"></i>
            <small class="error-message"></small>
            </div>

            <!-- Tipo Sanguíneo -->
            <div class="form-control">
                <i class="fas fa-tint"></i>
                <select id="tisangue" name="tisangue">
                    <option value="">Selecione o tipo sanguíneo</option>
                    @foreach ($tiposSanguineos as $tipo)
                        <option 
                            value="{{ $tipo }}" 
                            {{ old('tisangue') == $tipo ? 'selected' : '' }}
                        >
                            {{ $tipo }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-exclamation-circle icon-sucess"></i>
                <i class="fas fa-check-circle icon-sucess"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Email -->
            <div class="form-control">
            <i class="fas fa-envelope"></i>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="Email"
                >
                <i class="fas fa-exclamation-circle icon-sucess"></i>
                <i class="fas fa-check-circle icon-sucess"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Contacto -->
            <div class="form-control">
            <i class="fas fa-phone"></i>
                <input 
                    type="tel" 
                    id="contacto" 
                    name="contacto" 
                    value="{{ old('contacto') }}" 
                    placeholder="+244 9xxxxxxxx" 
                    maxlength="16" 
                    pattern="^(?:\+244)?9[1-7]\d{7}$"
                    title="Digite um número válido: 9xxxxxxxx ou +2449xxxxxxxxx"
                    
                >
                <i class="fas fa-exclamation-circle icon-sucess"></i>
                <i class="fas fa-check-circle icon-sucess"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Senha -->
            <div class="form-control">
            <i class="fas fa-lock"></i>
                <input 
                    type="password" 
                    id="senha" 
                    name="senha" 
                    placeholder="Senha"
                >
                <i class="fas fa-exclamation-circle icon-sucess"></i>
                <i class="fas fa-check-circle icon-sucess"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Confirmar Senha -->
            <div class="form-control">
            <i class="fas fa-lock"></i>
                <input 
                    type="password" 
                    id="confSenha" 
                    name="senha_confirmation" 
                    placeholder="Confirmar a Senha"
                >
                <i class="fas fa-exclamation-circle icon-sucess"></i>
                <i class="fas fa-check-circle icon-sucess"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Botão -->
            <div class="btn">
                <button type="submit">Registar</button>
            </div>
        </form>
    </main>

    <!-- Script para busca de dados por BI -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
$(document).ready(function () {
    // Busca dados por BI
    $('#bi').on('blur', function () {
        const bi = $(this).val().trim();

        $.ajax({
            url: `https://consulta.edgarsingui.ao/consultar/${bi}`,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (!response.error) {
                    $('#nome').val(response.name || '');
                    
                    // Obtém a data original da resposta (formato "yyyy-mm-dd")
                    let rawDate = response.data_de_nascimento || '';
                    
                    if (rawDate) {
                        // Cria um campo hidden para manter o valor original para submissão
                        if (!$('#dataHidden').length) {
                            $('#data').after('<input type="hidden" id="dataHidden" name="data" />');
                        }
                        $('#dataHidden').val(rawDate);
                        
                        // Exibe a data formatada para o usuário (dd/mm/aaaa) no campo visível
                        const parts = rawDate.split('-'); // [yyyy, mm, dd]
                        let formattedDate = (parts.length === 3) 
                            ? parts[2] + '/' + parts[1] + '/' + parts[0] 
                            : rawDate;
                        $('#data').val(formattedDate);

                        // Se a data original for "1900-01-01", cria o campo para atualização
                        if (rawDate === "1900-01-01") {
                            if ($('#updateData').length === 0) {
                                var updateField = `
                                    <div class="form-control" id="updateDateContainer">
                                        <i class="fas fa-calendar-check"></i>
                                        
                                        <input type="date" id="updateData" name="data_atualizada">
                                        <small class="error-message"></small>
                                    </div>
                                `;
                                $('.data').after(updateField);
                            }
                        } else {
                            // Se não for "1900-01-01", remove o campo de atualização, se existir
                            $('#updateDateContainer').remove();
                        }
                    } else {
                        $('#data').val('');
                    }
                } else {
                    alert("Dados não encontrados. Por favor acesse o site da AGT para actualizar seu bilhete.");
                }
            },
            error: function () {
                alert("Erro ao buscar os dados. Verifique a internet ou tente novamente.");
            }
        });
    });
    
    // Se o campo updateData existir, atualiza o valor do campo hidden com o valor do updateData
    $(document).on('change', '#updateData', function () {
        const newDate = $(this).val(); // valor já no formato yyyy-mm-dd
        $("#dataHidden").val(newDate);
    });
});

    </script>
    <script src="assets/js/form.js"></script>
    
</body>
</html>