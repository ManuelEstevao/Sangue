@extends('dador.DashbordDador')

@section('title', 'Agendamento de Doação')

@section('styles')
    <style>
        .card-header {
            background-color: #a50303;
            color: #fff;
        }
        /* Estilos para o mapa */
        #map {
            width: 100%;
            height: 400px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('conteudo')
<div class="container my-5">
    <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card shadow">
                 <div class="card-header text-center">
                     <h4>Agendar Doação de Sangue</h4>
                 </div>
                 <div class="card-body">
                     <form action="{{ route('agendamento.store') }}" method="POST">
                         @csrf
                         <!-- Data e Hora -->
                         <div class="mb-3">
                              <label for="data_agendamento" class="form-label">Data e Hora</label>
                              <input type="datetime-local" class="form-control" id="data_agendamento" name="data_agendamento" required>
                         </div>
                         <!-- Seleção do Centro/Hemocentro -->
                         <div class="mb-3">
                              <label for="id_centro" class="form-label">Centro/Hemocentro</label>
                              <select class="form-select" id="id_centro" name="id_centro" required>
                                  <option value="">Selecione um centro</option>
                                  @foreach($centros as $centro)
                                    <option value="{{ $centro->id_centro }}">{{ $centro->nome }}</option>
                                  @endforeach
                              </select>
                         </div>
                         <!-- Campanha (Opcional) -->
                         <div class="mb-3">
                              <label for="id_campanha" class="form-label">Campanha (Opcional)</label>
                              <select class="form-select" id="id_campanha" name="id_campanha">
                                  <option value="">Nenhuma</option>
                                  @foreach($campanhas as $campanha)
                                    <option value="{{ $campanha->id_campanha }}">{{ $campanha->titulo }}</option>
                                  @endforeach
                              </select>
                         </div>
                         <!-- Mapa para seleção do Centro -->
                         <div class="mb-3">
                             <label class="form-label">Localize o centro no mapa:</label>
                             <div id="map"></div>
                         </div>
                         <!-- Botão de Envio -->
                         <div class="text-center">
                             <button type="submit" class="btn btn-success">Agendar</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Inclui a API do Yandex Maps -->
    <script src="https://api-maps.yandex.ru/2.1/?lang=pt_PT&apikey=db51d640-6b39-495c-b35b-c2ec8a719fc9" type="text/javascript"></script>
    <script>
        ymaps.ready(init);
        function init() {
            // Inicializa o mapa centralizado em Angola (ajuste as coordenadas conforme necessário)
            var myMap = new ymaps.Map("map", {
                center: [-8.838333, 13.234444],
                zoom: 7,
                controls: ['zoomControl', 'geolocationControl']
            });
            
            // Adiciona marcadores para cada centro (centro)
            @foreach($centros as $centro)
                var placemark{{ $centro->id_centro }} = new ymaps.Placemark(
                    [{{ $centro->latitude }}, {{ $centro->longitude }}],
                    {
                        hintContent: "{{ $centro->nome }}",
                        balloonContent: "{{ $centro->endereco }}"
                    }
                );
                myMap.geoObjects.add(placemark{{ $centro->id_centro }});
                
                // Ao clicar no marcador, atualiza o campo do select
                placemark{{ $centro->id_centro }}.events.add('click', function (e) {
                    document.getElementById('id_centro').value = "{{ $centro->id_centro }}";
                });
            @endforeach
        }
    </script>
@endsection
