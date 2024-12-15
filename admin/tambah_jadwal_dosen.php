<?php
include 'header.php';
require '../assets/conn/koneksi.php'; // Koneksi ke database

session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Ambil daftar dosen dari tabel daftar_dosen
$queryDosen = "SELECT id, nama FROM daftar_dosen";
$resultDosen = mysqli_query($koneksi, $queryDosen);

if (isset($_POST["save"])) {
    if (tambahJadwal($_POST) > 0) {
        echo "
        <script>
            alert('Data jadwal dosen berhasil ditambahkan!');
            window.location.href = 'daftar_jadwal_dosen.php'; // Ubah ke halaman daftar jadwal dosen setelah berhasil
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Gagal menambahkan data jadwal dosen!');
        </script>
        ";
    }
}

function tambahJadwal($data) {
    global $koneksi;

    // Ambil data dari form
    $id_dosen = $data["id_dosen"];
    $hari = $data["hari"];
    $jam = $data["jam"];
    $ruangan = trim($data["ruangan"]);

    // Validasi input
    if (empty($id_dosen) || empty($hari) || empty($jam) || empty($ruangan)) {
        echo "<script>alert('Semua field wajib diisi!');</script>";
        return 0;
    }

    // Ambil nama dosen dari tabel daftar_dosen berdasarkan id_dosen
    $queryDosen = "SELECT nama FROM daftar_dosen WHERE id = '$id_dosen'";
    $resultDosen = mysqli_query($koneksi, $queryDosen);
    if ($row = mysqli_fetch_assoc($resultDosen)) {
        $nama_dosen = $row['nama'];
    } else {
        echo "<script>alert('Dosen tidak ditemukan!');</script>";
        return 0;
    }

    // Masukkan data hari dan jam ke tabel
    foreach ($hari as $h) {
        foreach ($jam as $j) {
            $query = "INSERT INTO jadwal_dosen (id_dosen, nama_dosen, hari, jam, ruangan) 
                      VALUES ('$id_dosen', '$nama_dosen', '$h', '$j', '$ruangan')";
            if (!mysqli_query($koneksi, $query)) {
                echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
                return 0;
            }
        }
    }

    return 1; // Berhasil
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
                        <h1>Tambah Data Jadwal Dosen</h1>
                        <hr>
                        <form class="form-horizontal" method="POST" action="">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Nama Dosen:</label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="id_dosen" required>
                                        <option value="">-- Pilih Nama Dosen --</option>
                                        <?php while ($row = mysqli_fetch_assoc($resultDosen)) : ?>
                                        <option value="<?= $row['id']; ?>"><?= $row['nama']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Hari:</label>
                                <div class="col-lg-8">
                                    <?php
                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                                    foreach ($days as $day) :
                                    ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="hari[]"
                                            value="<?= $day; ?>" id="<?= $day; ?>">
                                        <label class="form-check-label" for="<?= $day; ?>">
                                            <?= $day; ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Jam:</label>
                                <div class="col-lg-8">
                                    <?php for ($hour = 8; $hour <= 16; $hour++) : // Mulai dari jam 8 hingga 16 ?>
                                    <?php $time = sprintf("%02d:%02d", $hour, 0); // Set menit ke 00 ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="jam[]"
                                            value="<?= $time; ?>" id="jam-<?= $time; ?>">
                                        <label class="form-check-label" for="jam-<?= $time; ?>">
                                            <?= $time; ?>
                                        </label>
                                    </div>
                                    <?php endfor; ?>
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Ruangan:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="ruangan" type="text" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-8">
                                    <input type="submit" name="save" class="btn btn-primary" value="Simpan">
                                    <span></span>
                                    <a href="daftar_jadwal_dosen.php" class="btn btn-default">Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>