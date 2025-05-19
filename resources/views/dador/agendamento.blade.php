@extends('dador.DashbordDador')

@section('title', 'Agendamento de Doação')
<link href="assets/img/flavicon.png" rel="icon">

@section('styles')
<style>
    /* Ajustes para o Formulário de Agendamento */
    .agendamento-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    .map-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        position: relative;
    }
    .form-section {
        background: #ffffff;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .time-picker {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 1rem;
    }
    .btn-geolocation {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 1000;
        background: #fff;
        border-radius: 50%;
        padding: 0.8rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-confirm {
        padding: 0.5rem 1rem; 
        font-size: 1rem;      
    }

    .invalid-feedback {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

</style>
@endsection

@section('conteudo')
<div class="agendamento-container">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="page-title">Agendar Doação</h1>
    </div>

    <div class="row g-5">
        <!-- Mapa -->
        <div class="col-lg-7">
            <div class="map-container">
                <button id="btn-geolocation" class="btn-geolocation">
                    <i class="fas fa-location-crosshairs text-danger"></i>
                </button>
                <div id="map" style="width: 100%; height: 400px;"></div>
            </div>
        </div>

        <!-- Formulário -->
        <div class="col-lg-5">
            <form action="{{ route('agendamento.store') }}" method="POST" class="form-section">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label">📅 Data da Doação</label>
                    <input type="date" 
                           class="form-control" 
                           name="data_agendada"
                           min="{{ now()->format('Y-m-d') }}"
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label">⏰ Horário Preferencial</label>
                    <div class="time-picker">
                        <select class="form-select" name="horario" id="horario" required>
                            <option value="">Selecione um centro primeiro</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">🏥 Centro Selecionado</label>
                    <div class="input-group">
                        <select class="form-select" 
                                id="id_centro" 
                                name="id_centro" 
                                required>
                            <option value="">Selecione no mapa</option>
                            @foreach($centros as $centro)
                                <option value="{{ $centro->id_centro }}">{{ $centro->nome }}</option>
                            @endforeach
                        </select>
                        <button type="button" 
                                class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#centerDetails">
                            <i class="fas fa-info"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-lg btn-danger btn-confirm">
                        <i class="fas fa-calendar-check me-2"></i>
                        Confirmar Agendamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detalhes do Centro -->
<div class="modal fade" id="centerDetails" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Detalhes do Centro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="center-info">
                    <p class="text-muted">Selecione um centro no mapa para ver detalhes</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal do Questionário -->
<div class="modal fade" id="questionarioModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Questionário de Triagem</h5>
                </div>
                <form id="questionarioForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        @include('dador.partials.questionario')
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Enviar Respostas</button>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection

@section('scripts')
<script src="https://api-maps.yandex.ru/2.1/?lang=pt_PT&apikey=db51d640-6b39-495c-b35b-c2ec8a719fc9" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    ymaps.ready(init);

    let map;
    let userLocation;

    function init() {
        map = new ymaps.Map('map', {
            center: [-8.838333, 13.234444],
            zoom: 12,
            controls: ['zoomControl']
        });

        // Geolocalização do usuário
        ymaps.geolocation.get({
            provider: 'browser',
            autoReverseGeocode: true
        }).then(function (result) {
            userLocation = result.geoObjects.get(0).geometry.getCoordinates();
            map.setCenter(userLocation, 14);
            
            new ymaps.Placemark(userLocation, {
                hintContent: 'Sua localização atual',
                balloonContent: 'Você está aqui!'
            }, {
                preset: 'islands#circleIcon',
                iconColor: '#d10000'
            }).addTo(map);
        });

        // Adicionar marcadores dos centros
        @foreach($centros as $centro)
            const marker{{ $centro->id_centro }} = new ymaps.Placemark(
                [{{ $centro->latitude }}, {{ $centro->longitude }}],
                {
                    hintContent: '{{ $centro->nome }}',
                    balloonContent: `
                       <div class="map-popup">
                    <h6 class="mb-2">{{ $centro->nome }}</h6>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt text-danger "></i>
                        <span class="small ">{{ $centro->endereco }}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone text-danger "></i>
                        <span class="small">{{ $centro->telefone }}</span>
                    </div>
                    <button onclick="selectCenter({{ $centro->id_centro }})" 
                            class="btn btn-sm btn-danger mt-3 w-100">
                        <i class="fas fa-check me-2"></i>
                        Selecionar
                    </button>
                </div>
                    `
                },
                {
                    preset: 'islands#redMedicalIcon'
                }
            );
            map.geoObjects.add(marker{{ $centro->id_centro }});
        @endforeach

        // Botão de geolocalização
        document.getElementById('btn-geolocation').addEventListener('click', () => {
            if(userLocation) {
                map.setCenter(userLocation, 14);
            }
        });
    }

    function selectCenter(centroId) {
    const centro = @json($centros->keyBy('id_centro'))[centroId];
    
    document.getElementById('id_centro').value = centroId;
    const event = new Event('change');
    document.getElementById('id_centro').dispatchEvent(event);
    
    // Atualiza as informações do centro
    updateCenterInfo(centroId);
    map.balloon.close();
}

    const horariosCentros = @json($horariosCentros);

    document.getElementById('id_centro').addEventListener('change', function() {
        const centroId = this.value;
        const horarioSelect = document.getElementById('horario');
        
        horarioSelect.innerHTML = '<option value="">Selecione um horário</option>';
        
        if (centroId && horariosCentros[centroId]) {
            horariosCentros[centroId].forEach(horario => {
                const option = document.createElement('option');
                option.value = horario;
                option.textContent = horario;
                horarioSelect.appendChild(option);
            });
        }
    });

    function updateCenterInfo(centroId) {
        const centro = @json($centros->keyBy('id_centro'));
        const infoDiv = document.getElementById('center-info');
        
        infoDiv.innerHTML = `
            <h6>${centro[centroId].nome}</h6>
            <p class="small mb-1"><i class="fas fa-map-marker-alt"></i> ${centro[centroId].endereco}</p>
            <p class="small mb-1"><i class="fas fa-clock"></i> ${centro[centroId].horario_funcionamento}</p>
            <p class="small"><i class="fas fa-phone"></i> ${centro[centroId].telefone}</p>
        `;
    }
//data
    document.querySelector('input[name="data_agendada"]').addEventListener('change', checkBlockedDate);
document.getElementById('id_centro').addEventListener('change', checkBlockedDate);

async function checkBlockedDate() {
    const dataInput = document.querySelector('input[name="data_agendada"]');
    const centroSelect = document.getElementById('id_centro');
    const submitBtn = document.querySelector('button[type="submit"]');

    if (dataInput.value && centroSelect.value) {
        try {
            const response = await fetch(`/centro/verificar-data-bloqueada?centro=${centroSelect.value}&data=${dataInput.value}`);
            const result = await response.json();
            
            if (result.bloqueado) {
                dataInput.classList.add('is-invalid');
                submitBtn.disabled = true;
                
                // Cria elemento de erro se não existir
                if (!document.getElementById('data-error')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'data-error';
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = result.motivo || 'Data bloqueada para agendamentos';
                    dataInput.parentElement.appendChild(errorDiv);
                }
                
                Swal.fire('Data Indisponível', result.motivo || 'Este dia está bloqueado', 'warning');
            } else {
                dataInput.classList.remove('is-invalid');
                submitBtn.disabled = false;
                const errorDiv = document.getElementById('data-error');
                if (errorDiv) errorDiv.remove();
            }
        } catch (error) {
            console.error('Erro na verificação:', error);
        }
    }
}
    document.addEventListener("DOMContentLoaded", function () {

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: "{{ session('success') }}",
            });
        @endif

    });
