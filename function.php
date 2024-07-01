<?php
$conn = mysqli_connect("localhost", "root", "", "kantor");

function query($query, $params = []) {
    global $conn;
    $stmt = $conn->prepare($query);

    if ($params) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

function cari($keyword) {
    global $conn;
    $keyword = "%" . $keyword . "%";
    $stmt = $conn->prepare("SELECT * FROM karyawan WHERE nama LIKE ?");
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

