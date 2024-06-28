<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Delete data from gaji table for the given karyawan id
        $query_gaji = "DELETE FROM gaji WHERE id_karyawan=?";
        $stmt_gaji = mysqli_prepare($conn, $query_gaji);
        mysqli_stmt_bind_param($stmt_gaji, "i", $id);
        
        if (!mysqli_stmt_execute($stmt_gaji)) {
            throw new Exception("Error deleting from gaji: " . mysqli_stmt_error($stmt_gaji));
        }

        // Delete data from karyawan table
        $query_karyawan = "DELETE FROM karyawan WHERE id=?";
        $stmt_karyawan = mysqli_prepare($conn, $query_karyawan);
        mysqli_stmt_bind_param($stmt_karyawan, "i", $id);
        
        if (!mysqli_stmt_execute($stmt_karyawan)) {
            throw new Exception("Error deleting from karyawan: " . mysqli_stmt_error($stmt_karyawan));
        }

        // Commit transaction
        mysqli_commit($conn);

        // Redirect to index.php after successful deletion
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        echo "Failed to delete data: " . $e->getMessage();
    }
} else {
    echo "Parameter id not found.";
}

mysqli_close($conn);

