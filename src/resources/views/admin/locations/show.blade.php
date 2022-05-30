@extends('layouts.admin')

@section('styles')
<style>
    #map {
        height: 80vh; 
        width: 60vw; 
    }
</style>
@endsection

@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.show') }} {{ trans('cruds.location.title') }}
    </div>

    <div class="body">
        <div class="block pb-4">
            <a class="btn-md btn-gray" href="{{ route('admin.locations.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
        <table class="striped bordered show-table">
            <tbody>
                <tr>
                    <th>
                        {{ trans('cruds.location.fields.id') }}
                    </th>
                    <td>
                        {{ $location->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.location.fields.location_name') }}
                    </th>
                    <td>
                        {{ $location->location_name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.location.fields.location') }}
                    </th>
                    <td>
                        <div id="map"></div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="block pt-4">
            <a class="btn-md btn-gray" href="{{ route('admin.locations.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOFmWsZtOGHJ5kaw6Zp-_P80zrjZHm8mw&callback=initMap&libraries=drawing&v=weekly" defer></script>

<script>

    function initMap() {
        
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 14,
            center: {
                lat: 23.779089,
                lng: 90.3910898
            },
        });
        
        let locations = {!! json_encode($location) !!};

        try {
            if(locations){
                let locationStr = locations.location.replace(/,/g, "");
                let locationFilter = locationStr.split(/\s*[\(\)]\s*/).filter(Boolean);

                let triangleCoords = [];
                locationFilter.forEach(latlng => {
                    let l = latlng.split(' ');
                    let result = {lat: Number(l[0]), lng: Number(l[1])}
                    triangleCoords.push(result)
                });

                const bermudaTriangle = new google.maps.Polygon({
                    paths: triangleCoords,
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.9,
                    strokeWeight: 1,
                    fillColor: "#FF0000",
                    fillOpacity: 0.2,
                });

                bermudaTriangle.setMap(map)
            } 
        }catch (e) {
            if (e !== BreakException) throw e;
        }   
    }

    window.initMap = initMap;

</script>

@endsection