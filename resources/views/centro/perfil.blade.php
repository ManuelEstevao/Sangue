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
.is-invalid {
    border-color: #dc3545 !important;
    background-image: url("data:image/svg+xml,%3csvg...");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 0.25rem;
}

.fc-day-blocked {
    background-color: #ffe6e6 !important;
    cursor: not-allowed;
    position: relative;
}

.fc-day-blocked::after {
    content: "⛔";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.5em;
    opacity: 0.7;
}

.fc-daygrid-day-frame {
    pointer-events: none;
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
                        <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-lock me-2"></i>Alterar Senha
                        </a>
                        <a href="{{ route('notificacoes.historico') }}" class="list-group-item list-group-item-action">
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
                                                   name="telefone" pattern="^(?:\+244)?9[1-7]\d{7}$"
                                                    title="Digite um número válido: 9xxxxxxxx ou +2449xxxxxxxxx">
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
            <form method="POST" action="{{ route('centro.password.update') }}" id="passwordForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Senha Atual</label>
                        <input type="password" class="form-control" name="current_password" required>
                        <div class="invalid-feedback" id="currentPasswordError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label>Nova Senha</label>
                        <input type="password" class="form-control" name="new_password" required>
                        <div class="invalid-feedback" id="newPasswordError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label>Confirmar Nova Senha</label>
                        <input type="password" class="form-control" name="new_password_confirmation" required>
                        <div class="invalid-feedback" id="confirmPasswordError"></div>
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

    document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: new Date(),
        timeZone: 'UTC', // Adicionado para sincronização temporal
        validRange: { start: new Date() },
        locale: 'pt-br',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: ''
        },
        events: {
            url: '/centro/dias-bloqueados/{{ Auth::user()->centro->id_centro }}',
            method: 'GET',
            failure: (error) => {
                console.error('Falha ao carregar bloqueios:', error);
                Swal.fire('Erro', 'Falha ao carregar dias bloqueados', 'error');
            }
        },
        eventDataTransform: (eventData) => ({
            ...eventData,
            start: eventData.start + 'T00:00:00', 
            end: eventData.start + 'T23:59:59',
            extendedProps: {
                tipo: 'bloqueio',
                ...eventData.extendedProps
            }
        }),
        eventDidMount: (info) => {
            if(info.event.extendedProps.tipo === 'bloqueio') {
                info.el.classList.add('fc-day-blocked');
                info.el.innerHTML = `
                    <div class="fc-blocked-day">
                        <i class="fas fa-lock fa-xs"></i>
                    </div>
                `;
            }
        },
        dateClick: async (info) => {
            try {
                const clickedDateUTC = info.date.toISOString().split('T')[0];
                
                // Verificação precisa com dados UTC
                const existingBlock = calendar.getEvents().find(event => 
                    event.start.toISOString().split('T')[0] === clickedDateUTC &&
                    event.extendedProps.tipo === 'bloqueio'
                );

                if(existingBlock) {
                    await handleUnblock(existingBlock);
                } else {
                    await handleBlock(info);
                }
            } catch (error) {
                console.error('Erro na interação:', error);
                Swal.fire('Erro', error.message, 'error');
            }
        }
    });

    calendar.render();

   
    async function handleBlock(info) {
        const { value: motivo } = await Swal.fire({
            title: `Bloquear ${info.dateStr}?`,
            input: 'text',
            inputLabel: 'Motivo (opcional)',
            inputValidator: (value) => value?.length > 100 ? 'Máximo 100 caracteres' : null
        });

        if(motivo !== undefined) {
            const response = await fetch('/centro/dias-bloqueados', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_centro: {{ Auth::user()->centro->id_centro }},
                    data: info.dateStr,
                    motivo: motivo
                })
            });

            if(!response.ok) throw new Error('Falha no bloqueio');
            
            const data = await response.json();
            calendar.refetchEvents(); 
            Swal.fire('Sucesso!', 'Data bloqueada', 'success');
        }
    }

    async function handleUnblock(event) {
        const { isConfirmed } = await Swal.fire({
            title: `Desbloquear ${event.startStr.split('T')[0]}?`,
            html: event.title ? `<small class="text-muted">Motivo: "${event.title}"</small>` : '',
            showCancelButton: true,
            confirmButtonColor: '#28a745'
        });

        if(isConfirmed) {
            const response = await fetch(`/centro/dias-bloqueados/${event.id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            if(!response.ok) throw new Error('Falha no desbloqueio');
            
            calendar.refetchEvents();
            Swal.fire('Sucesso!', 'Data desbloqueada', 'success');
        }
    }
});

// Validação de Alteração de Senha
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('passwordForm');
    const currentPasswordInput = document.querySelector('input[name="current_password"]');
    const newPasswordInput = document.querySelector('input[name="new_password"]');
    const confirmPasswordInput = document.querySelector('input[name="new_password_confirmation"]');

    function showError(input, message) {
        input.classList.add('is-invalid');
        input.nextElementSibling.textContent = message;
    }

    function clearError(input) {
        input.classList.remove('is-invalid');
        input.nextElementSibling.textContent = '';
    }

    function validateForm() {
        let isValid = true;
        
        // Validação da senha atual
        if (currentPasswordInput.value.trim() === '') {
            showError(currentPasswordInput, 'Por favor insira sua senha atual');
            isValid = false;
        }

        // Validação da nova senha
        if (newPasswordInput.value.length < 8) {
            showError(newPasswordInput, 'A senha deve ter pelo menos 8 caracteres');
            isValid = false;
        }

        // Validação de correspondência
        if (newPasswordInput.value !== confirmPasswordInput.value) {
            showError(confirmPasswordInput, 'As senhas não coincidem');
            isValid = false;
        }

        return isValid;
    }

    // Validação em tempo real
    newPasswordInput.addEventListener('input', function() {
        if (this.value.length >= 8) clearError(this);
        if (this.value === confirmPasswordInput.value) clearError(confirmPasswordInput);
    });

    confirmPasswordInput.addEventListener('input', function() {
        if (this.value === newPasswordInput.value) clearError(this);
    });

    // Submissão do formulário
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!validateForm()) return;

        const swalInstance = Swal.fire({
            title: 'Verificando...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
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
                let errorMessage = 'Erro na alteração';
                if (data.errors) {
                    if (data.errors.current_password) {
                        showError(currentPasswordInput, data.errors.current_password[0]);
                        errorMessage = data.errors.current_password[0];
                    }
                }
                throw new Error(errorMessage);
            }
        } catch (error) {
            swalInstance.close();
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: error.message,
                confirmButtonColor: '#dc3545'
            });
        }
    });
});

</script>
@endsection
