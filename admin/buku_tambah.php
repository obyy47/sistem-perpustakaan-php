<?php
session_start();
require_once '../includes/koneksi.php';

/** @var mysqli $koneksi */

// Cek hak akses admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$pesan = '';

if (isset($_POST['tambah'])) {
    $judul        = trim($_POST['judul']);
    $penulis      = trim($_POST['penulis']);
    $penerbit     = trim($_POST['penerbit']);
    $tahun_terbit = (int)$_POST['tahun_terbit'];
    $stok         = (int)$_POST['stok'];

    $stmt = mysqli_prepare($koneksi, "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, stok) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssii", $judul, $penulis, $penerbit, $tahun_terbit, $stok);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: buku.php?pesan=tambah_sukses");
        exit;
    } else {
        $pesan = "Gagal menambahkan data buku!";
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Tambah Buku Baru</h2>
<a href="buku.php">&laquo; Kembali ke Data Buku</a>
<br><br>

<?php if ($pesan != ''): ?>
    <p style="color: red;"><?= $pesan; ?></p>
<?php endif; ?>

<form action="" method="post">
    <div>
        <label>Judul Buku:</label><br>
        <input type="text" name="judul" required>
    </div>
    <br>
    <div>
        <label>Penulis:</label><br>
        <input type="text" name="penulis" required>
    </div>
    <br>
    <div>
        <label>Penerbit:</label><br>
        <input type="text" name="penerbit" required>
    </div>
    <br>
    <div>
        <label>Tahun Terbit:</label><br>
        <input type="number" name="tahun_terbit" required>
    </div>
    <br>
    <div>
        <label>Stok:</label><br>
        <input type="number" name="stok" min="1" required>
    </div>
    <br>
    <button type="submit" name="tambah">Simpan Buku</button>
</form>

<?php include '../includes/footer.php'; ?>