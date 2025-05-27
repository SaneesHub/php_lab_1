<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <div class="container">
        <h1>Регистрация в системе</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <label for="username">Логин (минимум 3 символа):</label>
            <input type="text" id="username" name="username" required minlength="3">

            <label for="password">Пароль (минимум 6 символов):</label>
            <input type="password" id="password" name="password" required minlength="6">

            <label for="password_confirm">Подтвердите пароль:</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
            
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <label for="role">Роль:</label>
                <select id="role" name="role">
                    <option value="visitor">Посетитель</option>
                    <option value="keeper">Смотритель</option>
                    <option value="admin">Администратор</option>
                </select>
            <?php endif; ?>
            
            <button type="submit" class="btn">Зарегистрироваться</button>
        </form>
        
        <div style="margin-top: 20px;">
            Уже есть аккаунт? <a href="/login">Войти</a>
        </div>
    </div>
</body>
</html>