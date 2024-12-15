<?php

require '../assets/conn/koneksi.php'; // Koneksi ke database
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Ambil id_bimbingan yang ingin dihapus
$id_bimbingan = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id_bimbingan) {
    echo "ID bimbingan tidak valid.";
    exit;
}

// Query untuk menghapus data jadwal_bimbingan berdasarkan id_bimbingan
$query_delete = "DELETE FROM jadwal_bimbingan WHERE id = '$id_bimbingan'";

if (mysqli_query($koneksi, $query_delete)) {
    // Redirect setelah sukses
    header("Location: rekap_bimbingan.php?pesan=sukses");
    exit;
} else {
    // Menampilkan error jika gagal
    echo "Terjadi kesalahan saat menghapus data: " . mysqli_error($koneksi);
}

mysqli_close($koneksi);
?>