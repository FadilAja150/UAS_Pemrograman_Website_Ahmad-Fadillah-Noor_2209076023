<?php
// Mulai session untuk manajemen login
session_start();

// Konfigurasi Kredensial Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "data_barang";

// Membuat koneksi menggunakan MySQLi secara Objek-Orientasi (untuk Prepared Statements)
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Fungsi untuk mengarahkan pengguna ke halaman login jika belum login
function enforce_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>