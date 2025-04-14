@extends('dador.DashbordDador')
@section('title', 'Meu Perfil - ConectaDador')

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
        width: 140px;
        height: 140px;
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
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-cogs text-danger me-2"></i>
                        <h5 class="mb-0">Ações Rápidas</h5>
                    </div>
                    
                    <div class="list-group quick-actions">
                        
                        <button class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fas fa-user-edit me-2"></i>
                            Editar Perfil
                        </button>

                        <a href="" class="list-group-item list-group-item-action">
                            <i class="fas fa-lock me-2"></i>
                            Alterar Senha
                        </a>
                        
                        <a href="" class="list-group-item list-group-item-action">
                            <i class="fas fa-bell me-2"></i>
                            Gerenciar Notificações
                        </a>
                    </div>
                </div>
            </div>
        </div>


<!--
        <div class="qr-section">
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
                                        <input type="tel" class="form-control" 
                                               name="telefone" 
                                               value="{{ $doador->telefone }}"
                                               required>
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
@endsection

@section('scripts')
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
</script>
@endsection