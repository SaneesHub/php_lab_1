<?php
namespace App\Model;

use App\Core\Database;
use InvalidArgumentException;
class TicketsModel {
    private $pdo;
    private $sections = [
        'mammals' => ['price' => 500, 'name' => 'Млекопитающие'],
        'reptiles' => ['price' => 400, 'name' => 'Рептилии'],
        'birds' => ['price' => 300, 'name' => 'Птицы']
    ];

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create(array $data): bool {
        if (!array_key_exists($data['section'], $this->sections)) {
            throw new InvalidArgumentException("Неверная секция");
        }

        $total = $this->sections[$data['section']]['price'] * (int)$data['quantity'];

        $stmt = $this->pdo->prepare(
            "INSERT INTO zoo_tickets 
             (section, quantity, visit_date, customer_name, customer_email, total_price) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['section'],
            (int)$data['quantity'],
            $data['visit_date'],
            htmlspecialchars($data['customer_name']),
            filter_var($data['customer_email'], FILTER_SANITIZE_EMAIL),
            $total
        ]);
    }

    public function getSections(): array {
        return $this->sections;
    }
    public function getUserTickets($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM zoo_tickets WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }    
}