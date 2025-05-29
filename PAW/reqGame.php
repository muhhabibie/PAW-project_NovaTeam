<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['nim'])) {
    echo json_encode([
        "status" => "error",
        "title" => "Akses Ditolak",
        "message" => "Harap login terlebih dahulu",
        "redirect" => "login.html"
    ]);
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "gc_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "title" => "Koneksi Gagal",
        "message" => $conn->connect_error
    ]);
    exit;
}

$nama_gim = $_POST['nama_gim'] ?? '';
$tahun_rilis = $_POST['tahun_rilis'] ?? 0;
$pengembang = $_POST['pengembang'] ?? '';
$nim = $_SESSION['nim'];

if (empty($nama_gim) || empty($tahun_rilis) || empty($pengembang)) {
    echo json_encode([
        "status" => "error",
        "title" => "Gagal",
        "message" => "Semua field harus diisi!"
    ]);
    exit;
}

$sql = "INSERT INTO games (nama_gim, tahun_rilis, pengembang, nim) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siss", $nama_gim, $tahun_rilis, $pengembang, $nim);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "title" => "Berhasil",
        "message" => "Game berhasil diajukan!",
        "redirect" => "reqGame.html"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "title" => "Gagal",
        "message" => "Gagal menyimpan data: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
