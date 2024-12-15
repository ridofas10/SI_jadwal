<?php
require '../assets/conn/koneksi.php'; // Koneksi ke database
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Validasi parameter id_dosen
if (!isset($_GET['id_dosen']) || empty($_GET['id_dosen'])) {
    die("Error: Parameter id_dosen tidak ditemukan.");
}

$id_dosen = intval($_GET['id_dosen']);

// Query untuk menghapus data
$query = "DELETE FROM jadwal_dosen WHERE id_dosen = $id_dosen";
$result = mysqli_query($koneksi, $query);

// Redirect dengan notifikasi
if ($result) {
    header("Location: daftar_jadwal_dosen.php?pesan=sukses");
} else {
    header("Location: daftar_jadwal_dosen.php?pesan=error");
}

mysqli_close($koneksi);
?>