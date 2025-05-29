<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'gc_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$hari = isset($data['hari']) ? $conn->real_escape_string($data['hari']) : '';
$unit = isset($data['unit']) ? $conn->real_escape_string($data['unit']) : '';

if (!$hari || !$unit) {
    echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
    exit;
}

$sql = "SELECT waktu FROM jadwal_booking WHERE hari = '$hari' AND unit = '$unit'";
$result = $conn->query($sql);

$bookedTimes = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $bookedTimes[] = $row['waktu'];
    }
    echo json_encode(['status' => 'success', 'bookedTimes' => $bookedTimes]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil data']);
}

$conn->close();
?>
