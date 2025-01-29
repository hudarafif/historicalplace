<?php include('config.php'); 

$stmt = $pdo->query("SELECT name, description, latitude, longitude FROM locations");
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
<body>
     <!-- Navbar -->
     <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand mx-2" href="index.php">SIG Gardens</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse mx-4" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="http://localhost/SIG/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="pemetaan.php">pemetaan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">about</a></li>
                </ul>
                <a href="http://localhost/sigHPlace/admin.php" class="btn btn-light btn-sm">Mode Admin</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero d-flex flex-column justify-content-center align-items-center">
        <h1 class="display-4">Selamat Datang di SIG Gardens</h1>
        <p class="lead text-center text-light">Sistem Informasi Geografis untuk menampilkan lokasi taman</p>
        <a href="pemetaan.php" class="btn btn-success btn-lg  ">Jelajahi Peta</a>
    </div>





   <!-- Peta Interaktif -->
  

    <!-- Kartu Informasi Taman -->
<div class="container mt-5">
    <h2 class="text-center text-light mb-4">Taman Terpopuler</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card hover-card">
                <img src="./assets/img/tmn-indokaya.jpg" class="card-img-top" alt="Taman Indonesia Kaya">
                <div class="card-body">
                    <h5 class="card-title">Taman Indonesia Kaya</h5>
                    <p class="card-text">Taman indah di pusat kota dengan fasilitas modern.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card hover-card">
                <img src="./assets/img/Taman-Pandanaran.jpeg" class="card-img-top" alt="Taman Pandanaran">
                <div class="card-body">
                    <h5 class="card-title">Taman Pandanaran</h5>
                    <p class="card-text">Destinasi favorit untuk olahraga dan bersantai.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card hover-card">
                <img src="./assets/img/taman-srigunting.jpg" class="card-img-top" alt="Taman Srigunting">
                <div class="card-body">
                    <h5 class="card-title">Taman Srigunting</h5>
                    <p class="card-text">Taman hijau dengan suasana asri dan segar.</p>
                </div>
            </div>
        </div>
    </div>
</div>


    
   <!-- Leaflet Script -->


<!-- Footer -->
<footer>
    <p class="text-center mt-5 text-white">&copy; 2024 SIG Gardens | All Rights Reserved</p>
</footer>

</body>
</html>
