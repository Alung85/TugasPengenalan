<?php
require '../function.php';

$error_message = '';

if (isset($_GET['id'])) {
    $id_karyawan = $_GET['id'];

    $kontak = query("SELECT karyawan.nama, kontak.gmail, kontak.no_telp 
                     FROM kontak 
                     JOIN karyawan ON kontak.id_karyawan = karyawan.id 
                     WHERE kontak.id_karyawan = $id_karyawan");
    if (empty($kontak)) {
        die("Data tidak ditemukan");
    }

    $kontak = $kontak[0];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = $_POST['nama'];
        $gmail = $_POST['gmail'];
        $no_telp = $_POST['no_telp'];

        if (!empty($nama) && !empty($gmail) && !empty($no_telp)) {
            // Update karyawan table
            $sql_karyawan = "UPDATE karyawan SET nama = ? WHERE id = ?";
            $stmt_karyawan = $conn->prepare($sql_karyawan);
            $stmt_karyawan->bind_param("si", $nama, $id_karyawan);

            // Update kontak table
            $sql_kontak = "UPDATE kontak SET gmail = ?, no_telp = ? WHERE id_karyawan = ?";
            $stmt_kontak = $conn->prepare($sql_kontak);
            $stmt_kontak->bind_param("ssi", $gmail, $no_telp, $id_karyawan);

            if ($stmt_karyawan->execute() && $stmt_kontak->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Error: " . $stmt_karyawan->error . " / " . $stmt_kontak->error;
            }
        } else {
            $error_message = "Please fill in all fields.";
        }
    }
} else {
    die("ID tidak ditemukan");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Kontak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <style>
        .form-control {
            border: 2px solid #ccc;
            border-radius: 4px;
            padding: 10px;
        }
        .form-control:focus {
            border-color: #1e90ff;
            box-shadow: 0 0 0 0.25rem rgba(30, 144, 255, 0.25);
        }
        .btn-primary {
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Data Kontak</h1>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mt-2">
                <label for="nama" class="form-label">Nama Karyawan</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?= $kontak['nama'] ?>" autocomplete="off">

                <label for="gmail" class="form-label">Gmail</label>
                <input type="email" class="form-control" id="gmail" name="gmail" value="<?= $kontak['gmail'] ?>" autocomplete="off">
                
                <label for="no_telp" class="form-label">No. Telepon</label>
                <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?= $kontak['no_telp'] ?>" autocomplete="off">

                <button type="submit" class="btn btn-primary mt-4">Simpan</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
