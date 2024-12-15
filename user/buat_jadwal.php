<?php
include 'header.php';
include '../assets/conn/koneksi.php';

session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}

$id_user = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (!$id_user) {
    echo "Anda harus login untuk melanjutkan.";
    exit;
}
$queryUser = "SELECT nama_lengkap, npm FROM users WHERE id = '$id_user'";
$resultUser = mysqli_query($koneksi, $queryUser);
$userData = mysqli_fetch_assoc($resultUser);

// Ambil daftar dosen dari tabel daftar_dosen
$queryDosen = "SELECT id, nama FROM daftar_dosen";
$resultDosen = mysqli_query($koneksi, $queryDosen);

// Variabel untuk menampung data
$dosen_selected = isset($_POST['id_dosen']) ? mysqli_real_escape_string($koneksi, $_POST['id_dosen']) : null;
$jadwal_dosen = [];
$dosen_nama = "";
$unique_hari = [];
$unique_jam = [];
$unique_ruangan = [];

// Proses pilihan dosen
if ($dosen_selected) {
    $queryJadwal = "
        SELECT jd.hari, jd.jam, jd.ruangan, dd.nama AS nama_dosen
        FROM jadwal_dosen jd
        INNER JOIN daftar_dosen dd ON jd.id_dosen = dd.id
        WHERE jd.id_dosen = '$dosen_selected'
    ";
    $resultJadwal = mysqli_query($koneksi, $queryJadwal);

    while ($row = mysqli_fetch_assoc($resultJadwal)) {
        $jadwal_dosen[] = $row;
        $dosen_nama = $row['nama_dosen'];

        if (!in_array($row['hari'], $unique_hari)) $unique_hari[] = $row['hari'];
        if (!in_array($row['jam'], $unique_jam)) $unique_jam[] = $row['jam'];
        if (!in_array($row['ruangan'], $unique_ruangan)) $unique_ruangan[] = $row['ruangan'];
    }
}

// Proses pengiriman data ke database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_mahasiswa'], $_POST['hari'], $_POST['jam'], $_POST['tanggal'], $_POST['materi'])) {
    $nama_mahasiswa = mysqli_real_escape_string($koneksi, $_POST['nama_mahasiswa']);
    $npm = mysqli_real_escape_string($koneksi, $_POST['npm']);
    $hari = mysqli_real_escape_string($koneksi, $_POST['hari']);
    $jam = mysqli_real_escape_string($koneksi, $_POST['jam']);
    $tanggal_bimbingan = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $materi = mysqli_real_escape_string($koneksi, $_POST['materi']);
    $ruangan = mysqli_real_escape_string($koneksi, implode(', ', $unique_ruangan));
// Cek jadwal di hari, jam, dan tanggal yang sama dengan dosen tersebut
$queryCekJadwal = "
    SELECT COUNT(*) AS jumlah 
    FROM jadwal_bimbingan 
    WHERE hari = '$hari' AND jam = '$jam' AND tanggal = '$tanggal_bimbingan' AND nama_dosen = '$dosen_nama'
";
$resultCekJadwal = mysqli_query($koneksi, $queryCekJadwal);
$dataCekJadwal = mysqli_fetch_assoc($resultCekJadwal);

if ($dataCekJadwal['jumlah'] > 0) {
    echo "<script>alert('Jadwal bimbingan pada hari $hari jam $jam dengan dosen $dosen_nama sudah ada. Silakan pilih jam yang berbeda.'); window.location.href='jadwal_bimbingan.php';</script>";
    exit;
}

    // Cek batas maksimal bimbingan dengan dosen tertentu
    $queryCekBimbingan = "
        SELECT COUNT(*) AS jumlah 
        FROM jadwal_bimbingan 
        WHERE id_user = '$id_user' AND nama_dosen = '$dosen_nama'
    ";
    $resultCekBimbingan = mysqli_query($koneksi, $queryCekBimbingan);
    $dataCekBimbingan = mysqli_fetch_assoc($resultCekBimbingan);

    if ($dataCekBimbingan['jumlah'] >= 5) {
        echo "<script>alert('Anda sudah mencapai maksimal bimbingan dengan dosen $dosen_nama sebanyak 5 kali.'); window.location.href='jadwal_bimbingan.php';</script>";
        exit;
    }

    // Cek batas maksimal jadwal di tanggal tertentu dengan dosen yang sama
    $queryCekTanggal = "
        SELECT COUNT(*) AS jumlah 
        FROM jadwal_bimbingan 
        WHERE tanggal = '$tanggal_bimbingan' AND nama_dosen = '$dosen_nama'
    ";
    $resultCekTanggal = mysqli_query($koneksi, $queryCekTanggal);
    $dataCekTanggal = mysqli_fetch_assoc($resultCekTanggal);

    if ($dataCekTanggal['jumlah'] >= 5) {
        echo "<script>alert('Tanggal ini sudah penuh (maksimal 5 jadwal) dengan dosen $dosen_nama. Silakan pilih tanggal lain.'); window.location.href='jadwal_bimbingan.php';</script>";
        exit;
    }

    // Masukkan data ke dalam jadwal bimbingan
    $queryInsert = "
    INSERT INTO jadwal_bimbingan (id_user, nama_dosen, nama_mahasiswa, npm, hari, jam, tanggal, ruangan, materi, created_at) 
    VALUES ('$id_user', '$dosen_nama', '$nama_mahasiswa', '$npm', '$hari', '$jam', '$tanggal_bimbingan', '$ruangan', '$materi', NOW())
