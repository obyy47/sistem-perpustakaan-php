<?php
session_start();
include '../includes/koneksi.php';

/** @var mysqli $koneksi */

// cek autentikasi login khusus anggota
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'anggota') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// ambil statistik peminjaman anggota

$query_total = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM peminjaman WHERE user_id = '$user_id'"
);
$data_total = mysqli_fetch_assoc($query_total);

$query_dipinjam = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS dipinjam FROM peminjaman WHERE user_id = '$user_id' AND STATUS = 'dipinjam'"
);
$data_dipinjam = mysqli_fetch_assoc($query_dipinjam);
?>

<?php include '../includes/header.php'; ?>

<h2>Dashboard Anggota</h2>
<p>Selamat datang, <b><?= htmlspecialchars($_SESSION['nama']); ?></b>!</p>
<hr>

<h3>Ringkasan Aktivitas</h3>
<ul>
    <li>Total Buku Pernah Dipinjam: <b><?= $data_total['total']; ?></b></li>
    <li>Buku Sedang Dipinjam: <b><?= $data_dipinjam['dipinjam']; ?></b></li>
</ul>

<hr>
<h3>Menu Utama</h3>
<ul>
    <li><a href="index.php">Lihat Katalog & Pinjam Buku</a></li>
    <li><a href="riwayat.php">Riwayat Peminjaman Saya</a></li>
    <li><a href="../auth/logout.php">Logout</a></li>
</ul>

<?php include '../includes/footer.php'; ?>