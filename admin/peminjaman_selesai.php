<?php
session_start();
require_once '../includes/koneksi.php';

/** @var mysqli $koneksi */

// Cek hak akses admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil HANYA data yang sudah DIKEMBALIKAN
$query = "SELECT peminjaman.*, users.nama AS nama_peminjam, buku.judul 
          FROM peminjaman 
          JOIN users ON peminjaman.user_id = users.id 
          JOIN buku ON peminjaman.buku_id = buku.id 
          WHERE peminjaman.STATUS = 'dikembalikan'
          ORDER BY peminjaman.id DESC";

$result = mysqli_query($koneksi, $query);
?>

<?php include '../includes/header.php'; ?>

<h2>Riwayat Pengembalian Buku</h2>

<p>
    <a href="index.php">&laquo; Kembali ke Dashboard</a> |
    <a href="peminjaman.php">&laquo; Lihat Peminjaman Aktif</a>
</p>

<table cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_peminjam']); ?></td>
                    <td><?= htmlspecialchars($row['judul']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                    <td><b style="color: green;"><?= htmlspecialchars($row['STATUS']); ?></b></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" align="center">Belum ada riwayat pengembalian buku.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>