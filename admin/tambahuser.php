<?php 
include 'header.php';
require '../assets/conn/koneksi.php'; // Koneksi ke database

session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}

if(isset($_POST["save"])) {
    if(registrasi($_POST) > 0) {
        echo "
        <script>
            alert('User baru berhasil ditambahkan!');
            window.location.href = 'datauser.php'; // Ubah ke halaman daftar user setelah berhasil
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Gagal menambahkan user!');
        </script>
        ";
    }
}

function registrasi($data) {
    global $koneksi;

    // Ambil data dari form
    $nama_lengkap = strtolower(trim($data["nama_lengkap"]));
    $npm = trim($data["npm"]);
    $level = trim($data["level"]);
    $username = strtolower(trim($data["username"]));
    $password = mysqli_real_escape_string($koneksi, $data["password"]);
    $password2 = mysqli_real_escape_string($koneksi, $data["confirm_password"]);

    // Validasi input
    if (empty($nama_lengkap) || empty($npm) || empty($level) || empty($username) || empty($password)) {
        echo "<script>alert('Semua field wajib diisi!');</script>";
        return 0;
    }

    // Cek apakah username sudah ada
    $result = mysqli_query($koneksi, "SELECT username FROM users WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
        return 0;
    }

    // Cek konfirmasi password
    if ($password !== $password2) {
        echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
        return 0;
    }

    // Enkripsi password
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user baru ke database
    $query = "INSERT INTO users (nama_lengkap, npm, level, username, password) 
              VALUES ('$nama_lengkap', '$npm', '$level', '$username', '$password_hashed')";

    if (mysqli_query($koneksi, $query)) {
        return mysqli_affected_rows($koneksi); // Berhasil
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
        return 0; // Gagal
    }
}
?>


<!-- Page Wrapper -->
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
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">

                    <div class="container">
                        <h1>Tambah Pengguna</h1>
                        <hr>
                        <form class="form-horizontal" method="POST" action="">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Nama Lengkap:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="nama_lengkap" type="text" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">NPM/NIP:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="npm" type="text" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Level:</label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="level" required>
                                        <option value="">Pilih Level</option>
                                        <option value="user">User</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Username:</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="username" type="text" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Password:</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="password" type="password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Konfirmasi Password:</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="confirm_password" type="password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-8">
                                    <input type="submit" name="save" class="btn btn-primary" value="Simpan">
                                    <span></span>
                                    <a href="datauser.php" class="btn btn-default">Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>