
<?php
require_once 'db.php';
// Переделать распределение секций, не через массив, а добавить колонку в дб, из которой и будет считываться секция, а также в submit-е указать каким животным какая секция присваивается
$sections = [
    'mammals' => [
        'name' => 'Млекопитающие',
        'animals' => ['Тигр', 'Слон', 'Обезьяна', 'Лев', 'Медведь', 'Жираф', 'Бегемот'],
        'price' => 500
    ],
    'reptiles' => [
        'name' => 'Рептилии',
        'animals' => ['Хамелеон', 'Варан', 'Крокодил'],
        'price' => 400
    ],
    'birds' => [
        'name' => 'Птицы',
        'animals' => ['Сокол'],
        'price' => 300
    ]
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $section = $_POST['section'] ?? '';
    $quantity = (int)($_POST['quantity'] ?? 1);
    $visit_date = $_POST['visit_date'] ?? '';
    $customer_name = htmlspecialchars(trim($_POST['customer_name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $customer_email = filter_var(trim($_POST['customer_email'] ?? ''), FILTER_SANITIZE_EMAIL);

    $errors = [];
    
    if (!array_key_exists($section, $sections)) {
        $errors[] = "Выберите корректную секцию";
    }
    
    if ($quantity < 1 || $quantity > 10) {
        $errors[] = "Количество билетов должно быть от 1 до 10";
    }
    
    if (empty($visit_date) || strtotime($visit_date) < strtotime(date('Y-m-d'))) {
        $errors[] = "Выберите корректную дату посещения";
    }
    
    if (empty($customer_name) || strlen($customer_name) < 2) {
        $errors[] = "Введите ваше имя";
    }
    
    if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Введите корректный email";
    }

    if (empty($errors)) {
        try {
            $total_price = $quantity * $sections[$section]['price'];
            
            $stmt = $pdo->prepare("INSERT INTO zoo_tickets 
                                  (section, quantity, visit_date, customer_name, customer_email, total_price) 
                                  VALUES (?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $section,
                $quantity,
                $visit_date,
                $customer_name,
                $customer_email,
                $total_price
            ]);

            header("Location: tickets.php?success=1");
            exit;
            
        } catch (PDOException $e) {
            $errors[] = "Ошибка при сохранении данных: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Купить билеты в зоопарк</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
        h1, h2 {
            color: #2c3e50;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        select, input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        .animal-list {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .success {
            color: green;
            margin: 15px 0;
            padding: 10px;
            background: #dff0d8;
            border-radius: 4px;
        }
        .error {
            color: #721c24;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Купить билеты в зоопарк</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success">Билеты успешно оформлены! Наш сотрудник свяжется с вами для подтверждения.</div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="error"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="post">
            <h2>Контактные данные:</h2>
            <label for="customer_name">Ваше имя:</label>
            <input type="text" name="customer_name" required 
                   value="<?= htmlspecialchars($_POST['customer_name'] ?? '') ?>">
            
            <label for="customer_email">Email:</label>
            <input type="email" name="customer_email" required 
                   value="<?= htmlspecialchars($_POST['customer_email'] ?? '') ?>">
            
            <h2>Выберите секцию:</h2>
            
            <?php foreach ($sections as $key => $section): ?>
            <div class="animal-list">
                <label>
                    <input type="radio" name="section" value="<?= $key ?>" 
                           <?= ($_POST['section'] ?? '') === $key ? 'checked' : '' ?> required>
                    <strong><?= $section['name'] ?></strong> (<?= $section['price'] ?> руб./билет)
                </label>
                <p>Животные: <?= implode(', ', $section['animals']) ?></p>
            </div>
            <?php endforeach; ?>
            
            <label for="quantity">Количество билетов (1-10):</label>
            <input type="number" name="quantity" min="1" max="10" value="<?= $_POST['quantity'] ?? 1 ?>" required>
            
            <label for="visit_date">Дата посещения:</label>
            <input type="date" name="visit_date" required min="<?= date('Y-m-d') ?>"
                   value="<?= $_POST['visit_date'] ?? '' ?>">
            
            <button type="submit">Купить билеты</button>
        </form>
        
        <div style="margin-top: 20px;">
            <a href="index.php">Вернуться к форме добавления животных</a>
        </div>
    </div>
</body>
</html>