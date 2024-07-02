<?php
include '../koneksi.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $karyawan_id = $_POST['karyawan_id'];
    $gmail = $_POST['gmail'];
    $no_telp = $_POST['no_telp'];

    // Validate no_telp length
    if (strlen($no_telp) !== 12) {
        $error_message = "Nomor telepon harus terdiri dari 12 angka.";
    } else {
        // Check if the phone number already exists
        $sql_check_phone = "SELECT COUNT(*) AS count FROM kontak WHERE no_telp = ?";
        $stmt_check_phone = $conn->prepare($sql_check_phone);
        $stmt_check_phone->bind_param("s", $no_telp);
        $stmt_check_phone->execute();
        $result_check_phone = $stmt_check_phone->get_result();
        $row_check_phone = $result_check_phone->fetch_assoc();

        if ($row_check_phone['count'] > 0) {
            $error_message = "Nomor telepon ini sudah ada. Mohon masukkan nomor telepon yang lain.";
        } else {
            // Insert data into database
            $sql = "INSERT INTO kontak (id_karyawan, gmail, no_telp) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $karyawan_id, $gmail, $no_telp);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Error inserting record: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kontak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#karyawan_name").autocomplete({
                source: "get_karyawan_nama.php",
                minLength: 1,
                select: function(event, ui) {
                    $('#karyawan_id').val(ui.item.id);
                }
            });
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h3>Tambah Data Kontak</h3>
        <br>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="mb-3">
                <label for="karyawan_name" class="form-label">Nama Karyawan</label>
                <input type="text" id="karyawan_name" name="karyawan_name"  class="form-control" required>
                <input type="hidden" id="karyawan_id" name="karyawan_id">
            </div>
            <div class="mb-3">
                <label for="gmail" class="form-label">Gmail</label>
                <input type="email" id="gmail" name="gmail" class="form-control" autocomplete="off" required>
            </div>
            <div class="mb-3">
                <label for="no_telp" class="form-label">No Telepon</label>
                <input type="text" id="no_telp" name="no_telp" class="form-control" autocomplete="off" required>
            </div>
            <button type="submit" name="save" value="save" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
