<?php
namespace App\Controller;

use App\Model\UserModel;
use RuntimeException;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

   public function register()
    {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $this->userModel->registerUser($username, $password);
            
            $_SESSION['success'] = 'Регистрация успешна!';
            header("Location: /login");
            exit;
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
    }
    
    require __DIR__ . '/../View/auth/register.php';
    }

    public function login()
    {
        // Отладочный вывод
        error_log("Login method called");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("POST data: " . print_r($_POST, true));
            
            try {
                $user = $this->userModel->verifyUser(
                    $_POST['username'],
                    $_POST['password']
                );
                
                if ($user) {
                    $_SESSION['user'] = $user;
                    error_log("User authenticated: " . print_r($user, true));
                    
                    // Явная переадресация с отладкой
                    error_log("Redirecting to /view");
                    header("Location: /view");
                    exit;
                } else {
                    $error = "Неверные учетные данные";
                    error_log("Authentication failed");
                }
            } catch (RuntimeException $e) {
                $error = "Ошибка системы";
                error_log("Error: " . $e->getMessage());
            }
        }
        
        require __DIR__.'/../View/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
        exit;
    }
}