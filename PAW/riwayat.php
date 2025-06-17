<?php
require_once "Core/Session.php";
require_once "Core/Database.php";
require_once "Models/Riwayat.php";
require_once "Controllers/RiwayatController.php";

Session::start();
Session::redirectIfNotLoggedIn();

try {
    $db = new Database("localhost", "root", "", "gc_db");
    $conn = $db->getConnection();

    $riwayatModel = new Riwayat($conn);
    $controller = new RiwayatController($riwayatModel);

    $nim = Session::get('nim');
    $response = $controller->ambilData($nim);

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
