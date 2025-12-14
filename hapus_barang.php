<?php
include 'koneksi.php';
enforce_login();

if (isset($_GET['id'])) {
    
    $id_barang = $_GET['id'];

    $sql = "DELETE FROM barang WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_barang);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: data_barang.php?status=hapus_sukses");
        exit();
    } else {
        $error_msg = urlencode("Gagal menghapus data: " . $stmt->error);
        $stmt->close();
        $conn->close();
        header("Location: data_barang.php?status=hapus_gagal&error=" . $error_msg);
        exit();
    }
} else {
    $conn->close();
    header("Location: data_barang.php");
    exit();
}
?>