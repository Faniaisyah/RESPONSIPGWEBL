<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoServer Data</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        #map {
            height: 100vh; /* Full height of the viewport */
        }
        .popup-content img {
            width: 100%;
            height: auto;
        }
        .error-message {
            color: red;
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(255, 0, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div id="map"></div>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Initialize the map
        var map = L.map('map').setView([-7.7972, 110.3688], 10); // Center and zoom level

        // Add a tile layer to the map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // GeoJSON data from Laravel's controller
        var geojsonData = @json($geojsonData);

        // Define a style function for the polygons
        function style(feature) {
            return {
                fillColor: feature.properties.fillColor || '#A9A9A9', // Default color grey
                weight: 2,
                opacity: 1,
                color: 'blue',
                dashArray: '3',
                fillOpacity: 0.7
            };
        }

        // Add GeoJSON layer to the map with styling and popup
        L.geoJSON(geojsonData, {
            style: style,
            onEachFeature: function (feature, layer) {
                var popupContent = `
                    <div class="popup-content">
                        <h3>${feature.properties.name}</h3>
                        <p>${feature.properties.description}</p>
                        <p><strong>Area:</strong> ${feature.properties.area} sq meters</p>
                        <img src="${feature.properties.image}" alt="Image"/>
                    </div>`;
                layer.bindPopup(popupContent);
            }
        }).addTo(map);
    </script>
    @if(isset($error))
        <div class="error-message">Error: {{ $error }}</div>
    @endif
</body>
</html>
