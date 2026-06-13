-- MySQL Schema for klinik_reservasi (Local Version)
-- Compatible with MySQL/MariaDB (e.g. Laragon, XAMPP)

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS pembayaran;
DROP TABLE IF EXISTS hasil_pemeriksaan;
DROP TABLE IF EXISTS reservasi;
DROP TABLE IF EXISTS jadwal_dokter;
DROP TABLE IF EXISTS pasien;
DROP TABLE IF EXISTS dokter;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Users Table
CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100),
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'dokter', 'pasien', 'staf') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Dokter Table
CREATE TABLE dokter (
  id_dokter INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  nama_dokter VARCHAR(100) NOT NULL,
  spesialisasi VARCHAR(50),
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX dokter_id_user_idx ON dokter(id_user);

-- 3. Pasien Table
CREATE TABLE pasien (
  id_user INT NOT NULL,
  nik VARCHAR(20) PRIMARY KEY,
  nama_lengkap VARCHAR(100) NOT NULL,
  jenis_kelamin VARCHAR(20),
  tanggal_lahir DATE,
  alamat TEXT,
  no_hp VARCHAR(20),
  email VARCHAR(100),
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX pasien_id_user_idx ON pasien(id_user);

-- 4. Jadwal Dokter Table
CREATE TABLE jadwal_dokter (
  id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
  id_dokter INT NOT NULL,
  hari VARCHAR(20) NOT NULL,
  jam_mulai TIME NOT NULL,
  jam_selesai TIME NOT NULL,
  kuota INT DEFAULT 10,
  status VARCHAR(20) DEFAULT 'Aktif',
  FOREIGN KEY (id_dokter) REFERENCES dokter(id_dokter) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX jadwal_dokter_id_dokter_idx ON jadwal_dokter(id_dokter);

-- 5. Reservasi Table
CREATE TABLE reservasi (
  id_reservasi INT AUTO_INCREMENT PRIMARY KEY,
  nik VARCHAR(20) NOT NULL,
  id_jadwal INT NOT NULL,
  tanggal_kunjungan DATE NOT NULL,
  keluhan TEXT,
  no_antrian INT,
  status VARCHAR(50) DEFAULT 'Menunggu',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (nik) REFERENCES pasien(nik) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_jadwal) REFERENCES jadwal_dokter(id_jadwal) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX reservasi_id_jadwal_idx ON reservasi(id_jadwal);
CREATE INDEX reservasi_nik_idx ON reservasi(nik);

-- 6. Hasil Pemeriksaan Table
CREATE TABLE hasil_pemeriksaan (
  id_hasil INT AUTO_INCREMENT PRIMARY KEY,
  id_reservasi INT NOT NULL,
  tekanan_darah VARCHAR(20),
  suhu_badan VARCHAR(10),
  berat_badan VARCHAR(20),
  diagnosa TEXT NOT NULL,
  alergi_obat VARCHAR(100),
  resep_obat TEXT,
  tindakan TEXT,
  catatan_dokter TEXT,
  FOREIGN KEY (id_reservasi) REFERENCES reservasi(id_reservasi) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX hasil_pemeriksaan_id_reservasi_idx ON hasil_pemeriksaan(id_reservasi);

-- 7. Pembayaran Table
CREATE TABLE pembayaran (
  id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
  id_reservasi INT NOT NULL,
  snap_token VARCHAR(255),
  metode_pembayaran VARCHAR(50),
  bukti_pembayaran VARCHAR(255),
  jumlah_bayar DECIMAL(10,2),
  status_pembayaran ENUM('Pending', 'Lunas', 'Gagal') DEFAULT 'Pending',
  FOREIGN KEY (id_reservasi) REFERENCES reservasi(id_reservasi) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX pembayaran_id_reservasi_idx ON pembayaran(id_reservasi);


-- ==========================================
-- SEED DATA (DATA AWAL) UNTUK BAHAN UJI COBA
-- ==========================================

-- Seed Akun Admin
-- Username: admin, Password: admin123
INSERT INTO users (id_user, username, email, password, role) VALUES
(1, 'admin', 'admin@klinik.com', '$2y$10$/D2UK/M598mgip/UbTP77OWN8elxwJ1zP8J19ba35QOnCQAcvY2OC', 'admin');

-- Seed Akun Dokter Alpha (Spesialis Umum)
-- Username: dr_alpha, Password: dokter123
INSERT INTO users (id_user, username, email, password, role) VALUES
(2, 'dr_alpha', 'dr_alpha@klinik.com', '$2y$10$MquPcHHmChsyfKUtu9pdbOdrAfISORygAlVODXszFbT88UEmur7Se', 'dokter');

INSERT INTO dokter (id_dokter, id_user, nama_dokter, spesialisasi) VALUES
(1, 2, 'dr. Dummy Alpha', 'Umum');

-- Seed Akun Dokter Beta (Spesialis Gigi)
-- Username: dr_beta, Password: dokter123
INSERT INTO users (id_user, username, email, password, role) VALUES
(3, 'dr_beta', 'dr_beta@klinik.com', '$2y$10$MquPcHHmChsyfKUtu9pdbOdrAfISORygAlVODXszFbT88UEmur7Se', 'dokter');

INSERT INTO dokter (id_dokter, id_user, nama_dokter, spesialisasi) VALUES
(2, 3, 'dr. Dummy Beta', 'Gigi');

-- Seed Akun Pasien 1
-- Username: pasien1, Password: pasien123
INSERT INTO users (id_user, username, email, password, role) VALUES
(4, 'pasien1', 'pasien1@gmail.com', '$2y$10$fn9KOQM9gpU2ryC.y1hBJu9Eumhr28oMaFfjUm7NvZpKgzgHjsGCS', 'pasien');

INSERT INTO pasien (id_user, nik, nama_lengkap, jenis_kelamin, tanggal_lahir, alamat, no_hp, email) VALUES
(4, '1111111111111111', 'Pasien Uji Coba Satu', 'Laki-laki', '1995-08-20', 'Jl. Contoh Alamat Fiktif No. 1', '081234567890', 'pasien1@gmail.com');

-- Seed Akun Pasien 2
-- Username: pasien2, Password: pasien123
INSERT INTO users (id_user, username, email, password, role) VALUES
(5, 'pasien2', 'pasien2@gmail.com', '$2y$10$fn9KOQM9gpU2ryC.y1hBJu9Eumhr28oMaFfjUm7NvZpKgzgHjsGCS', 'pasien');

INSERT INTO pasien (id_user, nik, nama_lengkap, jenis_kelamin, tanggal_lahir, alamat, no_hp, email) VALUES
(5, '2222222222222222', 'Pasien Uji Coba Dua', 'Perempuan', '1998-12-05', 'Jl. Contoh Alamat Fiktif No. 2', '082198765432', 'pasien2@gmail.com');

-- Seed Jadwal Dokter
-- Dokter Alpha (ID 1) - Senin, Rabu, Jumat
INSERT INTO jadwal_dokter (id_jadwal, id_dokter, hari, jam_mulai, jam_selesai, kuota, status) VALUES
(1, 1, 'Senin', '08:00:00', '12:00:00', 10, 'Aktif'),
(2, 1, 'Rabu', '08:00:00', '12:00:00', 10, 'Aktif'),
(3, 1, 'Jumat', '13:00:00', '17:00:00', 5, 'Aktif');

-- Dokter Beta (ID 2) - Selasa, Kamis
INSERT INTO jadwal_dokter (id_jadwal, id_dokter, hari, jam_mulai, jam_selesai, kuota, status) VALUES
(4, 2, 'Selasa', '09:00:00', '13:00:00', 8, 'Aktif'),
(5, 2, 'Kamis', '09:00:00', '13:00:00', 8, 'Aktif');
