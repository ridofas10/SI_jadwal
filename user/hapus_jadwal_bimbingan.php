<?php

require '../assets/conn/koneksi.php';
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}

// Pastikan sesi login sudah dimulai dan id_user sudah terisi
$id_user = isset($_SESSION['id']) ? $_SESSION['id'] : null;
if (!$id_user) {
    echo "Anda harus login untuk menghapus jadwal bimbingan.";
    exit;
}

// Ambil id_bimbingan yang ingin dihapus
$id_bimbingan = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id_bimbingan) {
    echo "ID bimbingan tidak valid.";
    exit;
}

// Query untuk memeriksa apakah data yang akan dihapus milik user yang sedang login
$query_check = "SELECT * FROM jadwal_bimbingan WHERE id = '$id_bimbingan' AND id_user = '$id_user'";
$result_check = mysqli_query($koneksi, $query_check);

// Jika data tidak ditemukan atau bukan milik user yang sedang login
if (mysqli_num_rows($result_check) == 0) {
    echo "Data tidak ditemukan atau Anda tidak memiliki hak akses untuk menghapus jadwal ini.";
    exit;
}

// Query untuk menghapus data
$query_delete = "DELETE FROM jadwal_bimbingan WHERE id = '$id_bimbingan' AND id_user = '$id_user'";

if (mysqli_query($koneksi, $query_delete)) {
    // Redirect setelah sukses
    header("Location: riwayat_bimbingan.php?pesan=sukses");
    exit;
} else {
    // Menampilkan error jika gagal
    echo "Terjadi kesalahan saat menghapus data: " . mysqli_error($koneksi);
}

mysqli_close($koneksi);
?>