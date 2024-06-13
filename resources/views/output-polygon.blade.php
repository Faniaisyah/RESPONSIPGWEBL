@extends('layouts.template')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />

    <!-- Plugin CSS -->
    <!-- Search CSS Library -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/leaflet-search/leaflet-search.css') }}" />

    <!-- Geolocation CSS Library -->
    <link rel="stylesheet" href="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.css" />

    <!-- Leaflet Mouse Position CSS Library -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/leaflet-mouseposition/L.Control.MousePosition.css') }}" />

    <!-- Leaflet Measure CSS Library -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/leaflet-measure/leaflet-measure.css') }}" />

    <!-- EasyPrint CSS Library -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/leaflet-easyprint/easyPrint.css') }}" />

    <!-- Routing CSS Library -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/leaflet-routing/leaflet-routing-machine.css') }}" />

    <style>
        #map { height: 100vh; }
        .search-form {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
        }
        .search-form input {
            width: 200px;
            padding: 5px;
            margin-right: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-form button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-form button:hover {
            background-color: #0056b3;
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>
@endsection

@section('script')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

    <!-- Plugin JS -->
    <!-- Search JS Library -->
    <script src="{{ asset('assets/plugins/leaflet-search/leaflet-search.js') }}"></script>

    <!-- Geolocation JS Library -->
    <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.min.js"></script>

    <!-- Leaflet Mouse Position JS Library -->
    <script src="{{ asset('assets/plugins/leaflet-mouseposition/L.Control.MousePosition.js') }}"></script>

    <!-- Leaflet Measure JS Library -->
    <script src="{{ asset('assets/plugins/leaflet-measure/leaflet-measure.js') }}"></script>

    <!-- EasyPrint JS Library -->
    <script src="{{ asset('assets/plugins/leaflet-easyprint/leaflet.easyPrint.js') }}"></script>

    <!-- Routing JS Library -->
    <script src="{{ asset('assets/plugins/leaflet-routing/leaflet-routing-machine.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([-7.7951, 110.3631], 13); // Set center to Yogyakarta and zoom level

            var baseLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var polygons = @json($polygons);

            var markerClusterGroup = L.markerClusterGroup(); // Initialize marker cluster group

            polygons.forEach(function(polygon) {
                var polygonData = {
                    type: 'Feature',
                    geometry: JSON.parse(polygon.geom),
                    properties: {
                        id: polygon.id,
                        name: polygon.name,
                        description: polygon.description,
                        image: polygon.image ? "{{ asset('storage/images/') }}/" + polygon.image : null,
                        created_at: polygon.created_at,
                        updated_at: polygon.updated_at
                    }
                };

                L.geoJSON(polygonData, {
                    style: function(feature) {
                        return { color: "#3388ff", weight: 2, opacity: 0.65 };
                    },
                    onEachFeature: function (feature, layer) {
                        var popupContent = `
                            <strong>Nama:</strong> ${feature.properties.name}<br>
                            <strong>Deskripsi:</strong> ${feature.properties.description}<br>
                            ${feature.properties.image ? `<strong>Foto:</strong> <img src="${feature.properties.image}" class="img-thumbnail" alt="${feature.properties.name}"><br>` : ''}
                        `;
                        layer.bindPopup(popupContent);
                    }
                }).addTo(markerClusterGroup);
            });

            var shapefileLayer = L.layerGroup(); // Create layer group for shapefile

            fetch('{{ asset('storage/shapefile/Admin_Sleman.geojson') }}')
                .then(response => response.json())
                .then(data => {
                    L.geoJSON(data, {
                        style: function(feature) {
                            return { color: "#808080", weight: 2, opacity: 0.65 };
                        },
                        onEachFeature: function (feature, layer) {
                            var popupContent = `<strong>${feature.properties.name}</strong><br>${feature.properties.description}`;
                            layer.bindPopup(popupContent);
                        }
                    }).addTo(shapefileLayer);
                })
                .catch(error => console.error('Error loading shapefile:', error));

            map.addLayer(markerClusterGroup);

            // Create layer control
            var baseMaps = {
                "OpenStreetMap": baseLayer
            };

            var overlayMaps = {
                "Polygons": markerClusterGroup,
                "Shapefile": shapefileLayer
            };

            L.control.layers(baseMaps, overlayMaps).addTo(map);

            // Menambahkan form input koordinat
            var searchForm = L.DomUtil.create('div', 'search-form');
            searchForm.innerHTML = '<input type="text" id="coordinate-input" placeholder="Masukkan koordinat (latitude,longitude)" /> <button id="search-btn">Cari</button>';
            map.getContainer().appendChild(searchForm);

            // Event listener untuk tombol pencarian
            document.getElementById('search-btn').addEventListener('click', function() {
                var input = document.getElementById('coordinate-input').value.trim();
                var coords = input.split(',').map(parseFloat);

                if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
                    var latlng = L.latLng(coords[0], coords[1]);
                    map.setView(latlng, 15);

                    // Hapus marker lama jika ada
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.Marker) {
                            map.removeLayer(layer);
                        }
                    });

                    // Tambahkan marker baru
                    L.marker(latlng).addTo(map)
                        .bindPopup("Koordinat: " + input)
                        .openPopup();
                } else {
                    alert("Format koordinat tidak valid. Harap masukkan dalam format latitude,longitude.");
                }
            });

            // Plugin Search
            var searchControl = new L.Control.Search({
                position: "topleft",
                layer: markerClusterGroup,
                initial: false,
                zoom: 15,
                marker: false, // Tidak menampilkan marker di hasil pencarian
                moveToLocation: function(latlng, title, map) {
                    map.setView(latlng, 15);
                }
            });

            searchControl.on("search:locationfound", function(e) {
                e.layer.setStyle({
                    fillColor: "#ffff00",
                    color: "#0000ff",
                });
                if (!e.layer._popup) {
                    e.layer.bindPopup(e.text).openPopup();
                }
            }).on("search:collapse", function(e) {
                markerClusterGroup.eachLayer(function(layer) {
                    markerClusterGroup.resetStyle(layer);
                });
            });

            map.addControl(searchControl);

            /* Plugin Geolocation */
            var locateControl = L.control.locate({
                position: "topleft",
                drawCircle: true,
                follow: true,
                setView: true,
                keepCurrentZoomLevel: false,
                markerStyle: {
                    weight: 1,
                    opacity: 0.8,
                    fillOpacity:                     0.8,
                },
                circleStyle: {
                    weight: 1,
                    clickable: false,
                },
                icon: "fas fa-crosshairs",
                metric: true,
                strings: {
                    title: "Click for Your Location",
                    popup: "You're here. Accuracy {distance} {unit}",
                    outsideMapBoundsMsg: "Not available",
                },
                locateOptions: {
                    maxZoom: 16,
                    watch: true,
                    enableHighAccuracy: true,
                    maximumAge: 10000,
                    timeout: 10000,
                },
            }).addTo(map);

            /* Plugin Mouse Position Coordinate */
            L.control.mousePosition({
                position: "bottomright",
                separator: ",",
                prefix: "Coordinate: "
            }).addTo(map);

            /* Plugin Measurement Tool */
            var measureControl = new L.Control.Measure({
                position: "topleft",
                primaryLengthUnit: "meters",
                secondaryLengthUnit: "kilometers",
                primaryAreaUnit: "hectares",
                secondaryAreaUnit: "sqmeters",
                activeColor: "#FF0000",
                completedColor: "#00FF00",
            });
            measureControl.addTo(map);

            /* Plugin EasyPrint */
            var easyPrintControl = L.easyPrint({
                title: 'Print',
                filename: 'leaflet-map',
                sizeModes: ['Current', 'A4Landscape', 'A4Portrait'],
                exportOnly: true,
                removeControls: true
            }).addTo(map);

            /* Plugin Routing */
            var routingControl = L.Routing.control({
                waypoints: [], // Start and end points will be added dynamically
                routeWhileDragging: true,
                createMarker: function() { return null; }
            }).addTo(map);

            // Enable users to add waypoints by clicking on the map
            map.on('click', function(e) {
                var newWaypoint = L.latLng(e.latlng.lat, e.latlng.lng);
                routingControl.spliceWaypoints(routingControl.getWaypoints().length - 1, 0, newWaypoint);
            });

            // Initialize starting and ending waypoints
            routingControl.on('routesfound', function(e) {
                var routes = e.routes;
                console.log(routes);
            });

            // Set default waypoints
            routingControl.setWaypoints([
                L.latLng(-7.665955576806191, 110.3987200219),  // Start point
                L.latLng(-7.658214720042727, 110.42155098458319)   // End point
            ]);

            // Add button to dynamically start routing
            L.easyButton('fa-road', function(btn, map) {
                var waypoints = routingControl.getWaypoints();
                routingControl.spliceWaypoints(1, 0, L.latLng(-7.8001, 110.3681)); // Example additional point
            }).addTo(map);
        });
    </script>
@endsection

