<?php
require '../function.php'; // Asumsikan ada file function.php untuk koneksi database

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kontak_id = $_POST['kontak_id'];
    $karyawan_id = $_POST['karyawan_id'];
    $gmail = $_POST['gmail'];
    $no_telp = $_POST['no_telp'];
    $karyawan_name = $_POST['karyawan_name'];

    // Validate no_telp length and ensure it contains only numbers
    if (!preg_match("/^[0-9]{12}$/", $no_telp)) {
        $error_message = "Nomor telepon harus terdiri dari 12 angka dan hanya mengandung angka.";
    } else {
        // Check if the phone number already exists
        $sql_check_phone = "SELECT COUNT(*) AS count FROM kontak WHERE no_telp = ? AND id != ?";
        $stmt_check_phone = $conn->prepare($sql_check_phone);
        $stmt_check_phone->bind_param("si", $no_telp, $kontak_id);
        $stmt_check_phone->execute();
        $result_check_phone = $stmt_check_phone->get_result();
        $row_check_phone = $result_check_phone->fetch_assoc();

        if ($row_check_phone['count'] > 0) {
            $error_message = "Nomor telepon ini sudah ada. Mohon masukkan nomor telepon yang lain.";
        } else {
            // Check if the email already exists
            $sql_check_email = "SELECT COUNT(*) AS count FROM kontak WHERE gmail = ? AND id != ?";
            $stmt_check_email = $conn->prepare($sql_check_email);
            $stmt_check_email->bind_param("si", $gmail, $kontak_id);
            $stmt_check_email->execute();
            $result_check_email = $stmt_check_email->get_result();
            $row_check_email = $result_check_email->fetch_assoc();

            if ($row_check_email['count'] > 0) {
                $error_message = "Alamat Gmail ini sudah ada. Mohon masukkan alamat Gmail yang lain.";
            } else {
                // Check if the name already exists in kontak table
                $sql_check_name_kontak = "SELECT COUNT(*) AS count FROM kontak 
                                          INNER JOIN karyawan ON kontak.id_karyawan = karyawan.id 
                                          WHERE karyawan.nama = ? AND kontak.id != ?";
                $stmt_check_name_kontak = $conn->prepare($sql_check_name_kontak);
                $stmt_check_name_kontak->bind_param("si", $karyawan_name, $kontak_id);
                $stmt_check_name_kontak->execute();
                $result_check_name_kontak = $stmt_check_name_kontak->get_result();
                $row_check_name_kontak = $result_check_name_kontak->fetch_assoc();

                if ($row_check_name_kontak['count'] > 0) {
                    $error_message = "Nama karyawan ini sudah ada dalam data kontak.";
                } else {
                    // Check if the name already exists in karyawan table
                    $sql_check_name_karyawan = "SELECT id FROM karyawan WHERE nama = ?";
                    $stmt_check_name_karyawan = $conn->prepare($sql_check_name_karyawan);
                    $stmt_check_name_karyawan->bind_param("s", $karyawan_name);
                    $stmt_check_name_karyawan->execute();
                    $result_check_name_karyawan = $stmt_check_name_karyawan->get_result();
                    $row_check_name_karyawan = $result_check_name_karyawan->fetch_assoc();

                    if (!$row_check_name_karyawan) {
                        $error_message = "Nama karyawan ini tidak ditemukan di tabel karyawan.";
                    } else {
                        $new_karyawan_id = $row_check_name_karyawan['id'];

                        // Begin a transaction
                        $conn->begin_transaction();

                        // Update data kontak di tabel kontak
                        $sql_update_kontak = "UPDATE kontak SET 
                                              gmail = ?, 
                                              no_telp = ?, 
                                              id_karyawan = ? 
                                              WHERE id = ?";
                        $stmt_update_kontak = $conn->prepare($sql_update_kontak);
                        $stmt_update_kontak->bind_param("ssii", $gmail, $no_telp, $new_karyawan_id, $kontak_id);
                        $update_kontak_success = $stmt_update_kontak->execute();

                        // Commit or rollback transaction based on update success
                        if ($update_kontak_success) {
                            $conn->commit();
                            header("Location: index.php");
                            exit();
                        } else {
                            $conn->rollback();
                            $error_message = "Error updating record.";
                        }
                    }
                }
            }
        }
    }
}

if (isset($_GET['id'])) {
    $kontak_id = $_GET['id'];
    $sql = "SELECT kontak.*, karyawan.nama 
            FROM kontak 
            INNER JOIN karyawan ON kontak.id_karyawan = karyawan.id 
            WHERE kontak.id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kontak_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Data kontak tidak ditemukan.";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kontak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

            // Validate phone number input to only allow numbers
            $("#no_telp").on("input", function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h3>Edit Data Kontak</h3>
        <br>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $kontak_id; ?>" method="POST">
            <input type="hidden" name="kontak_id" value="<?php echo $row['id']; ?>">
            <div class="mb-3">
                <label for="karyawan_name" class="form-label">Nama Karyawan</label>
                <input type="text" id="karyawan_name" name="karyawan_name" class="form-control" value="<?php echo $row['nama']; ?>" required autofocus>
                <input type="hidden" id="karyawan_id" name="karyawan_id" value="<?php echo $row['id_karyawan']; ?>">
            </div>
            <div class="mb-3">
                <label for="gmail" class="form-label">Gmail</label>
                <input type="email" id="gmail" name="gmail" class="form-control" value="<?php echo $row['gmail']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="no_telp" class="form-label">No Telepon</label>
                <input type="text" id="no_telp" name="no_telp" class="form-control" value="<?php echo $row['no_telp']; ?>" required>
            </div>
            <button type="submit" name="update" value="update" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
