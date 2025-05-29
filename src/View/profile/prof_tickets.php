<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <h3>Мои билеты</h3>
    <table>
        <tr>
            <th>Дата</th>
            <th>Секция</th>
            <th>Количество</th>
            <th>Сумма</th>
        </tr>
        <?php foreach ($tickets as $ticket): ?>
        <tr>
            <td><?= $ticket['visit_date'] ?></td>
            <td><?= $ticket['section'] ?></td>
            <td><?= $ticket['quantity'] ?></td>
            <td><?= $ticket['total_price'] ?> руб.</td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

