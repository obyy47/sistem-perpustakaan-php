<?php
session_start();
include '../includes/koneksi.php';

/** @var mysqli $koneksi */

// 1. Cek autentikasi login khusus admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// 2. Ambil ID anggota dari URL
if (!isset($_GET['id'])) {
    header("Location: anggota.php");
    exit;
}

$id = (int)$_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    header("Location: anggota.php");
    exit;
}

$pesan = '';

// 3. Proses update data saat tombol diklik
if (isset($_POST['update'])) {
    $nama  = trim($_POST['nama']);
    $email = trim($_POST['email']);

    // Jika password diisi, update password baru (di-hash). Jika kosong, pakai password lama.
    if (!empty($_POST['password'])) {
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama = ?, email = ?, password = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $password_hash, $id);
    } else {
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama = ?, email = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $nama, $email, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        // Tampilkan alert sukses lalu redirect otomatis
        echo "<script>
                alert('Data anggota berhasil diubah!');
                window.location.href = 'anggota.php?pesan=edit_sukses';
              </script>";
        exit;
    } else {
        $pesan = "Gagal memperbarui data anggota!";
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Edit Data Anggota</h2>
<a href="anggota.php">Kembali</a>
<br><br>

<?php if ($pesan != ''): ?>
    <p style="color: red;"><?= $pesan; ?></p>
<?php endif; ?>

<form action="" method="POST">
    <label>Nama Lengkap:</label><br>
    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required><br><br>

    <label>Password Baru (kosongkan jika tidak ingin diubah):</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit" name="update">Simpan Perubahan</button>
</form>

<?php include '../includes/footer.php'; ?>