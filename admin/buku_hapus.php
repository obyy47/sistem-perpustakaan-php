<?php
session_start();
require_once '../includes/koneksi.php';

/** @var mysqli $koneksi */

// Cek hak akses admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = mysqli_prepare($koneksi, "DELETE FROM buku WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: buku.php?pesan=hapus_sukses");
        exit;
    } else {
        header("Location: buku.php");
        exit;
    }
} else {
    header("Location: buku.php");
    exit;
}
