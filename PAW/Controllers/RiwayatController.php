<?php
class RiwayatController {
    private $model;

    public function __construct(Riwayat $model) {
        $this->model = $model;
    }

    public function ambilData($nim) {
        $data = $this->model->getByNIM($nim);
        return ['status' => 'success', 'data' => $data];
    }
}
