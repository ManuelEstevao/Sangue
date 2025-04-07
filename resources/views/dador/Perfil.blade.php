@extends('dador.DashbordDador')
@section('title', 'Meu Perfil - ConectaDador')
<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap JS e Popper.js (no final do body) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>
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
        background: var(--primary);
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
    }

    .blood-type {
        position: absolute;
        bottom: -20px;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        color: var(--primary);
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-weight: 700;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .profile-body {
        padding: 3rem 2rem 2rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        margin-bottom: 1rem;
        background: var(--secondary);
        border-radius: 10px;
    }

    .info-icon {
        font-size: 1.5rem;
        color: var(--primary);
        width: 40px;
        text-align: center;
    }

    .qr-section {
        text-align: center;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 15px;
        margin-top: 2rem;
    }

    .edit-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        transition: all 0.3s;
    }

    .edit-btn:hover {
        background: rgba(255,255,255,0.3);
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 1.5rem;
        }
        
        .avatar-wrapper {
            width: 100px;
            height: 100px;
        }
    }
</style>
@endsection

@section('conteudo')
<div class="profile-card">
    <div class="profile-header">
        <button class="edit-btn">
            <i class="fas fa-edit me-2"></i>
            Editar
        </button>
        
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
            <div class="col-md-6">
                <div class="info-item">
                    <i class="fas fa-calendar-alt info-icon"></i>
                    @php
                            $doador = Auth::user()->doador; // Obtendo dados do doador
                            $dataNascimento = \Carbon\Carbon::parse($doador->data_nascimento)->format('d/m/Y');
                            $genero = $doador->genero; // Supondo que "genero" é um campo no modelo Doador
                        @endphp
                    <div>
                        <small class="text-muted d-block">Data de Nascimento</small>
                       <strong>{{ $dataNascimento }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-item">
                    <i class="fas fa-phone info-icon"></i>
                    <div>
                        <small class="text-muted d-block">Telefone</small>
                        <strong>{{ $doador->telefone }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt info-icon"></i>
                    <div>
                        <small class="text-muted d-block">Localização</small>
                       
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-item">
                    <i class="fas fa-envelope info-icon"></i>
                    <div>
                        <small class="text-muted d-block">E-mail</small>
                        <strong>{{ Auth::user()->email }}</strong>
                    </div>
                </div>
            </div>
        </div>
<!--
        <div class="qr-section">
            <h5 class="mb-3">Identificação do Doador</h5>
            <div class="d-inline-block p-3 bg-white rounded">
           
            </div>
            <p class="text-muted mt-3 mb-0 small">Apresente este código nos postos de coleta</p>
        </div>
    </div>
</div>-->


<!-- Adicione no final da seção conteudo -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="row">
                        <!-- Upload de Foto -->
                        <div class="col-md-4 text-center">
                            <div class="avatar-wrapper mb-3">
                                <img id="avatarPreview" 
                                     src="{{ $doador->foto ? asset('storage/'.$doador->foto) : asset('assets/img/profile.png') }}" 
                                     class="img-fluid rounded-circle" 
                                     style="width: 150px; height: 150px; object-fit: cover">
                            </div>
                            <div class="form-group">
                                <input type="file" 
                                       class="form-control" 
                                       id="foto" 
                                       name="foto" 
                                       accept="image/*">
                            </div>
                        </div>

                        <!-- Campos Editáveis -->
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label>Telefone</label>
                                <input type="tel" 
                                       class="form-control" 
                                       name="telefone" 
                                       value="{{ $doador->telefone }}"
                                       required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Endereço</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="endereco" 
                                       value="{{ $doador->endereco }}">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Cidade</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="cidade" 
                                           value="{{ $doador->cidade }}">
                                </div>
                                <div class="col-md-6">
                                    <label>Província</label>
                                    <select class="form-select" name="estado">
                                        <option value="Luanda" {{ $doador->estado == 'Luando' ? 'selected' : '' }}>Luanda</option>
                                        <!-- Adicione outros estados -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Contato de Emergência</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="contato_emergencia" 
                                       value="{{ $doador->contato_emergencia }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Abrir modal
    document.querySelector('.edit-btn').addEventListener('click', () => {
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });

    // Preview da imagem
    document.getElementById('foto').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('avatarPreview').src = event.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    });
});
</script>
@endsection
