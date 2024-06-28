<?php
$conn = mysqli_connect("localhost", "root", "", "kantor");

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
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

