<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой профиль</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <div class="profile-dashboard">
        <h2>Личная панель</h2>
        <div class="user-info">
            <p><strong>Имя пользователя:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Роль:</strong> 
                <?= match($user['role']) {
                    'visitor' => 'Посетитель',
                    'keeper' => 'Смотритель',
                    'admin' => 'Администратор',
                    default => 'Неизвестно'
                } ?>
            </p>
        </div>

        <div class="dashboard-actions">
            <?php if ($user['role'] === 'visitor'): ?>
                <a href="/profile/tickets" class="btn">Мои билеты</a>
            <?php elseif ($user['role'] === 'keeper'): ?>
                <a href="/profile/sections" class="btn">Мои секции</a>
            <?php elseif ($user['role'] === 'admin'): ?>
                <a href="/profile/roles" class="btn">Управление пользователями</a>
                <div class="report-actions">
                    <h3>Генерация отчетов</h3>
                    <a href="/report/pdf" class="btn btn-pdf">PDF</a>
                    <a href="/report/excel" class="btn btn-excel">Excel</a>
                    <a href="/report/csv" class="btn btn-csv">CSV</a>
                </div>
            <?php endif; ?>

            <form action="/logout" method="POST" class="logout-form">
                <button type="submit" class="btn-danger">Выйти из системы</button>
            </form>
        </div>
    </div>
</body>
</html>
