<?php
class UserProfile {
    private $nim;
    private $nama;
    private $angkatan;

    public function __construct($nim, $nama, $angkatan) {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->angkatan = $angkatan;
    }

    public function getNIM() {
        return $this->nim;
    }

    public function getNama() {
        return $this->nama;
    }

    public function getAngkatan() {
        return $this->angkatan;
    }
}
