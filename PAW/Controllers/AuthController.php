<?php
class AuthController {
    private $userModel;

    public function __construct(User $userModel) {
        $this->userModel = $userModel;
    }   

    public function login($nim, $password) {
        if (empty($nim) || empty($password)) {
            return $this->response("error", "Error", "NIM dan Password harus diisi!");
        }

        $user = $this->userModel->findByCredentials($nim, $password);
        if ($user) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['nim'] = $user['nim'];
            $_SESSION['angkatan'] = $user['angkatan'];

            return $this->response("success", "Login Berhasil", "Selamat datang, " . htmlspecialchars($user['nama']), "homepage.html");
        } else {
            return $this->response("error", "Login Gagal", "NIM atau password salah.");
        }
    }

    private function response($status, $title, $message, $redirect = null) {
        return [
            "status" => $status,
            "title" => $title,
            "message" => $message,
            "redirect" => $redirect
        ];
    }
}
