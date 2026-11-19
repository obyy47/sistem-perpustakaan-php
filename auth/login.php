<?php
session_start();
require_once '../includes/koneksi.php';

/** @var mysqli $koneksi */

// Jika sudah login, langsung lempar sesuai role
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/index.php");
    } else {
        header("Location: ../user/index.php");
    }
    exit;
}

$pesan = '';

// Tangkap notifikasi dari register jika ada
if (isset($_GET['pesan']) && $_GET['pesan'] == 'registrasi_berhasil') {
    $pesan_sukses = "Registrasi berhasil! Silakan login.";
}

// Proses login
if (isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Ambil data user dari database (panggil ROLE kapital biar sesuai DB)
    $stmt = mysqli_prepare($koneksi, "SELECT id, nama, password, ROLE FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        // Verifikasi password hash
        if (password_verify($password, $user['password'])) {
            // Set session login
            $_SESSION['login']   = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['role']    = $user['ROLE'];

            // Arahkan ke halaman sesuai role
            if ($user['ROLE'] == 'admin') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../user/index.php");
            }
            exit;
        } else {
            $pesan = "Password salah!";
        }
    } else {
        $pesan = "Email tidak ditemukan!";
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Login Akun</h2>

<?php if (isset($pesan_sukses)): ?>
    <p style="color: green;"><?= $pesan_sukses; ?></p>
<?php endif; ?>

<?php if ($pesan != ''): ?>
    <p style="color: red;"><?= $pesan; ?></p>
<?php endif; ?>

<form action="" method="post">
    <div>
        <label>Email:</label>
        <br>
        <input type="email" name="email" required>
    </div>
    <br>
    <div>
        <label>Password:</label>
        <br>
        <input type="password" name="password" required>
    </div>
    <br>
    <button type="submit" name="login">Login</button>
</form>

<p>Belum punya akun? <a href="register.php">Daftar disini!</a></p>

<?php include '../includes/footer.php'; ?>