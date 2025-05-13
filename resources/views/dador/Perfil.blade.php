@extends('dador.DashbordDador')
@section('title', 'Meu Perfil - ConectaDador')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('styles')
<style>
    :root {
        --primary: #dc3545;
        --secondary: #ffe6e6;
        --accent: #ff4d4d;
    }

    .profile-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--primary), #c82333);
        padding: 2rem;
        color: white;
        position: relative;
    }

    .avatar-wrapper {
        width: 120px;
        height: 120px;
        border: 4px solid white;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        transition: all 0.3s;
    }

    .avatar-wrapper:hover {
        transform: scale(1.05);
    }

    .blood-type {
        position: absolute;
        bottom: -25px;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        color: var(--primary);
        padding: 0.8rem 2rem;
        border-radius: 25px;
        font-weight: 700;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        font-size: 1.2rem;
    }

    .profile-body {
        padding: 3rem 2rem 2rem;
        background: #f8f9fa;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .info-icon {
        font-size: 1.8rem;
        color: var(--primary);
        width: 50px;
        text-align: center;
    }

    .donation-history {
        border-left: 4px solid var(--primary);
        padding-left: 1.5rem;
    }

    .eligibility-status {
        background: var(--secondary);
        padding: 1rem;
        border-radius: 10px;
        text-align: center;
    }

    .qr-section {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: 15px;
        margin-top: 2rem;
    }

    .edit-btn {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: rgba(255,255,255,0.2);
        border: 2px solid white;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        transition: all 0.3s;
    }

    .edit-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
    }

    .modal-header {
        background: #dc3545;
        color: white;
    }
    .avatar-preview {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.is-invalid {
        border-color: #dc3545 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }

.password-strength {
    height: 4px;
    margin-top: 5px;
    background: #eee;
    border-radius: 2px;
    overflow: hidden;
}

.password-strength span {
    display: block;
    height: 100%;
    transition: all 0.3s;
}

.weak-password { background: #dc3545; }
.medium-password { background: #ffc107; }
.strong-password { background: #28a745; }



    @media (max-width: 768px) {
        .profile-header {
            padding: 1.5rem;
        }
        
        .avatar-wrapper {
            width: 120px;
            height: 120px;
        }
    }
</style>
@endsection
@section('conteudo')
<div class="profile-card">
    <div class="profile-header">
      <!--  <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#editModal">
            <i class="fas fa-edit me-2"></i>
            Editar
        </button>-->
        
        <div class="avatar-wrapper">
            <img src="{{ $doador->foto ? asset('storage/fotos/' . $doador->foto) : asset('assets/img/profile.png') }}" 
                 class="img-fluid" 
                 alt="Foto do perfil">
        </div>
        
        <div class="blood-type">
            <i class="fas fa-tint"></i>
            {{ $doador->tipo_sanguineo }}
        </div>
    </div>

    <div class="profile-body">
        <h2 class="text-center mb-4">{{ $doador->nome }}</h2>
        
        <div class="row">
            <!-- Coluna Esquerda - Informações Pessoais -->
            <div class="col-lg-6">
                <div class="info-card">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-id-card info-icon"></i>
                        <h5 class="mb-0">Informações Pessoais</h5>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Data de Nascimento</small>
                            <strong>{{ \Carbon\Carbon::parse($doador->data_nascimento)->format('d/m/Y') }}</strong>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Gênero</small>
                            <strong>{{ ucfirst($doador->genero) }}</strong>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Documento de Identificação</small>
                            <strong>{{ $doador->numero_bilhete }}</strong>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Endereço</small>
                            <strong>{{ $doador->endereco }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita - Ações Rápidas -->
            <div class="col-lg-6">
                <div class="info-card">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-cogs text-danger me-2"></i>
                        <h5 class="mb-0">Ações Rápidas</h5>
                    </div>
                    
                    <div class="list-group quick-actions">
                        
                        <button class="list-group-item list-group-item-action p-2" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fas fa-user-edit me-2"></i>
                            Editar Perfil
                        </button>

                        <a href="" class="list-group-item list-group-item-action p-2 " data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-lock me-2"></i>
                            Alterar Senha
                        </a>
                        
                        <a href="" class="list-group-item list-group-item-action p-2 ">
                            <i class="fas fa-bell me-2"></i>
                            Gerenciar Notificações
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!--
        <div class="qr-section ">
            <h5 class="mb-3">Identificação Digital</h5>
            <div class="d-inline-block p-3 bg-white rounded">
               
            </div>
            <p class="text-muted mt-3 mb-0 small">Apresente este código nos postos de coleta</p>
        </div>
    </div>
</div>-->

<!-- Modal de Edição -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit me-2"></i>Editar Perfil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <!-- Upload de Foto -->
                        <div class="col-md-4 text-center mb-4">
                            <div class="position-relative mb-3">
                                <img id="avatarPreview" 
                                     src="{{ $doador->foto ? asset('storage/fotos/' . $doador->foto) : asset('assets/img/profile.png') }}" 
                                     class="avatar-preview mb-3" 
                                     >
                                <label class="btn btn-danger btn-sm position-absolute bottom-0 start-50 translate-middle-x">
                                    <i class="fas fa-camera"></i>
                                    <input type="file" 
                                           name="foto" 
                                           id="avatarUpload" 
                                           class="d-none"
                                           accept="image/*" 
                                           onchange="previewImage(event)">
                                </label>
                            </div>
                            <small class="text-muted">Formatos: JPG, JPEG, PNG (Max. 2MB)</small>
                        </div>

                        <!-- Demais Campos -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Telefone</label>
                                         <input
                                            type="tel"
                                            class="form-control"
                                            name="telefone"
                                            value="{{ old('telefone', $doador->telefone) }}"
                                            required
                                            pattern="^(?:\+244)?9[1-7]\d{7}$"
                                            title="Digite um número válido: 9xxxxxxxx ou +2449xxxxxxxxx"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Endereço</label>
                                        <input type="text" class="form-control" 
                                               name="endereco" 
                                               value="{{ $doador->endereco }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal de Alteração de Senha -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="fas fa-lock me-2"></i>Alterar Senha
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('password.update') }}" id="passwordForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Senha Atual</label>
                        <input type="password" class="form-control" name="current_password" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label>Nova Senha</label>
                        <input type="password" class="form-control" name="new_password" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label>Confirmar Nova Senha</label>
                        <input type="password" class="form-control" name="new_password_confirmation" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Atualizar Senha</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Função para pré-visualizar a imagem
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Adiciona o listener ao input de arquivo
    const avatarUpload = document.getElementById('avatarUpload');
    if (avatarUpload) {
        avatarUpload.addEventListener('change', previewImage);
    }

    // Validação em tempo real
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const phone = document.querySelector('[name="telefone"]').value;
        if (!/^\d{9}$/.test(phone)) {
            e.preventDefault();
            alert('Número de telefone inválido! Deve conter 9 dígitos.');
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('passwordForm');
    const currentPasswordInput = document.querySelector('input[name="current_password"]');
    const newPasswordInput = document.querySelector('input[name="new_password"]');
    const confirmPasswordInput = document.querySelector('input[name="new_password_confirmation"]');

    // Funções de manipulação de erros
    function showError(input, message) {
        input.classList.add('is-invalid');
        const errorField = input.nextElementSibling;
        errorField.textContent = message;
        errorField.style.display = 'block';
    }

    function clearErrors() {
        [currentPasswordInput, newPasswordInput, confirmPasswordInput].forEach(input => {
            input.classList.remove('is-invalid');
            input.nextElementSibling.textContent = '';
            input.nextElementSibling.style.display = 'none';
        });
    }

    // Validação do formulário
    function validateForm() {
        clearErrors();
        let isValid = true;

        if (currentPasswordInput.value.trim() === '') {
            showError(currentPasswordInput, 'Por favor insira a senha atual');
            isValid = false;
        }

        if (newPasswordInput.value.length < 8) {
            showError(newPasswordInput, 'A senha deve ter pelo menos 8 caracteres');
            isValid = false;
        }

        if (newPasswordInput.value !== confirmPasswordInput.value) {
            showError(confirmPasswordInput, 'As senhas não coincidem');
            isValid = false;
        }

        return isValid;
    }

    // Submissão do formulário
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!validateForm()) return;

        const swalInstance = Swal.fire({
            title: 'Verificando credenciais...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                swalInstance.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Senha alterada!',
                    text: 'Sua senha foi atualizada com sucesso',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    form.reset();
                    bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
                });
            } else {
                let errorMessage = 'Ocorreu um erro';
                if (data.errors) {
                    if (data.errors.current_password) {
                        showError(currentPasswordInput, data.errors.current_password[0]);
                        errorMessage = data.errors.current_password[0];
                    }
                    if (data.errors.new_password) {
                        showError(newPasswordInput, data.errors.new_password[0]);
                    }
                }
                throw new Error(errorMessage);
            }
        } catch (error) {
            swalInstance.close();
            Swal.fire({
                icon: 'error',
                title: 'Erro na alteração',
                text: error.message,
                confirmButtonColor: '#dc3545'
            });
        }
    });

    // Reset ao fechar o modal
    document.getElementById('changePasswordModal').addEventListener('hidden.bs.modal', () => {
        form.reset();
        clearErrors();
    });
});
</script>
@endsection