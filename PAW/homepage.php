<?php
session_start();
header('Content-Type: application/json');

require_once "Core/Database.php";
require_once "Models/Jadwal.php";
require_once "Controllers/JadwalController.php";

try {
    if (!isset($_SESSION['nim'])) {
        echo json_encode(['status' => 'error', 'message' => 'User belum login']);
        exit;
    }

    $nim = $_SESSION['nim'];

    $db = new Database('localhost', 'root', '', 'gc_db');
    $conn = $db->getConnection();

    $model = new Jadwal($conn);
    $controller = new JadwalController($model);

    $response = $controller->index($nim);
    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
