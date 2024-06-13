@extends('layouts.template')

@section('styles')
<style>
    html,
    body,
    #map {
        height: 100%;
        width: 100%;
        margin: 0;
    }

    #map {
        height: calc(100vh - 56px);
        width: 70%;
        float: left;
        margin: 0;
    }

    .info-container {
        width: 30%;
        float: left;
        padding: 20px;
        box-sizing: border-box;
        background-color: #f8f9fa;
        border-left: 2px solid #ddd;
        height: calc(100vh - 56px);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    .info-header {
        font-size: 1.5rem;
        margin-bottom: 20px;
        color: #244f7c;
    }

    .info-button {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 10px;
        background-color: #244f7c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        align-self: center; /* Center the button horizontally */
    }

    .info-button:hover {
        background-color: #1a3a5a;
    }

    .info-link {
        display: block;
        margin-top: 10px;
        color: #244f7c;
        text-decoration: none;
        font-size: 1rem;
    }

    .info-link:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div id="map"></div>
<div class="info-container">
    <h4 style="font-size: 2rem; font-weight: bold; margin-bottom: 20px; color: #244f7c;">Informasi</h4>
    <p>Web SafeZoneSlemanGIS adalah platform yang menyediakan akses mudah dan cepat ke berbagai data geografis di Kabupaten Sleman untuk tujuan keamanan. Dengan menggunakan teknologi GIS (Geographic Information System), pengguna dapat memvisualisasikan, menganalisis, dan mengelola data spasial terkait keamanan dengan lebih efektif, mendukung berbagai kebutuhan perencanaan dan pengambilan keputusan terkait keamanan publik di wilayah Sleman.

    <ul> <strong>Fitur Utama:</strong></ul>
    <ul>
        <li><strong>Pemetaan Interaktif:</strong> Menampilkan data spatial dalam format peta dengan berbagai layer informasi terkait keamanan.</li>
        <li><strong>Analisis Spasial:</strong> Memungkinkan analisis geospasial untuk perencanaan dan pengelolaan keamanan.</li>
        <li><strong>Data Keamanan Terkini:</strong> Menyediakan informasi dan data terkait keamanan publik, termasuk lokasi strategis dan potensi risiko keamanan.</li>
    </ul>
    Untuk akses lebih lanjut dan informasi detail, Anda dapat mengunjungi halaman di bawah ini.</p>
    <a href="https://geoportal.slemankab.go.id/" target="_blank" class="info-button">Geoportal Sleman</a>
</div>
@endsection

@section('script')
<script>
    // Map setup
    var map = L.map('map').setView([-7.7695046638297995, 110.37782895530609], 13);

    // Basemap
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // GeoJSON Point
    var point = L.geoJson(null, {
        onEachFeature: function(feature, layer) {
            var popupContent = "Name: " + feature.properties.name + "<br>" +
                "Description: " + feature.properties.description + "<br>" +
                "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                "' class='img-thumbnail' alt='...'>" + "<br>";

            layer.on({
                click: function(e) {
                    layer.bindPopup(popupContent).openPopup();
                },
                mouseover: function(e) {
                    layer.bindTooltip(feature.properties.kab_kota).openTooltip();
                },
            });
        },
    });

    $.getJSON("{{ route('points') }}", function(data) {
        point.addData(data);
        map.addLayer(point);
    });

    // GeoJSON Polyline
    var polyline = L.geoJson(null, {
        onEachFeature: function(feature, layer) {
            var popupContent = "Nama: " + feature.properties.name + "<br>" +
                "Deskripsi: " + feature.properties.description + "<br>" +
                "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                "' class='img-thumbnail' alt='...'>" + "<br>";

            layer.on({
                click: function(e) {
                    polyline.bindPopup(popupContent);
                },
                mouseover: function(e) {
                    polyline.bindTooltip(feature.properties.name);
                },
            });
        },
    });

    $.getJSON("{{ route('polylines') }}", function(data) {
        polyline.addData(data);
        map.addLayer(polyline);
    });

    // GeoJSON Polygon
    var polygon = L.geoJson(null, {
        onEachFeature: function(feature, layer) {
            var popupContent = "Nama: " + feature.properties.name + "<br>" +
                "Deskripsi: " + feature.properties.description + "<br>" +
                "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                "' class='img-thumbnail' alt='...'>" + "<br>";

            layer.on({
                click: function(e) {
                    polygon.bindPopup(popupContent);
                },
                mouseover: function(e) {
                    polygon.bindTooltip(feature.properties.name);
                },
            });
        },
    });

    $.getJSON("{{ route('polygons') }}", function(data) {
        polygon.addData(data);
        map.addLayer(polygon);
    });

    // Layer control
    var overlayMaps = {
        "Point": point,
        "Polyline": polyline,
        "Polygon": polygon
    };


    // Admin Sleman GeoJSON
    var adminSlemanStyle = {
        color: "#808080",
        weight: 2,
        opacity: 1,
        fillOpacity: 0.5
    };

    var adminSleman = L.geoJson(null, {
        style: adminSlemanStyle,
        onEachFeature: function(feature, layer) {
            var popupContent = "ID: " + feature.properties.id + "<br>" +
                "Name: " + feature.properties.name;

            layer.on({
                click: function(e) {
                    layer.bindPopup(popupContent).openPopup();
                }
            });
        },
    });

    $.getJSON("{{ asset('storage/shapefile/Admin_Sleman.geojson') }}", function(data) {
        adminSleman.addData(data);
        map.addLayer(adminSleman);
    });

    // Layer control
    var overlayMaps = {
        "Point": point,
        "Polyline": polyline,
        "Polygon": polygon,
        "Admin Sleman": adminSleman
    };

    var layerControl = L.control.layers(null, overlayMaps).addTo(map);


</script>
@endsection
