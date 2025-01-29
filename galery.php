<?php
include 'config.php'; // Koneksi database Anda

// Ambil data dari tabel locations
$query = "SELECT * FROM locations";
$locations = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Galeri Taman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            text-align: center;
            padding: 20px;
            background-color: #4CAF50;
            color: white;
        }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .gallery-item img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.3s;
        }
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        .gallery-item .caption {
            position: absolute;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            width: 100%;
            text-align: center;
            padding: 10px;
            font-size: 18px;
            transition: opacity 0.3s;
            opacity: 0;
        }
        .gallery-item:hover .caption {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Galeri Taman di Semarang</h1>
        <p>Jelajahi keindahan taman-taman di Semarang melalui galeri ini.</p>
        <a href="index.php" class="btn btn-primary mb-4">Back to Home</a>
    </div>
    <div class="gallery">
        <?php foreach ($locations as $location): ?>
        <div class="gallery-item">
            <img src="uploads/<?= htmlspecialchars($location['images']) ?>" alt="<?= htmlspecialchars($location['name']) ?>">
            <div class="caption"><?= htmlspecialchars($location['name']) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
