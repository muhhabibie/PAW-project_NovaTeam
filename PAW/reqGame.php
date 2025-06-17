<?php
require_once "Core/Session.php";
require_once "Core/Database.php";
require_once "Models/Game.php";
require_once "Controllers/GameController.php";

Session::start();
Session::redirectIfNotLoggedIn(); // ✅ Akan mengembalikan JSON jika diminta dari fetch()

try {
    $db = new Database("localhost", "root", "", "gc_db");
    $conn = $db->getConnection();

    $model = new Game($conn);
    $controller = new GameController($model);

    $nama = $_POST['nama_gim'] ?? '';
    $tahun = (int) ($_POST['tahun_rilis'] ?? 0);
    $pengembang = $_POST['pengembang'] ?? '';
    $nim = Session::get('nim');

    $result = $controller->ajukan($nama, $tahun, $pengembang, $nim);
    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "title" => "Server Error",
        "message" => $e->getMessage()
    ]);
}