";
    if (mysqli_query($koneksi, $queryInsert)) {
        $pesan_notifikasi = "Jadwal bimbingan baru untuk mahasiswa $nama_mahasiswa pada hari $hari jam $jam telah berhasil dibuat.";
        
        $queryNotifikasi = "
            INSERT INTO notifikasi (nama_dosen, nama_mahasiswa, pesan, status) 
            VALUES ('$dosen_nama', '$nama_mahasiswa', '$pesan_notifikasi', 'unread')
        ";

        if (mysqli_query($koneksi, $queryNotifikasi)) {
            echo "<script>alert('Data berhasil disimpan dan notifikasi dikirim!'); window.location.href='jadwal_bimbingan.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat mengirim notifikasi.');</script>";
        }
    } else {
        echo "<script>alert('Terjadi kesalahan saat menyimpan data.');</script>";
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
                    <h1>Buat Jadwal Dengan Dosen</h1>
                    <hr>

                    <!-- Form untuk memilih dosen -->
                    <form class="form-horizontal" method="POST" action="">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Pilih Dosen:</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="id_dosen" required onchange="this.form.submit()">
                                    <option value="">-- Pilih Nama Dosen --</option>
                                    <?php while ($row = mysqli_fetch_assoc($resultDosen)) : ?>
                                    <option value="<?= $row['id']; ?>"
                                        <?= $dosen_selected == $row['id'] ? 'selected' : ''; ?>>
                                        <?= $row['nama']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </form>

                    <?php if ($dosen_selected): ?>
                    <!-- Form untuk menampilkan data jadwal dosen -->
                    <form class="form-horizontal" method="POST" action="">

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Nama Dosen:</label>
                            <div class="col-lg-8">
                                <input class="form-control" type="text" value="<?= htmlspecialchars($dosen_nama); ?>"
                                    disabled>
                                <input type="hidden" name="id_dosen" value="<?= $dosen_selected; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Nama Mahasiswa:</label>
                            <div class="col-lg-8">
                                <input class="form-control" name="nama_mahasiswa" type="text"
                                    value="<?= htmlspecialchars($userData['nama_lengkap']); ?>" disabled>
                                <input type="hidden" name="nama_mahasiswa"
                                    value="<?= htmlspecialchars($userData['nama_lengkap']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">NPM:</label>
                            <div class="col-lg-8">
                                <input class="form-control" name="npm" type="text"
                                    value="<?= htmlspecialchars($userData['npm']); ?>" disabled>
                                <input type="hidden" name="npm" value="<?= htmlspecialchars($userData['npm']); ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-3 control-label">Hari:</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="hari" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <?php foreach ($unique_hari as $hari): ?>
                                    <option value="<?= htmlspecialchars($hari); ?>"><?= htmlspecialchars($hari); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tanggal:</label>
                            <div class="col-lg-8">
                                <input class="form-control" type="date" name="tanggal" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Jam:</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="jam" required>
                                    <option value="">-- Pilih Jam --</option>
                                    <?php foreach ($unique_jam as $jam): ?>
                                    <option value="<?= htmlspecialchars($jam); ?>"><?= htmlspecialchars($jam); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Ruangan:</label>
                            <div class="col-lg-8">
                                <ul class="list-group">
                                    <?php foreach ($unique_ruangan as $ruangan): ?>
                                    <li class="list-group-item"><?= htmlspecialchars($ruangan); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Materi:</label>
                            <div class="col-lg-8">
                                <textarea class="form-control" name="materi" placeholder="Masukkan Materi" rows="3"
                                    required></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 offset-md-3">
                                <button type="submit" class="btn btn-primary">Buat Jadwal</button>
                                <a href="jadwal_bimbingan.php" class="btn btn-default">Kembali</a>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>