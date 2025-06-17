<?php
session_start();
header('Content-Type: application/json');

require_once "Models/User.php";
require_once "Controllers/AuthController.php";


$conn = new mysqli('localhost', 'root', '', 'gc_db');
if ($conn->connect_error) {
    echo json_encode([
        'status' => 'error',
        'title' => 'Koneksi Gagal',
        'message' => 'Tidak bisa konek ke database.'
    ]);
    exit;
}


$nim = $_POST['nim'] ?? '';
$password = $_POST['password'] ?? '';

$userModel = new User($conn);
$auth = new AuthController($userModel);

$response = $auth->login($nim, $password);
echo json_encode($response);
