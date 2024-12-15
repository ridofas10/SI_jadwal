<?php
require '../assets/conn/koneksi.php'; // Koneksi ke database

// Memulai sesi
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['nama_lengkap'])) {
    die("Anda belum login. Silakan login terlebih dahulu.");
}

// Ambil nama dosen dari sesi
$nama_lengkap = $_SESSION['nama_lengkap'];

// Cek apakah id_user tersedia
if (!isset($_GET['id_user'])) {
    die("ID User tidak ditemukan.");
}

$id_user = mysqli_real_escape_string($koneksi, $_GET['id_user']);

// ** Cek jumlah bimbingan terlebih dahulu **
$query_cek_bimbingan = "
    SELECT COUNT(*) AS jumlah_bimbingan 
    FROM jadwal_bimbingan 
    WHERE id_user = '$id_user' AND nama_dosen = '$nama_lengkap'
";
$result_cek_bimbingan = mysqli_query($koneksi, $query_cek_bimbingan);
$data_bimbingan = mysqli_fetch_assoc($result_cek_bimbingan);
$jumlah_bimbingan = $data_bimbingan['jumlah_bimbingan'] ?? 0;

// Query untuk mengambil nama mahasiswa berdasarkan id_user dan nama_dosen
$user_query = "
    SELECT DISTINCT nama_mahasiswa 
    FROM jadwal_bimbingan 
    WHERE id_user = '$id_user' AND nama_dosen = '$nama_lengkap'
";
$user_result = mysqli_query($koneksi, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
$nama_mahasiswa = $user_data['nama_mahasiswa'] ?? 'Tidak Diketahui';

// Jika jumlah bimbingan kurang dari 3, tampilkan alert dan kembalikan ke halaman rekap_bimbingan.php
if ($jumlah_bimbingan < 3) {
    echo "<script>
        alert('Bimbingan atas nama mahasiswa " . addslashes($nama_mahasiswa) . " belum memenuhi syarat minimal 3 kali.');
        window.location.href = 'rekap_bimbingan.php?pesan=belum_cukup';
    </script>";
    exit();
}



// Query untuk mengambil data bimbingan berdasarkan id_user dan nama_dosen
$query = "
    SELECT 
        id, id_user, nama_dosen, nama_mahasiswa, hari, tanggal, jam, ruangan, materi
    FROM jadwal_bimbingan
    WHERE id_user = '$id_user' AND nama_dosen = '$nama_lengkap'
    ORDER BY tanggal, jam
";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

// Ambil nama mahasiswa untuk header
$user_query = "
    SELECT DISTINCT nama_mahasiswa, npm 
    FROM jadwal_bimbingan 
    WHERE id_user = '$id_user' AND nama_dosen = '$nama_lengkap'
";
$user_result = mysqli_query($koneksi, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
$nama_mahasiswa = $user_data['nama_mahasiswa'] ?? 'Tidak Diketahui';
$npm = $user_data['npm'] ?? 'Tidak Diketahui';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Bimbingan</title>
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
    <h3>Kartu Bimbingan Mahasiswa</h3>
    <p><strong>Nama Mahasiswa :</strong> <?php echo htmlspecialchars($nama_mahasiswa); ?></p>
    <p><strong>NPM :</strong> <?php echo htmlspecialchars($npm); ?></p>
    <p><strong>Program Studi :</strong> Teknik Komputer</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Tema Bimbingan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
    if (mysqli_num_rows($result) > 0) {
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))) . '</td>';
            echo '<td>' . htmlspecialchars($row['materi']) . '</td>';
            echo '<td>OKE</td>'; 
            echo '</tr>';
        }

        // Tambahkan baris baru setelah perulangan selesai
        echo '<tr>';
        echo '<td colspan="3" style="text-align: center; font-weight: bold;">BIMBINGAN SELESAI</td>';
        echo '<td><strong>ACC</strong></td>';
        echo '</tr>';

    } else {
        echo '<tr><td colspan="6">Tidak ada data bimbingan untuk dosen ini.</td></tr>';
    }
    ?>
        </tbody>
    </table>
    <!-- Tanda Tangan -->
    <div class="tanda-tangan">
        <p>Kediri, <?= date('d-m-Y'); ?></p><br><br><br>
        <p><strong><?php echo htmlspecialchars($nama_lengkap); ?></strong></p>

    </div>
</body>

</html>

<?php
// Menutup koneksi database
mysqli_close($koneksi);
?>