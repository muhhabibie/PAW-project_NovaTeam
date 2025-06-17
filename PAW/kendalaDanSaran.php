<?php
session_start();
header('Content-Type: application/json');

require_once "Core/Database.php";
require_once "Models/Feedback.php";
require_once "Controllers/FeedbackController.php";

if (!isset($_SESSION['nim'])) {
    echo json_encode([
        "status" => "error",
        "title" => "Akses Ditolak",
        "message" => "Anda harus login terlebih dahulu.",
        "redirect" => "login.html"
    ]);
    exit;
}

try {
    $db = new Database('localhost', 'root', '', 'gc_db');
    $conn = $db->getConnection();

    $model = new Feedback($conn);
    $controller = new FeedbackController($model);

    $nim = $_SESSION['nim'];
    $pengalaman = trim($_POST['pengalaman'] ?? '');
    $catatan = trim($_POST['catatan'] ?? '');

    $result = $controller->kirim($nim, $pengalaman, $catatan);
    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "title" => "Server Error",
        "message" => $e->getMessage()
    ]);
}
