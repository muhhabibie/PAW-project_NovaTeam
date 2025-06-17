<?php
class GameController {
    private $model;

    public function __construct(Game $model) {
        $this->model = $model;
    }

    public function ajukan($nama_gim, $tahun_rilis, $pengembang, $nim) {
        if (empty($nama_gim) || empty($tahun_rilis) || empty($pengembang)) {
            return [
                "status" => "error",
                "title" => "Gagal",
                "message" => "Semua field harus diisi!"
            ];
        }

        [$success, $error] = $this->model->insert($nama_gim, $tahun_rilis, $pengembang, $nim);
        if ($success) {
            return [
                "status" => "success",
                "title" => "Berhasil",
                "message" => "Game berhasil diajukan!",
                "redirect" => "reqGame.html"
            ];
        } else {
            return [
                "status" => "error",
                "title" => "Gagal",
                "message" => "Gagal menyimpan data: $error"
            ];
        }
    }
}
