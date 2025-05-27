<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Купить билеты в зоопарк</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['user'])): ?>
            <a href="/profile" class="btn">Мой профиль</a>
        <?php endif; ?>
        <h1>Купить билеты в зоопарк</h1>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Билеты успешно оформлены!</div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="POST">
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
                <p>Животные: 
                    <?= isset($section['animals']) && is_array($section['animals']) 
                    ? htmlspecialchars(implode(', ', $section['animals'])) 
                    : 'Нет данных' ?>
                </p>

            </div>
            <?php endforeach; ?>
            
            <label for="quantity">Количество билетов (1-10):</label>
            <input type="number" name="quantity" min="1" max="10" value="<?= $_POST['quantity'] ?? 1 ?>" required>
            
            <label for="visit_date">Дата посещения:</label>
            <input type="date" name="visit_date" required min="<?= date('Y-m-d') ?>"
                   value="<?= $_POST['visit_date'] ?? '' ?>">
            
            
            <div class="action-buttons">
                <button type="submit">Купить билеты</button>
                <a href="/view" class="btn">Просмотреть данные из БД</a>
                <a href="/sections" class="btn">Посмотреть секции</a>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <a href="/" class="btn">Добавить животное</a>
                <?php endif; ?>
            </div>
        </form>
        
    </div>
</body>
</html>