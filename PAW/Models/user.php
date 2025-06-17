<?php
class User {
    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function findByCredentials($nim, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE nim = ? AND password = ?");
        $stmt->bind_param("ss", $nim, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
