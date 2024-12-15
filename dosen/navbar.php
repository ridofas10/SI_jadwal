<?php
// Memulai output buffering
ob_start(); 

// Cek apakah session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Mulai session hanya jika belum dimulai
}

require '../assets/conn/koneksi.php';

// Pastikan dosen sudah login
if (!isset($_SESSION['nama_lengkap']) || $_SESSION['level'] !== 'dosen') {
    echo json_encode([]); // Jika tidak login sebagai dosen, kirim response kosong
    exit();
}

// Ambil nama dosen yang sedang login
$nama_lengkap = $_SESSION['nama_lengkap'];
$query_dosen = "SELECT id FROM users WHERE nama_lengkap = '$nama_lengkap'";
$result_dosen = mysqli_query($koneksi, $query_dosen);
$dosen = mysqli_fetch_assoc($result_dosen);

if ($dosen) {
    $id_dosen = $dosen['id'];

    // Ambil jumlah notifikasi yang belum dibaca
    $query_unread = "
        SELECT COUNT(*) AS total 
        FROM notifikasi 
        WHERE nama_dosen = '$nama_lengkap' AND status = 'unread'
    ";
    $result_unread = mysqli_query($koneksi, $query_unread);
    $unread_count = mysqli_fetch_assoc($result_unread)['total'];

    // Ambil detail notifikasi untuk dosen yang sedang login dan hanya yang belum dibaca
    $query_notifikasi = "
        SELECT id, nama_dosen, nama_mahasiswa, pesan, status, DATE_FORMAT(tanggal, '%d %M %Y %H:%i') AS tanggal 
        FROM notifikasi 
        WHERE nama_dosen = '$nama_lengkap' AND status = 'unread'
        ORDER BY tanggal DESC LIMIT 5
    ";
    $result_notifikasi = mysqli_query($koneksi, $query_notifikasi);
    $notifikasi = mysqli_fetch_all($result_notifikasi, MYSQLI_ASSOC);
    
    // Jika ada parameter "mark_as_read", ubah status notifikasi menjadi "read" dan redirect
    if (isset($_GET['mark_as_read'])) {
        $notif_id = $_GET['mark_as_read'];

        // 🔥 Ubah status notifikasi menjadi 'read'
        $query_mark_read = "UPDATE notifikasi SET status = 'read' WHERE id = '$notif_id'";
        mysqli_query($koneksi, $query_mark_read);

        // 🔥 Hapus semua notifikasi dengan status 'read' setelah status notifikasi diperbarui
        $query_delete_read = "DELETE FROM notifikasi WHERE nama_dosen = '$nama_lengkap' AND status = 'read'";
        mysqli_query($koneksi, $query_delete_read);

        // Kurangi jumlah unread notifikasi
        $unread_count--;

        // Redirect ke halaman rekap_bimbingan.php setelah status diubah
        header("Location: rekap_bimbingan.php");
        exit(); // Pastikan script berhenti setelah redirect
    }
}
?>


<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - Alerts -->

        <!-- Dropdown Notifikasi -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span
                    class="badge badge-danger badge-counter"><?= $unread_count > 0 ? $unread_count . "+" : "0"; ?></span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>

                <?php if (count($notifikasi) > 0): ?>
                <?php foreach ($notifikasi as $notif): ?>
                <a class="dropdown-item d-flex align-items-center" href="?mark_as_read=<?= $notif['id']; ?>">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500"><?= $notif['tanggal']; ?></div>
                        <span class="font-weight-bold"><?= htmlspecialchars($notif['pesan']); ?></span>
                    </div>
                </a>
                <?php endforeach; ?>
                <?php else: ?>
                <a class="dropdown-item text-center small text-gray-500" href="#">No notifications</a>
                <?php endif; ?>

                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
            </div>
        </li>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">DOSEN</span>
                <i class="fas fa-fw fa-user"></i>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="profile.php">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->
<!-- Bootstrap core JavaScript-->
<script src="../assets/adminT/vendor/jquery/jquery.min.js"></script>
<script src="../assets/adminT/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../assets/adminT/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../assets/adminT/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../assets/adminT/vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="../assets/adminT/js/demo/chart-area-demo.js"></script>
<script src="../assets/adminT/js/demo/chart-pie-demo.js"></script>

<?php
// Mengakhiri output buffering
ob_end_flush();