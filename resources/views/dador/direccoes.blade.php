@extends('dador.DashbordDador')

@section('title', 'Direções para o Centro')
@section('styles')
<style>
     .route-container {
        height: 80vh;
        min-height: 300px;
        max-height: 470px;
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid #e9ecef;
        margin: 1rem 0;
    }
    
    #routeMap {
        height: 100%;
        width: 100%;
        background: #f8f9fa;
    }

    .route-controls {
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.9);
        padding: 0.75rem 1rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        gap: 0.75rem;
        backdrop-filter: blur(5px);
    }

    .transport-select {
        border: 2px solid #dc3545;
        border-radius: 8px;
        padding: 0.4rem 1rem;
        font-size: 0.9rem;
        background: white;
        transition: all 0.3s ease;
    }

    .transport-select:focus {
        border-color: #ff6b6b;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.25);
    }

    .route-details {
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.95);
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        min-width: 300px;
        max-width: 90%;
        text-align: center;
        backdrop-filter: blur(5px);
    }

    .route-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0.5rem 0;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .route-container {
            height: 70vh;
            min-height: 400px;
        }
        
        .route-controls {
            flex-direction: column;
            width: calc(100% - 2rem);
            left: 1rem;
            right: 1rem;
            top: 1rem;
        }
        
        .transport-select {
            width: 100%;
        }
        
        .route-details {
            bottom: 0.5rem;
            padding: 0.75rem;
            min-width: auto;
        }
        
        .route-info-item {
            font-size: 0.85rem;
        }
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
    }
</style>
@endsection

@section('conteudo')
<div class="container-fluid ">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Direções para {{ $agendamento->centro->nome }}</h1>
    </div>

    <div class="route-container">
        <div class="route-controls">
            <select id="transportMode" class="form-select transport-select">
                <option value="auto">🚗 Carro</option>
                <option value="masstransit">🚌 Transporte Público</option>
                <option value="pedestrian">🚶 A Pé</option>
            </select>
            <button id="refreshLocation" class="btn btn-sm btn-primary">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>

        <div id="routeMap"></div>

        <div class="route-details">
            <div id="routeInfo" class="mb-2">
                <div class="text-danger fw-bold">Calculando rota...</div>
            </div>
            <a href="{{ $googleMapsUrl }}" target="_blank" class="btn btn-sm btn-outline-danger">
                <i class="fab fa-google me-2"></i>Abrir no Google Maps
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://api-maps.yandex.ru/2.1/?lang=pt_PT&apikey=db51d640-6b39-495c-b35b-c2ec8a719fc9"></script>
<script>
    let map, route, userMarker, centerMarker;
    const centerCoords = [@json($agendamento->centro->latitude), @json($agendamento->centro->longitude)];
    const googleMapsUrl = "{{ $googleMapsUrl }}";

    ymaps.ready(init);

    function init() {
        // Inicializa o mapa
        map = new ymaps.Map('routeMap', {
            center: centerCoords,
            zoom: 14,
            controls: ['zoomControl', 'fullscreenControl']
        });

        // Adiciona marcador do centro
        addCenterMarker();
        
        // Configura listeners
        document.getElementById('refreshLocation').addEventListener('click', refreshRoute);
        document.getElementById('transportMode').addEventListener('change', refreshRoute);

        // Calcula rota inicial
        getLocationAndCalculateRoute();
    }

    function addCenterMarker() {
        centerMarker = new ymaps.Placemark(centerCoords, {
            hintContent: 'Centro de Doação',
            balloonContent: `
                <strong>${@json($agendamento->centro->nome)}</strong><br>
                ${@json($agendamento->centro->endereco)}<br>
                📞 ${@json($agendamento->centro->telefone)}
            `
        }, {
            preset: 'islands#redMedicalIcon',
            iconColor: '#dc3545'
        });
        map.geoObjects.add(centerMarker);
    }

    async function getLocationAndCalculateRoute() {
        try {
            const result = await ymaps.geolocation.get({
                provider: 'browser',
                autoReverseGeocode: true,
                mapStateAutoApply: true
            });

            const userCoords = result.geoObjects.get(0).geometry.getCoordinates();
            updateUserMarker(userCoords);
            calculateRoute(userCoords);
            
        } catch (error) {
            handleLocationError();
        }
    }

    function updateUserMarker(coords) {
        if(userMarker) map.geoObjects.remove(userMarker);
        
        userMarker = new ymaps.Placemark(coords, {
            hintContent: 'Sua localização',
            balloonContent: 'Posição atual do dispositivo'
        }, {
            preset: 'islands#circleIcon',
            iconColor: '#28a745'
        });
        map.geoObjects.add(userMarker);
    }

    function calculateRoute(userCoords) {
        // Remove rota anterior
        if(route) map.geoObjects.remove(route);

        const mode = document.getElementById('transportMode').value;
        
        route = new ymaps.multiRouter.MultiRoute({
            referencePoints: [userCoords, centerCoords],
            params: {
                routingMode: mode,
                results: 1
            }
        }, {
            boundsAutoApply: true,
            routeActiveStrokeWidth: 4,
            routeActiveStrokeColor: "#dc3545",
            viaPointVisible: false
        });

        // Eventos da rota
        route.model.events.add('requestsuccess', updateRouteInfo);
        route.model.events.add('requestfail', handleRouteError);

        map.geoObjects.add(route);
    }

    function updateRouteInfo() {
        const activeRoute = route.getActiveRoute();
        const distance = activeRoute.properties.get('distance').text;
        const duration = activeRoute.properties.get('duration').text;
        
        document.getElementById('routeInfo').innerHTML = `
            <div class="text-success">✅ Rota calculada</div>
            <div>📏 Distância: ${distance}</div>
            <div>⏱ Tempo: ${duration}</div>
        `;
    }

    function handleRouteError() {
        document.getElementById('routeInfo').innerHTML = `
            <div class="text-danger">❌ Não foi possível calcular a rota</div>
            <small>Tente outro meio de transporte</small>
        `;
    }

    async function refreshRoute() {
        map.geoObjects.removeAll();
        addCenterMarker();
        await getLocationAndCalculateRoute();
    }

    function handleLocationError() {
        Swal.fire({
            icon: 'error',
            title: 'Localização não disponível',
            text: 'Mostrando apenas a localização do centro',
        });
        map.setCenter(centerCoords, 12);
        document.getElementById('routeInfo').innerHTML = `
            <div class="text-danger">⚠️ Ative a localização para ver rotas</div>
        `;
    }

    if (msg) {
      Swal.fire({
        icon: 'success',
        title: 'Pré Triagem concluída!',
        text: msg,
        confirmButtonText: 'Óptimo'
      });
      // 2) remover para não repetir no próximo carregamento
      localStorage.removeItem('success_questionario');
    }
</script>
@endsection