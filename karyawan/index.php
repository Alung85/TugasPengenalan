<?php
require '../function.php';

$karyawan = query("SELECT karyawan.id, karyawan.nama, jabatan.nama_jabatan, departemen.nama_departemen, kerja.kerja 
                   FROM karyawan 
                   INNER JOIN jabatan ON karyawan.jabatan = jabatan.id 
                   INNER JOIN departemen ON karyawan.departemen = departemen.id 
                   INNER JOIN kerja ON karyawan.kerja = kerja.id
                   ORDER BY karyawan.id ASC");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include("../navbar.php"); ?>
    <div class="container">
        <h2>Data Karyawan</h2>
        <a href="tambah.php"><button class="btn btn-primary mb-2">Tambah Data Karyawan Baru</button></a>

        <table style="text-align: center;" id="datatable" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Jabatan</th>
                    <th>Department</th>
                    <th>Tipe Kerja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1; // Variabel untuk nomor urut
                foreach ($karyawan as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['nama_jabatan'] ?></td>
                    <td><?= $row['nama_departemen'] ?></td>
                    <td><?= $row['kerja'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>"><button class="btn btn-warning btn-sm"><svg 
                         xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  
                         fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  
                         stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                         <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                         <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></button></a>
                        <a href="hapus.php?id=<?= $row['id'] ?>"><button class="btn btn-danger btn-sm"><svg  
                        xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  
                        fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  
                        class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });
        });
    </script>
    <?php include('../footer.php'); ?>
</body>
</html>
