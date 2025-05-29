<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['nim'])) {
    echo json_encode(['status' => 'error', 'message' => 'User belum login']);
    exit;
}

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'gc_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal']);
    exit;
}

$nim = $conn->real_escape_string($_SESSION['nim']);

$sql = "SELECT booking_id, hari, pemain, waktu, unit FROM jadwal_booking WHERE nim='$nim' ORDER BY hari, waktu";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil data jadwal']);
    exit;
}

$jadwal = [];
while ($row = $result->fetch_assoc()) {
    $jadwal[] = $row;
}

echo json_encode(['status' => 'success', 'data' => $jadwal]);

$conn->close();
?>
