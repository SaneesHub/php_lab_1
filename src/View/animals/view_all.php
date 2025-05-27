<?php
$sectionNames = [
    'mammals' => 'Млекопитающие',
    'reptiles' => 'Рептилии',
    'birds' => 'Птицы'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Просмотр всех животных</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['user'])): ?>
            <a href="/profile" class="btn">Мой профиль</a>
        <?php endif; ?>
        <h1>Все животные в зоопарке</h1>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Операция выполнена успешно!</div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Животное</th>
                    <th>Секция</th>
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
                        <a href="/view?delete_id=<?= $animal['id'] ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Вы уверены?')">
                            Удалить
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="nav-links">
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <a href="/" class="btn">Добавить животное</a>
            <?php endif; ?>
            <a href="/sections" class="btn">Посмотреть секции</a>
            <a href="/tickets" class="btn">Купить билеты</a>
        </div>
    </div>
</body>
</html>