<?php
session_start();

// cek apakah user sudah login atau belom
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: user/index.php");
    }
    exit;
} else {
    // Jika belum login, langsung alihkan ke halaman login
    header("Location: auth/login.php");
    exit;
}
