<?php
require 'header.php';
require '../assets/conn/koneksi.php';

// Pastikan sesi dimulai
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}

if (!isset($_SESSION['id'])) { // Ganti 'id_user' dengan nama kolom yang benar
    die("Anda harus login untuk mengakses halaman ini.");
}

$id_user = $_SESSION['id']; // Ganti 'id_user' dengan nama kolom yang benar

// Ambil data pengguna yang sedang login
$query = "SELECT * FROM users WHERE id = '$id_user' LIMIT 1"; // Pastikan kolom NPM ada di database
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

$user_data = mysqli_fetch_assoc($result);

// Proses penyimpanan
if (isset($_POST['save'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $npm = mysqli_real_escape_string($koneksi, $_POST['npm']); // Ambil NPM
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);

    // Validasi password
    if (!empty($password) && $password !== $confirm_password) {
        echo "<div class='alert alert-danger'>Password dan Konfirmasi Password tidak cocok.</div>";
    } else {
        // Hash password jika tidak kosong
        $hashed_password = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : $user_data['password'];

        // Update data pengguna
        $update_query = "UPDATE users SET 
                            nama_lengkap = '$nama_lengkap',
                            username = '$username',
                            npm = '$npm', -- Update NPM
                            password = '$hashed_password'
                         WHERE id = '$id_user' LIMIT 1";

        $update_result = mysqli_query($koneksi, $update_query);

        if ($update_result) {
            echo "<div class='alert alert-success'>Profil berhasil diperbarui.</div>";
        } else {
            echo "<div class='alert alert-danger'>Terjadi kesalahan: " . mysqli_error($koneksi) . "</div>";
        }
    }
}
?>

<!-- Page Wrapper -->
<div id="wrapper">
    <?php include 'sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
            <?php include 'navbar.php'; ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <div class="container">
                    <h1>Edit Profile</h1>
                    <hr>
                    <h3>Silahkan Edit Informasi Anda</h3>

                    <form class="form-horizontal" role="form" method="POST" action="">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Nama Lengkap:</label>
                            <div class="col-lg-8">
                                <input class="form-control" name="nama_lengkap" type="text"
                                    value="<?= htmlspecialchars($user_data['nama_lengkap']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">NPM/NIK:</label>
                            <div class="col-md-8">
                                <input class="form-control" name="npm" type="text"
                                    value="<?= htmlspecialchars($user_data['npm']); ?>">
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Username:</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="username" type="text"
                                        value="<?= htmlspecialchars($user_data['username']); ?>">
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Password:</label>
                            <div class="col-md-8">
                                <input class="form-control" name="password" type="password">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Konfirmasi Password:</label>
                            <div class="col-md-8">
                                <input class="form-control" name="confirm_password" type="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8 offset-md-3">
                                <input type="submit" name="save" class="btn btn-primary" value="Simpan">
                                <a href="dashboard.php" class="btn btn-default">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>