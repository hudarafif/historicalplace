<?php
session_start();

// Hardcoded credentials
$valid_username = "admin";
$valid_password = "admin123";

// Ambil data dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Cek kredensial
if ($username === $valid_username && $password === $valid_password) {
    $_SESSION['logged_in'] = true; // Simpan status login
    header("Location: admin.php"); // Redirect ke halaman admin
    exit;
} else {
    // Redirect kembali ke login dengan pesan error
    header("Location: login.php?error=invalid_credentials");
    exit;
}
?>
