<!DOCTYPE html>
<!DOCTYPE html>
<html>

<head>
    <title>Drawing tools on map</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
        html,
        body,
        #map-canvas {
            height: 95%;
            margin: 0px;
            padding: 0px
        }
    </style>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=drawing"></script> -->
    
    
</head>

<body>

    <div id="map-canvas"></div>
    <form method="post" accept-charset="utf-8" id="map_form" style="margin: 10px;">
        <input type="text" name="vertices" value="" id="vertices" />
        <input type="button" name="save" value="Save!" id="save" />
    </form>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
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
                }
            });
            drawingManager.setMap(map);

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                var newShape = event.overlay;
                newShape.type = event.type;
            });

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                overlayClickListener(event.overlay);
                $('#vertices').val(event.overlay.getPath().getArray());
            });
        }

        function overlayClickListener(overlay) {
            google.maps.event.addListener(overlay, "mouseup", function(event) {
                $('#vertices').val(overlay.getPath().getArray());
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);

        $(function() {
            $('#save').click(function() {
                //iterate polygon vertices?
            });
        });
    });

</script>

<script src="https://maps.googleapis.com/maps/api/js?v=weekly&v=3.exp&libraries=drawing"></script>



</html>