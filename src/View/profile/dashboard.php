<?php
echo '<link rel="stylesheet" href="/assets/styles.css">';
?>
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
            <a href="/animals/manage" class="btn">Управление животными</a>
        <?php elseif ($user['role'] === 'admin'): ?>
            <a href="/profile/roles" class="btn">Управление пользователями</a>
            <a href="/reports" class="btn">Отчеты</a>
        <?php endif; ?>

        <form action="/logout" method="POST" class="logout-form">
            <button type="submit" class="btn-danger">Выйти из системы</button>
        </form>
    </div>
</div>