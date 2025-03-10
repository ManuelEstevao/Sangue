<div class="modal fade" id="modalCampanha" tabindex="-1" aria-labelledby="modalCampanhaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCampanhaLabel">Nova Campanha</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('campanhas.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Título *</label>
                            <input type="text" class="form-control" name="titulo" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" name="descricao" rows="3"></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Data Início *</label>
                            <input type="text" class="form-control" id="data_inicio" name="data_inicio" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Data Término *</label>
                            <input type="text" class="form-control" id="data_fim" name="data_fim" required>
                        </div>
                         <!-- Horários -->
                         <div class="col-md-6">
                            <label class="form-label">Hora Início *</label>
                            <input type="time" class="form-control" name="hora_inicio" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Hora Término *</label>
                            <input type="time" class="form-control" name="hora_fim" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Imagem de Divulgação</label>
                            <input type="file" class="form-control" name="foto" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Campanha</button>
                </div>
            </form>
        </div>
    </div>
</div>