<?php
namespace App\Controller;

use App\Model\AnimalModel;
use App\Model\TicketsModel;
use Exception;
class ZooController {
    private $animalModel;
    private $ticketsModel;

    public function __construct() {
        $this->animalModel = new AnimalModel();
        $this->ticketsModel = new TicketsModel();
    }

    public function addAnimal()
    {
        // Проверяем авторизацию без редиректа
        if (!isset($_SESSION['user'])) {
            require __DIR__.'/../View/auth/login.php';
            return;
        }
        
        // Проверяем права без редиректа
        if ($_SESSION['user']['role'] !== 'admin') {
            $error = 'Доступ запрещен: требуется роль администратора';
            require __DIR__.'/../View/animals/add.php';
            return;
        }

        // Остальная логика метода
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->animalModel->add($_POST);
                header("Location: /view?success=1");
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        require __DIR__.'/../View/animals/add.php';
    }

    public function viewAnimals()
    {
        $success = isset($_GET['success']);
        
        if (isset($_GET['delete_id'])) {
            $this->animalModel->delete($_GET['delete_id']);
            header("Location: /view?success=1");
            exit;
        }
        
        $animals = $this->animalModel->getAll();
        require __DIR__.'/../View/animals/view_all.php';
    }

    public function showSections() {
        $sections = $this->animalModel->getGroupedBySections();
        require __DIR__.'/../View/animals/sections.php';
    }

    public function buyTickets() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->ticketsModel->create($_POST);
                header("Location: /tickets?success=1");
                exit;
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }
        $sections = $this->ticketsModel->getSections();
        require __DIR__.'/../View/tickets/buy.php';
    }
}