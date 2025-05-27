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
    <title>Секции зоопарка</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <div class="container">
    <?php if (isset($_SESSION['user'])): ?>
        <a href="/profile/dashboard-actions" class="btn">Мой профиль</a>
    <?php endif; ?>
        <h1>Секции нашего зоопарка</h1>
        <?php foreach ($sections as $sectionId => $section): ?>
        <div class="section">
            <h2><?= htmlspecialchars($sectionNames[$sectionId] ?? 'Неизвестная секция') ?></h2>
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
        </div>
        <?php endforeach; ?>
        
        <div class="nav-links">
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <a href="/" class="btn">Добавить животное</a>
            <?php endif; ?>
            <a href="/view" class="btn">Просмотреть всех животных</a>
            <a href="/tickets" class="btn">Купить билеты</a>
        </div>
    </div>
</body>
</html>