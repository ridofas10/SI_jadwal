<?php 
session_start();

require 'functions.php';

if(isset($_POST["register"])) {

    if(registrasi($_POST)>0) {
        echo "
        <script>
            alert('user baru berhasil ditambahkan');
        </script>
        
        ";
    } else {
     echo mysqli_error($koneksi);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Registrasi</title>
    <link rel="stylesheet" href="modal.css" />
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
    <section class="vh-100 bg-image">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Daftar Akun</h2>

                                <form action="" method="POST">

                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <input type="text" name="nama_lengkap" id="form3Example1cg"
                                            class="form-control form-control-lg" required />
                                        <label class="form-label" for="form3Example1cg">Nama Lengkap</label>
                                    </div>
                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <input type="text" name="npm" id="form3Example1cg"
                                            class="form-control form-control-lg" required />
                                        <label class="form-label" for="form3Example1cg">NPM</label>
                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <input type="text" name="username" id="form3Example3cg"
                                            class="form-control form-control-lg" required />
                                        <label class="form-label" for="form3Example3cg">Username</label>
                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <input type="password" name="password" id="form3Example4cg"
                                            class="form-control form-control-lg" required />
                                        <label class="form-label" for="form3Example4cg">Password</label>
                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <input type="password" name="password2" id="form3Example4cdg"
                                            class="form-control form-control-lg" required />
                                        <label class="form-label" for="form3Example4cdg">Ulangi Password</label>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <button type="submit" name="register"
                                            class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">
                                            Daftar
                                        </button>
                                    </div>

                                    <p class="text-center text-muted mt-5 mb-0">Sudah Daftar? <a href="index.php"
                                            class="fw-bold text-body"><u>Login Disini</u></a></p>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- End your project here -->

    <!-- MDB -->
    <script type="text/javascript" src="assets/Bootstrap/js/mdb.umd.min.js"></script>
    <!-- Custom scripts -->
    <script type="text/javascript"></script>
</body>

</html>