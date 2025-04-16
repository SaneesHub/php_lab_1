<?php
    $allowedAnimals = ["Тигр", "Слон", "Обезьяна", "Лев", "Медведь", "Жираф"];

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        die("Ошибка: неверный метод запроса!");
    }

    if (empty($_POST["animal"]) || empty($_POST["cage"]) || empty($_POST["conditions"])) {
        die("Ошибка: Все поля должны быть заполнены!");
    }

    $animal = htmlspecialchars(trim($_POST["animal"]), ENT_QUOTES, 'UTF-8');
    $cage = htmlspecialchars(trim($_POST["cage"]), ENT_QUOTES, 'UTF-8');
    $conditions = htmlspecialchars(trim($_POST["conditions"]), ENT_QUOTES, 'UTF-8');

    if (!in_array($animal, $allowedAnimals)) {
        die("Ошибка: выберите животное из списка!");
    }

    if (!is_numeric($cage) || (int)$cage <= 0) {
        die("Ошибка: номер клетки должен быть положительным числом!");
    }

    $file = fopen("data.csv", "a+");
    if ($file === false) {
        die("Ошибка: не удалось открыть файл для записи!");
    }
    fputcsv($file, [$animal, $cage, $conditions]);
    fclose($file);

    header("Location: index.php?success=1");
    exit();
?>