<?php
session_start();
header('Content-Type: application/json');

require_once "Core/Database.php";
require_once "Models/Booking.php";
require_once "Controllers/BookingController.php";

try {
    $db = new Database('localhost', 'root', '', 'gc_db');
    $conn = $db->getConnection();

    $booking = new Booking($conn);
    $controller = new BookingController($booking);

    $data = json_decode(file_get_contents('php://input'), true);
    $hari = $data['hari'] ?? '';
    $unit = $data['unit'] ?? '';

    $result = $controller->cekTerbooking($hari, $unit);
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
