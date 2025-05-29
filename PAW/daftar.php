<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['nim'])) {
    echo json_encode(['status' => 'error', 'message' => 'User belum login']);
    exit;
}

date_default_timezone_set('Asia/Jakarta');

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'gc_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal']);
    exit;
}

$conn->query("SET time_zone = '+07:00'");

$data = json_decode(file_get_contents('php://input'), true);
$nim = $_SESSION['nim'];
$hari = $data['hari'] ?? '';
$pemain = (int)($data['pemain'] ?? 0);
$waktu = $data['waktu'] ?? '';
$unit = $data['unit'] ?? '';

if (!$hari || !$waktu || !$unit || $pemain <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Data input tidak lengkap atau tidak valid']);
    exit;
}

$stmtCek = $conn->prepare("SELECT 1 FROM jadwal_booking WHERE hari=? AND waktu=? AND unit=? LIMIT 1");
$stmtCek->bind_param('sss', $hari, $waktu, $unit);
$stmtCek->execute();
$stmtCek->store_result();
if ($stmtCek->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Jadwal sudah dibooking untuk unit ini.']);
    exit;
}
$stmtCek->close();

$stmtLast = $conn->prepare("SELECT created_time FROM jadwal_booking WHERE nim=? ORDER BY created_time DESC LIMIT 1");
$stmtLast->bind_param('s', $nim);
$stmtLast->execute();
$resultLast = $stmtLast->get_result();

if ($resultLast && $resultLast->num_rows > 0) {
    $lastRow = $resultLast->fetch_assoc();
    $lastCreatedTime = strtotime($lastRow['created_time']);
    $now = time();

    if (($now - $lastCreatedTime) < 3600) { 
        echo json_encode([
            'status' => 'error',
            'message' => 'Kamu sudah booking. Tunggu 1 jam sebelum booking lagi.'
        ]);
        exit;
    }
}
$stmtLast->close();


$nowDateTime = date('Y-m-d H:i:s');
$stmtInsert = $conn->prepare("INSERT INTO jadwal_booking (nim, hari, pemain, waktu, unit, created_time) VALUES (?, ?, ?, ?, ?, ?)");
$stmtInsert->bind_param('ssisss', $nim, $hari, $pemain, $waktu, $unit, $nowDateTime);

if (!$stmtInsert->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan jadwal: ' . $stmtInsert->error]);
    exit;
}

$last_id = $stmtInsert->insert_id;
$stmtInsert->close();

$prefix = '';
if (stripos($unit, 'PS-1') !== false || stripos($unit, 'PlayStation') !== false) {
    $prefix = 'PS1';
} elseif (stripos($unit, 'PS-2') !== false) {
    $prefix = 'PS2';
} elseif (stripos($unit, 'XBOX-1') !== false) {
    $prefix = 'XB1';
} elseif (stripos($unit, 'XBOX-2') !== false) {
    $prefix = 'XB2';
} elseif (stripos($unit, 'PC') !== false) {
    $prefix = 'PC';
} elseif (stripos($unit, 'Dance Pad') !== false) {
    $prefix = 'DP';
}

$booking_id = $prefix . '-' . str_pad($last_id, 4, '0', STR_PAD_LEFT);

$stmtUpdate = $conn->prepare("UPDATE jadwal_booking SET booking_id=? WHERE id=?");
$stmtUpdate->bind_param('si', $booking_id, $last_id);
$stmtUpdate->execute();
$stmtUpdate->close();

$conn->close();

echo json_encode([
    'status' => 'success',
    'booking_id' => $booking_id
]);
