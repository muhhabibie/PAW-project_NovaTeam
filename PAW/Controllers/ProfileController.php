<?php
class ProfileController {
    public function getUserFromSession() {
        $nim = Session::get('nim');
        $nama = Session::get('nama');
        $angkatan = Session::get('angkatan');

        if ($nim && $nama && $angkatan) {
            return new UserProfile($nim, $nama, $angkatan);
        }

        return null;
    }
}
