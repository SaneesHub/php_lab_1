<?php
$animalSectionMap = [
    'Тигр' => 'mammals', 'Слон' => 'mammals', 'Обезьяна' => 'mammals',
    'Лев' => 'mammals', 'Медведь' => 'mammals', 'Жираф' => 'mammals',
    'Бегемот' => 'mammals', 'Хамелеон' => 'reptiles', 'Варан' => 'reptiles',
    'Крокодил' => 'reptiles', 'Сокол' => 'birds'
];
$sectionOptions = [
    'mammals' => 'Млекопитающие',
    'reptiles' => 'Рептилии',
    'birds' => 'Птицы'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить животное</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <?php if (!isset($_SESSION['user'])): ?>
    <div class="alert alert-info">
        Для продолжения <a href="/login">войдите в систему</a>
    </div>
    <?php elseif ($_SESSION['user']['role'] !== 'admin'): ?>
        <div class="alert alert-danger">
            Доступ запрещен. Требуются права администратора.
        </div>
    <?php else: ?>
    <div class="container">
        <?php if (isset($_SESSION['user'])): ?>
            <a href="/profile" class="btn">Мой профиль</a>
        <?php endif; ?>
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
            
            <div class="action-buttons">
                <button type="submit">Сохранить</button>
                <a href="/view" class="btn">Просмотреть данные из БД</a>
                <a href="/sections" class="btn">Посмотреть секции</a>
            </div>
        </form>
    <?php endif; ?>
    </div>

    <div style="margin-top: 20px; text-align: center;">
        <a href="/tickets" class="btn btn-blue">Купить билеты в зоопарк</a>
    </div>

    <script>
        document.getElementById('animal').addEventListener('change', function() {
            const animalMap = <?= json_encode($animalSectionMap) ?>;
            document.getElementById('sectionSelect').value = animalMap[this.value];
        });
    </script>
</body>
</html>