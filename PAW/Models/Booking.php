<?php
class Booking {
    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function getBookedTimes($hari, $unit) {
        $stmt = $this->conn->prepare("SELECT waktu FROM jadwal_booking WHERE hari = ? AND unit = ?");
        $stmt->bind_param('ss', $hari, $unit);
        $stmt->execute();
        $result = $stmt->get_result();
        $waktu = [];
        while ($row = $result->fetch_assoc()) {
            $waktu[] = $row['waktu'];
        }
        return $waktu;
    }

    public function isAlreadyBooked($hari, $waktu, $unit) {
        $stmt = $this->conn->prepare("SELECT 1 FROM jadwal_booking WHERE hari = ? AND waktu = ? AND unit = ? LIMIT 1");
        $stmt->bind_param('sss', $hari, $waktu, $unit);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function lastBookingTime($nim) {
        $stmt = $this->conn->prepare("SELECT created_time FROM jadwal_booking WHERE nim = ? ORDER BY created_time DESC LIMIT 1");
        $stmt->bind_param('s', $nim);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['created_time'] ?? null;
    }

    public function insertBooking($nim, $hari, $pemain, $waktu, $unit) {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare("INSERT INTO jadwal_booking (nim, hari, pemain, waktu, unit, created_time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssisss', $nim, $hari, $pemain, $waktu, $unit, $now);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return [$id, $now];
    }

    public function updateBookingId($id, $booking_id) {
        $stmt = $this->conn->prepare("UPDATE jadwal_booking SET booking_id = ? WHERE id = ?");
        $stmt->bind_param('si', $booking_id, $id);
        return $stmt->execute();
    }

    public function generateBookingId($unit, $id) {
        $prefix = match(true) {
            stripos($unit, 'PS-1') !== false => 'PS1',
            stripos($unit, 'PS-2') !== false => 'PS2',
            stripos($unit, 'XBOX-1') !== false => 'XB1',
            stripos($unit, 'XBOX-2') !== false => 'XB2',
            stripos($unit, 'PC') !== false => 'PC',
            stripos($unit, 'Dance Pad') !== false => 'DP',
            default => 'UN'
        };
        return $prefix . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
    }
}
