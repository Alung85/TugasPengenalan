<?php
include '../koneksi.php';

$term = $_GET['term'];
$query = "SELECT id, nama FROM karyawan WHERE nama LIKE '%" . $term . "%'";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'value' => $row['nama']
    ];
}

echo json_encode($data);    
