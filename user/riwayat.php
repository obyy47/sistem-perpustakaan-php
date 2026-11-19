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

// Query mengambil data peminjaman milik user yang sedang login
$query = mysqli_query(
    $koneksi,
    "SELECT peminjaman.*, peminjaman.STATUS AS status_pinjam, buku.judul
     FROM peminjaman
     JOIN buku ON peminjaman.buku_id = buku.id
     WHERE peminjaman.user_id = '$user_id'
     ORDER BY peminjaman.id DESC"
);
?>

<?php include '../includes/header.php'; ?>

<h2>Riwayat Peminjaman Buku Saya</h2>
<a href="index.php">Kembali ke Katalog</a>
<br><br>

<table cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['judul']); ?></td>
                <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                <td>
                    <?php if ($row['status_pinjam'] == 'dipinjam') : ?>
                        <span style="color: #2980b9; font-weight: bold;">dipinjam</span>
                    <?php elseif ($row['status_pinjam'] == 'dikembalikan') : ?>
                        <span style="color: #27ae60; font-weight: bold;">dikembalikan</span>
                    <?php else : ?>
                        <?= htmlspecialchars($row['status_pinjam']); ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>