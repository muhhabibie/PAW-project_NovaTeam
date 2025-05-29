<?php
session_start();
header('Content-Type: application/json');


if (!isset($_SESSION['nim'])) {
    echo json_encode(['status' => 'error', 'message' => 'Belum login']);
    exit;
}

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'gc_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi gagal']);
    exit;
}

$nim = $conn->real_escape_string($_SESSION['nim']);
$sql = "SELECT booking_id, hari, pemain, waktu, unit, created_time FROM jadwal_booking WHERE nim='$nim' ORDER BY created_time DESC";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $hari = strtotime($row['hari']);
    $data[] = [
        'booking_id' => $row['booking_id'],
        'hari' => $row['hari'],
        'pemain' => $row['pemain'],
        'waktu' => $row['waktu'],
        'unit' => $row['unit'],
        'day' => date('d', $hari),
        'month' => date('M', $hari),
        'year' => date('Y', $hari)
    ];
}

echo json_encode(['status' => 'success', 'data' => $data]);
$conn->close();
