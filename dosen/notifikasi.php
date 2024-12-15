<?php

require '../assets/conn/koneksi.php';

session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Pastikan dosen sudah login
if (!isset($_SESSION['nama_lengkap']) || $_SESSION['level'] !== 'dosen') {
    echo json_encode([]); // Jika tidak login sebagai dosen, kirim response kosong
    exit();
}

// Ambil nama dosen yang sedang login
$nama_lengkap = $_SESSION['nama_lengkap'];
$query_dosen = "SELECT id FROM users WHERE nama_lengkap = '$nama_lengkap'";
$result_dosen = mysqli_query($koneksi, $query_dosen);
$dosen = mysqli_fetch_assoc($result_dosen);

if ($dosen) {
    $id_dosen = $dosen['id'];

    // Ambil jumlah notifikasi yang belum dibaca
    $query_unread = "
        SELECT COUNT(*) AS total 
        FROM notifikasi 
        WHERE nama_dosen = '$nama_lengkap' AND status = 'unread'
    ";
    $result_unread = mysqli_query($koneksi, $query_unread);
    $unread_count = mysqli_fetch_assoc($result_unread)['total'];

    // Ambil detail notifikasi untuk dosen yang sedang login
    $query_notifikasi = "
        SELECT id, nama_dosen, nama_mahasiswa, pesan, status, DATE_FORMAT(tanggal, '%d %M %Y %H:%i') AS tanggal 
        FROM notifikasi 
        WHERE nama_dosen = '$nama_lengkap'
        ORDER BY tanggal DESC LIMIT 5
    ";
    $result_notifikasi = mysqli_query($koneksi, $query_notifikasi);
    $notifikasi = mysqli_fetch_all($result_notifikasi, MYSQLI_ASSOC);

    // Kirim data dalam format JSON
    echo json_encode([
        'unread_count' => $unread_count,
        'notifikasi' => $notifikasi,
    ]);
} else {
    echo json_encode([]); // Jika tidak ditemukan dosen, kirim response kosong
}
?>