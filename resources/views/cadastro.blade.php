<!DOCTYPE html>
<html lang="PT-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/css/formulario.css') }}">
    <title>Cadastro</title>
</head>
<body>
    <main>
        <header>
            <h2>Registar-se</h2>
        </header>

        <form id="form" class="form" action="{{ route('store-cadastro') }}" method="POST">
        @csrf

            <!-- Bilhete de Identidade -->
            <div class="form-control">
                <input 
                    type="text" 
                    id="bi" 
                    name="bi" 
                    value="{{ old('bi') }}" 
                    placeholder="Bilhete de Identidade (14 caracteres)" 
                    maxlength="14"
                >
                <i class="fas fa-exclamation-circle"></i>
                <i class="fas fa-check-circle"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Nome -->
            <div class="form-control">
                <input 
                    type="text" 
                    id="nome" 
                    name="nome" 
                    value="{{ old('nome') }}" 
                    placeholder="Nome Completo" 
                    readonly
                >
                <i class="fas fa-exclamation-circle"></i>
                <i class="fas fa-check-circle"></i>
                <small class="error-message"></small>
                
            </div>

              
                <small class="error-message"></small>
                
            </div>

            <!-- Data de Nascimento -->
            <div class="data" >
                <input 
                    type="text" 
                    id="data" 
                    name="data" 
                    value="{{ old('data') }}" 
                    placeholder="Data de Nascimento" 
                    readonly
                >
        
                <small class="error-message"></small>
                
            </div>
          <!-- Gênero -->
            <div class="form-control">
            <select id="genero" name="genero">
                <option value="">Selecione o gênero</option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino" >Feminino</option>
            </select>
            <i class="fas fa-exclamation-circle"></i>
            <i class="fas fa-check-circle"></i>
            <small class="error-message"></small>
            </div>

            <!-- Tipo Sanguíneo -->
            <div class="form-control">
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
                <i class="fas fa-exclamation-circle"></i>
                <i class="fas fa-check-circle"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Email -->
            <div class="form-control">
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="Email"
                >
                <i class="fas fa-exclamation-circle"></i>
                <i class="fas fa-check-circle"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Contacto -->
            <div class="form-control">
                <input 
                    type="tel" 
                    id="contacto" 
                    name="contacto" 
                    value="{{ old('contacto') }}" 
                    placeholder="+244 9xx xxx xxx" 
                    maxlength="16" 
                    pattern="\+244\s[0-9]{3}\s[0-9]{3}\s[0-9]{3}"
                    
                >
                <i class="fas fa-exclamation-circle"></i>
                <i class="fas fa-check-circle"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Senha -->
            <div class="form-control">
                <input 
                    type="password" 
                    id="senha" 
                    name="senha" 
                    placeholder="Senha"
                >
                <i class="fas fa-exclamation-circle"></i>
                <i class="fas fa-check-circle"></i>
                <small class="error-message"></small>
                
            </div>

            <!-- Confirmar Senha -->
            <div class="form-control">
                <input 
                    type="password" 
                    id="confSenha" 
                    name="senha_confirmation" 
                    placeholder="Confirmar a Senha"
                >
                <i class="fas fa-exclamation-circle"></i>
                <i class="fas fa-check-circle"></i>
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
                                        <label for="updateData">Atualize sua data de nascimento</label>
                                        <input type="date" id="updateData" name="data_atualizada">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <i class="fas fa-check-circle"></i>
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
                    alert("Dados não encontrados.");
                }
            },
            error: function () {
                alert("Erro ao buscar os dados. Verifique o BI ou tente novamente.");
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
    <script
      src="https://kit.fontawesome.com/f9e19193d6.js"
      crossorigin="anonymous"
    ></script>
</body>
</html>
