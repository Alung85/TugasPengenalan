<?php
include '../koneksi.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gaji_id = $_POST['gaji_id'];
    $karyawan_id = $_POST['karyawan_id'];
    $jumlah_gaji = $_POST['jumlah_gaji'];
    $tanggal_gaji = $_POST['tanggal_gaji'];

    $sql = "UPDATE gaji SET 
            id_karyawan=?, 
            jumlah_gaji=?, 
            tanggal_gaji=? 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $karyawan_id, $jumlah_gaji, $tanggal_gaji, $gaji_id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Error updating record: " . $stmt->error;
    }
}

if (isset($_GET['id'])) {
    $gaji_id = $_GET['id'];
    $sql = "SELECT gaji.*, karyawan.nama 
            FROM gaji 
            INNER JOIN karyawan ON gaji.id_karyawan = karyawan.id 
            WHERE gaji.id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gaji_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Data gaji tidak ditemukan.";
        exit();
    }
} else {
    echo "Parameter ID tidak ada.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Gaji</title>
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
                minLength: 2,
                select: function(event, ui) {
                    $('#karyawan_id').val(ui.item.id);
                }
            });
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h3>Edit Data Gaji</h3>
        <br>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" name="gaji_id" value="<?php echo $row['id']; ?>">
            <div class="mb-3">
                <label for="karyawan_name" class="form-label">Nama Karyawan</label>
                <input type="text" id="karyawan_name" name="karyawan_name" class="form-control" value="<?php echo $row['nama']; ?>" required>
                <input type="hidden" id="karyawan_id" name="karyawan_id" value="<?php echo $row['id_karyawan']; ?>">
            </div>
            <div class="mb-3">
                <label for="jumlah_gaji" class="form-label">Jumlah Gaji</label>
                <input type="text" id="jumlah_gaji" name="jumlah_gaji" class="form-control" value="<?php echo $row['jumlah_gaji']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_gaji" class="form-label">Tanggal Gaji</label>
                <input type="date" id="tanggal_gaji" name="tanggal_gaji" class="form-control" value="<?php echo $row['tanggal_gaji']; ?>" required>
            </div>
            <button type="submit" name="update" value="update" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
