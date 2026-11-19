<?php
session_start();

// Cek apakah user sudah login dan role-nya adalah admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<h2>Dashboard Admin</h2>
<p>Selamat datang, Admin <strong><?= $_SESSION['nama']; ?></strong></p>

<hr>

<ul style="text-align: center;">
    <li><a href="buku.php">Kelola Data Buku</a></li>
    <li><a href="anggota.php">Kelola Data Anggota</a></li>
    <li><a href="peminjaman.php">Persetujuan Peminjaman Buku</a></li>
    <li><a href="peminjaman_selesai.php">Riwayat Pengembalian Buku</a></li>
    <li><a href="../auth/logout.php">Logout</a></li>
</ul>

<?php include '../includes/footer.php'; ?>