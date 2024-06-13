<!-- resources/views/view.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WFS Data View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <style>
        #map {
            height: 600px;
        }
        .info-box {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-4 mb-3">Data dari WFS</h1>
        <div id="map"></div>
        <div id="dataContainer" class="mt-3"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi peta Leaflet
            var map = L.map('map').setView([-7.797068, 110.370529], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            fetch('/fetch-wfs-data')
                .then(response => response.json())
                .then(data => {
                    if(data.features && data.features.length > 0) {
                        data.features.forEach(feature => {
                            const { properties, geometry } = feature;
                            const coordinates = geometry.coordinates;
                            const popupContent = `<div class="info-box">
                                <h5>${properties.NAMA_PROVINSI}</h5>
                                <p>Jumlah Penduduk: ${properties.JUMLAH_PENDUDUK}</p>
                                <p><strong>Lokasi: </strong>[${coordinates[1]}, ${coordinates[0]}]</p>
                            </div>`;

                            L.marker([coordinates[1], coordinates[0]])
                                .bindPopup(popupContent)
                                .addTo(map);

                            const infoElement = document.createElement('div');
                            infoElement.innerHTML = popupContent;
                            document.getElementById('dataContainer').appendChild(infoElement);
                        });
                    } else {
                        document.getElementById('dataContainer').innerHTML = '<p>Tidak ada data yang ditemukan.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    document.getElementById('dataContainer').innerHTML = '<p>Gagal mengambil data. Coba lagi nanti.</p>';
                });
        });
    </script>
</body>
</html>
