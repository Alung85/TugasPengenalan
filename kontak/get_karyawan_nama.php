<?php
include '../koneksi.php';

$term = $_GET['term'];

$query = $conn->prepare("SELECT id, nama FROM karyawan WHERE nama LIKE ?");
$like_term = "%" . $term . "%";
$query->bind_param("s", $like_term);
$query->execute();
$result = $query->get_result();

$karyawan = [];
while ($row = $result->fetch_assoc()) {
    $karyawan[] = [
        'id' => $row['id'],
        'label' => $row['nama'],
        'value' => $row['nama']
    ];
}

echo json_encode($karyawan);

