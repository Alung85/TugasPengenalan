<?php
require '../function.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete from kontak table
    $sql_kontak = "DELETE FROM kontak WHERE id = ?";
    $stmt_kontak = $conn->prepare($sql_kontak);
    $stmt_kontak->bind_param("i", $id);

    if ($stmt_kontak->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt_kontak->error;
    }
} else {
    die("ID tidak ditemukan");
}

