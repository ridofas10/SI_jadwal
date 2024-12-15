<?php
require '../assets/conn/koneksi.php'; // Koneksi ke database
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Validasi data
if (!isset($_POST['id_dosen']) || !isset($_POST['id'])) {
    die("Error: Data tidak lengkap.");
}

$id_dosen = intval($_POST['id_dosen']);
$ids = $_POST['id'];
$hari = $_POST['hari'];
$jam = $_POST['jam'];
$ruangan = $_POST['ruangan'];

// Loop untuk update setiap jadwal
foreach ($ids as $index => $id) {
    $id = intval($id);
    $hariEdit = mysqli_real_escape_string($koneksi, $hari[$index]);
    $jamEdit = mysqli_real_escape_string($koneksi, $jam[$index]);
    $ruanganEdit = mysqli_real_escape_string($koneksi, $ruangan[$index]);

    $query = "
        UPDATE jadwal_dosen
        SET hari = '$hariEdit', jam = '$jamEdit', ruangan = '$ruanganEdit'
        WHERE id = $id AND id_dosen = $id_dosen
    ";
    mysqli_query($koneksi, $query) or die("Query error: " . mysqli_error($koneksi));
}

header("Location: daftar_jadwal_dosen.php?pesan=edit_sukses");
?>