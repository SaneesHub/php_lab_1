<?php
namespace App\Core;

class AuthMiddleware {
    public function handle() {
        if (empty($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }
    }
    
    public function requireRole($role) {
        if ($_SESSION['user']['role'] !== $role) {
            http_response_code(403);
            die("Доступ запрещен");
        }
    }
}