<?php
session_start();
include '../includes/koneksi.php';

/** @var mysqli $koneksi */

// cek autentikasi admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $hapus = mysqli_query($koneksi, "DELETE FROM users WHERE id = '$id'");

    if ($hapus) {
        header("Location: anggota.php");
        exit;
    }
}
