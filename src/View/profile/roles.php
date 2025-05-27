<?php
echo '<link rel="stylesheet" href="/assets/styles.css">';
?>
<h3>Управление ролями пользователей</h3>
<table>
    <tr>
        <th>Пользователь</th>
        <th>Текущая роль</th>
        <th>Новая роль</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <form method="POST" action="/profile/update-role">
        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
        <tr>
            <td><?= $user['username'] ?></td>
            <td><?= $user['role'] ?></td>
            <td>
                <select name="role">
                    <option value="visitor">Посетитель</option>
                    <option value="keeper">Смотритель</option>
                    <option value="admin">Администратор</option>
                </select>
            </td>
        </tr>
    </form>
    <?php endforeach; ?>
</table>