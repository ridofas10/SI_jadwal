<?php
include 'header.php';
require '../assets/conn/koneksi.php'; // Koneksi ke database

session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
    echo "
    <script>
        alert('ID dosen tidak ditemukan!');
        window.location.href = 'daftar_dosen.php'; // Redirect jika ID tidak valid
    </script>";
    exit;
}

// Ambil data dosen berdasarkan ID
$query = "SELECT * FROM daftar_dosen WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "
    <script>
        alert('Data dosen tidak ditemukan!');
        window.location.href = 'daftar_dosen.php'; // Redirect jika data tidak ditemukan
    </script>";
    exit;
}

$dosen = mysqli_fetch_assoc($result);

// Jika tombol Simpan ditekan
if (isset($_POST['update'])) {
    $nip = trim($_POST['nip']);
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $bidang = trim($_POST['bidang']);

    // Validasi input
    if (empty($nip) || empty($nama) || empty($alamat) || empty($bidang)) {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    } else {
        // Perbarui data dosen di database
        $queryUpdate = "UPDATE daftar_dosen 
                        SET nip = '$nip', nama = '$nama', alamat = '$alamat', bidang = '$bidang' 
                        WHERE id = '$id'";

        if (mysqli_query($koneksi, $queryUpdate)) {
            echo "
            <script>
                alert('Data dosen berhasil diperbarui!');
                window.location.href = 'daftar_dosen.php'; // Redirect setelah berhasil
            </script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
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
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">

                    <div class="container">
                        <h1>Edit Data Dosen</h1>
                        <hr>
                        <form class="form-horizontal" method="POST" action="">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">NIP:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="nip" type="text"
                                        value="<?= htmlspecialchars($dosen['nip']); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Nama:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="nama" type="text"
                                        value="<?= htmlspecialchars($dosen['nama']); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Alamat:</label>
                                <div class="col-lg-8">
                                    <textarea class="form-control" name="alamat" rows="3"
                                        required><?= htmlspecialchars($dosen['alamat']); ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Bidang:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="bidang" type="text"
                                        value="<?= htmlspecialchars($dosen['bidang']); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-8">
                                    <input type="submit" name="update" class="btn btn-primary" value="Simpan">
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
<?php mysqli_close($koneksi); ?>