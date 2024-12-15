<?php
include 'header.php';
require '../assets/conn/koneksi.php';
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Validasi parameter id_dosen
if (!isset($_GET['id_dosen']) || empty($_GET['id_dosen'])) {
    die("Error: Parameter id_dosen tidak ditemukan.");
}

// Ambil id_dosen dari URL
$id_dosen = intval($_GET['id_dosen']);

// Query untuk mengambil jadwal dosen berdasarkan id_dosen
$query = "
    SELECT jd.id, jd.hari, jd.jam, jd.ruangan, dd.nama AS nama_dosen
    FROM jadwal_dosen jd
    JOIN daftar_dosen dd ON jd.id_dosen = dd.id
    WHERE jd.id_dosen = $id_dosen
";
$result = mysqli_query($koneksi, $query);

// Validasi hasil query
if (!$result || mysqli_num_rows($result) === 0) {
    die("Error: Tidak ada data untuk id_dosen ini.");
}

// Ambil nama dosen
$dosen = mysqli_fetch_assoc($result)['nama_dosen'];
mysqli_data_seek($result, 0);
?>
<!-- Page Wrapper -->
<div id="wrapper">
    <?php include'sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
            <?php include 'navbar.php'; ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4"></div>
                <div class="container">
                    <h4>Edit Jadwal Dosen: <?= htmlspecialchars($dosen); ?></h4>
                    <form action="proses_edit_jadwal_dosen.php" method="POST">
                        <input type="hidden" name="id_dosen" value="<?= $id_dosen; ?>">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Ruangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td><input type="text" name="hari[]" class="form-control"
                                            value="<?= htmlspecialchars($row['hari']); ?>"></td>
                                    <td><input type="text" name="jam[]" class="form-control"
                                            value="<?= htmlspecialchars($row['jam']); ?>"></td>
                                    <td><input type="text" name="ruangan[]" class="form-control"
                                            value="<?= htmlspecialchars($row['ruangan']); ?>"></td>
                                    <input type="hidden" name="id[]" value="<?= $row['id']; ?>">
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="daftar_jadwal_dosen.php" class="btn btn-default">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<?php mysqli_close($koneksi); ?>