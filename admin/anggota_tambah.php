<?php
session_start();
include '../includes/koneksi.php';

/** @var mysqli $koneksi */

// cek autentikasi admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = ucwords(trim($_POST['nama']));
    $email = strtolower(trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'anggota';

    $insert = mysqli_query($koneksi, "INSERT INTO users (nama, email, password, ROLE)
    VALUES ('$nama', '$email', '$password', '$role')
    ");

    if ($insert) {
        header("Location: anggota.php");
        exit;
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Tambah Anggota Baru</h2>
<a href="anggota.php">Kembali</a>
<br><br>

<form action="" method="POST">
    <label>Nama Lengkap:</label><br>
    <input type="text" name="nama" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit" name="simpan">Simpan Anggota</button>
</form>

<?php include '../includes/footer.php'; ?>