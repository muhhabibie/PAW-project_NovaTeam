<?php
class FeedbackController {
    private $model;

    public function __construct(Feedback $model) {
        $this->model = $model;
    }

    public function kirim($nim, $pengalaman, $catatan) {
        if (empty($pengalaman)) {
            return [
                "status" => "warning",
                "title" => "Input Tidak Valid",
                "message" => "Kolom pengalaman tidak boleh kosong."
            ];
        }

        if (strlen($pengalaman) > 50) {
            return [
                "status" => "warning",
                "title" => "Terlalu Panjang",
                "message" => "Pengalaman maksimal 50 karakter."
            ];
        }

        $success = $this->model->insert($nim, $pengalaman, $catatan);
        if ($success) {
            return [
                "status" => "success",
                "title" => "Berhasil",
                "message" => "Masukan Anda berhasil dikirim.",
                "redirect" => "kendalaDanSaran.html"
            ];
        }

        return [
            "status" => "error",
            "title" => "Gagal",
            "message" => "Gagal mengirim masukan. Silakan coba lagi."
        ];
    }
}
