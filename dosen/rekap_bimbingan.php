<?php
include 'header.php';
require '../assets/conn/koneksi.php';

session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Cek apakah user sudah login dan memiliki level 'dosen'
if (!isset($_SESSION['nama_lengkap']) || !isset($_SESSION['level']) || $_SESSION['level'] !== 'dosen') {
    echo "<div style='text-align: center; margin-top: 20%;'>
            <h2>Anda tidak memiliki akses ke halaman ini.</h2>
            <a href='login.php' style='color: blue; text-decoration: underline;'>Kembali ke Login</a>
          </div>";
    exit();
}

// Ambil nama dosen dari session
$dosen = $_SESSION['nama_lengkap'];

// Query untuk mengambil data jadwal_bimbingan yang sesuai dengan nama_dosen
$query = "
    SELECT 
        jb.id, -- Pastikan kolom ini sesuai dengan yang digunakan
        jb.id_user,
        jb.nama_dosen,
        jb.nama_mahasiswa,
        jb.hari,
        jb.tanggal,
        jb.jam,
        jb.ruangan,
        jb.materi,
        jb.created_at
    FROM jadwal_bimbingan jb
    WHERE jb.nama_dosen = '$dosen'
    ORDER BY jb.hari, jb.jam
";



$result = mysqli_query($koneksi, $query);

// Error handling jika query gagal
if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

// Pesan notifikasi
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'sukses') {
        echo "<div class='alert alert-success'>Data jadwal bimbingan berhasil dihapus.</div>";
    } elseif ($_GET['pesan'] == 'error') {
        echo "<div class='alert alert-danger'>Terjadi kesalahan saat menghapus data jadwal bimbingan.</div>";
    } elseif ($_GET['pesan'] == 'edit_sukses') {
        echo "<div class='alert alert-success'>Data jadwal bimbingan berhasil diperbarui.</div>";
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
                <h4>Rekap Bimbingan</h4><br>
                <!-- Input Pencarian -->
                <div class="input-group rounded">
                    <input type="search" class="form-control rounded" id="search-input" placeholder="Cari... "
                        aria-label="Search" aria-describedby="search-addon" />
                    <span class="input-group-text border-0" id="search-addon">
                        <i class="fas fa-search"></i>
                    </span>
                </div>

                <!-- Tabel Daftar Jadwal Bimbingan -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div class="table-responsive">
                        <table class="table table-striped" id="jadwal-table">
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
                                    <th>Reservasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                            $no = 1; // Variabel untuk nomor urut
                            while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_dosen']); ?></td>
                                    <td><?= htmlspecialchars($row['nama_mahasiswa']); ?></td>
                                    <td><?= htmlspecialchars($row['hari']); ?></td>
                                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))); ?></td>
                                    <!-- Menampilkan Tanggal -->
                                    <td><?= htmlspecialchars($row['jam']); ?></td>
                                    <td><?= htmlspecialchars($row['ruangan']); ?></td>
                                    <td><?= htmlspecialchars($row['materi']); ?></td>
                                    <td><?= htmlspecialchars(date('d-m-Y H:i:s', strtotime($row['created_at']))); ?>
                                    </td>
                                    <td>
                                        <a href="cetak.php?id_user=<?= urlencode($row['id_user']); ?>"
                                            class="btn btn-primary btn-sm">
                                            <i class="fas fa-print">&nbsp;</i> Cetak Kartu Bimbingan
                                        </a>
                                        <a href="edit_materi.php?id=<?= urlencode($row['id']); ?>"
                                            class="btn btn-success btn-sm">
                                            <i class="fas fa-edit">&nbsp;</i> Edit Materi
                                        </a>
                                        <a href="hapus_rekap.php?id=<?= urlencode($row['id']); ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash">&nbsp;</i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination" id="pagination">
                        <!-- Pagination items akan dibuat secara dinamis menggunakan JavaScript -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script>
    // Fungsi pencarian tabel
    document.getElementById('search-input').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#jadwal-table tbody tr');

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });

    // Pagination untuk tabel menggunakan JavaScript
    const rowsPerPage = 5;
    const table = document.getElementById('jadwal-table');
    const tableBody = table.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');
    const pagination = document.getElementById('pagination');
    let currentPage = 1;

    function displayRows() {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, index) => {
            row.style.display = index >= start && index < end ? '' : 'none';
        });
    }

    function setupPagination() {
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        pagination.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = i;
                updatePagination();
                displayRows();
            });
            pagination.appendChild(pageItem);
        }
    }

    function updatePagination() {
        const items = pagination.querySelectorAll('.page-item');
        items.forEach((item, index) => {
            item.classList.toggle('active', index + 1 === currentPage);
        });
    }

    displayRows();
    setupPagination();
    </script>
</div>

<?php
// Menutup koneksi
mysqli_close($koneksi);
?>