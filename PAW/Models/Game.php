<?php
class Game {
    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function insert($nama, $tahun, $pengembang, $nim) {
        $stmt = $this->conn->prepare("INSERT INTO games (nama_gim, tahun_rilis, pengembang, nim) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $nama, $tahun, $pengembang, $nim);
        $success = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        return [$success, $error];
    }
}
