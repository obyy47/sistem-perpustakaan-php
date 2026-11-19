<?php
// Jalankan lewat terminal: php buat_admin.php "Nama" email password
if (php_sapi_name() !== 'cli') {
    exit;
}

[$_, $nama, $email, $pw] = $argv + [null, null, null, null];
if (!$nama || !$email || strlen($pw ?? '') < 8) {
    exit("Cara pakai: php buat_admin.php \"Nama\" email password(min 8)\n");
}

require __DIR__ . '/includes/koneksi.php';

$hash = password_hash($pw, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($koneksi, "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'admin')");
mysqli_stmt_bind_param($stmt, 'sss', $nama, $email, $hash);
mysqli_stmt_execute($stmt);

echo "Admin dibuat: $email\n";
