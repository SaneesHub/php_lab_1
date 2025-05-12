<?php
require_once 'db.php';

// Единый маппинг животных на секции
$animalSectionMap = [
    // Млекопитающие
    'Тигр' => 'mammals',
    'Слон' => 'mammals',
    'Обезьяна' => 'mammals',
    'Лев' => 'mammals',
    'Медведь' => 'mammals',
    'Жираф' => 'mammals',
    'Бегемот' => 'mammals',
    
    // Рептилии
    'Хамелеон' => 'reptiles',
    'Варан' => 'reptiles',
    'Крокодил' => 'reptiles',
    
    // Птицы
    'Сокол' => 'birds'
];

// Опции для select
$sectionOptions = [
    'mammals' => 'Млекопитающие',
    'reptiles' => 'Рептилии',
    'birds' => 'Птицы'
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $animal = trim($_POST["animal"]);
    $cage = (int)$_POST["cage"];
    $conditions = trim($_POST["conditions"]);
    $selectedSection = $_POST["section"];
    
    // Нормализуем регистр для сравнения
    $normalizedAnimal = mb_convert_case($animal, MB_CASE_TITLE, "UTF-8");
    
    // Проверяем существует ли животное в системе
    if (!array_key_exists($normalizedAnimal, $animalSectionMap)) {
        die("Ошибка: животное '$normalizedAnimal' не поддерживается в системе");
    }
    
    // Получаем правильную секцию для этого животного
    $correctSection = $animalSectionMap[$normalizedAnimal];
    
    // Сравниваем с выбранной секцией
    if ($selectedSection !== $correctSection) {
        $correctSectionName = $sectionOptions[$correctSection];
        $selectedSectionName = $sectionOptions[$selectedSection];
        die("Ошибка: $normalizedAnimal относится к секции '$correctSectionName', а не к '$selectedSectionName'");
    }
    
    // Если проверки пройдены - сохраняем
    try {
        $stmt = $pdo->prepare("INSERT INTO zoo_db (animal, cage, condition_zoo, section) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            htmlspecialchars($normalizedAnimal, ENT_QUOTES, 'UTF-8'),
            $cage,
            htmlspecialchars($conditions, ENT_QUOTES, 'UTF-8'),
            $selectedSection
        ]);
        header("Location: view.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Ошибка сохранения: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить животное</title>
    <style>
        body {
            background-color: #f4f1de;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            width: 40%;
            margin: 50px auto;
            background-color: #8fc1a9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #2c3e50;
        }
        a {
            text-decoration: none;
        }
        label {
            font-weight: bold;
            color: #3b3b3b;
            display: block;
            margin-top: 10px;
        }
        select, input, textarea {
            width: 95%;
            padding: 10px;
            margin:8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #d88c3d;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 15px;
        }
        button:hover {
            background-color: #a15d2c;
        }
        .btn { 
            display: inline-block; padding: 10px 15px; margin: 5px;
            background: #4CAF50; color: white; text-decoration: none;
            border-radius: 5px;
        }
        .success {
            color: green;
            margin: 15px 0;
        }
        table { 
            width: 100%; border-collapse: collapse; margin-top: 20px; 
        }
        th, td { 
            padding: 8px; border: 1px solid #ddd; text-align: left; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Добавить животное</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="animal">Название животного:</label>
            <select id="animal" name="animal" required>
                <option value="">-- Выберите животное --</option>
                <?php foreach (array_keys($animalSectionMap) as $animal): ?>
                    <option value="<?= htmlspecialchars($animal) ?>"><?= htmlspecialchars($animal) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Секция:</label>
            <select name="section" id="sectionSelect" required>
                <?php foreach ($sectionOptions as $value => $label): ?>
                    <option value="<?= $value ?>"><?= $label ?></option>
                <?php endforeach; ?>
            </select>

            <label for="cage">Номер клетки:</label>
            <input type="number" id="cage" name="cage" min="1" required>

            <label for="conditions">Условия содержания:</label>
            <textarea id="conditions" name="conditions" required></textarea>

            <button type="submit">Сохранить</button>
        </form>
        <div class="action-buttons">
            <a href="view.php" class="btn">Просмотреть данные из БД</a>
            <a href="sections.php" class="btn">Посмотреть секции</a>
        </div>
    </div>
    <div style="margin-top: 20px;">
        <a href="tickets.php" style="display: inline-block; padding: 10px 15px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px;">
            Купить билеты в зоопарк
        </a>
    </div>
    <script>
        function validateForm() {
            let cage = document.getElementById("cage").value.trim();
            let conditions = document.getElementById("conditions").value.trim();

            if (!cage.match(/^\d+$/) || parseInt(cage) <= 0) {
                alert("Ошибка: номер клетки должен быть положительным числом!");
                return false;
            }

            if (conditions === "") {
                alert("Ошибка: заполните условия содержания!");
                return false;
            }

            return true;
        }
        
        // Автоматическое обновление секции при выборе животного
        document.getElementById('animal').addEventListener('change', function() {
            const animal = this.value;
            const sectionSelect = document.getElementById('sectionSelect');
            const animalMap = <?= json_encode($animalSectionMap) ?>;
            
            if (animalMap[animal]) {
                sectionSelect.value = animalMap[animal];
            }
        });
    </script>
</body>
</html>