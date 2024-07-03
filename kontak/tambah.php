<?php
require '../function.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $karyawan_id = $_POST['karyawan_id'];
    $gmail = $_POST['gmail'];
    $no_telp = $_POST['no_telp'];

    if (!empty($karyawan_id) && !empty($gmail) && !empty($no_telp)) {
        // Check if the karyawan_id already exists in kontak table
        $sql_check_karyawan = "SELECT COUNT(*) AS count FROM kontak WHERE id_karyawan = ?";
        $stmt_check_karyawan = $conn->prepare($sql_check_karyawan);
        $stmt_check_karyawan->bind_param("i", $karyawan_id);
        $stmt_check_karyawan->execute();
        $result_check_karyawan = $stmt_check_karyawan->get_result();
        $row_karyawan = $result_check_karyawan->fetch_assoc();

        // Check if the gmail already exists in kontak table
        $sql_check_gmail = "SELECT COUNT(*) AS count FROM kontak WHERE gmail = ?";
        $stmt_check_gmail = $conn->prepare($sql_check_gmail);
        $stmt_check_gmail->bind_param("s", $gmail);
        $stmt_check_gmail->execute();
        $result_check_gmail = $stmt_check_gmail->get_result();
        $row_gmail = $result_check_gmail->fetch_assoc();

        // Check if the no_telp already exists in kontak table
        $sql_check_no_telp = "SELECT COUNT(*) AS count FROM kontak WHERE no_telp = ?";
        $stmt_check_no_telp = $conn->prepare($sql_check_no_telp);
        $stmt_check_no_telp->bind_param("s", $no_telp);
        $stmt_check_no_telp->execute();
        $result_check_no_telp = $stmt_check_no_telp->get_result();
        $row_no_telp = $result_check_no_telp->fetch_assoc();

        if ($row_karyawan['count'] > 0) {
            $error_message = "Data kontak untuk karyawan ini sudah ada. Mohon masukkan data lain.";
        } elseif ($row_gmail['count'] > 0) {
            $error_message = "Gmail ini sudah digunakan. Mohon masukkan Gmail lain.";
        } elseif ($row_no_telp['count'] > 0) {
            $error_message = "Nomor telepon ini sudah digunakan. Mohon masukkan nomor telepon lain.";
        } else {
            $sql = "INSERT INTO kontak (id_karyawan, gmail, no_telp) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $karyawan_id, $gmail, $no_telp);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }
    } else {
        $error_message = "Silakan isi semua kolom.";
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

        function validatePhoneNumber(input) {
            input.value = input.value.replace(/\D/g, ''); // Menghapus semua karakter yang bukan angka
        }
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
                <input type="text" id="karyawan_name" name="karyawan_name" class="form-control" required autofocus>
                <input type="hidden" id="karyawan_id" name="karyawan_id">
            </div>
            <div class="mb-3">
                <label for="gmail" class="form-label">Gmail</label>
                <input type="email" id="gmail" name="gmail" class="form-control" autocomplete="off" required>
                <div id="emailHelp" class="form-text"></div>
            </div>
            <div class="mb-3">
                <label for="no_telp" class="form-label">No Telepon</label>
                <input type="text" id="no_telp" name="no_telp" class="form-control" autocomplete="off" pattern="\d{12}" maxlength="12" oninput="validatePhoneNumber(this)" required>
            </div>
            <button type="submit" name="save" value="save" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
