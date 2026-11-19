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

// Ambil data buku berdasarkan ID yang dikirim
if (!isset($_GET['id'])) {
    header("Location: buku.php");
    exit;
}

$id = (int)$_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM buku WHERE id = '$id'");
$buku  = mysqli_fetch_assoc($query);

if (!$buku) {
    header("Location: buku.php");
    exit;
}

// Proses update data
if (isset($_POST['update'])) {
    $judul        = trim($_POST['judul']);
    $penulis      = trim($_POST['penulis']);
    $penerbit     = trim($_POST['penerbit']);
    $tahun_terbit = (int)$_POST['tahun_terbit'];
    $stok         = (int)$_POST['stok'];

    $stmt = mysqli_prepare($koneksi, "UPDATE buku SET judul=?, penulis=?, penerbit=?, tahun_terbit=?, stok=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sssiii", $judul, $penulis, $penerbit, $tahun_terbit, $stok, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: buku.php?pesan=edit_sukses");
        exit;
    } else {
        $pesan = "Gagal memperbarui data buku!";
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Edit Data Buku</h2>
<a href="buku.php">&laquo; Kembali ke Data Buku</a>
<br><br>

<?php if ($pesan != ''): ?>
    <p style="color: red;"><?= $pesan; ?></p>
<?php endif; ?>

<form action="" method="post">
    <div>
        <label>Judul Buku:</label><br>
        <input type="text" name="judul" value="<?= htmlspecialchars($buku['judul']); ?>" required>
    </div>
    <br>
    <div>
        <label>Penulis:</label><br>
        <input type="text" name="penulis" value="<?= htmlspecialchars($buku['penulis']); ?>" required>
    </div>
    <br>
    <div>
        <label>Penerbit:</label><br>
        <input type="text" name="penerbit" value="<?= htmlspecialchars($buku['penerbit']); ?>" required>
    </div>
    <br>
    <div>
        <label>Tahun Terbit:</label><br>
        <input type="number" name="tahun_terbit" value="<?= htmlspecialchars($buku['tahun_terbit']); ?>" required>
    </div>
    <br>
    <div>
        <label>Stok:</label><br>
        <input type="number" name="stok" value="<?= htmlspecialchars($buku['stok']); ?>" min="0" required>
    </div>
    <br>
    <button type="submit" name="update">Update Buku</button>
</form>

<?php include '../includes/footer.php'; ?>