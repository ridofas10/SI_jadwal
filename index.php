<?php
session_start();
require 'functions.php';
require 'assets/conn/koneksi.php'; // Pastikan koneksi database sudah benar

// Cek cookie untuk login otomatis
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    // Ambil username berdasarkan id
    $result = mysqli_query($koneksi, "SELECT username, level FROM users WHERE id = $id");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Cek kecocokan cookie
        if ($key === hash('sha256', $row['username'])) {
            $_SESSION['login'] = true;
            $_SESSION['level'] = $row['level']; // Pastikan level tersimpan
            $_SESSION['id'] = $id; // Menyimpan id pengguna
            $_SESSION['nama_lengkap'] = $row['nama_lengkap']; // Menyimpan nama lengkap
        }
    }
}

// Redirect jika sudah login
if (isset($_SESSION["login"])) {
    if ($_SESSION['level'] === 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($_SESSION['level'] === 'user') {
        header("Location: user/dashboard.php");
    } elseif ($_SESSION['level'] === 'dosen') {
        header("Location: dosen/dashboard.php");
    } elseif ($_SESSION['level'] === 'superadmin') {
        header("Location: assets/Bootstrap/superadmin/dass.php");
    }
    exit;
}


// Proses login
if (isset($_POST["login"])) {
    $username = mysqli_real_escape_string($koneksi, $_POST["username"]);
    $password = $_POST["password"]; // Tidak perlu sanitasi karena akan digunakan dengan password_verify

    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");

    // Cek username
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $row["password"])) {
            // Set sesi
            $_SESSION["login"] = true;
            $_SESSION["level"] = $row["level"];
            $_SESSION['id'] = $row['id']; // Menyimpan id pengguna
            $_SESSION['nama_lengkap'] = $row['nama_lengkap']; // Menyimpan nama lengkap

            // Cek "Remember Me"
            if (isset($_POST['remember'])) {
                // Set cookie berlaku selama 1 hari
                setcookie('id', $row['id'], time() + 86400, '/');
                setcookie('key', hash('sha256', $row['username']), time() + 86400, '/');
            }

            // Redirect sesuai level
            if ($row['level'] === 'admin') {
                header("Location: admin/dashboard.php");
            } elseif ($row['level'] === 'user') {
                header("Location: user/dashboard.php");
            } elseif ($row['level'] === 'dosen') {
                header("Location: dosen/dashboard.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Login Page</title>
    <!-- MDB icon -->
    <link rel="icon" href="" type="image/x-icon" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="assets/Bootstrap/css/mdb.min.css" />
    <style>
    /* Gaya untuk background gambar */
    body {
        background: url('assets/img/background.jpg') no-repeat center center fixed;
        background-size: cover;
        min-height: 100dvh;
        margin: 0;
        overflow-x: hidden;
    }

    image {
        margin-left: 50px;
    }

    /* Overlay dengan efek blur */
    .background-blur {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        background-color: rgba(0, 0, 0, 0.2);
        /* Lebih terang dari sebelumnya (0.2) */
        z-index: -1;
    }

    @media (max-width: 1024px) {
        .background-blur {
            backdrop-filter: blur(6px);
            /* Blur lebih kecil dari sebelumnya */
            -webkit-backdrop-filter: blur(6px);
            background-color: rgba(0, 0, 0, 0.2);
            /* Warna latar belakang tetap lebih terang */
        }
    }

    @media (max-width: 768px) {
        .background-blur {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            background-color: rgba(0, 0, 0, 0.25);
            /* Sedikit lebih gelap dari desktop */
        }
    }

    @media (max-width: 480px) {
        .background-blur {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background-color: rgba(0, 0, 0, 0.3);
            /* Lebih gelap pada layar kecil agar kontras lebih baik */
        }
    }

    /* Bagian tengah */
    .centered-section {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100dvh;
        position: relative;
        z-index: 1;
    }

    .card {
        max-width: 800px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
    }

    .form-outline .form-control {
        background-color: #f8f9fa;
    }

    .card img {
        border-radius: 0.5rem 0 0 0.5rem;
        object-fit: cover;
        height: 100%;
    }
    </style>
</head>

<body>
    <!-- Overlay blur -->
    <div class="background-blur"></div>

    <!-- Section: Design Block -->
    <section class="centered-section">
        <div class="card mb-3">
            <div class="row g-0 d-flex align-items-center">
                <div class="col-lg-4 d-none d-lg-flex">
                    <img src="assets/img/login2.png" alt="Login Image"
                        class="w-100 rounded-t-5 rounded-tr-lg-0 rounded-bl-lg-5" />
                </div>
                <div class="col-lg-8">
                    <div class="card-body py-5 px-md-5">
                        <h2 class="text-uppercase text-center mb-5">Form Login</h2>
                        <form action="" method="post">
                            <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $error; ?>
                            </div>
                            <?php endif; ?>

                            <!-- Username input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" name="username" id="form2Example1" class="form-control" />
                                <label class="form-label" for="form2Example1">Username</label>
                            </div>

                            <!-- Password input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="password" name="password" id="form2Example2" class="form-control" />
                                <label class="form-label" for="form2Example2">Password</label>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" name="login" class="btn btn-primary btn-block mb-4">
                                Login
                            </button>

                            <div class="col d-flex justify-content-center">
                                <a href="registrasi.php">Belum Punya Akun? Daftar disini</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Design Block -->

    <!-- MDB -->
    <script type="text/javascript" src="assets/Bootstrap/js/mdb.umd.min.js"></script>
    <!-- Custom scripts -->
    <script type="text/javascript"></script>
</body>

</html>