<?php
include 'header.php';
require '../assets/conn/koneksi.php'; // Koneksi ke database
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Query untuk mengambil data dari tabel jadwal_dosen
$query = "
    SELECT 
        dd.nama AS nama_dosen,
        GROUP_CONCAT(DISTINCT jd.hari ORDER BY FIELD(jd.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu') SEPARATOR ', ') AS hari,
        GROUP_CONCAT(DISTINCT jd.jam ORDER BY jd.jam SEPARATOR ', ') AS jam,
        jd.ruangan,
        dd.id AS id_dosen
    FROM jadwal_dosen jd
    JOIN daftar_dosen dd ON jd.id_dosen = dd.id
    GROUP BY dd.id, jd.ruangan
    ORDER BY dd.nama
";
$result = mysqli_query($koneksi, $query);

// Error handling jika query gagal
if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

// Pesan notifikasi
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'sukses') {
        echo "<div class='alert alert-success'>Data jadwal dosen berhasil dihapus.</div>";
    } elseif ($_GET['pesan'] == 'error') {
        echo "<div class='alert alert-danger'>Terjadi kesalahan saat menghapus data jadwal dosen.</div>";
    } elseif ($_GET['pesan'] == 'edit_sukses') {
        echo "<div class='alert alert-success'>Data jadwal dosen berhasil diperbarui.</div>";
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
                <h4>Daftar Jadwal Dosen</h4><br>
                <!-- Tombol Tambah Data -->
                <div>
                    <a href="tambah_jadwal_dosen.php" class="btn btn-success btn-lg float-end">Tambah Jadwal</a>
                </div><br>
                <!-- Input Pencarian -->
                <div class="input-group rounded">
                    <input type="search" class="form-control rounded" id="search-input" placeholder="Cari... "
                        aria-label="Search" aria-describedby="search-addon" />
                    <span class="input-group-text border-0" id="search-addon">
                        <i class="fas fa-search"></i>
                    </span>
                </div>

                <!-- Tabel Daftar Jadwal Dosen -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div class="table-responsive">
                        <table class="table table-striped" id="jadwal-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dosen</th>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Ruangan</th>
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
                                    <td><?= htmlspecialchars($row['hari']); ?></td>
                                    <td><?= htmlspecialchars($row['jam']); ?></td>
                                    <td><?= htmlspecialchars($row['ruangan']); ?></td>
                                    <td>
                                        <!-- <a href="edit_jadwal_dosen.php?id_dosen=<?= urlencode($row['id_dosen']); ?>"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit">&nbsp;</i>Edit
                                    </a> -->

                                        <a href="hapus_jadwal_dosen.php?id_dosen=<?= urlencode($row['id_dosen']); ?>"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus semua jadwal untuk dosen ini?');"
                                            class="btn btn-danger btn-sm"><i class="fas fa-trash">&nbsp;</i>
                                            Hapus
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