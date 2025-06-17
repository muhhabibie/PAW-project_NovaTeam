<?php
class Database {
    private $conn;

    public function __construct($host, $user, $pass, $db) {
        $this->conn = new mysqli($host, $user, $pass, $db);
        if ($this->conn->connect_error) {
            throw new Exception("Koneksi gagal: " . $this->conn->connect_error);
        }
        $this->conn->query("SET time_zone = '+07:00'");
    }

    public function getConnection() {
        return $this->conn;
    }
}
