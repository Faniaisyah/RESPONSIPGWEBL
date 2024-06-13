@extends('layouts.template')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
<!-- Search CSS Library -->
<link rel="stylesheet" href="{{ asset('assets/plugins/leaflet-search/leaflet-search.css') }}" />

<!-- Search CSS Library -->
<link rel="stylesheet" href="{{ asset('assets/plugins/leaflet-search/leaflet-search.css') }}" />
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
        width: 100%;
        margin: 0;
    }


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

<!-- Modal Create Point-->
<div class="modal fade" id="PointModal" tabindex="-1" aria-labelledby="PointModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="PointModalLabel">Create Point</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('store-point') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Feature Name">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="geom" class="form-label">Geometry</label>
                        <textarea class="form-control" id="geom_point" name="geom" rows="3" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image_point" name="image" onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                    </div>
                    <div class="mb-3">
                        <img src="" alt="Preview" id="preview-image-point" class="img-thumbnail" width="400">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Create Polylines-->
<div class="modal fade" id="PolylineModal" tabindex="-1" aria-labelledby="PolylineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="PolylineModalLabel">Create Polyline</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('store-polyline') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Feature Name">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="geom" class="form-label">Geometry</label>
                        <textarea class="form-control" id="geom_polyline" name="geom" rows="3" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image_polyline" name="image" onchange="document.getElementById('preview-image-polyline').src = window.URL.createObjectURL(this.files[0])">
                    </div>
                    <div class="mb-3">
                        <img src="" alt="Preview" id="preview-image-polyline" class="img-thumbnail" width="400">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Create Polygons-->
<div class="modal fade" id="PolygonModal" tabindex="-1" aria-labelledby="PolygonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="PolygonModalLabel">Create Polygon</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('store-polygon') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Feature Name">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="geom" class="form-label">Geometry</label>
                        <textarea class="form-control" id="geom_polygon" name="geom" rows="3" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image_polygon" name="image" onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                    </div>
                    <div class="mb-3">
                        <img src="" alt="Preview" id="preview-image-polygon" class="img-thumbnail" width="400">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://unpkg.com/terraformer@1.0.7/terraformer.js"></script>
<script src="https://unpkg.com/terraformer-wkt-parser@1.1.2/terraformer-wkt-parser.js"></script>
<!-- Search JS Library -->
<script src="{{ asset('assets/plugins/leaflet-search/leaflet-search.js') }}"></script>
<script>
    // Map initialization
    var map = L.map('map').setView([-7.7695046638297995, 110.37782895530609], 13);

    // Basemap
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Digitize Function
    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    var drawControl = new L.Control.Draw({
        draw: {
            position: 'topleft',
            polyline: { color: 'red' },
            polygon: { color: 'red' },
            rectangle: { color: 'red' },
            circle: false,
            marker: true,
            circlemarker: false
        },
        edit: false
    });

    map.addControl(drawControl);

    map.on('draw:created', function(e) {
        var type = e.layerType,
            layer = e.layer;

        console.log(type);

        var drawnJSONObject = layer.toGeoJSON();
        var objectGeometry = Terraformer.WKT.convert(drawnJSONObject.geometry);

        console.log(drawnJSONObject);
        console.log(objectGeometry);

        if (type === 'polyline') {
            $("#geom_polyline").val(objectGeometry);
            $("#PolylineModal").modal('show');
        } else if (type === 'polygon' || type === 'rectangle') {
            $("#geom_polygon").val(objectGeometry);
            $("#PolygonModal").modal('show');
        } else if (type === 'marker') {
            $("#geom_point").val(objectGeometry);
            $("#PointModal").modal('show');
        } else {
            console.log('undefined');
        }

        drawnItems.addLayer(layer);
    });

    // Custom icon for points
    var customIcon = L.divIcon({
        html: '<i class="fa-solid fa-location-dot" style="color:black;"></i>',
        iconSize: [20, 20],
        className: 'custom-div-icon'
    });

    // GeoJSON Point
    var point = L.geoJson(null, {
        pointToLayer: function (feature, latlng) {
            return L.marker(latlng, { icon: customIcon });
        },
        onEachFeature: function(feature, layer) {
            var popupContent = "Name: " + feature.properties.name + "<br>" +
                "Description: " + feature.properties.description + "<br>" +
                "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                "' class='img-thumbnail' alt='...'>" + "<br>" +

                "<div class='flex-row mt-3 d-flex'>" +
                "<a href='{{ url('edit-point') }}/" + feature.properties.id + "' class='btn btn-sm btn-warning'><i class='fa-solid fa-edit'></i></a>" +
                "<form action='{{ url('delete-point') }}/" + feature.properties.id + "' method='POST'>" +
                '{{ csrf_field() }}' +
                '{{ method_field('delete') }}' +
                "<button type='submit' class='btn btn-danger' onclick='return confirm(Yakin Anda akan menghapus data ini?)'>" +
                "<i class='fa-solid fa-trash'></i></button>" +
                "</form>" +
                "</div>";

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
    var polylineStyle = {
        color: "red",
        weight: 2,
        opacity: 1
    };

    var polyline = L.geoJson(null, {
        style: polylineStyle,
        onEachFeature: function(feature, layer) {
            var popupContent = "Nama: " + feature.properties.name + "<br>" +
                "Deskripsi: " + feature.properties.description + "<br>" +
                "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                "' class='img-thumbnail' alt='...'>" + "<br>" +

                "<div class='flex-row mt-3 d-flex'>" +
                "<a href='{{ url('edit-polyline') }}/" + feature.properties.id + "' class='btn btn-sm btn-warning'><i class='fa-solid fa-edit'></i></a>" +
                "<form action='{{ url('delete-polyline') }}/" + feature.properties.id + "' method='POST'>" +
                '{{ csrf_field() }}' +
                '{{ method_field('delete') }}' +
                "<button type='submit' class='btn btn-danger' onclick='return confirm(Yakin Anda akan menghapus data ini?)'>" +
                "<i class='fa-solid fa-trash'></i></button>" +
                "</form>" +
                "</div>";

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
    var polygonStyle = {
        color: "black",
        weight: 2,
        opacity: 1,
        fillColor: "#B22222",
        fillOpacity: 0.5
    };

    var polygon = L.geoJson(null, {
        style: polygonStyle,
        onEachFeature: function(feature, layer) {
            var popupContent = "Nama: " + feature.properties.name + "<br>" +
                "Deskripsi: " + feature.properties.description + "<br>" +
                "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                "' class='img-thumbnail' alt='...'>" + "<br>" +

                "<div class='flex-row mt-3 d-flex'>" +
                "<a href='{{ url('edit-polygon') }}/" + feature.properties.id + "' class='btn btn-sm btn-warning'><i class='fa-solid fa-edit'></i></a>" +
                "<form action='{{ url('delete-polygon') }}/" + feature.properties.id + "' method='POST'>" +
                '{{ csrf_field() }}' +
                '{{ method_field('delete') }}' +
                "<button type='submit' class='btn btn-danger' onclick='return confirm(Yakin Anda akan menghapus data ini?)'>" +
                "<i class='fa-solid fa-trash'></i></button>" +
                "</form>" +
                "</div>";

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
    var popupContent = "Nama Desa: " + feature.properties.NAMOBJ; // Mengganti 'name' dengan 'NAMOBJ'

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



</script>
@endsection
