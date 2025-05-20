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
                        <!-- Título e Descrição -->
                        <div class="col-md-6 mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Datas -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="data_inicio" class="form-label">Data Início</label>
                            <input  type="date"  class="form-control" id="data_inicio"   min="<?= date('Y-m-d') ?>"   name="data_inicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_fim" class="form-label">Data Fim</label>
                            <input type="date" class="form-control" id="data_fim"  min="<?= date('Y-m-d') ?>"   name="data_fim" required>
                        </div>
                    </div>

                    <!-- Horários -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="hora_inicio" class="form-label">Hora Início</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_fim" class="form-label">Hora Fim</label>
                            <input type="time" class="form-control" id="hora_fim" name="hora_fim" required>
                        </div>
                    </div>

                    <!-- Endereço e Foto -->
                    <div class="row g-3">
                        <div class="col-md-8 mb-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" name="endereco" id="endereco" placeholder="Local da campanha (opcional)">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="foto" class="form-label">Imagem</label>
                            <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
                        </div>
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
.modal-content {
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.form-control {
    border: 1px solid #dee2e6;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220,53,69,.25);
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}
</style>

<script>
    function formatarDataParaComparacao(dataStr) {
    const partes = dataStr.split('/');
    return new Date(`${partes[2]}-${partes[1]}-${partes[0]}T00:00:00`);
}

    document.addEventListener('DOMContentLoaded', function() {
    // Função de validação reutilizável
    function validarCampanha(form) {
        let isValid = true;
        const errors = {};
        const campos = {
            titulo: form.querySelector('#titulo'),
            descricao: form.querySelector('#descricao'),
            data_inicio: form.querySelector('#data_inicio'),
            data_fim: form.querySelector('#data_fim'),
            hora_inicio: form.querySelector('#hora_inicio'),
            hora_fim: form.querySelector('#hora_fim'),
            foto: form.querySelector('#foto')
        };

        // Validação Título
        if (!campos.titulo.value.trim() || campos.titulo.value.trim().length < 5) {
            errors.titulo = 'Título deve ter pelo menos 5 caracteres';
            isValid = false;
        }

        // Validação Datas
        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);
       const dataInicio = campos.data_inicio.value ? formatarDataParaComparacao(campos.data_inicio.value) : null;
        const dataFim = campos.data_fim.value ? formatarDataParaComparacao(campos.data_fim.value) : null;


        // Valida data início
        if (!campos.data_inicio.value || dataInicio < hoje) {
            errors.data_inicio = 'Data deve ser hoje ou futuro';
            isValid = false;
        }

        // Valida data fim
        if (!campos.data_fim.value) {
            errors.data_fim = 'Data final é obrigatória';
            isValid = false;
        } else if (dataFim < hoje) {
            errors.data_fim = 'Data final não pode ser no passado';
            isValid = false;
        } else if (dataFim < dataInicio) {
            errors.data_fim = 'Data final deve ser após a data inicial';
            isValid = false;
        }

        // Validação Horários
        if (campos.data_inicio.value === campos.data_fim.value) {
            if (campos.hora_inicio.value >= campos.hora_fim.value) {
                errors.hora_fim = 'Horário final deve ser posterior';
                isValid = false;
            }
        }

        // Validação Imagem
        if (campos.foto.files.length > 0) {
            const file = campos.foto.files[0];
            const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
            const tamanhoMaximo = 2 * 1024 * 1024; // 2MB

            if (!tiposPermitidos.includes(file.type)) {
                errors.foto = 'Formato inválido (use JPG, PNG ou GIF)';
                isValid = false;
            }

            if (file.size > tamanhoMaximo) {
                errors.foto = 'Tamanho máximo excedido (2MB)';
                isValid = false;
            }
        }

        // Aplicar erros
        Object.keys(campos).forEach(key => {
            const feedback = campos[key].nextElementSibling;
            if (errors[key]) {
                campos[key].classList.add('is-invalid');
                feedback.textContent = errors[key];
            } else {
                campos[key].classList.remove('is-invalid');
                feedback.textContent = '';
            }
        });

        return isValid;
    }

    // Configurar validação para o modal de campanha
    const formCampanha = document.querySelector('#formCampanha');
    if (formCampanha) {
        // Adicionar feedbacks de erro
        const campos = ['titulo', 'descricao', 'data_inicio', 'data_fim', 'hora_inicio', 'hora_fim', 'foto'];
        campos.forEach(campo => {
            const elemento = formCampanha.querySelector(`#${campo}`);
            if (elemento && !elemento.nextElementSibling?.classList.contains('invalid-feedback')) {
                const erroDiv = document.createElement('div');
                erroDiv.className = 'invalid-feedback';
                elemento.parentNode.appendChild(erroDiv);
            }
        });

        // Configura datas mínimas
        const hojeHTML = new Date().toISOString().split('T')[0];
        const dataInicio = formCampanha.querySelector('#data_inicio');
        const dataFim = formCampanha.querySelector('#data_fim');
        
        dataInicio.min = hojeHTML;
        dataFim.min = hojeHTML;

        // Evento de submit
        formCampanha.addEventListener('submit', function(e) {
            e.preventDefault();
            if (validarCampanha(this)) {
                this.submit();
            }
        });

        // Validação em tempo real para datas
        dataInicio.addEventListener('change', function() {
            const dataSelecionada = new Date(this.value);
            const hoje = new Date();
            hoje.setHours(0, 0, 0, 0);
            
            if (dataSelecionada < hoje) {
                this.value = hojeHTML;
            }
            
            dataFim.min = this.value;
            dataFim.value = this.value > dataFim.value ? this.value : dataFim.value;
        });

        dataFim.addEventListener('change', function() {
            const dataInicioValue = new Date(dataInicio.value);
            const dataFimValue = new Date(this.value);
            
            if (dataFimValue < dataInicioValue) {
                this.value = dataInicio.value;
            }
        });
    }

    // Resetar validação ao fechar modal
    document.getElementById('modalCampanha').addEventListener('hidden.bs.modal', () => {
        const form = document.getElementById('formCampanha');
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        form.reset();
        
        // Reseta as datas mínimas
        const hojeHTML = new Date().toISOString().split('T')[0];
        form.querySelector('#data_inicio').min = hojeHTML;
        form.querySelector('#data_fim').min = hojeHTML;
    });
});
</script>