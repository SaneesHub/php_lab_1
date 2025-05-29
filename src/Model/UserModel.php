<?php
namespace App\Model;

use App\Core\Database;
use RuntimeException;
use InvalidArgumentException;

class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }
    public function isUsernameTaken(string $username): bool {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch() !== false;
    }

    public function registerUser(string $username, string $password): bool
    {
        if (strlen($username) < 3) {
            throw new RuntimeException('Логин слишком короткий');
        }
        
        if (strlen($password) < 6) {
            throw new RuntimeException('Пароль слишком короткий');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, password_hash, role) VALUES (?, ?, 'visitor')"
        );
        
        return $stmt->execute([$username, $hash]);
    }

    public function create($username, $password, $role = 'visitor') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$username, $hash, $role]);
    }

    public function findByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    public function getAllUsers(): array
    {
        return $this->pdo->query(
            "SELECT id, username, role FROM users ORDER BY username"
        )->fetchAll();
    }

    public function updateUserRole(int $userId, string $role): bool
    {
        $allowedRoles = ['visitor', 'keeper', 'admin'];
        if (!in_array($role, $allowedRoles)) {
            throw new InvalidArgumentException("Недопустимая роль");
        }

        $stmt = $this->pdo->prepare(
            "UPDATE users SET role = ? WHERE id = ?"
        );
        return $stmt->execute([$role, $userId]);
    }
    public function verifyUser($username, $password)
    {
        $user = $this->findByUsername($username);
        
        if (!$user) {
            error_log("User not found: " . $username);
            return false;
        }
        
        if (!password_verify($password, $user['password_hash'])) {
            error_log("Password verification failed for user: " . $username);
            return false;
        }
        
        error_log("User authenticated successfully: " . $username);
        return $user;
    }
}