<?php
// Pastikan file koneksi.php sudah termasuk atau di-require di sini
require '../function.php';

// Cek jika parameter id telah diterima melalui GET
if (isset($_GET['id'])) {
    $gaji_id = $_GET['id'];

    // Query untuk menghapus data gaji berdasarkan id
    $sql = "DELETE FROM gaji WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gaji_id);

    if ($stmt->execute()) {
        // Redirect kembali ke halaman utama setelah menghapus data
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }
} else {
    echo "Parameter ID tidak ditemukan.";
}

