<?php
class Jadwal {
    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function getByNIM($nim) {
        $stmt = $this->conn->prepare("SELECT booking_id, hari, pemain, waktu, unit FROM jadwal_booking WHERE nim = ? ORDER BY hari, waktu");
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();

        $jadwal = [];
        while ($row = $result->fetch_assoc()) {
            $jadwal[] = $row;
        }

        return $jadwal;
    }
}
