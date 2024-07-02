<?php
// Pastikan file koneksi.php sudah termasuk atau di-require di sini
require '../function.php';

// Cek jika parameter id telah diterima melalui GET
if (isset($_GET['id'])) {
    $gaji_id = $_GET['id'];

    // Cek jika konfirmasi penghapusan telah diterima melalui GET
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        // Query untuk menghapus data gaji berdasarkan id
        $sql = "DELETE FROM gaji WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $gaji_id);

        if ($stmt->execute()) {
            // Tampilkan alert dan redirect ke halaman utama setelah menghapus data
            echo "<script>
                    alert('Data berhasil dihapus.');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            // Tampilkan alert jika terjadi kesalahan
            $error_message = $stmt->error;
            echo "<script>
                    alert('Error: " . $error_message . "');
                    window.history.back();
                  </script>";
        }
    } else {
        // Tampilkan konfirmasi penghapusan menggunakan JavaScript
        echo "<script>
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    window.location.href = 'hapus.php?id={$gaji_id}&confirm=yes';
                } else {
                    window.location.href = 'index.php';
                }
              </script>";
    }
} else {
    echo "<script>
            alert('Parameter ID tidak ditemukan.');
            window.history.back();
          </script>";
}

$conn->close();

