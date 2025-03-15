
<div class="container py-5">
    <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Cadastro de Centro de Coleta</h4>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('centro.submit') }}">
                @csrf

                <!-- Dados do Centro -->
                <div class="mb-3">
                    <label class="form-label">Nome do Centro *</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>

                <!-- Dados de Acesso -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Senha *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirme a Senha *</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <!-- Localização -->
                <div class="mb-3">
                    <label class="form-label">Endereço Completo *</label>
                    <textarea name="endereco" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Capacidade *</label>
                    <input type="number" name="capacidade" class="form-control" rows="3" required>
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

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="fas fa-hospital me-2"></i>Registrar Centro
                </button>
            </form>
        </div>
    </div>
</div>
