<?php
require '../function.php';

if (isset($_GET['id'])) {
    $id_karyawan = $_GET['id'];

    // Delete from kontak table
    $sql_kontak = "DELETE FROM kontak WHERE id_karyawan = ?";
    $stmt_kontak = $conn->prepare($sql_kontak);
    $stmt_kontak->bind_param("i", $id_karyawan);

    if ($stmt_kontak->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt_kontak->error;
    }
} else {
    die("ID tidak ditemukan");
}

