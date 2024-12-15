<?php 
include 'header.php';
require '../assets/conn/koneksi.php';

session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Ambil ID jadwal dari URL
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID tidak ditemukan.";
    exit;
}

// Query untuk mengambil data jadwal berdasarkan ID
$query = "SELECT * FROM jadwal_bimbingan WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tangkap data dari form
    $materi = mysqli_real_escape_string($koneksi, $_POST['materi']);
    
    // Validasi input
    if (empty($materi)) {
        $error = "Materi tidak boleh kosong.";
    } else {
        // Update data di database
        $updateQuery = "UPDATE jadwal_bimbingan SET materi = '$materi' WHERE id = '$id'";
        if (mysqli_query($koneksi, $updateQuery)) {
            echo "<script>alert('Data berhasil diperbarui!'); window.location.href='rekap_bimbingan.php';</script>";
        } else {
            $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
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
                <h1 class="h3 mb-4 text-gray-800">Edit Materi Bimbingan</h1>

                <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="form-group">
                        <label for="materi">Materi Bimbingan</label>
                        <textarea name="materi" id="materi" class="form-control" rows="5"
                            required><?= htmlspecialchars($data['materi']) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="rekap_bimbingan.php" class="btn btn-secondary">Batal</a>
                </form>

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->