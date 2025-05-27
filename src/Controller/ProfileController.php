<?php
namespace App\Controller;

use App\Model\TicketsModel;
use App\Model\AnimalModel;
use App\Model\UserModel;

class ProfileController
{
    private $ticketsModel;
    private $animalModel;
    private $userModel;

    public function __construct()
    {
        $this->ticketsModel = new TicketsModel();
        $this->animalModel = new AnimalModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $this->checkAuth();
        $user = $_SESSION['user'];
        require __DIR__.'/../View/profile/dashboard.php';
    }

    public function tickets()
    {
        $this->checkAuth();
        $tickets = $this->ticketsModel->getUserTickets($_SESSION['user']['id']);
        require __DIR__.'/../View/profile/tickets.php';
    }

    public function sections()
    {
        $this->checkAuth();
        if ($_SESSION['user']['role'] !== 'keeper') {
            header("Location: /profile");
            exit;
        }
        $sections = $this->animalModel->getKeeperSections($_SESSION['user']['id']);
        require __DIR__.'/../View/profile/sections.php';
    }

    public function roles()
    {
        $this->checkAuth();
        if ($_SESSION['user']['role'] !== 'admin') {
            header("Location: /profile");
            exit;
        }
        $users = $this->userModel->getAllUsers();
        require __DIR__.'/../View/profile/roles.php';
    }
    public function updateRole()
    {
        $this->checkAuth();
        
        if ($_SESSION['user']['role'] !== 'admin') {
            header("Location: /profile");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->updateRole(
                $_POST['user_id'],
                $_POST['role']
            );
        }

        header("Location: /profile/roles");
        exit;
    }
    private function checkAuth()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }
    }
}