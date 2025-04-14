<div class="modal fade" id="modalCampanha" tabindex="-1" role="dialog" aria-labelledby="modalCampanhaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form id="formCampanha" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title" id="modalCampanhaLabel">Nova Campanha</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-5 mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo"  required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="row g-3 mb-3 ">
                        <div class="col-md-5 mb-3">
                            <label for="data_inicio" class="form-label">Data Início</label>
                            <input type="text" class="form-control" id="data_inicio" name="data_inicio" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="data_fim" class="form-label">Data Fim</label>
                            <input type="text" class="form-control" id="data_fim" name="data_fim" required>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 mb-3 ">
                            <label for="hora_inicio" class="form-label">Hora Início</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_fim" class="form-label">Hora Fim</label>
                            <input type="time" class="form-control" id="hora_fim" name="hora_fim" required>
                        </div>
                    </div>

                    <div class="mb-3 col-12">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" name="endereco" id="endereco" placeholder="Local da campanha (opcional)">
                    </div>

                    <div class="mb-3 col-12">
                        <label for="foto" class="form-label">Imagem da Campanha</label>
                        <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-danger">Salvar Campanha</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .modal-body {
        display: flex;
        flex-direction: row; /* Alinha os elementos na horizontal */
        flex-wrap: wrap; /* Permite que os elementos quebrem para a próxima linha se necessário */
    }
</style>