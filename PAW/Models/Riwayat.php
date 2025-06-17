<?php
class Riwayat {
    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function getByNIM($nim) {
        $stmt = $this->conn->prepare(
            "SELECT booking_id, hari, pemain, waktu, unit, created_time FROM jadwal_booking
             WHERE nim = ? ORDER BY created_time DESC"
        );
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $hari = strtotime($row['hari']);
            $data[] = [
                'booking_id' => $row['booking_id'],
                'hari'       => $row['hari'],
                'pemain'     => $row['pemain'],
                'waktu'      => $row['waktu'],
                'unit'       => $row['unit'],
                'day'        => date('d', $hari),
                'month'      => date('M', $hari),
                'year'       => date('Y', $hari)
            ];
        }

        return $data;
    }
}
