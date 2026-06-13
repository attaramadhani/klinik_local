# Cliniq - Sistem Informasi Reservasi Klinik Online (Versi Lokal MySQL)

Aplikasi Web Sistem Reservasi dan Manajemen Klinik Online berbasis **PHP Native** dan **MySQL**. Projek ini dirancang secara modular dan disederhanakan khusus untuk dijalankan pada lingkungan lokal (*localhost*) seperti **Laragon** atau **XAMPP**. Sangat cocok sebagai bahan tugas kuliah, portofolio, maupun bahan evaluasi akademik bersama dosen.

---

## 🚀 Fitur Utama Berdasarkan Aktor (Role-Based Access)

Sistem ini mendukung Multi-User dengan pembagian akses sebagai berikut:

### 1. Portal Pasien
*   **Registrasi & Login Mandiri**: Pasien dapat membuat akun menggunakan NIK (Nomor Induk Kependudukan 16 digit).
*   **Pencarian Jadwal Praktik**: Melihat jadwal dokter yang aktif berdasarkan Poli (Gigi, Umum, dll) secara *real-time*.
*   **Reservasi Online**: Melakukan pendaftaran reservasi kunjungan untuk tanggal tertentu secara praktis.
*   **Sistem Antrian**: Pasien secara otomatis mendapatkan nomor antrian setelah berhasil melakukan reservasi.
*   **Cetak Tiket**: Menghasilkan tiket antrian digital yang dapat dicetak langsung dari browser.
*   **Riwayat Pemeriksaan**: Pasien dapat memantau riwayat medis, hasil diagnosa, resep obat, serta catatan dokter dari kunjungan sebelumnya.

### 2. Dashboard Dokter
*   **Manajemen Jadwal Praktik**: Dokter dapat mengatur hari praktik, jam mulai/selesai, serta kuota maksimal pasien per hari.
*   **Daftar Kunjungan Pasien**: Memantau daftar pasien yang melakukan reservasi pada jadwalnya hari ini.
*   **Input Hasil Pemeriksaan**: Menginput hasil diagnosa medis, data fisik (suhu badan, tekanan darah, berat badan), tindakan, alergi obat, resep obat, serta catatan medis langsung ke sistem.

### 3. Dashboard Administrator (Admin)
*   **Manajemen Data Master**: CRUD lengkap untuk data Dokter (beserta pembuatan akunnya), data Pasien, dan Jadwal Praktik.
*   **Konfirmasi Pembayaran**: Mengelola transaksi pembayaran pasien baik metode tunai (verifikasi manual admin) maupun non-tunai (otomatis).
*   **Laporan Keuangan & Statistik**: Dashboard interaktif yang menyajikan laporan grafik pendapatan bulanan/tahunan serta total kunjungan pasien.

---

## 🛠️ Spesifikasi Teknologi (Tech Stack)

*   **Bahasa Pemrograman**: PHP Native (Versi Rekomendasi: PHP 8.0 ke atas, kompatibel dengan PHP 8.3).
*   **Database**: MySQL / MariaDB (Driver PHP `mysqli` native).
*   **Desain Antarmuka (UI/UX)**: Bootstrap 5, FontAwesome v6, dan Google Fonts (Plus Jakarta Sans).
*   **Notifikasi & Dialog**: SweetAlert2 untuk *alert* interaktif yang premium.
*   **Integrasi Pembayaran**: Midtrans Snap JS (untuk simulasi pembayaran online QRIS/E-Wallet dalam mode Sandbox).

---

## 📂 Panduan Instalasi Lokal (Langkah demi Langkah)

Ikuti langkah-langkah di bawah ini untuk menjalankan projek di komputer lokal Anda:

### Langkah 1: Persiapan Folder Projek
1. Salin atau pindahkan folder `klinik_local` ke direktori web root server lokal Anda:
   *   Jika menggunakan **Laragon**: `C:\laragon\www\klinik_local`
   *   Jika menggunakan **XAMPP**: `C:\xampp\htdocs\klinik_local`

### Langkah 2: Import Database MySQL (Wajib Dilakukan)
Agar aplikasi dapat berjalan dan menampilkan data, Anda **harus** meng-import file `.sql` yang telah disediakan. Berikut cara mudahnya:
1. Aktifkan modul **MySQL** dan **Apache/Nginx** di Laragon atau XAMPP Anda.
2. Buka browser dan akses **phpMyAdmin** (biasanya di `http://localhost/phpmyadmin`).
3. Buat database baru dengan nama persis: `klinik_reservasi`.
4. Pilih database `klinik_reservasi` yang baru dibuat tersebut.
5. Klik tab **Import** di menu atas phpMyAdmin.
6. Klik tombol **Choose File** (Pilih File), lalu cari dan pilih file `klinik_reservasi.sql` yang ada di dalam folder projek ini.
7. Scroll ke bawah dan klik tombol **Go** (atau Import) untuk mulai memasukkan tabel dan data dummy ke database Anda.

### Langkah 3: Konfigurasi Environment Variable (`.env`)
Salin file template `.env` yang berada di root direktori projek, lalu sesuaikan port MySQL sesuai konfigurasi komputer Anda:
```env
# Database Configuration (MySQL / MariaDB)
DB_HOST="127.0.0.1"
DB_PORT="3306"          # Laragon default biasanya 3306, jika gagal ganti ke 3307
DB_USER="root"
DB_PASS=""              # Kosongkan jika menggunakan default XAMPP/Laragon
DB_NAME="klinik_reservasi"
DB_DEBUG="true"         # Set "false" untuk menyembunyikan detail error di produksi
```

