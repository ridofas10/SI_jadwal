<?php
include '../../conn/koneksi.php';  // Pastikan koneksi ke database sudah benar

// Cek apakah id user diterima melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data user berdasarkan id
    $query = "DELETE FROM users WHERE id = $id";
    if (mysqli_query($koneksi, $query)) {
        echo "User telah dihapus!";
        header("Location: dass.php");  // Redirect ke halaman backdoor setelah penghapusan
        exit;
    } else {
        echo "Terjadi kesalahan saat menghapus user.";
    }
} else {
    echo "ID user tidak ditemukan.";
}
?>