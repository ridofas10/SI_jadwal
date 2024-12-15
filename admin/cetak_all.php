<?php
require '../assets/conn/koneksi.php'; // Koneksi ke database

// Query untuk mengambil semua data jadwal bimbingan
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
    ORDER BY jb.hari, jb.jam
";

$result = mysqli_query($koneksi, $query);

// Error handling jika query gagal
if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Bimbingan</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    img {
        width: 110px;
        height: 110px;
        float: left;
        margin-right: 2px;
    }

    h1 {
        text-align: center;
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

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 8px;
        text-align: center;
    }

    .kop .form-number {
        font-size: 12px;
    }

    .tanda-tangan {
        text-align: right;
        margin-top: 50px;
    }
    </style>
    <script>
    // Trigger dialog print saat halaman dimuat
    window.onload = function() {
        window.print();
    };
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
    <div class="header">
        <h1>Rekap Jadwal Bimbingan</h1>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Dosen</th>
                <th>Nama Mahasiswa</th>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Ruangan</th>
                <th>Materi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo '
                <tr>
                    <td style="text-align: center;">' . $no++ . '</td>
                    <td>' . htmlspecialchars($row['nama_dosen']) . '</td>
                    <td>' . htmlspecialchars($row['nama_mahasiswa']) . '</td>
                    <td>' . htmlspecialchars($row['hari']) . '</td>
                    <td style="text-align: center;">' . htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))) . '</td>
                    <td style="text-align: center;">' . htmlspecialchars($row['jam']) . '</td>
                    <td>' . htmlspecialchars($row['ruangan']) . '</td>
                    <td>' . htmlspecialchars($row['materi']) . '</td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
    <!-- Tanda Tangan -->
    <div class="tanda-tangan">
        <p>Kediri, <?= date('d-m-Y'); ?></p><br><br><br>
    </div>
</body>

</html>