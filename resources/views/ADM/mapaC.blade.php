@extends('ADM.main')

@section('title', 'Mapa de Centros')

@push('styles')
<style>
  #map { width: 100%;  
         height: 380px;
         border-radius: 10px;
         box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
         margin-bottom: 20px; 
        }
</style>
@endpush

@section('conteudo')
  <h2>Mapa de Centros de Coleta</h2>
  <div id="map"></div>
@endsection

@push('scripts')

  <script src="https://api-maps.yandex.ru/2.1/?lang=pt_PT&apikey=db51d640-6b39-495c-b35b-c2ec8a719fc9" type="text/javascript"></script>

  <script>
    ymaps.ready(init);

    function init() {
      
      var map = new ymaps.Map('map', {
        center: [-8.8383, 13.2344],
        zoom: 8,
        controls: ['zoomControl']
      });

      // 3. Cria uma coleção de geoObjects
      var collection = new ymaps.GeoObjectCollection();

      var centers = @json($centros);  
      centers.forEach(function(c) {
        var placemark = new ymaps.Placemark(
          [c.latitude, c.longitude],
          { balloonContent: c.nome },
          { preset: 'islands#icon', iconColor: '#e74c3c' }
        );  
        collection.add(placemark);
      });

      map.geoObjects.add(collection);

      var bounds = collection.getBounds();      
      map.setBounds(bounds, { checkZoomRange: true }); 
    }
  </script>
@endpush
