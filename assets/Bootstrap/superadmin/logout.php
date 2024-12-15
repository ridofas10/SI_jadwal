<?php
session_start();

// Hapus semua data session
$_SESSION = [];

// Hapus semua session
session_destroy();

// Hapus cookie id dan key
setcookie('id', '', time() - 3600, '/'); // Tambahkan path '/' agar berlaku di seluruh situs
setcookie('key', '', time() - 3600, '/');

// Redirect ke halaman login
header("Location: ../../../index.php");
exit;
?>