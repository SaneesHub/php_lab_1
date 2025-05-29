<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <h3>Закрепленные секции</h3>
    <ul class="sections-list">
        <?php foreach ($sections as $section): ?>
        <li>
            <h4><?= htmlspecialchars($section['section']) ?></h4>
            <p>Животных: <?= $section['animal_count'] ?></p>
            <a href="/animals?section=<?= urlencode($section['section']) ?>" class="btn">
                Просмотреть животных
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
