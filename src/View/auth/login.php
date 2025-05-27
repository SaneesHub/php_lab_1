<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <div class="container">
        <h1>Вход в систему зоопарка</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="/login">
            <label for="username">Логин:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Пароль (минимум 6 символов):</label>
            <input type="password" id="password" name="password" required minlength="6">

            <button type="submit" class="btn">Войти</button>
        </form>
        <div style="margin-top: 20px;">
            Нет аккаунта? <a href="/register">Зарегистрироваться</a>
        </div>
    </div>
</body>
</html>