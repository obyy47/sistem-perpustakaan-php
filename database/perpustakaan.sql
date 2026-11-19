CREATE DATABASE IF NOT EXISTS perpustakaan;
USE perpustakaan;

-- table users buat login & hash password
CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nama VARCHAR(100) NOT NULL,
	email VARCHAR(100) NOT NULL,
	PASSWORD VARCHAR(255) NOT NULL,
	ROLE ENUM ('admin', 'anggota') DEFAULT 'anggota'
);

-- table buku buat CRUD data buku
CREATE TABLE buku (
	id INT AUTO_INCREMENT PRIMARY KEY,
	judul VARCHAR(100) NOT NULL,
	penulis VARCHAR(100) NOT NULL,
	penerbit VARCHAR(100) NOT NULL,
	tahun_terbit INT(4) NOT NULL,
	stok INT NOT NULL DEFAULT 0,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- table peminjaman buat logika transaksi simpel
CREATE TABLE peminjaman (
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	buku_id INT NOT NULL,
	tanggal_pinjam DATE NOT NULL,
	STATUS ENUM ('dipinjam', 'dikembalikan') DEFAULT 'dipinjam',
	FOREIGN KEY (user_id) REFERENCES users(id),
	FOREIGN KEY (buku_id) REFERENCES buku(id)
);