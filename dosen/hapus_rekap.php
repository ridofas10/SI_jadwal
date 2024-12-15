<?php
require '../assets/conn/koneksi.php'; // Koneksi ke database
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Cek apakah id ada di URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Query untuk menghapus data berdasarkan id
    $query = "DELETE FROM jadwal_bimbingan WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Redirect kembali dengan pesan sukses
        header("Location: rekap_bimbingan.php?pesan=sukses");
        exit();
    } else {
        // Jika terjadi kesalahan saat menghapus
        echo "<script>alert('Terjadi kesalahan saat menghapus data'); window.location.href='rekap_bimbingan.php';</script>";
    }
} else {
    // Jika parameter id tidak ada di URL
    echo "<script>alert('ID tidak ditemukan'); window.location.href='rekap_bimbingan.php';</script>";
}

// Menutup koneksi database
mysqli_close($koneksi);
?>