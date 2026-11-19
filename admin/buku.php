<?php
session_start();
require_once '../includes/koneksi.php';

/** @var mysqli $koneksi */

// Cek hak akses admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil semua data buku dari database
$query = "SELECT * FROM buku ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<?php include '../includes/header.php'; ?>

<h2>Kelola Data Buku</h2>

<p>
    <a href="index.php">&laquo; Kembali ke Dashboard</a> |
    <a href="buku_tambah.php">+ Tambah Buku Baru</a>
</p>

<!-- Pesan Notifikasi -->
<?php if (isset($_GET['pesan'])): ?>
    <?php if ($_GET['pesan'] == 'tambah_sukses'): ?>
        <p style="color: green;">Buku berhasil ditambahkan!</p>
    <?php elseif ($_GET['pesan'] == 'edit_sukses'): ?>
        <p style="color: green;">Data buku berhasil diperbarui!</p>
    <?php elseif ($_GET['pesan'] == 'hapus_sukses'): ?>
        <p style="color: green;">Buku berhasil dihapus!</p>
    <?php endif; ?>
<?php endif; ?>

<!-- Tabel daftar buku -->
<table cellpadding="8" cellspacing="0">
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
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php $no = 1; ?>
            <?php while ($buku = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($buku['judul']); ?></td>
                    <td><?= htmlspecialchars($buku['penulis']); ?></td>
                    <td><?= htmlspecialchars($buku['penerbit']); ?></td>
                    <td><?= htmlspecialchars($buku['tahun_terbit']); ?></td>
                    <td><?= htmlspecialchars($buku['stok']); ?></td>
                    <td>
                        <a href="buku_edit.php?id=<?= $buku['id']; ?>">Edit</a> |
                        <a href="buku_hapus.php?id=<?= $buku['id']; ?>" onclick="return confirm('Yakin ingin menghapus buku ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" align="center">Belum ada data buku.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>