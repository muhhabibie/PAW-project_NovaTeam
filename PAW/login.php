<?php
session_start();

header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'gc_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  echo json_encode([
    'status' => 'error',
    'title' => 'Koneksi Gagal',
    'message' => 'Gagal koneksi ke database: ' . $conn->connect_error
  ]);
  exit();
}

$nim = $_POST['nim'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($nim) || empty($password)) {
  echo json_encode([
    'status' => 'error',
    'title' => 'Error',
    'message' => 'NIM dan password harus diisi!'
  ]);
  exit();
}

$sql = "SELECT * FROM users WHERE nim = ? AND password = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  echo json_encode([
    'status' => 'error',
    'title' => 'Error',
    'message' => 'Gagal mempersiapkan query: ' . $conn->error
  ]);
  exit();
}

$stmt->bind_param("ss", $nim, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();


  $_SESSION['id'] = $user['id'];
  $_SESSION['nama'] = $user['nama'];
  $_SESSION['nim'] = $user['nim'];
  $_SESSION['angkatan'] = $user['angkatan'];

  echo json_encode([
    'status' => 'success',
    'title' => 'Login Berhasil',
    'message' => 'Selamat datang, ' . htmlspecialchars($user['nama']),
    'redirect' => 'homepage.html'  
  ]);
} else {
  echo json_encode([
    'status' => 'error',
    'title' => 'Login Gagal',
    'message' => 'NIM atau password salah'
  ]);
}

$stmt->close();
$conn->close();