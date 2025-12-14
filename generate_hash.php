<?php
// Kata sandi yang ingin Anda gunakan (misalnya, untuk user 'admin')
$password_plain = "Admin_123"; 

// Fungsi password_hash() akan mengenkripsi kata sandi tersebut dengan aman
$password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

echo "Kata Sandi Asli: " . $password_plain . "<br>";
echo "Hash yang Dihasilkan: <strong>" . $password_hash . "</strong><br><br>";
echo "Salin hash ini ke tabel 'users' Anda di phpMyAdmin.";

// Contoh output hash: $2y$10$Q73u8eA7Zc.M/7M0p4O71eQ5b3T1r5h4j0g9k2l1m9n8o7p6q5r4s3t2u1v0w9x8y7z6
?>