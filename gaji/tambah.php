<?php
require '../function.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $karyawan_id = $_POST['karyawan_id'];
    $jumlah_gaji = str_replace('.', '', $_POST['jumlah_gaji']); // Menghapus titik pemisah ribuan sebelum menyimpan ke database
    $tanggal_gaji = $_POST['tanggal_gaji'];

    // Memastikan format tanggal sesuai dengan 'YYYY-MM-DD'
    $tanggal_gaji = date('Y-m-d', strtotime($tanggal_gaji));

    if (!empty($karyawan_id) && !empty($jumlah_gaji) && !empty($tanggal_gaji)) {
        // Check if the karyawan_id already exists in gaji table
        $sql_check = "SELECT COUNT(*) AS count FROM gaji WHERE id_karyawan = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $karyawan_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row = $result_check->fetch_assoc();

        if ($row['count'] > 0) {
            $error_message = "Data dengan nama karyawan ini sudah ada. Mohon masukkan data lain.";
        } else {
            $sql = "INSERT INTO gaji (id_karyawan, jumlah_gaji, tanggal_gaji) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $karyawan_id, $jumlah_gaji, $tanggal_gaji);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Gaji Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#karyawan_name").autocomplete({
                source: "get_karyawan_nama.php", // Pastikan ini sesuai dengan nama file atau endpoint yang benar
                minLength: 1,
                select: function(event, ui) {
                    $('#karyawan_id').val(ui.item.id);
                }
            });

            $("form").on("submit", function() {
                if ($('#karyawan_id').val() === '') {
                    alert('Please select a valid Karyawan.');
                    return false;
                }
            });

            $("#jumlah_gaji").on("input", function() {
                var value = this.value.replace(/\D/g, ''); // Hanya angka
                var formattedValue = "";
                for (var i = value.length - 1; i >= 0; i--) {
                    formattedValue = value[i] + formattedValue;
                    if ((value.length - i) % 3 === 0 && i !== 0) {
                        formattedValue = "." + formattedValue;
                    }
                }
                this.value = formattedValue;
            });
        });
    </script>
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
        <h1>Tambah Data Gaji Karyawan</h1>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="mt-2">
                <label for="karyawan_name" class="form-label">Nama Karyawan</label>
                <input type="text" class="form-control" id="karyawan_name" name="karyawan_name" autocomplete="off" autofocus="on">
                <input type="hidden" id="karyawan_id" name="karyawan_id">

                <label for="jumlah_gaji" class="form-label">Jumlah Gaji</label>
                <input type="text" class="form-control" id="jumlah_gaji" name="jumlah_gaji" autocomplete="off">

                <label for="tanggal_gaji" class="form-label">Tanggal Gaji</label>
                <input type="date" class="form-control " id="tanggal_gaji" name="tanggal_gaji">

                <button type="submit" name="tambah" value="tambah" class="btn btn-primary mt-4">Simpan</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
