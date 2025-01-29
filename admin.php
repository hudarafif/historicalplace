<?php
include('config.php');
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Batas waktu sesi (10 menit)
$timeout_duration = 600; // 10 menit
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_destroy();
    header("Location: login.php?error=session_expired");
    exit;
}
$_SESSION['last_activity'] = time();

// Menambahkan lokasi/mall baru
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $district = $_POST['district'];
    $description = $_POST['description'];
    $image = '';

    if ($_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($fileType), $allowTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $image = $fileName;
            } else {
                echo "<div class='alert alert-danger'>Gagal mengunggah gambar.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Format file gambar tidak valid.</div>";
        }
    }

    if (!empty($name) && !empty($latitude) && !empty($longitude) && !empty($district)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO locations (name, latitude, longitude, district, description, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $latitude, $longitude, $district, $description, $image]);
            header("Location: admin.php");
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Menghapus lokasi/mall
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("SELECT image FROM locations WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && !empty($row['image']) && file_exists("uploads/" . $row['image'])) {
            unlink("uploads/" . $row['image']);
        }

        $stmt = $pdo->prepare("DELETE FROM locations WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: admin.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Lokasi/Mall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Admin Panel - Kelola Lokasi/Mall</h2>
    <a href="index.php" class="btn btn-secondary mb-4">Kembali ke Peta</a>

    <!-- Form Tambah Lokasi -->
    <form method="POST" enctype="multipart/form-data">
        <h3>Tambah Lokasi/Mall</h3>
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lokasi/Mall</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="text" class="form-control" id="latitude" name="latitude" required>
        </div>
        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="text" class="form-control" id="longitude" name="longitude" required>
        </div>
        <div class="mb-3">
            <label for="district" class="form-label">Distrik</label>
            <input type="text" class="form-control" id="district" name="district" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Unggah Gambar</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Tambah Lokasi</button>
    </form>

    <!-- Daftar Lokasi -->
    <h3 class="mt-5">Daftar Lokasi/Mall</h3>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Distrik</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM locations ORDER BY id DESC");
            while ($row = $stmt->fetch()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['latitude']) . "</td>
                        <td>" . htmlspecialchars($row['longitude']) . "</td>
                        <td>" . htmlspecialchars($row['district']) . "</td>
                        <td>" . htmlspecialchars($row['description']) . "</td>
                        <td>";
                if (!empty($row['image']) && file_exists("uploads/" . $row['image'])) {
                    echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" alt="Gambar" width="100" class="img-thumbnail">';
                } else {
                    echo "Tidak ada gambar";
                }
                echo "</td>
                        <td>
                            <a href='update.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='admin.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus lokasi ini?\")'>Hapus</a>
                        </td>
                    </tr>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
