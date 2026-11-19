# Sistem Peminjaman Buku Perpustakaan

Aplikasi web sederhana berbasis **PHP Native** dan **MySQL** untuk mengelola katalog buku, data anggota, dan transaksi peminjaman/pengembalian buku perpustakaan. Proyek ini dibuat sebagai latihan dasar CRUD multi-role (`admin` dan `anggota`) serta pengelolaan stok buku secara otomatis saat transaksi berlangsung.

## Fitur Utama

### Autentikasi (`auth/`)
- Pendaftaran akun baru dengan peran default `anggota`, pengecekan duplikasi email, dan enkripsi password menggunakan `password_hash`.
- Login dengan verifikasi `password_verify` dan pengalihan halaman otomatis berdasarkan peran (`admin` ke `admin/index.php`, `anggota` ke `user/index.php`).
- Logout untuk membersihkan sesi pengguna.

### Peran Admin (`admin/`)
- **Dashboard Admin:** Halaman navigasi utama ke modul buku, anggota, peminjaman aktif, dan riwayat pengembalian.
- **Kelola Data Buku (CRUD):** Melihat daftar buku, menambah buku baru, mengubah data buku (judul, penulis, penerbit, tahun terbit, stok), serta menghapus buku.
- **Kelola Data Anggota (CRUD):** Melihat daftar pengguna dengan peran `anggota`, menambah anggota baru, mengubah data anggota (dengan opsi mengubah password), dan menghapus anggota.
- **Persetujuan / Pengembalian Buku:** Melihat daftar peminjaman yang masih berstatus `dipinjam` dan memproses pengembalian (status berubah menjadi `dikembalikan` dan stok buku bertambah `+1`).
- **Riwayat Pengembalian:** Melihat daftar transaksi buku yang telah berstatus `dikembalikan`.

### Peran Anggota (`user/`)
- **Katalog Buku:** Melihat daftar buku yang memiliki stok tersedia (`stok > 0`) dan melakukan peminjaman langsung dari katalog.
- **Validasi Peminjaman Ganda:** Buku yang sedang berstatus `dipinjam` oleh anggota yang sama akan ditandai dengan teks **"Buku sudah dipinjam"** dan ditolak di sisi server agar tidak bisa dipinjam ulang sebelum dikembalikan. Setiap peminjaman yang berhasil mengurangi stok buku `-1`.
- **Riwayat Peminjaman:** Melihat seluruh riwayat peminjaman milik sendiri beserta indikator warna status (`dipinjam` berwarna biru, `dikembalikan` berwarna hijau).
- **Dashboard Anggota (`user/dashboard.php`):** Halaman ringkasan jumlah total buku yang pernah dipinjam dan buku yang sedang dipinjam.

## Teknologi yang Digunakan
- **Backend:** PHP Native (prosedural, ekstensi `mysqli`)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML dan CSS (native)

## Skema Database
File skema tersedia di `database/perpustakaan.sql` untuk database `perpustakaan` dengan 3 tabel utama:
1. `users`: Menyimpan data akun (`id`, `nama`, `email`, `PASSWORD`, `ROLE`).
2. `buku`: Menyimpan data buku (`id`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `stok`, `created_at`).
3. `peminjaman`: Menyimpan transaksi peminjaman (`id`, `user_id`, `buku_id`, `tanggal_pinjam`, `STATUS`) dengan *foreign key* ke `users(id)` dan `buku(id)`.

## Struktur Folder
```text
sistem-perpustakaan/
├── admin/
│   ├── anggota.php             # Daftar data anggota
│   ├── anggota_edit.php        # Edit data anggota
│   ├── anggota_hapus.php       # Hapus data anggota
│   ├── anggota_tambah.php      # Tambah anggota baru
│   ├── buku.php                # Daftar data buku
│   ├── buku_edit.php           # Edit data buku
│   ├── buku_hapus.php          # Hapus data buku
│   ├── buku_tambah.php         # Tambah buku baru
│   ├── index.php               # Dashboard utama admin
│   ├── peminjaman.php          # Daftar peminjaman aktif & proses pengembalian
│   └── peminjaman_selesai.php  # Riwayat buku yang sudah dikembalikan
├── assets/
│   └── css/
│       └── style.css           # Styling tampilan aplikasi
├── auth/
│   ├── login.php               # Halaman & proses login
│   ├── logout.php              # Proses logout
│   └── register.php            # Halaman & proses registrasi anggota
├── database/
│   └── perpustakaan.sql        # Skema database MySQL
├── includes/
│   ├── footer.php              # Penutup layout HTML
│   ├── header.php              # Header HTML & pemanggilan CSS
│   └── koneksi.php             # Konfigurasi koneksi MySQL
├── user/
│   ├── dashboard.php           # Ringkasan aktivitas anggota
│   ├── index.php               # Katalog buku & tombol pinjam
│   ├── pinjam.php              # Proses peminjaman & pengurangan stok
│   └── riwayat.php             # Riwayat peminjaman anggota
├── buat_admin.php              # Script CLI untuk membuat akun admin
├── index.php                   # Pengalihan awal sesuai sesi dan peran
└── README.md
```

## Cara Menjalankan di Lokal
1. Salin folder `sistem-perpustakaan` ke direktori web server lokal (`htdocs` pada XAMPP atau `www` pada Laragon).
2. Buat dan import database dengan menjalankan `database/perpustakaan.sql` melalui phpMyAdmin atau HeidiSQL.
3. Sesuaikan kredensial koneksi database di `includes/koneksi.php` bila berbeda dari default (`localhost`, user `root`, password kosong, database `perpustakaan`).
4. Buat akun admin melalui terminal (cukup dijalankan sekali, password minimal 8 karakter):
   ```bash
   php buat_admin.php "Admin Perpus" admin@email.com Password123
   ```
5. Akses aplikasi lewat browser, misalnya `http://localhost/sistem-perpustakaan/`.

## Catatan & Batasan Proyek
- Proyek ini merupakan latihan tahap awal PHP Native; *prepared statements* diterapkan pada autentikasi, CRUD buku, edit anggota, dan script pembuatan admin, sementara beberapa query lain masih menggunakan `mysqli_query` langsung dan aksi pinjam/hapus masih melalui metode `GET` tanpa token CSRF.
- Belum tersedia fitur perhitungan denda keterlambatan, batas tanggal kembali, maupun pencarian/filter buku.