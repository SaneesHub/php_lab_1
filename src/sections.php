<?php
require_once 'db.php';

// Определяем названия секций
$sectionNames = [
    'mammals' => 'Млекопитающие',
    'reptiles' => 'Рептилии',
    'birds' => 'Птицы'
];

// Инициализируем переменную
$sectionData = [];

try {
    // Получаем данные из БД и группируем по секциям
    $stmt = $pdo->query("
        SELECT 
            section,
            animal,
            cage,
            COUNT(*) as count
        FROM zoo_db
        GROUP BY section, animal, cage
        ORDER BY section, animal
    ");
    
    // Группируем результаты по секциям
    while ($row = $stmt->fetch()) {
        $section = $row['section'];
        if (!isset($sectionData[$section])) {
            $sectionData[$section] = [
                'name' => $sectionNames[$section] ?? $section,
                'animals' => []
            ];
        }
        $sectionData[$section]['animals'][] = $row;
    }
    
    // Если данных нет - создаем пустые секции
    foreach ($sectionNames as $key => $name) {
        if (!isset($sectionData[$key])) {
            $sectionData[$key] = [
                'name' => $name,
                'animals' => []
            ];
        }
    }
    
} catch (PDOException $e) {
    die("Ошибка при получении данных: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Секции зоопарка</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .section h2 {
            color: #2c3e50;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .animal-group {
            margin: 15px 0;
            padding: 15px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .animal-name {
            font-weight: bold;
            font-size: 18px;
            color: #2c3e50;
        }
        .cage-info {
            margin-left: 20px;
            margin-top: 8px;
        }
        .cage-number {
            display: inline-block;
            width: 100px;
            font-weight: bold;
        }
        .animal-count {
            color: #666;
            font-style: italic;
        }
        .nav-links {
            margin-top: 30px;
            text-align: center;
        }
        .nav-links a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 15px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Секции нашего зоопарка</h1>
        
        <?php foreach ($sectionData as $sectionId => $section): ?>
        <div class="section">
            <h2><?= htmlspecialchars($section['name']) ?></h2>
            
            <?php if (empty($section['animals'])): ?>
                <p>В этой секции пока нет животных</p>
            <?php else: ?>
                <?php foreach ($section['animals'] as $animal): ?>
                <div class="animal-group">
                    <div class="animal-name"><?= htmlspecialchars($animal['animal']) ?></div>
                    <div class="cage-info">
                        <span class="cage-number">Клетка №<?= htmlspecialchars($animal['cage']) ?></span>
                        <?php if ($animal['count'] > 1): ?>
                        <span class="animal-count">(<?= $animal['count'] ?> особи)</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        
        <div class="nav-links">
            <a href="index.php">Добавить животное</a>
            <a href="view.php">Просмотреть всех животных</a>
            <a href="tickets.php">Купить билеты</a>
        </div>
    </div>
</body>
</html>