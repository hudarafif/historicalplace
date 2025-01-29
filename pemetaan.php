<?php include('config.php'); 

$stmt = $pdo->query("SELECT *  FROM locations");
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG Gardens</title>
    <!-- Tambahkan CSS Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script src="config.php"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .leaflet-popup-content table {
        width: 100%;
        border-collapse: collapse;
    }

    .leaflet-popup-content td {
        border: 1px solid #ddd;
        padding: 5px;
    }

    .leaflet-popup-content table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .leaflet-popup-content table tr:hover {
        background-color: #f1f1f1;
    }
</style>

<body>
     <!-- Navbar -->
     <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand mx-3" href="index.php">SIG Gardens</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mx-4">
                    <li class="nav-item"><a class="nav-link " href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="pemetaan.php">pemetaan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">about</a></li>
                </ul>
                <a href="http://localhost/sigHPlace/admin.php" class="btn btn-light btn-sm">Mode Admin</a>
            </div>
        </div>
    </nav>


     <!-- Peta Interaktif -->
   <div id="map-section" class="container mt-5">
        <h2 class="text-center mb-4 text-light">Peta Lokasi Taman</h2>
        <div id="map"></div>
    </div>
    <script>

    // Definisikan tile layers
    var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    });

    var googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });
    googleTerrain = L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    });
    // Inisialisasi peta
    var map = L.map('map').setView([-6.9923897, 110.4170767], 12);

    // Tambahkan layer tile
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Google Maps'
    }).addTo(map);

    // Data lokasi dari PHP
    var locations = <?php echo json_encode($locations); ?>;



    var info = L.control();
    info.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info bg-light text-dark border p-2');
        this.update();
        return this._div;
    };

    info.update = function (props) {
        this._div.innerHTML = '<h6>Location Information</h6>' + (
            props ? '<b>' + 
            props.name :'Hover over a location');
        
    };
    info.addTo(map);

// Tambahkan marker ke peta
console.log(locations);

        locations.forEach(function(location) {
        var popupContent = `
            <div style="min-width: 250px;">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                    <tr>
                        <td><strong>Nama Wisata</strong></td>
                        <td>${location.name}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>${location.district}</td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi</strong></td>
                        <td>${
                                location.description.length > 100 
                                    ? location.description.substring(0, 100) + '... <a href="detail.php?id=' + location.id + '" class="text-primary">Baca Selengkapnya</a>'
                                    : location.description
                            }</td>
                    </tr>
                </table>
                <div style="margin-top: 10px; display: flex; justify-content: space-between;">
                     <a href="detail.php?id=${location.id}" class="btn btn-warning btn-sm ">Detail</a>
                    <button class="btn btn-warning btn-sm" onclick="goToMap(${location.latitude}, ${location.longitude})">Menuju Map</button>
                </div>
            </div>
        `;
    var marker = L.marker([location.latitude, location.longitude])
        .addTo(map)
        .bindPopup(popupContent);

function showDetails(name) {
    alert(`Menampilkan detail untuk: ${name}`);
    // Anda dapat mengganti ini dengan logika lain seperti memuat halaman detail
}

function goToMap(lat, lng) {
    map.setView([lat, lng], 16); // Zoom ke lokasi yang dipilih
}


    // Hover function untuk marker
    marker.on('mouseover', function (e) {
        info.update(location);
    });
    marker.on('mouseout', function (e) {
        info.update(null);
    });
    });

    info.addTo(map);

    var baseLayers = {
    "Google Satellite": googleSat,
    "OpenStreetMap": osm,
    "Google Hybrid": googleHybrid,
    "Google Terrain": googleTerrain
    };

    L.control.layers(baseLayers).addTo(map);






</script>

<!-- Footer -->
<footer>
    <p class="text-center mt-5 text-white">&copy; 2024 SIG Gardens | All Rights Reserved</p>
</footer>

</body>
</html>