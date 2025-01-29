<?php
// Hubungkan dengan database
include('config.php');

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mendapatkan detail lokasi
$stmt = $pdo->prepare("SELECT * FROM locations WHERE id = :id");
$stmt->execute(['id' => $id]);
$location = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika lokasi tidak ditemukan
if (!$location) {
    echo "<h1>Detail Taman Tidak Ditemukan</h1>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Taman - <?= htmlspecialchars($location['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
            border: 1px solid #ddd;
        }
        .img-fluid {
            max-height: 400px;
            object-fit: cover;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center"><?= htmlspecialchars($location['name']); ?></h1>
        <div class="row mt-4">
            <!-- Kolom Kiri: Peta -->
            <div class="col-md-6">
                <div id="map"></div>
            </div>
            <!-- Kolom Kanan: Gambar -->
            <div class="col-md-6">
                <img src="uploads/<?= htmlspecialchars($location['image']); ?>" 
                     alt="<?= htmlspecialchars($location['name']); ?>" 
                     class="img-fluid">
            </div>
        </div>
        <table class="table table-bordered mt-4">
            <tr>
                <th>Alamat</th>
                <td><?= htmlspecialchars($location['district']); ?></td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td><?= htmlspecialchars($location['description']); ?></td>
            </tr>
            <tr>
                <th>Latitude</th>
                <td><?= htmlspecialchars($location['latitude']); ?></td>
            </tr>
            <tr>
                <th>Longitude</th>
                <td><?= htmlspecialchars($location['longitude']); ?></td>
            </tr>
        </table>
        <div class="text-center mt-4 mb-5">
            <a href="pemetaan.php" class="btn btn-primary ">Kembali ke Peta</a>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
        // Inisialisasi Peta
        var map = L.map('map').setView([<?= $location['latitude']; ?>, <?= $location['longitude']; ?>], 15);

        // Tambahkan Layer Peta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan Marker
        L.marker([<?= $location['latitude']; ?>, <?= $location['longitude']; ?>]).addTo(map)
            .bindPopup("<b><?= htmlspecialchars($location['name']); ?></b><br><?= htmlspecialchars($location['description']); ?>")
            .openPopup();

    var baseLayers = {
    "Google Satellite": googleSat,
    "OpenStreetMap": osm,
    "Google Hybrid": googleHybrid,
    "Google Terrain": googleTerrain
    };

    L.control.layers(baseLayers).addTo(map);
    </script>

<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <!-- Bagian Kiri -->
            <div class="col-md-6 mb-3 mb-md-0">
                <h3 class="fw-bold">Kota Semarang - 2024-2025</h3>
                <p class="mb-0 text-justify">
                    Kota Semarang memiliki luas 373,70 km² atau 37.366,836 Ha terdiri dari 16 kecamatan dan 117 kelurahan. 
                    Penduduknya sangat heterogen terdiri dari campuran beberapa etnis: Jawa, Cina, Arab, dan Keturunan lainnya. 
                    Mayoritas penduduk memeluk agama Islam, diikuti oleh Kristen, Katholik, Hindu, 
                    dan Budha. Mata pencaharian penduduk beraneka ragam, seperti pedagang, pegawai pemerintah, pekerja pabrik, 
                    hingga petani.
                </p>
            </div>
            <!-- Bagian Kanan -->
            <div class="col-md-6 text-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Seal_of_the_City_of_Semarang.svg/800px-Seal_of_the_City_of_Semarang.svg.png" alt="Logo Kota Semarang"  style="max-width: 250px; height: 150px;">
            </div>
        </div>
        <div class="text-center mt-4">
            <p class="mb-0">&copy; 2024-2025 Kota Semarang. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