//questionario e comprovativo
    document.addEventListener('DOMContentLoaded', () => {
  const csrfToken        = document.querySelector('meta[name="csrf-token"]').content;
  const agForm           = document.querySelector('form[action="{{ route('agendamento.store') }}"]');
  const questionarioForm = document.getElementById('questionarioForm');
  const questionarioModal= new bootstrap.Modal(document.getElementById('questionarioModal'));
  let   agendamentoId    = null;

  // 1️⃣ Submeter Agendamento
  if (agForm) {
      agForm.addEventListener('submit', async e => {
      e.preventDefault();
      const btn = agForm.querySelector('button[type="submit"]');
      btn.disabled   = true;
      btn.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> Processando...';

      const resp = await fetch(agForm.action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN':     csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept':           'application/json'
        },
        body: new FormData(agForm)
      });
     

      // 422 = erros de validação
      if (resp.status === 422) {
        const json     = await resp.json();
        // Se houver key "global", exibe essa mensagem primeiro
        const global   = (json.errors.global || []).join('<br>');
        const fields   = Object.entries(json.errors)
                               .filter(([k]) => k !== 'global')
                               .map(([,msgs]) => msgs.join('<br>'))
                               .join('<br>');
        Swal.fire({
          icon: 'error',
          title: 'Erro no formulário',
          html: [global, fields].filter(Boolean).join('<br>')
        });
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-calendar-check me-2"></i> Confirmar Agendamento';
        return;
      }

      // outros erros HTTP (inclui o global lançado como ValidationException não tratado)
      if (!resp.ok) {
        let msg = 'Não foi possível agendar.';
        try {
          const json = await resp.json();
          // se tiver "message" no JSON, use-o
          msg = json.message || msg;
        } catch {}
        Swal.fire('Erro', msg, 'error');
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-calendar-check me-2"></i> Confirmar Agendamento';
        return;
      }


      // sucesso: abre modal de questionário
      const data = await resp.json();
      agendamentoId = data.agendamento_id;
      questionarioForm.action = `/doador/agendamento/${agendamentoId}/questionario`;
      btn.disabled   = false;
      btn.innerHTML  = '<i class="fas fa-calendar-check me-2"></i> Confirmar Agendamento';
      questionarioModal.show();
    });
  }

  // 2️⃣ Submeter Questionário e baixar PDF
  if (questionarioForm) {
    questionarioForm.addEventListener('submit', async e => {
      e.preventDefault();
      const btn = questionarioForm.querySelector('button[type="submit"]');
      btn.disabled   = true;
      btn.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

      const resp = await fetch(questionarioForm.action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN':     csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept':           'application/json'
        },
        body: new FormData(questionarioForm)
      });

      
    
      const data = await resp.json();

      // 1) Abre o PDF em nova aba
      window.open(data.pdf_url, '_blank');
      localStorage.setItem('success_questionario', data.message);
      window.location.href = data.redirect_url;
    });
  }
});
</script>
@endsection
