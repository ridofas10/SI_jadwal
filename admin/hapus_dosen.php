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
    // Ambil ID dari parameter GET dan ubah ke integer
    $id = intval($_GET['id']); 

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    try {
        // Query untuk menghapus data dari tabel daftar_dosen
        $query1 = "DELETE FROM daftar_dosen WHERE id = $id";
        $result1 = mysqli_query($koneksi, $query1);
        
        if (!$result1) {
            throw new Exception("Gagal menghapus data dari tabel daftar_dosen: " . mysqli_error($koneksi));
        }

        // Query untuk menghapus data dari tabel jadwal_dosen yang memiliki id_dosen yang sama
        $query2 = "DELETE FROM jadwal_dosen WHERE id_dosen = $id";
        $result2 = mysqli_query($koneksi, $query2);
        
        if (!$result2) {
            throw new Exception("Gagal menghapus data dari tabel jadwal_dosen: " . mysqli_error($koneksi));
        }

        // Jika semua query berhasil, lakukan commit
        mysqli_commit($koneksi);

        // Redirect ke halaman daftar dosen dengan pesan sukses
        header("Location: daftar_dosen.php?pesan=sukses");
        exit();

    } catch (Exception $e) {
        // Jika ada kesalahan, lakukan rollback
        mysqli_rollback($koneksi);
        
        // Tampilkan pesan error
        echo "Error: " . $e->getMessage();
    }

} else {
    // Jika parameter ID tidak ada, redirect ke halaman daftar dosen dengan pesan error
    header("Location: daftar_dosen.php?pesan=error");
    exit();
}

// Tutup koneksi database
mysqli_close($koneksi);
?>