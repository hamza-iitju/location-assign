@extends('layouts.admin')

@section('styles')
<style>
    #map-canvas {
        height: 80vh; 
        width: 100%; 
    }
</style>
@endsection

@section('content')

<div class="main-card">
    <div class="header">
        {{ trans('global.create') }} {{ trans('cruds.location.title_singular') }}
    </div>

    <form method="POST" action="{{ route("admin.locations.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="body">
            <div class="mb-3">
                <label for="location_name" class="text-xs required">{{ trans('cruds.location.fields.location_name') }}</label>

                <div class="form-group">
                    <input type="text" id="name" name="location_name" class="{{ $errors->has('location_name') ? 'is-invalid' : '' }}" value="{{ old('location_name') }}" required>
                </div>
                @if($errors->has('location_name'))
                    <p class="invalid-feedback">{{ $errors->first('location_name') }}</p>
                @endif
                <span class="block">{{ trans('cruds.location.fields.location_name_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="location" class="text-xs required">Select Location</label>
                <div id="map-canvas"></div>
                {{-- <form method="post" accept-charset="utf-8" id="map_form" style="margin: 10px;"> --}}
                    {{-- <input type="text" name="vertices" value="" id="vertices" />
                    <input type="button" name="save" value="Save!" id="save" /> --}}
                {{-- </form> --}}
            </div>

            <div class="mb-3">
                <label for="location" class="text-xs required">Lat Lng</label>

                <div class="form-group">
                    <input type="text" id="location" name="location" class="{{ $errors->has('location') ? 'is-invalid' : '' }}" value="{{ old('location') }}" required>
                </div>
                @if($errors->has('location'))
                    <p class="invalid-feedback">{{ $errors->first('location') }}</p>
                @endif
                <span class="block">{{ trans('cruds.location.fields.location_helper') }}</span>
            </div>
        </div>

        <div class="footer">
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection
{{-- <div id="map-canvas"></div> --}}

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOFmWsZtOGHJ5kaw6Zp-_P80zrjZHm8mw&v=weekly&v=3.exp&signed_in=true&libraries=places,drawing" async defer></script>

<script>
  $( document ).ready(function() {
  var map; // Global declaration of the map
  var iw = new google.maps.InfoWindow(); // Global declaration of the infowindow
  var lat_longs = new Array();
  var markers = new Array();
  var drawingManager;

  function initialize() {
      var myLatlng = new google.maps.LatLng(23.779089, 90.3910898);
      var myOptions = {
          zoom: 12,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
      drawingManager = new google.maps.drawing.DrawingManager({
          drawingMode: google.maps.drawing.OverlayType.POLYGON,
          drawingControl: true,
          drawingControlOptions: {
              position: google.maps.ControlPosition.TOP_CENTER,
              drawingModes: [google.maps.drawing.OverlayType.POLYGON]
          },
          polygonOptions: {
              editable: true
          },
          markerOptions: {
              draggable: true
          },
      });

    // Draw with circle
    // drawingManager = new google.maps.drawing.DrawingManager({
    //     drawingMode: google.maps.drawing.OverlayType.POLYGON,
    //     drawingControl: true,
    //     drawingControlOptions: {
    //     position: google.maps.ControlPosition.TOP_CENTER,
    //     drawingModes: [
    //         google.maps.drawing.OverlayType.MARKER,
    //         google.maps.drawing.OverlayType.CIRCLE,
    //         google.maps.drawing.OverlayType.POLYGON,
    //         google.maps.drawing.OverlayType.POLYLINE,
    //         google.maps.drawing.OverlayType.RECTANGLE,
    //     ],
    //     },
    //     polygonOptions: {
    //         editable: true
    //     },
    //     circleOptions: {
    //     editable: true,
    //     },
    //     polylineOptions: {
    //     editable: true,
    //     },
    //     rectangleOptions: {
    //     editable: true,
    //     },
    // });
      drawingManager.setMap(map);

      google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
          var newShape = event.overlay;
          newShape.type = event.type;
      });

      google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
          overlayClickListener(event.overlay);
          $('#location').val(event.overlay.getPath().getArray());
      });
  }

  function overlayClickListener(overlay) {
      google.maps.event.addListener(overlay, "mouseup", function(event) {
          $('#location').val(overlay.getPath().getArray());
      });
  }
  google.maps.event.addDomListener(window, 'load', initialize);
});


</script>
@endsection