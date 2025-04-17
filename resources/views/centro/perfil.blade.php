@extends('centro.main')

@section('title', 'Perfil do Centro')
@section('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #ffe3e3, #ffffff);
        border-radius: 15px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    .profile-cover {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 150px;
        background: #dc3545;
        z-index: 0;
    }
    .profile-content {
        position: relative;
        z-index: 1;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border: 4px solid white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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
    /* Estilização para o calendário */
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        
    }

    .cal{
        margin-top: -28px;
    }
    
.fc .fc-prev-button {
    background-color: transparent !important;
    border: none !important;                 
    color: #000;                              
}
.fc .fc-prev-button:hover {
    background-color: #e0e7ff !important; 
}


</style>
@endsection

@section('conteudo')
<div class="page-inner">
    <div class="profile-header">
        <div class="profile-cover"></div>
        <div class="profile-content text-center">
            <img src="{{ $centro->foto ? asset('storage/centros/' . $centro->foto) : asset('assets/img/profile.png') }}" 
                 class="profile-avatar rounded-circle mb-3" 
                 alt="Logo do Centro">
            <h1 class="text-dark mb-1">{{ Auth::user()->centro->nome }}</h1>
            <p class="text-muted">{{ Auth::user()->centro->endereco }}</p>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Informações Principais -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fas fa-info-circle me-2"></i>Informações do Centro</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="contact-info list-unstyled">
                                <li>
                                    <i class="fas fa-phone me-2"></i>
                                    {{ Auth::user()->centro->telefone }}
                                </li>
                                <li>
                                    <i class="fas fa-envelope me-2"></i>
                                    {{ Auth::user()->email }}
                                </li>
                                <li>
                                    <i class="fas fa-users me-2"></i>
                                    Capacidade máxima por Horário: {{ Auth::user()->centro->capacidade_maxima ?? 'Não definido' }}
                                </li>
                                <li>
                                    <i class="fas fa-clock me-2"></i>
                                    Horário: {{ Auth::user()->centro->horario_abertura }} - {{ Auth::user()->centro->horario_fechamento }}
                                </li>

                            </ul>
                        </div>
                        <div class="col-md-6 cal">
                            <!-- Calendário -->
                            <div class="map-container" style="height: 265px; background: #f5f5f5; border-radius: 10px; overflow: hidden;">

                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fas fa-cogs me-2"></i>Ações Rápidas</h4>
                    <div class="list-group">
                        <button type="button" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fas fa-pencil-alt me-2"></i>Editar Perfil
                        </button>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-lock me-2"></i>Alterar Senha
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-bell me-2"></i>Notificações
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                <form method="POST" action="{{ route('centro.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <!-- Upload de Foto -->
                            <div class="col-md-4 text-center mb-4">
                                <div class="position-relative mb-3">
                                    <img id="avatarPreview" 
                                         src="{{ $centro->foto ? asset('storage/centros/' . $centro->foto) : asset('assets/img/profile.png') }}" 
                                         class="avatar-preview mb-3">
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
                                            <label>Nome do Centro</label>
                                            <input type="text" class="form-control" 
                                                   value="{{ old('nome', Auth::user()->centro->nome) }}" 
                                                   name="nome" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Telefone</label>
                                            <input type="text" class="form-control" 
                                                   value="{{ old('telefone', Auth::user()->centro->telefone) }}" 
                                                   name="telefone" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Horário de Abertura</label>
                                            <input type="time" class="form-control" 
                                                   value="{{ old('horario_abertura', Auth::user()->centro->horario_abertura) }}" 
                                                   name="horario_abertura" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Horário de Fechamento</label>
                                            <input type="time" class="form-control" 
                                                   value="{{ old('horario_fechamento', Auth::user()->centro->horario_fechamento) }}" 
                                                   name="horario_fechamento" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Capacidade Máxima por Horário</label>
                                            <input type="number" class="form-control"
                                                name="capacidade_maxima"
                                                min="1"
                                                value="{{ old('capacidade_maxima', Auth::user()->centro->capacidade_maxima) }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label>Endereço</label>
                                            <textarea class="form-control" 
                                                      name="endereco"
                                                      rows="2">{{ old('endereco', Auth::user()->centro->endereco) }}</textarea>
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
</div>
                    
@endsection

@section('scripts')
<!-- Script do FullCalendar via CDN -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.6/index.global.min.js"></script>
<script>
    // Função para preview da imagem
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

    // Fechar modal após sucesso
    @if(session('success'))
        const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
        if(modal) modal.hide();
    @endif

    // Inicializar o calendário com dados dinâmicos (caso haja dias bloqueados)
    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: new Date(), // inicia na data atual
        validRange: {
            start: new Date() // todas as datas antes da data atual ficam desabilitadas
        },
        locale: 'pt-br',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: ''
        },
        events: [], // sem eventos inicialmente
        dateClick: function(info) {
            // Exemplo: mostra um alerta apenas quando a data estiver disponível (datas passadas não serão clicáveis)
            alert('Data selecionada: ' + info.dateStr);
        }
    });
    

    calendar.render();
    


});

</script>
@endsection