### Langkah 4: Uji Coba di Browser
1. Akses aplikasi melalui URL berikut:
   *   `http://localhost/klinik_local` (atau `http://klinik_local.test` jika menggunakan fitur auto-virtualhost Laragon).

---

## 🔐 Kredensial Akun untuk Pengujian (Uji Coba Sistem)

Gunakan daftar akun berikut untuk mendemonstrasikan fitur multi-user kepada dosen penguji:

| No | Peran (Role) | Username | Password | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| 1 | **Administrator** | `admin` | `admin123` | Akses penuh seluruh sistem & laporan keuangan |
| 2 | **Dokter (Umum)** | `dr_alpha` | `dokter123` | Profil: dr. Dummy Alpha (Mengisi diagnosa pasien) |
| 3 | **Dokter (Gigi)** | `dr_beta` | `dokter123` | Profil: dr. Dummy Beta (Mengisi diagnosa pasien) |
| 4 | **Pasien 1** | `pasien1` | `pasien123` | Profil: Pasien Uji Coba Satu (NIK: 1111111111111111) |
| 5 | **Pasien 2** | `pasien2` | `pasien123` | Profil: Pasien Uji Coba Dua (NIK: 2222222222222222) |

### 🧭 Panduan Alur Pengujian (Testing Flow) Menggunakan Akun Dummy

Untuk memperlihatkan fungsionalitas sistem secara menyeluruh kepada dosen, ikuti alur skenario berikut:

1. **Skenario Pasien (Reservasi)**:
   * Buka aplikasi dan login menggunakan akun **Pasien 1** (`pasien1` / `pasien123`).
   * Masuk ke menu **Reservasi**, pilih Poli Umum, lalu pilih jadwal untuk **dr. Dummy Alpha**.
   * Isi keluhan medis dan simpan. Sistem akan men-generate nomor antrian.
   * Coba cetak tiket antrian untuk memperlihatkan fitur cetak.
   * Logout.

2. **Skenario Dokter (Pemeriksaan)**:
   * Login menggunakan akun **Dokter Alpha** (`dr_alpha` / `dokter123`).
   * Masuk ke menu **Pemeriksaan Pasien**. Anda akan melihat antrian dari Pasien 1 yang baru saja mendaftar.
   * Klik **Periksa**, lalu input hasil diagnosa, tekanan darah, dan resep obat. Simpan data.
   * Logout.

3. **Skenario Pasien (Melihat Hasil & Pembayaran)**:
   * Login kembali menggunakan akun **Pasien 1**.
   * Masuk ke menu **Riwayat & Hasil**, lihat detail hasil pemeriksaan yang baru saja diinput oleh dokter.
   * Klik tombol **Bayar** (Pilih metode Tunai/Cash untuk kemudahan pengujian lokal).
   * Logout.

4. **Skenario Administrator (Konfirmasi Pembayaran & Laporan)**:
   * Login menggunakan akun **Administrator** (`admin` / `admin123`).
   * Masuk ke menu **Konfirmasi Pembayaran**, lalu setujui (verifikasi) pembayaran tunai dari Pasien 1.
   * Buka menu **Laporan Pendapatan**, perlihatkan kepada dosen bahwa grafik dan total pendapatan bulan ini telah bertambah sesuai dengan transaksi yang baru saja diselesaikan.

---

## 📐 Skema Relasi Database (Entity Relationship)

Aplikasi ini menggunakan database relasional ternormalisasi yang terdiri dari 7 tabel utama:
1.  **`users`**: Menyimpan kredensial autentikasi (username, email, password terenkripsi bcrypt, role).
2.  **`dokter`**: Informasi detail data diri dokter, berelasi satu-ke-satu (*one-to-one*) dengan tabel `users`.
3.  **`pasien`**: Informasi biodata lengkap pasien (NIK, nama, tanggal lahir, dll) dengan primary key berupa `nik`.
4.  **`jadwal_dokter`**: Jadwal praktik dokter berdasarkan hari, jam mulai/selesai, kuota harian, dan status keaktifan.
5.  **`reservasi`**: Data antrian kunjungan pasien, merekam NIK pasien, ID jadwal dokter, tanggal berkunjung, keluhan, dan nomor antrian yang digenerate otomatis.
6.  **`hasil_pemeriksaan`**: Rekam medis pasien pasca-pemeriksaan oleh dokter (tensi, suhu, diagnosa, resep, dll).
7.  **`pembayaran`**: Pencatatan bukti transaksi pembayaran reservasi, mendukung integrasi token Midtrans Snap untuk pembayaran digital.

---

## 💡 Catatan Akademik & Nilai Tambah Projek (Untuk Penilaian Dosen)

*   **Keamanan Terjaga**: Password disimpan menggunakan algoritma hash satu arah `password_hash()` bawaan PHP dengan salt dinamis standar industri (Bcrypt), bukan enkripsi MD5 yang usang.
*   **Proteksi SQL Injection**: Query database telah melewati proses sanitasi menggunakan fungsi `db_real_escape_string()` pada file `db.php` untuk memastikan input pengguna aman dari injeksi SQL berbahaya.
*   **Pemecahan Kode Modular**: Sistem koneksi database dipisahkan ke dalam `db.php` (berisi wrapper function query database) dan `koneksi.php` (pengendali instansiasi koneksi).
*   **Separation of Concerns**: Konfigurasi sensitif dipisahkan dari logika kode dan diletakkan pada file `.env`, mempermudah proses migrasi dari localhost ke server hosting tanpa mengubah isi kode program.
