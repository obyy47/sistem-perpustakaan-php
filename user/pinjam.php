<?php
session_start();
include '../includes/koneksi.php';

/** @var mysqli $koneksi */

// 1. Cek autentikasi login (role 'anggota' / selain 'admin')
if (!isset($_SESSION['login']) || $_SESSION['role'] == 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $buku_id    = (int)$_GET['id'];
    $user_id    = (int)$_SESSION['user_id'];
    $tgl_pinjam = date('Y-m-d');

    // 2. Cek apakah user sedang meminjam buku yang sama
    $cek_pinjam = mysqli_query(
        $koneksi,
        "SELECT id FROM peminjaman WHERE user_id = '$user_id' AND buku_id = '$buku_id' AND STATUS = 'dipinjam'"
    );
    if (mysqli_num_rows($cek_pinjam) > 0) {
        echo "<script>alert('Buku ini sedang kamu pinjam!'); window.location='index.php';</script>";
        exit;
    }

    // 3. Cek stok buku terlebih dahulu
    $cek_stok  = mysqli_query($koneksi, "SELECT stok FROM buku WHERE id = '$buku_id'");
    $data_buku = mysqli_fetch_assoc($cek_stok);

    if ($data_buku && $data_buku['stok'] > 0) {
        // 4. Insert data ke tabel peminjaman
        $insert = mysqli_query(
            $koneksi,
            "INSERT INTO peminjaman (user_id, buku_id, tanggal_pinjam, STATUS)
             VALUES ('$user_id', '$buku_id', '$tgl_pinjam', 'dipinjam')"
        );

        // 5. Kurangi stok buku sebanyak -1
        $update_stok = mysqli_query(
            $koneksi,
            "UPDATE buku SET stok = stok - 1 WHERE id = '$buku_id'"
        );

        if ($insert && $update_stok) {
            // Ubah ke 'index.php' kalau mau langsung lihat perubahan tulisan di katalog
            header("Location: index.php");
            exit;
        }
    } else {
        echo "<script>alert('Stok buku habis!'); window.location='index.php';</script>";
    }
}
