<?php
require_once 'db.php';

try {
    if (($handle = fopen("data.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) < 3) continue;
            [$animal, $cage, $conditions] = $data;
            $stmt = $pdo->prepare("INSERT INTO zoo_db (animal, cage, condition_zoo) VALUES (?, ?, ?)");
            $stmt->execute([
                htmlspecialchars(trim($animal), ENT_QUOTES, 'UTF-8'),
                (int)$cage,
                htmlspecialchars(trim($conditions), ENT_QUOTES, 'UTF-8')
            ]);
        }
        fclose($handle);
        echo "Данные успешно импортированы!";
    } else {
        echo "Не удалось открыть файл data.csv";
    }
} catch (PDOException $e) {
    die("Ошибка при импорте: " . $e->getMessage());
}
?>