<?php
date_default_timezone_set('Asia/Jakarta');

session_start();
header('Content-Type: application/json');

require_once "Core/Database.php";
require_once "Models/Booking.php";
require_once "Controllers/BookingController.php";

if (!isset($_SESSION['nim'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User belum login'
    ]);
    exit;
}

try {
    $db = new Database('localhost', 'root', '', 'gc_db');
    $conn = $db->getConnection();

    $booking = new Booking($conn);
    $controller = new BookingController($booking);

    $data = json_decode(file_get_contents('php://input'), true);
    $nim = $_SESSION['nim'];
    $hari = $data['hari'] ?? '';
    $pemain = (int)($data['pemain'] ?? 0);
    $waktu = $data['waktu'] ?? '';
    $unit = $data['unit'] ?? '';

    $result = $controller->daftar($nim, $hari, $pemain, $waktu, $unit);
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
