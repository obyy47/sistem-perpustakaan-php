<?php
session_start();
include '../includes/koneksi.php';

/** @var mysqli $koneksi */

// cek autentikasi login
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// ambil semua data dari tabel users yang rolenya 'anggota'
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE role = 'anggota' ORDER BY id DESC");
?>

<?php include '../includes/header.php'; ?>

<h2>Kelola Data Anggota</h2>
<a href="index.php">Kembali ke Dashboard</a> |
<a href="anggota_tambah.php">Tambah Anggota Baru</a>
<br><br>

<table cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['ROLE']); ?></td>
                <td>
                    <a href="anggota_edit.php?id=<?= $row['id']; ?>">Edit</a> |
                    <a href="anggota_hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus anggota ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>