<?php
namespace App\Model;

use App\Core\Database;
use InvalidArgumentException;
class AnimalModel {
    private $pdo;
    private $sectionMap = [
        'Тигр' => 'mammals', 'Слон' => 'mammals',
        'Обезьяна' => 'mammals', 'Лев' => 'mammals',
        'Медведь' => 'mammals', 'Жираф' => 'mammals',
        'Бегемот' => 'mammals', 'Хамелеон' => 'reptiles',
        'Варан' => 'reptiles', 'Крокодил' => 'reptiles',
        'Сокол' => 'birds'
    ];

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function add(array $data): bool {
        if (empty($data['animal']) || !array_key_exists($data['animal'], $this->sectionMap)) {
            throw new InvalidArgumentException("Недопустимое животное");
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO zoo_db (animal, cage, condition_zoo, section) 
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['animal'],
            (int)$data['cage'],
            htmlspecialchars($data['conditions']),
            $this->sectionMap[$data['animal']]
        ]);
    }
    public function getKeeperSections($keeperId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT section, COUNT(*) as animal_count 
            FROM zoo_db 
            WHERE keeper_id = ? 
            GROUP BY section"
        );
        $stmt->execute([$keeperId]);
        return $stmt->fetchAll();
    }
    public function delete(int $id): void {
        $this->pdo->prepare("DELETE FROM zoo_db WHERE id = ?")->execute([$id]);
    }

    public function getAll(): array {
        return $this->pdo->query("SELECT * FROM zoo_db ORDER BY id DESC")->fetchAll();
    }

    public function getGroupedBySections(): array {
        $result = [];
        $stmt = $this->pdo->query("SELECT section, animal, cage, COUNT(*) as count FROM zoo_db GROUP BY section, animal, cage");
        while ($row = $stmt->fetch()) {
            $result[$row['section']]['animals'][] = $row;
        }
        return $result;
    }
}