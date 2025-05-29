<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles.css">
    <title>Уровень доступа в системе</title>
</head>
<body>
    <h3>Управление ролями пользователей</h3>
    <table>
        <tr>
            <th>Пользователь</th>
            <th>Текущая роль</th>
            <th>Новая роль</th>
        </tr>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <table>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td>
                    <form method="POST" action="/profile/roles">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <select name="role" onchange="this.form.submit()">
                            <option value="visitor" <?= $user['role'] === 'visitor' ? 'selected' : '' ?>>Посетитель</option>
                            <option value="keeper" <?= $user['role'] === 'keeper' ? 'selected' : '' ?>>Смотритель</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Администратор</option>
                        </select>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
       </table>
    <a href="/profile" class="btn">Назад</a>
</body>
</html>
