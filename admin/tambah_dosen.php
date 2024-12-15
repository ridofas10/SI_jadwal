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
    if(tambahDosen($_POST) > 0) {
        echo "
        <script>
            alert('Data dosen berhasil ditambahkan!');
            window.location.href = 'daftar_dosen.php'; // Ubah ke halaman daftar dosen setelah berhasil
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Gagal menambahkan data dosen!');
        </script>
        ";
    }
}

function tambahDosen($data) {
    global $koneksi;

    // Ambil data dari form
    $nip = trim($data["nip"]);
    $nama = trim($data["nama"]);
    $alamat = trim($data["alamat"]);
    $bidang = trim($data["bidang"]);

    // Validasi input
    if (empty($nip) || empty($nama) || empty($alamat) || empty($bidang)) {
        echo "<script>alert('Semua field wajib diisi!');</script>";
        return 0;
    }

    // Cek apakah NIP sudah ada
    $result = mysqli_query($koneksi, "SELECT nip FROM daftar_dosen WHERE nip = '$nip'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('NIP sudah terdaftar!');</script>";
        return 0;
    }

    // Tambahkan data dosen baru ke database
    $query = "INSERT INTO daftar_dosen (nip, nama, alamat, bidang) 
              VALUES ('$nip', '$nama', '$alamat', '$bidang')";

    if (mysqli_query($koneksi, $query)) {
        return mysqli_affected_rows($koneksi); // Berhasil
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
        return 0; // Gagal
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
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">

                    <div class="container">
                        <h1>Tambah Data Dosen</h1>
                        <hr>
                        <form class="form-horizontal" method="POST" action="">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">NIP/NIK:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="nip" type="text" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Nama:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="nama" type="text" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Alamat:</label>
                                <div class="col-lg-8">
                                    <textarea class="form-control" name="alamat" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Bidang:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="bidang" type="text" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-8">
                                    <input type="submit" name="save" class="btn btn-primary" value="Simpan">
                                    <span></span>
                                    <a href="daftar_dosen.php" class="btn btn-default">Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>