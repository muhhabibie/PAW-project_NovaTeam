<?php
class Feedback {
    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function insert($nim, $pengalaman, $catatan) {
        $stmt = $this->conn->prepare("INSERT INTO feedback (nim, pengalaman, catatan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nim, $pengalaman, $catatan);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
