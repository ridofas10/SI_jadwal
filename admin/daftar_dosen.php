<?php 

include 'header.php';
require '../assets/conn/koneksi.php'; // Koneksi ke database
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Query untuk mengambil data dari tabel daftar_dosen
$query = "SELECT * FROM daftar_dosen";
$result = mysqli_query($koneksi, $query);

// Error handling jika query gagal
if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

// Pesan notifikasi
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'sukses') {
        echo "<div class='alert alert-success'>Data dosen berhasil dihapus.</div>";
    } elseif ($_GET['pesan'] == 'error') {
        echo "<div class='alert alert-danger'>Terjadi kesalahan saat menghapus data dosen.</div>";
    } elseif ($_GET['pesan'] == 'edit_sukses') {
        echo "<div class='alert alert-success'>Data dosen berhasil diperbarui.</div>";
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
                <h4>Daftar Dosen</h4><br>
                <!-- Tombol Tambah Data -->
                <div>
                    <a href="tambah_dosen.php" class="btn btn-success btn-lg float-end">Tambah Data</a>
                </div><br>
                <!-- Input Pencarian -->
                <div class="input-group rounded">
                    <input type="search" class="form-control rounded" id="search-input" placeholder="Cari..."
                        aria-label="Search" aria-describedby="search-addon" />
                    <span class="input-group-text border-0" id="search-addon">
                        <i class="fas fa-search"></i>
                    </span>
                </div>

                <!-- Tabel Daftar Dosen -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dosen-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama Dosen</th>
                                    <th>Alamat</th>
                                    <th>Bidang Keahlian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                            $no = 1; // Variabel untuk nomor urut
                            while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nip']); ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                                    <td><?= htmlspecialchars($row['bidang']); ?></td>
                                    <td>
                                        <a href="edit_dosen.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit">&nbsp;</i>Edit
                                        </a>
                                        <a href="hapus_dosen.php?id=<?= $row['id']; ?>"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"
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
                        <!-- Item pagination akan dibuat secara dinamis -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script>
    // Fungsi pencarian tabel
    document.getElementById('search-input').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#dosen-table tbody tr');

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    });

    // Pagination untuk tabel
    const rowsPerPage = 5;
    const table = document.getElementById('dosen-table');
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