<?php
session_start();
require_once '../includes/koneksi.php';

/** @var mysqli $koneksi */

// 1. Jika sudah login, langsung alihkan sesuai role
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/index.php");
    } else {
        header("Location: ../user/index.php");
    }
    exit;
}

$pesan = '';

if (isset($_POST['register'])) {
    $nama     = ucwords(trim($_POST['nama']));
    $email    = strtolower(trim($_POST['email']));
    $password = $_POST['password'];
    $role     = 'anggota'; // Disesuaikan dengan pilihan ENUM di database MySQL

    // 2. Cek apakah email sudah terdaftar
    $stmt = mysqli_prepare($koneksi, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $pesan = "Email sudah terdaftar, mohon gunakan email lain!";
    } else {
        // Hash password sebelum disimpan
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // 3. Simpan data user baru
        $stmt_insert = mysqli_prepare($koneksi, "INSERT INTO users (nama, email, password, ROLE) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "ssss", $nama, $email, $password_hashed, $role);

        if (mysqli_stmt_execute($stmt_insert)) {
            header("Location: login.php?pesan=registrasi_berhasil");
            exit;
        } else {
            $pesan = "Gagal mendaftar!";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Pendaftaran Anggota Baru</h2>

<?php if ($pesan != ''): ?>
    <p style="color: red;"><?= $pesan; ?></p>
<?php endif; ?>

<form action="" method="post">
    <div>
        <label>Nama Lengkap:</label>
        <br>
        <input type="text" name="nama" required>
    </div>
    <br>
    <div>
        <label>Email:</label>
        <br>
        <input type="email" name="email" required>
    </div>
    <br>
    <div>
        <label>Password:</label>
        <br>
        <input type="password" name="password" required>
    </div>
    <br>
    <button type="submit" name="register">Daftar Sekarang</button>
</form>

<p>Sudah punya akun? <a href="login.php">Login disini</a></p>

<?php include '../includes/footer.php'; ?>