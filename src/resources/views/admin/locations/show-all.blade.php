@extends('layouts.admin')

@section('styles')
<style>
    #map {
        height: 80vh; 
        width: 74vw; 
    }
</style>
@endsection

@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.show') }} All Locations
    </div>

    <div class="body">
        <div class="block pb-4">
            <a class="btn-md btn-gray" href="{{ route('admin.locations.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
        <div class="block">
            <h3>All Locations</h3>
            <div id="map"></div>
        </div>
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
            zoom: 12,
            center: {
                lat: 23.779089,
                lng: 90.3910898
            }            
        });
        
        let locations = {!! json_encode($locations) !!};
        try {
            if(locations){
                locations.forEach(location => {

                    let locationStr = location.replace(/,/g, "");
                    let locationFilter = locationStr.split(/\s*[\(\)]\s*/).filter(Boolean);
   
                    let triangleCoords = [];
                    locationFilter.forEach(latlng => {
                        let l = latlng.split(' ');
                        let result = {lat: Number(l[0]), lng: Number(l[1])}
                        triangleCoords.push(result)
                    });

                    function getRandomColor() {
                        var letters = '0123456789ABCDEF';
                        var color = '#';
                        for (var i = 0; i < 6; i++ ) {
                            color += letters[Math.floor(Math.random() * 16)];
                        }
                        return color;
                    }

                    const bermudaTriangle = new google.maps.Polygon({
                        paths: triangleCoords,
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.9,
                        strokeWeight: 2,
                        // fillColor: '#FF00CC',
                        fillOpacity: 0.4,
                    });

                    bermudaTriangle.setOptions({fillColor: getRandomColor()});

                    bermudaTriangle.setMap(map)

                    bermudaTriangle.addListener("click", showArrays);
                    infoWindow = new google.maps.InfoWindow();
                });

                function showArrays(event) {
                    const polygon = this;
                    const vertices = polygon.getPath();
                    let contentString =
                        "<b>Mohakhali DOHS Branch</b><br>" +
                        "Your area to sell your medicine. <br>";

                    // Iterate over the vertices.
                    // for (let i = 0; i < vertices.getLength(); i++) {
                    //     const xy = vertices.getAt(i);

                    //     contentString +=
                    //         "<br>" + "Coordinate " + i + ":<br>" + xy.lat() + "," + xy.lng();
                    // }

                    // Replace the info window's content and position.
                    infoWindow.setContent(contentString);
                    infoWindow.setPosition(event.latLng);
                    infoWindow.open(map);
                }
            } 
        }catch (e) {
            if (e !== BreakException) throw e;
        }   
    }

    window.initMap = initMap;

</script>

@endsection