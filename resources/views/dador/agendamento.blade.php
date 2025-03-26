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
        padding: 0.5rem 1rem; /* Ajuste conforme necessário */
        font-size: 1rem;      /* Tamanho da fonte */
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
                        <select class="form-select" name="horario" required>
                            @for($h = 8; $h <= 17; $h++)
                                <option value="{{ $h }}:00">{{ sprintf('%02d:00', $h) }}</option>
                                @if($h < 17)
                                    <option value="{{ $h }}:30">{{ sprintf('%02d:30', $h) }}</option>
                                @endif
                            @endfor
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
@endsection

@section('scripts')
<script src="https://api-maps.yandex.ru/2.1/?lang=pt_PT&apikey=db51d640-6b39-495c-b35b-c2ec8a719fc9" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                            <h6>{{ $centro->nome }}</h6>
                            <p class="small">{{ $centro->endereco }}</p>
                            <p class="small">{{ $centro->telefone }}</p>
                            <button onclick="selectCenter({{ $centro->id_centro }})" 
                                    class="btn btn-sm btn-danger mt-2">
                                Selecionar Centro
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
        document.getElementById('id_centro').value = centroId;
        updateCenterInfo(centroId);
    }

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
    document.addEventListener("DOMContentLoaded", function () {
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: "{{ session('error') }}",
            });
        @endif

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: "{{ session('success') }}",
            });
        @endif

        @if ($errors->any())
            let errorMessages = "";
            @foreach ($errors->all() as $error)
                errorMessages += "{{ $error }}<br>";
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Erro no Agendamento',
                html: errorMessages,
                confirmButtonColor: '#d33'
            });
        @endif
    });
</script>
@endsection
