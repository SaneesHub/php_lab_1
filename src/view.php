<?php
require_once 'db.php';
$sectionNames = [
    'mammals' => 'Млекопитающие',
    'reptiles' => 'Рептилии',
    'birds' => 'Птицы'
];
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM zoo_db WHERE id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: view.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Ошибка при удалении: " . $e->getMessage());
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM zoo_db ORDER BY id DESC");
    $animals = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Ошибка при получении данных: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Просмотр всех животных</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
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
            transition: background-color 0.3s;
        }
        .nav-links a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Все животные в зоопарке</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="message alert-success">
                Операция выполнена успешно!
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Секция</th>
                    <th>Животное</th>
                    <th>Клетка</th>
                    <th>Условия содержания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animals as $animal): ?>
                <tr>
                    <td><?= htmlspecialchars($animal['id']) ?></td>
                    <td><?= htmlspecialchars($animal['animal']) ?></td>
                    <td><?= htmlspecialchars($sectionNames[$animal['section']] ?? 'Неизвестно') ?></td>
                    <td><?= htmlspecialchars($animal['cage']) ?></td>
                    <td><?= htmlspecialchars($animal['condition_zoo']) ?></td>
                    <td>
                        <a href="view.php?delete_id=<?= $animal['id'] ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Вы уверены, что хотите удалить эту запись?')">
                            Удалить
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="nav-links">
            <a href="index.php">Добавить животное</a>
            <a href="sections.php">Посмотреть секции</a>
            <a href="tickets.php">Купить билеты</a>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Вы уверены, что хотите удалить эту запись?");
        }
    </script>
</body>
</html>