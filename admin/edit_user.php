<?php
require 'header.php';
require '../assets/conn/koneksi.php'; // Koneksi ke database
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}


if (!isset($_SESSION['id'])) { // Validasi sesi login
    die("Anda harus login untuk mengakses halaman ini.");
}

// Validasi ID pengguna dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID pengguna tidak ditemukan.");
}

$id_user = intval($_GET['id']); // Ambil ID dari parameter GET dan pastikan aman

// Ambil data pengguna yang sesuai dengan ID
$query = "SELECT * FROM users WHERE id = '$id_user' LIMIT 1"; // Pastikan kolom 'id' digunakan
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Pengguna tidak ditemukan.");
}

$user_data = mysqli_fetch_assoc($result); // Data pengguna

// Proses update jika form disubmit
if (isset($_POST['save'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $npm = mysqli_real_escape_string($koneksi, $_POST['npm']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);

    // Validasi password
    if (!empty($password) && $password !== $confirm_password) {
        echo "<div class='alert alert-danger'>Password dan Konfirmasi Password tidak cocok.</div>";
    } else {
        // Hash password jika tidak kosong
        $hashed_password = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : $user_data['password'];

        // Query update data pengguna
        $update_query = "UPDATE users SET 
                            nama_lengkap = '$nama_lengkap',
                            npm = '$npm',
                            username = '$username',
                            password = '$hashed_password',
                            level = '$level'
                         WHERE id = '$id_user' LIMIT 1";

        $update_result = mysqli_query($koneksi, $update_query);

        if ($update_result) {
            echo "<div class='alert alert-success'>Profil pengguna berhasil diperbarui.</div>";
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
                    <h1>Edit Profile Pengguna</h1>
                    <hr>
                    <h3>Silahkan Edit Informasi Pengguna</h3>

                    <form class="form-horizontal" role="form" method="POST" action="">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Nama Lengkap:</label>
                            <div class="col-lg-8">
                                <input class="form-control" name="nama_lengkap" type="text"
                                    value="<?= htmlspecialchars($user_data['nama_lengkap']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">NPM:</label>
                            <div class="col-lg-8">
                                <input class="form-control" name="npm" type="text"
                                    value="<?= htmlspecialchars($user_data['npm']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Username:</label>
                            <div class="col-md-8">
                                <input class="form-control" name="username" type="text"
                                    value="<?= htmlspecialchars($user_data['username']); ?>">
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
                            <label class="col-md-3 control-label">Level:</label>
                            <div class="col-md-8">
                                <select class="form-control" name="level">
                                    <option value="admin" <?= $user_data['level'] === 'admin' ? 'selected' : ''; ?>>
                                        Admin</option>
                                    <option value="user" <?= $user_data['level'] === 'user' ? 'selected' : ''; ?>>User
                                    </option>
                                    <option value="dosen" <?= $user_data['level'] === 'dosen' ? 'selected' : ''; ?>>
                                        Dosen</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 offset-md-3">
                                <input type="submit" name="save" class="btn btn-primary" value="Simpan">
                                <a href="datauser.php" class="btn btn-default">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>