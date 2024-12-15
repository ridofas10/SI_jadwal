<?php
// Konfigurasi koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "sijadwal");

// Cek koneksi
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>