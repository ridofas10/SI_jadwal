<?php
// Include koneksi ke database
require '../assets/conn/koneksi.php'; // Koneksi ke database
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Periksa apakah parameter 'id' ada
if (isset($_GET['id'])) {
    // Ambil ID dari parameter GET
    $id = intval($_GET['id']); // Mengubah ke tipe integer untuk keamanan

    // Query untuk menghapus data berdasarkan ID
    $query = "DELETE FROM users WHERE id = $id";

    // Eksekusi query
    if (mysqli_query($koneksi, $query)) {
        // Redirect ke halaman data pengguna dengan pesan sukses
        header("Location: datauser.php?pesan=sukses");
        exit();
    } else {
        // Tampilkan pesan error jika query gagal
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    // Jika parameter ID tidak ada, redirect ke halaman data pengguna
    header("Location: datauser.php?pesan=error");
    exit();
}

// Tutup koneksi database
mysqli_close($koneksi);
?>