<?php
session_start();
include '../includes/koneksi.php';

/** @var mysqli $koneksi */

// cek status login khusus anggota
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'anggota') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// ambil daftar ID buku yang SEDANG dipinjam oleh user ini
$buku_dipinjam = [];
$q_pinjam = mysqli_query(
    $koneksi,
    "SELECT buku_id FROM peminjaman WHERE user_id = '$user_id' AND STATUS = 'dipinjam'"
);
while ($p = mysqli_fetch_assoc($q_pinjam)) {
    $buku_dipinjam[] = $p['buku_id'];
}

// ambil data semua buku yang stoknya masih ada
$buku = mysqli_query(
    $koneksi,
    "SELECT * FROM buku WHERE stok > 0 ORDER BY id DESC"
);
?>

<?php include '../includes/header.php'; ?>

<h2>Katalog Buku Perpustakaan</h2>
<p>Selamat datang, <b><?= htmlspecialchars($_SESSION['nama']); ?></b> | <a href="../auth/logout.php">Logout</a></p>
<a href="riwayat.php">Riwayat Peminjaman Saya</a>
<br><br>

<table cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Tahun</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php while ($row = mysqli_fetch_assoc($buku)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['judul']); ?></td>
                <td><?= htmlspecialchars($row['penulis']); ?></td>
                <td><?= htmlspecialchars($row['penerbit']); ?></td>
                <td><?= htmlspecialchars($row['tahun_terbit']); ?></td>
                <td><?= htmlspecialchars($row['stok']); ?></td>
                <td>
                    <?php if (in_array($row['id'], $buku_dipinjam)) : ?>
                        <span style="color: #000000; font-weight: bold;">Buku sudah dipinjam</span>
                    <?php else : ?>
                        <a href="pinjam.php?id=<?= $row['id']; ?>" onclick="return confirm('Apakah kamu yakin ingin meminjam buku ini?')">Pinjam Buku</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>