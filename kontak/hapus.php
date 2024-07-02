<?php
require '../function.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Cek jika konfirmasi penghapusan telah diterima melalui GET
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        // Query untuk menghapus data dari tabel kontak berdasarkan id
        $sql_kontak = "DELETE FROM kontak WHERE id = ?";
        $stmt_kontak = $conn->prepare($sql_kontak);
        $stmt_kontak->bind_param("i", $id);

        if ($stmt_kontak->execute()) {
            // Tampilkan alert dan redirect ke halaman utama setelah menghapus data
            echo "<script>
                    alert('Data berhasil dihapus.');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            // Tampilkan alert jika terjadi kesalahan
            $error_message = $stmt_kontak->error;
            echo "<script>
                    alert('Error: " . $error_message . "');
                    window.history.back();
                  </script>";
        }
    } else {
        // Tampilkan konfirmasi penghapusan menggunakan JavaScript
        echo "<script>
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    window.location.href = 'hapus.php?id={$id}&confirm=yes';
                } else {
                    window.location.href = 'index.php';
                }
              </script>";
    }
} else {
    echo "<script>
            alert('ID tidak ditemukan.');
            window.history.back();
          </script>";
}

$conn->close();

