<!DOCTYPE html>

<html>

<head>
    <title>Polygon Contains Location</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>
    <!-- jsFiddle will insert css and js -->
    <style>
        #map {
            height: 100%;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 10px;
            text-overflow: ellipsis;
            width: 400px;
            margin-top: 10px;
            height: 20px;
            border: none;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }
    </style>
</head>

<body>
    <input id="pac-input" class="controls" type="text" placeholder="Search Box" />

    <div id="map"></div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOFmWsZtOGHJ5kaw6Zp-_P80zrjZHm8mw&callback=initMap&libraries=geometry,places&v=weekly" defer></script>

    <script>

        if (navigator.geolocation) {
            var location_timeout = setTimeout("geolocFail()", 10000);

            navigator.geolocation.getCurrentPosition(function(position) {
                clearTimeout(location_timeout);

                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                var loc = {lat: lat, lng: lng}

                geocodeLatLng(loc);
            }, function(error) {
                clearTimeout(location_timeout);
                geolocFail();
            });
        } else {
            // Fallback for no geolocation
            geolocFail();
        }

        let position, map;

        function geocodeLatLng(loc) {
            this.position = loc;
        }

        function geolocFail() {
            console.log("Error!");
        }

        function initMap() {
            let userPosition;
            if(this.position) {
                userPosition = this.position;
            }else {
                userPosition = { lat: 23.8795517, lng: 90.2704554 };
            }
            
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: userPosition,
            });
            
            let locations = {!! json_encode($locations) !!};

            // Search
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });

            let markers = [];

            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            const place_change = searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();

                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }

                    // const icon = {
                    //     url: place.icon,
                    //     size: new google.maps.Size(71, 71),
                    //     origin: new google.maps.Point(0, 0),
                    //     anchor: new google.maps.Point(17, 34),
                    //     scaledSize: new google.maps.Size(25, 25),
                    // };

                    // Create a marker for each place.
                    userPosition = place.geometry.location;
                    markers.push(
                        new google.maps.Marker({
                            map,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );
                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);

                dynamicLocation(locations, markers, userPosition);

            });
            // try {

                dynamicLocation(locations, markers, userPosition);
                
            // }catch (e) {
            //     if (e !== BreakException) throw e;
            // }   
        }

        function dynamicLocation(locations, markers, userPosition) {
            if(locations) {
                // let bermudaTriangle;
                // google.maps.event.addListener(bermudaTriangle, "overlaycomplete", function(event) {
                //     console.log("Called");
                //     var shapePath= e.overlay;
                //     shapePath.setMap(null);
                // });
                locations.forEach(location => {
                    let locationStr = location.location.replace(/,/g, "");
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

                    google.maps.event.addListener(bermudaTriangle, "drag", function(event) {
                        console.log("Called");
                        var shapePath= e.overlay;
                        shapePath.setMap(null);
                    });

                    markers.push(
                        new google.maps.Marker({
                            position: userPosition,
                            map,
                            title: "Pharmacy",
                        })
                    );

                    const resultColor = google.maps.geometry.poly.containsLocation(userPosition, bermudaTriangle);
                    console.log(resultColor);
                    if(resultColor) {
                        bermudaTriangle.setMap(map)
                        // throw BreakException;
                    } else {
                        bermudaTriangle.setMap(null);
                    }
                });
            } else {
                new google.maps.Marker({
                    position: userPosition,
                    map,
                    title: "Pharmacy",
                });
            }
        }

        window.initMap = initMap;

    </script>

</body>

</html>