<?php
/**
 * Database Helper Functions for MySQL (Local Version)
 * Menggunakan ekstensi mysqli secara eksklusif.
 */

class DbConnection
{
    public $handle;
    public string $error = '';

    public function __construct($handle)
    {
        $this->handle = $handle;
    }
}

/**
 * Menyimpan detail error koneksi database terakhir.
 */
function db_set_last_connect_error(string $message): void
{
    $GLOBALS['DB_LAST_CONNECT_ERROR'] = $message;
}

/**
 * Mengambil detail error koneksi database terakhir.
 */
function db_last_connect_error(): string
{
    return $GLOBALS['DB_LAST_CONNECT_ERROR'] ?? '';
}

/**
 * Melakukan koneksi ke database MySQL menggunakan environment variables.
 * Jika tidak didefinisikan, akan menggunakan konfigurasi default lokal.
 */
function db_connect_from_env(): ?DbConnection
{
    db_set_last_connect_error('');

    if (!function_exists('mysqli_connect')) {
        db_set_last_connect_error('Ekstensi mysqli tidak tersedia pada web server PHP Anda.');
        return null;
    }

    // Menonaktifkan exception bawaan mysqli agar error ditangani secara manual via db_error
    mysqli_report(MYSQLI_REPORT_OFF);

    // Membaca konfigurasi dari environment variables atau menggunakan default Laragon/XAMPP
    $host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: "127.0.0.1";
    $user = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: "root";
    $pass = getenv('DB_PASS') ?: getenv('MYSQLPASSWORD') ?: "";
    $db = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: "klinik_reservasi";
    $port = (int) (getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: 3306); // Default port MySQL standar 3306

    $handle = @mysqli_connect($host, $user, $pass, $db, $port);

    if (!$handle) {
        db_set_last_connect_error(mysqli_connect_error() ?: 'Koneksi database MySQL gagal.');
        return null;
    }

    // Set charset ke utf8mb4 agar mendukung karakter khusus secara aman
    mysqli_set_charset($handle, 'utf8mb4');

    return new DbConnection($handle);
}

/**
 * Menjalankan SQL query pada koneksi database MySQL.
 */
function db_query(?DbConnection $conn, string $query)
{
    if (!$conn) {
        return false;
    }

    $result = mysqli_query($conn->handle, $query);
    if ($result === false) {
        $conn->error = mysqli_error($conn->handle);
    }

    return $result;
}

/**
 * Mengambil baris hasil query sebagai array asosiatif.
 */
function db_fetch_assoc($result): ?array
{
    if ($result instanceof mysqli_result) {
        $row = mysqli_fetch_assoc($result);
        return $row === null ? null : $row;
    }

    return null;
}

/**
 * Mengambil jumlah baris hasil query SELECT.
 */
function db_num_rows($result): int
{
    if ($result instanceof mysqli_result) {
        return mysqli_num_rows($result);
    }

    return 0;
}

/**
 * Melarikan (escape) string untuk mencegah SQL Injection.
 */
function db_real_escape_string(?DbConnection $conn, $value): string
{
    $value = (string) $value;

    if ($conn) {
        return mysqli_real_escape_string($conn->handle, $value);
    }

    return addslashes($value);
}

/**
 * Mengambil ID auto-increment yang terakhir dihasilkan dari operasi INSERT.
 */
function db_insert_id(?DbConnection $conn): int
{
    if (!$conn) {
        return 0;
    }

    return (int) mysqli_insert_id($conn->handle);
}

/**
 * Mengambil pesan error SQL terakhir.
 */
function db_error(?DbConnection $conn): string
{
    if (!$conn) {
        return 'Koneksi database tidak tersedia.';
    }

    return mysqli_error($conn->handle) ?: $conn->error;
}
