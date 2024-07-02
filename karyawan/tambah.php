<?php
require '../function.php';

if (isset($_POST['tambah'])) {
    // Validate and sanitize input
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $jabatan = isset($_POST['jabatan']) ? intval($_POST['jabatan']) : 0;
    $departemen = isset($_POST['departemen']) ? intval($_POST['departemen']) : 0;
    $kerja = isset($_POST['kerja']) ? intval($_POST['kerja']) : 0;

    // Remove any non-alphabetic characters
    $nama = preg_replace("/[^a-zA-Z\s]/", "", $nama);

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'kantor');

    // Sanitize inputs (optional if using prepared statements)
    $nama = $conn->real_escape_string($nama);

    // Check if name already exists
    $sql_check = "SELECT * FROM karyawan WHERE nama = '$nama'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Nama sudah ada. Masukkan nama lain.');</script>";
    } else {
        // Insert into database
        $sql = "INSERT INTO karyawan (nama, jabatan, departemen, kerja) 
                VALUES ('$nama', $jabatan, $departemen, $kerja)";

        if ($conn->query($sql) === TRUE) {
            header('Location: /karyawan/index.php');
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .form-control {
            border: 2px solid #ccc;
            border-radius: 4px;
            padding: 10px;
        }
        .form-control:focus {
            border-color: #1e90ff;
            box-shadow: 0 0 0 0.2rem rgba(30, 144, 255, 0.25);
        }
        .btn-primary {
            padding: 10px 20px;
        }
        .form.label {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
    <script>
        function validateForm() {
            var nama = document.getElementById("nama").value;
            
            // Check if name is empty or contains non-alphabet characters
            if (nama === '') {
                alert("Nama tidak boleh kosong.");
                return false;
            }

            var regex = /^[a-zA-Z\s]+$/;
            if (!regex.test(nama)) {
                alert("Nama harus menggunakan alphabet saja.");
                return false;
            }
            
            return true;
        }

        function onlyAlphabets(e, t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                } else if (e) {
                    var charCode = e.which;
                } else {
                    return true;
                }
                if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32) {
                    return true;
                } else {
                    return false;
                }
            } catch (err) {
                alert(err.Description);
            }
        }

        function handlePaste(e) {
            var pastedData = e.clipboardData.getData('text');
            var regex = /^[a-zA-Z\s]+$/;

            if (!regex.test(pastedData)) {
                alert("Nama harus menggunakan alphabet saja.");
                e.preventDefault();
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            var namaField = document.getElementById("nama");
            namaField.onkeypress = onlyAlphabets;
            namaField.onpaste = handlePaste;
        });
    </script>
</head>
<body>
    <div class="container">
        <h3>Tambah Data Karyawan</h3>
        <br>
        <form action="tambah.php" method="POST" onsubmit="return validateForm()">
            <div class="mt-2">
                <label for="nama" class="form-label">Nama Lengkap karyawan</label>
                <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" autofocus="on">
            </div>
            <div class="form-group">
                <label class="mt-2" for="jabatan">Jabatan</label>
                <select class="form-control mt-1" name="jabatan" id="jabatan">
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'kantor');

                    if ($conn->connect_error) {
                        die("Koneksi gagal: " . $conn->connect_error);
                    }

                    $sql = "SELECT id, nama_jabatan FROM jabatan";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['nama_jabatan'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Tidak ada Jabatan tersedia</option>';
                    }
                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="mt-2" for="departemen">Department</label>
                <select class="form-control mt-1" name="departemen" id="departemen">
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'kantor');

                    if ($conn->connect_error) {
                        die("Koneksi gagal: " . $conn->connect_error);
                    }

                    $sql = "SELECT id, nama_departemen FROM departemen";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['nama_departemen'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Tidak ada Departemen tersedia</option>';
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="mt-2" for="kerja">Tipe Kerja</label>
                <select class="form-control mt-1" name="kerja" id="kerja">
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'kantor');

                    if ($conn->connect_error) {
                        die("Koneksi gagal: " . $conn->connect_error);
                    }

                    $sql = "SELECT id, kerja FROM kerja";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['kerja'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Tidak ada Tipe Kerja tersedia</option>';
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <button type="submit" name="tambah" value="tambah" class="btn btn-primary mt-4">Simpan</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
