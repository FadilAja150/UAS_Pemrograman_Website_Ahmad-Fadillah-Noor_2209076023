<?php
session_start();
session_unset();    // Hapus semua variabel session
session_destroy();  // Hancurkan session
header("Location: login.php"); // Redirect kembali ke halaman login
exit();
?>