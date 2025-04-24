<?php
require_once 'db.php';

$allowedAnimals = ["Тигр", "Слон", "Обезьяна", "Лев", "Медведь", "Жираф", "Сокол", "Хамелеон", "Варан", "Бегемот", "Крокодил"];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Ошибка: неверный метод запроса!");
}

if (empty($_POST["animal"]) || empty($_POST["cage"]) || empty($_POST["conditions"])) {
    die("Ошибка: Все поля должны быть заполнены!");
}

$animal = htmlspecialchars(trim($_POST["animal"]), ENT_QUOTES, 'UTF-8');
$cage = (int)$_POST["cage"];
$conditions = htmlspecialchars(trim($_POST["conditions"]), ENT_QUOTES, 'UTF-8');

$animal = mb_convert_case($animal, MB_CASE_TITLE, "UTF-8");
if (!in_array($animal, $allowedAnimals)) {
    die("Ошибка: выберите животное из списка!");
}

if ($cage <= 0) {
    die("Ошибка: номер клетки должен быть положительным числом!");
}

try {
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM zoo_db 
                               WHERE animal = ? AND cage = ? AND condition_zoo = ?");
    $checkStmt->execute([$animal, $cage, $conditions]);
    $exists = $checkStmt->fetchColumn();
    
    if ($exists > 0) {
        die("Ошибка: такая запись уже существует (животное, клетка и условия совпадают)");
    }

    $insertStmt = $pdo->prepare("INSERT INTO zoo_db (animal, cage, condition_zoo) VALUES (?, ?, ?)");
    $insertStmt->execute([$animal, $cage, $conditions]);
    
    header("Location: index.php?success=1");
    exit;
} catch (PDOException $e) {
    die("Ошибка сохранения: " . $e->getMessage());
}
?>