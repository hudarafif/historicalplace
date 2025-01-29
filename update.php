<?php
require 'config.php'; // Gunakan file config.php untuk koneksi database

// Ambil data berdasarkan ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID berupa angka
    $stmt = $pdo->prepare("SELECT * FROM locations WHERE id = ?");
    $stmt->execute([$id]);
    $location = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika lokasi tidak ditemukan
    if (!$location) {
        echo "<h1>Data lokasi tidak ditemukan.</h1>";
        exit();
    }
}

// Update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $latitude = htmlspecialchars($_POST['latitude'], ENT_QUOTES, 'UTF-8');
    $longitude = htmlspecialchars($_POST['longitude'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
    $image_name = $location['image'];

    // Periksa apakah ada gambar baru yang diunggah
    if (!empty($_FILES['image']['name'])) {
        $image_name = uniqid() . "_" . basename($_FILES['image']['name']); // Nama unik untuk menghindari duplikasi
        $target_dir = "uploads/";
        $target_file = $target_dir . $image_name;

        // Validasi tipe file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            echo "Error: Format gambar tidak didukung.";
            exit();
        }

        // Pindahkan file ke folder uploads
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "Error: Gagal mengunggah gambar.";
            exit();
        }

        // Hapus gambar lama jika ada
        if ($location['image'] && file_exists($target_dir . $location['image'])) {
            unlink($target_dir . $location['image']);
        }
    }

    // Update data ke database
    $stmt = $pdo->prepare("UPDATE locations SET name = ?, latitude = ?, longitude = ?, description = ?, image = ? WHERE id = ?");
    if ($stmt->execute([$name, $latitude, $longitude, $description, $image_name, $id])) {
        header("Location: admin.php?message=success");
        exit();
    } else {
        echo "Error: Gagal memperbarui data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lokasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Lokasi</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Taman:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($location['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="latitude" class="form-label">Latitude:</label>
                <input type="text" name="latitude" id="latitude" class="form-control" value="<?= htmlspecialchars($location['latitude'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="longitude" class="form-label">Longitude:</label>
                <input type="text" name="longitude" id="longitude" class="form-control" value="<?= htmlspecialchars($location['longitude'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi:</label>
                <textarea name="description" id="description" class="form-control" rows="5" required><?= htmlspecialchars($location['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Gambar:</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                <?php if ($location['image']): ?>
                    <p class="mt-2">Gambar saat ini:</p>
                    <img src="uploads/<?= htmlspecialchars($location['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Current Image" width="100" class="img-thumbnail">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
        <div class="mt-4">
            <a href="admin.php" class="btn btn-secondary">Kembali ke Halaman Admin</a>
        </div>
    </div>
</body>
</html>
