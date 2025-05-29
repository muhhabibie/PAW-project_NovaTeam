<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['nim'])) {
    echo json_encode([
        "status" => "error",
        "title" => "Akses Ditolak",
        "message" => "Anda harus login terlebih dahulu.",
        "redirect" => "login.html"
    ]);
    exit();
}

$nim = $_SESSION['nim'];
$pengalaman = trim($_POST['pengalaman'] ?? '');
$catatan = trim($_POST['catatan'] ?? '');

if ($pengalaman === '') {
    echo json_encode([
        "status" => "warning",
        "title" => "Input Tidak Valid",
        "message" => "Kolom pengalaman tidak boleh kosong."
    ]);
    exit();
}

if (strlen($pengalaman) > 50) {
    echo json_encode([
        "status" => "warning",
        "title" => "Terlalu Panjang",
        "message" => "Pengalaman maksimal 50 karakter."
    ]);
    exit();
}

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'gc_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "title" => "Koneksi Gagal",
        "message" => "Tidak dapat terhubung ke database."
    ]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO feedback (nim, pengalaman, catatan) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nim, $pengalaman, $catatan);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "title" => "Berhasil",
        "message" => "Masukan Anda berhasil dikirim.",
        "redirect" => "kendalaDanSaran.html"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "title" => "Gagal",
        "message" => "Gagal mengirim masukan. Silakan coba lagi."
    ]);
}

$stmt->close();
$conn->close();
?>
