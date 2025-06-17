<?php
class BookingController {
    private $booking;

    public function __construct(Booking $booking) {
        $this->booking = $booking;
    }

    public function cekTerbooking($hari, $unit) {
        if (!$hari || !$unit) {
            return ['status' => 'error', 'message' => 'Parameter tidak lengkap'];
        }

        $booked = $this->booking->getBookedTimes($hari, $unit);
        return ['status' => 'success', 'bookedTimes' => $booked];
    }

    public function daftar($nim, $hari, $pemain, $waktu, $unit) {
        if (!$hari || !$waktu || !$unit || $pemain <= 0) {
            return ['status' => 'error', 'message' => 'Data input tidak lengkap atau tidak valid'];
        }

        if ($this->booking->isAlreadyBooked($hari, $waktu, $unit)) {
            return ['status' => 'error', 'message' => 'Jadwal sudah dibooking untuk unit ini.'];
        }

        $lastTime = $this->booking->lastBookingTime($nim);
        if ($lastTime && (time() - strtotime($lastTime)) < 3600) {
            return ['status' => 'error', 'message' => 'Kamu sudah booking. Tunggu 1 jam sebelum booking lagi.'];
        }

        [$lastId, $_] = $this->booking->insertBooking($nim, $hari, $pemain, $waktu, $unit);
        $bookingId = $this->booking->generateBookingId($unit, $lastId);
        $this->booking->updateBookingId($lastId, $bookingId);

        return ['status' => 'success', 'booking_id' => $bookingId];
    }
}
