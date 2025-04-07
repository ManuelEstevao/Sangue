<div class="container py-5">
    <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Cadastro de Centro de Coleta</h4>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('centro.submit') }}" enctype="multipart/form-data">
                @csrf

                <!-- Dados Principais -->
                <div class="mb-3">
                    <label class="form-label">Nome do Centro *</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Horário de Abertura *</label>
                        <input type="time" name="horario_abertura" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Horário de Fechamento *</label>
                        <input type="time" name="horario_fechamento" class="form-control" required>
                    </div>
                </div>

                <!-- Contato -->
                <div class="mb-3">
                    <label class="form-label">Telefone *</label>
                    <input type="tel" name="telefone" class="form-control" required>
                </div>

                <!-- Localização -->
                <div class="mb-3">
                    <label class="form-label">Endereço Completo *</label>
                    <textarea name="endereco" class="form-control" rows="2" required></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Latitude *</label>
                        <input type="number" step="any" name="latitude" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Longitude *</label>
                        <input type="number" step="any" name="longitude" class="form-control" required>
                    </div>
                </div>

                <!-- Capacidade e Foto -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Capacidade Máxima *</label>
                        <input type="number" name="capacidade_maxima" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto do Centro</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                </div>

                <!-- Dados de Acesso -->
                <div class="border-top pt-3">
                    <h6 class="text-muted mb-3">Dados de Acesso</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Senha *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirme a Senha *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mt-3">
                    <i class="fas fa-hospital me-2"></i>Registrar Centro
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'Sucesso!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @elseif($errors->any())
            Swal.fire({
                title: 'Erro!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>