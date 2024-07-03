<?php
require '../function.php';

$error_message = '';

if (!isset($_GET['id'])) {
    $error_message = "Parameter ID tidak ada.";
    echo "<div class='alert alert-danger' role='alert'>$error_message</div>";
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM karyawan WHERE id = $id");
if (!$result || mysqli_num_rows($result) == 0) {
    $error_message = "Data karyawan tidak ditemukan.";
    echo "<div class='alert alert-danger' role='alert'>$error_message</div>";
    exit();
}
$row = mysqli_fetch_assoc($result);

$query_jabatan = "SELECT * FROM jabatan";
$result_jabatan = mysqli_query($conn, $query_jabatan);

$query_departemen = "SELECT * FROM departemen";
$result_departemen = mysqli_query($conn, $query_departemen);

$query_kerja = "SELECT * FROM kerja";
$result_kerja = mysqli_query($conn, $query_kerja);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = (int)$_POST['jabatan'];
    $departemen = (int)$_POST['departemen'];
    $kerja = (int)$_POST['kerja'];

    // Check if the name already exists
    $sql_check = "SELECT COUNT(*) AS count FROM karyawan WHERE nama = ? AND id != ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("si", $nama, $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();

    if ($row_check['count'] > 0) {
        $error_message = "Data dengan nama $nama sudah ada. Mohon masukkan nama yang lain.";
    } else {
        $query_update = "UPDATE karyawan SET nama = '$nama', jabatan = $jabatan, departemen = $departemen, kerja = $kerja WHERE id = $id";
        $result_update = mysqli_query($conn, $query_update);

        if ($result_update) {
            header('location: index.php');
            exit();
        } else {
            $error_message = "Gagal menyimpan perubahan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Karyawan</title>
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
</head>
<body>
    <div class="container mt-5">
        <h3>Edit Data Karyawan</h3>
        <br>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="edit.php?id=<?= $row['id'] ?>" method="POST">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Karyawan</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?= $row['nama'] ?>" required autocomplete="off" autofocus="on">
            </div>
            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <select id="jabatan" name="jabatan" class="form-control" required>
                    <?php while ($jabatan = mysqli_fetch_assoc($result_jabatan)) : ?>
                        <option value="<?= $jabatan['id'] ?>" <?= ($row['jabatan'] == $jabatan['id']) ? 'selected' : '' ?>><?= $jabatan['nama_jabatan'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="departemen" class="form-label">Department</label>
                <select id="departemen" name="departemen" class="form-control" required>
                    <?php while ($departemen = mysqli_fetch_assoc($result_departemen)) : ?>
                        <option value="<?= $departemen['id'] ?>" <?= ($row['departemen'] == $departemen['id']) ? 'selected' : '' ?>><?= $departemen['nama_departemen'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="kerja" class="form-label">Tipe Kerja</label>
                <select id="kerja" name="kerja" class="form-control" required>
                    <?php while ($kerja = mysqli_fetch_assoc($result_kerja)) : ?>
                        <option value="<?= $kerja['id'] ?>" <?= ($row['kerja'] == $kerja['id']) ? 'selected' : '' ?>><?= $kerja['kerja'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="update" value="update" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
