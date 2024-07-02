<?php
require '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id = $_POST['id'];
    $jumlah_gaji = $_POST['jumlah_gaji'];
    $tanggal_gaji = $_POST['tanggal_gaji'];

    // Query update untuk gaji
    $sql = "UPDATE gaji SET jumlah_gaji = ?, tanggal_gaji = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dsi", $jumlah_gaji, $tanggal_gaji, $id);

    if ($stmt->execute()) {
        // Redirect ke halaman index gaji setelah berhasil update
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

