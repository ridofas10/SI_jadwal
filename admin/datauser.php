<?php 

include 'header.php';
require '../assets/conn/koneksi.php'; // Koneksi ke database
session_start();

// Jika tidak ada session login, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php"); // Redirect ke halaman login
    exit;
}
// Query untuk mengambil data dari tabel users
$query = "SELECT * FROM users WHERE level NOT IN ('admin', 'superadmin')";

$result = mysqli_query($koneksi, $query);

// Error handling jika query gagal
if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'sukses') {
        echo "<div class='alert alert-success'>Pengguna berhasil dihapus.</div>";
    } elseif ($_GET['pesan'] == 'error') {
        echo "<div class='alert alert-danger'>Terjadi kesalahan saat menghapus pengguna.</div>";
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
                <h4>Data Pengguna</h4><br>
                <!-- Page Heading -->
                <div>
                    <a href="tambahuser.php" class="btn btn-success btn-lg float-end">Tambah User</a>
                </div><br>
                <div class="input-group rounded">
                    <input type="search" class="form-control rounded" id="search-input" placeholder="Search"
                        aria-label="Search" aria-describedby="search-addon" />
                    <span class="input-group-text border-0" id="search-addon">
                        <i class="fas fa-search"></i>
                    </span>
                </div>

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div class="table-responsive">

                        <table class="table table-striped" id="user-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Lengkap</th>
                                    <th>NPM/NIP</th>
                                    <th>Username</th>
                                    <th>Level</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
        $no = 1; // Variabel untuk nomor urut
        while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                                    <td><?= htmlspecialchars($row['npm']); ?></td>
                                    <td><?= htmlspecialchars($row['username']); ?></td>
                                    <td><?= htmlspecialchars($row['level']); ?></td>
                                    <td>
                                        <!-- Link Edit -->
                                        <a href="edit_user.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit">&nbsp;</i> Edit
                                        </a>
                                        <!-- Link Hapus -->
                                        <a href="hapus_user.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination" id="pagination">
                        <!-- Item pagination akan dibuat secara dinamis -->
                    </ul>
                </nav>

            </div>
        </div>
    </div>
    <script>
    document.getElementById('search-input').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase(); // Ambil nilai input dan ubah ke lowercase
        const tableRows = document.querySelectorAll('#user-table tbody tr'); // Ambil semua baris di tabel

        tableRows.forEach(row => {
            const rowText = row.textContent
                .toLowerCase(); // Ambil semua teks di baris dan ubah ke lowercase
            if (rowText.includes(searchValue)) {
                row.style.display = ''; // Tampilkan baris jika cocok
            } else {
                row.style.display = 'none'; // Sembunyikan baris jika tidak cocok
            }
        });
    });
    </script>
    <script>
    const rowsPerPage = 5; // Jumlah baris per halaman
    const table = document.getElementById('user-table');
    const tableBody = table.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');
    const pagination = document.getElementById('pagination');

    let currentPage = 1; // Halaman awal

    // Fungsi untuk menampilkan baris sesuai halaman aktif
    function displayRows() {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = ''; // Tampilkan baris
            } else {
                row.style.display = 'none'; // Sembunyikan baris
            }
        });
    }

    // Fungsi untuk membuat elemen pagination
    function setupPagination() {
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        pagination.innerHTML = ''; // Kosongkan elemen pagination

        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = i; // Ubah halaman aktif
                updatePagination();
                displayRows();
            });
            pagination.appendChild(pageItem);
        }
    }

    // Fungsi untuk memperbarui tampilan pagination
    function updatePagination() {
        const items = pagination.querySelectorAll('.page-item');
        items.forEach((item, index) => {
            if (index + 1 === currentPage) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    // Inisialisasi
    displayRows();
    setupPagination();
    </script>

</div>

<?php
// Menutup koneksi
mysqli_close($koneksi);
?>