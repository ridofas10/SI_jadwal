<?php
// Menghubungkan ke database
include '../../conn/koneksi.php';  

// Cek apakah ada parameter id yang diterima melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data user berdasarkan id
    $query = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "User tidak ditemukan.";
        exit;
    }
} else {
    echo "ID user tidak ditemukan.";
    exit;
}

// Proses update data user jika form disubmit
if (isset($_POST['update'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $npm = mysqli_real_escape_string($koneksi, $_POST['npm']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

    // Update data user
    $update_query = "UPDATE users SET 
                        nama_lengkap = '$nama_lengkap', 
                        npm = '$npm', 
                        level = '$level', 
                        username = '$username', 
                        password = '$password' 
                    WHERE id = $id";

    if (mysqli_query($koneksi, $update_query)) {
        echo "User berhasil diperbarui!";
        // Redirect ke halaman user setelah berhasil update
        header("Location: dass.php");
        exit;
    } else {
        echo "Terjadi kesalahan saat memperbarui user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>

<body>
    <h1>Edit User</h1>
    <form method="POST">
        <label>Nama Lengkap:</label>
        <input type="text" name="nama_lengkap" value="<?php echo $user['nama_lengkap']; ?>" required><br>

        <label>NPM:</label>
        <input type="text" name="npm" value="<?php echo $user['npm']; ?>" required><br>

        <label>Level:</label>
        <select name="level" required>
            <option value="admin" <?php echo ($user['level'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="user" <?php echo ($user['level'] === 'user') ? 'selected' : ''; ?>>User</option>
            <option value="dosen" <?php echo ($user['level'] === 'dosen') ? 'selected' : ''; ?>>Dosen</option>
        </select><br>

        <label>Username:</label>
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Leave empty to keep current password"><br>

        <button type="submit" name="update">Update</button>
    </form>
</body>

</html>