<?php
class JadwalController {
    private $model;

    public function __construct(Jadwal $model) {
        $this->model = $model;
    }

    public function index($nim) {
        if (!$nim) {
            return ['status' => 'error', 'message' => 'User belum login'];
        }

        $data = $this->model->getByNIM($nim);
        return ['status' => 'success', 'data' => $data];
    }
}
