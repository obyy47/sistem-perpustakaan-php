<?php
session_start();
require_once '../includes/koneksi.php';

/** @var mysqli $koneksi */

// Cek hak akses admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Proses persetujuan/pengembalian buku
if (isset($_GET['selesai_id'])) {
    $peminjaman_id = (int)$_GET['selesai_id'];

    // Ambil data buku_id dari transaksi
    $q_transaksi = mysqli_query($koneksi, "SELECT buku_id FROM peminjaman WHERE id = '$peminjaman_id'");
    $transaksi   = mysqli_fetch_assoc($q_transaksi);

    if ($transaksi) {
        $buku_id = $transaksi['buku_id'];

        // 1. Update status peminjaman menjadi 'dikembalikan'
        $update_status = mysqli_query($koneksi, "UPDATE peminjaman SET STATUS = 'dikembalikan' WHERE id = '$peminjaman_id'");

        // 2. Tambah stok buku (+1)
        $update_stok = mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id = '$buku_id'");

        if ($update_status && $update_stok) {
            echo "<script>
                    alert('Buku berhasil dikembalikan!');
                    window.location.href = 'peminjaman.php';
                  </script>";
            exit;
        }
    }
}

// Ambil HANYA data yang masih DIPINJAM
$query = "SELECT peminjaman.*, users.nama AS nama_peminjam, buku.judul 
          FROM peminjaman 
          JOIN users ON peminjaman.user_id = users.id 
          JOIN buku ON peminjaman.buku_id = buku.id 
          WHERE peminjaman.STATUS = 'dipinjam'
          ORDER BY peminjaman.id DESC";

$result = mysqli_query($koneksi, $query);
?>

<?php include '../includes/header.php'; ?>

<h2>Data Peminjaman Buku (Aktif)</h2>

<p>
    <a href="index.php">&laquo; Kembali ke Dashboard</a> |
    <a href="peminjaman_selesai.php">Lihat Riwayat Pengembalian &raquo;</a>
</p>

<table cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Status</th>
            <th>Aksi</th>
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
                    <td><?= htmlspecialchars($row['STATUS']); ?></td>
                    <td>
                        <a href="peminjaman.php?selesai_id=<?= $row['id']; ?>" onclick="return confirm('Proses pengembalian buku ini?')">Selesaikan (Kembalikan)</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" align="center">Tidak ada transaksi peminjaman aktif saat ini.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>