<?php
session_start();
require '../assets/conn/koneksi.php'; // Koneksi ke database

// Ambil ID dari URL
$id_jadwal = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id_jadwal) {
    echo "ID Jadwal tidak ditemukan.";
    exit;
}

// Query untuk mengambil data jadwal bimbingan berdasarkan id yang diklik
$query = "
    SELECT 
        jb.id,
        jb.nama_dosen,
        jb.nama_mahasiswa,
        jb.hari,
        jb.tanggal,
        jb.jam,
        jb.ruangan,
        jb.materi
    FROM jadwal_bimbingan jb
    WHERE jb.id = '$id_jadwal'
";
$result = mysqli_query($koneksi, $query);

// Error handling jika query gagal
if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

$row = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan
if (!$row) {
    echo "Data tidak ditemukan.";
    exit;
}

mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengantar Bimbingan</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 20px;
    }

    img {
        width: 110px;
        height: 110px;
        float: left;
        margin-right: 2px;
    }

    h3 {
        margin-top: -0px;
    }

    h4 {
        text-align: center;
    }

    .garis1 {
        margin-top: -6em;
        border-top: 3px solid black;
        height: 2px;
        border-bottom: 1px solid black;
    }

    h5 {
        margin-top: -0px;
    }

    h3 {
        text-align: center;
    }

    h6 {
        margin-top: 5px;
        text-align: center;
        margin-right: 0px;
        font-weight: normal;
    }

    .judul-surat {
        text-align: center;
        font-weight: bold;
        margin-top: -5px;
        text-decoration: underline;
    }

    .isi-surat {
        margin-top: -10px;
        line-height: 1.8;
        font-size: 15px;
    }

    .informasi-bimbingan p {
        line-height: 1.8;
        margin-top: -10px;
    }

    .tanda-tangan {
        text-align: right;
        margin-top: 50px;
    }

    .detail-info {
        margin-left: 60px;
        margin-top: -10px;
        line-height: 1.8;
    }

    .detail-info p {
        font-size: 15px;
        margin-top: -10px;
    }

    .detail-info span {
        font-weight: bold;
        margin-top: -10px;
    }
    </style>

    <script>
    function printPage() {
        window.print();
    }
    </script>
</head>

<body>

    <nav>
        <img src="../assets/img/uniska.png" />
        <div class="judul">
            <h4>YAYASAN BINA CENDEKIA MUSLIM PANCASILA KEDIRI
                <h3>FAKULTAS TEKNIK <br>PRODI TEKNIK KOMPUTER<br>UNIVERSITAS ISLAM KADIRI
                    <h6>Jl. Sersan Suharmadji No. 38 Telp. (0354) 684651 – 683243 Fax. 699057 Kediri (64128)<br>
                        Website: <a href="https://ft.uniska-kediri.ac.id/">https://ft.uniska-kediri.ac.id/</a>
                    </h6>
                </h3>
            </h4>
        </div>
    </nav>
    <br><br>
    <div class="garis1"></div><br>
    <!-- Judul Surat -->
    <h2 class="judul-surat">PENGANTAR BIMBINGAN</h2>

    <!-- Isi Surat -->
    <div class="isi-surat">
        <p>Kepada Yth,</p>
        <p><strong><?= htmlspecialchars($row['nama_dosen']); ?></strong></p>
        <p>Di tempat</p>

        <p>Dengan hormat,</p>

        <p>Sehubungan dengan pelaksanaan bimbingan akademik, bersama ini kami sampaikan jadwal bimbingan untuk mahasiswa
            berikut:</p>

        <!-- Informasi Bimbingan -->
        <div class="detail-info">
            <p>Nama Mahasiswa&nbsp;: <span><?= htmlspecialchars($row['nama_mahasiswa']); ?></span></p>
            <p>Nama
                Dosen&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                <span><?= htmlspecialchars($row['nama_dosen']); ?></span>
            </p>
            <p>Hari&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                <span><?= htmlspecialchars($row['hari']); ?></span>
            </p>
            <p>Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                <span><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))); ?></span>
            </p>
            <p>Jam&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                <span><?= htmlspecialchars($row['jam']); ?></span>
            </p>
            <p>Ruangan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                <span><?= htmlspecialchars($row['ruangan']); ?></span>
            </p>
            <p>Materi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                <span><?= htmlspecialchars($row['materi']); ?></span>
            </p>
        </div>

        <p>Demikian surat pengantar bimbingan ini dibuat. Atas perhatiannya, kami ucapkan terima kasih.</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="tanda-tangan">
        <p>Kediri, <?= date('d-m-Y'); ?></p>
        <br><br>
        <p><strong><?= htmlspecialchars($row['nama_mahasiswa']); ?></strong></p>
    </div>
</body>

</html>