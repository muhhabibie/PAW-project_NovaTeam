<?php
class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function checkLogin() {
        return isset($_SESSION['nim']);
    }

    public static function redirectIfNotLoggedIn($location = "login.html") {
        if (!self::checkLogin()) {
            echo json_encode([
                "status" => "error",
                "title" => "Akses Ditolak",
                "message" => "Harap login terlebih dahulu",
                "redirect" => $location
            ]);
            exit;
        }
    }
}
