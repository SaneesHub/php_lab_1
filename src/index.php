<?php
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить животное</title>
    <style>
        body {
            background-color: #f4f1de;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            width: 40%;
            margin: 50px auto;
            background-color: #8fc1a9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #2c3e50;
        }
        a {
            text-decoration: none;
        }
        label {
            font-weight: bold;
            color: #3b3b3b;
            display: block;
            margin-top: 10px;
        }
        select, input, textarea {
            width: 95%;
            padding: 10px;
            margin:8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #d88c3d;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 15px;
        }
        button:hover {
            background-color: #a15d2c;
        }
        .btn { 
            display: inline-block; padding: 10px 15px; margin: 5px;
            background: #4CAF50; color: white; text-decoration: none;
            border-radius: 5px;
        }
        .success {
            color: green;
            margin: 15px 0;
        }
        table { 
            width: 100%; border-collapse: collapse; margin-top: 20px; 
        }
        th, td { 
            padding: 8px; border: 1px solid #ddd; text-align: left; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Добавить животное в зоопарк</h2>
        <a href="sections.php">Секции зоопарка</a>
        <?php if (isset($_GET['success'])): ?>
            <p class="success">Данные успешно сохранены!</p>
        <?php endif; ?>

        <form action="submit.php" method="post" onsubmit="return validateForm();">
            <label for="animal">Название животного:</label>
            <select id="animal" name="animal" required>
                <option value="">-- Выберите животное --</option>
                <option value="Тигр">Тигр</option>
                <option value="Слон">Слон</option>
                <option value="Обезьяна">Обезьяна</option>
                <option value="Лев">Лев</option>
                <option value="Медведь">Медведь</option>
                <option value="Бегемот">Бегемот</option>
                <option value="Хамелеон">Хамелеон</option>
                <option value="Крокодил">Крокодил</option>
                <option value="Сокол">Сокол</option>
                <option value="Варан">Варан</option>
            </select>

            <label for="cage">Номер клетки:</label>
            <input type="number" id="cage" name="cage" min="1" required>

            <label for="conditions">Условия содержания:</label>
            <textarea id="conditions" name="conditions" required></textarea>

            <button type="submit">Сохранить</button>
        </form>
        <div class="action-buttons">
            <a href="view.php" class="btn">Просмотреть данные из БД</a>
            <a href="import.php" class="btn">Импорт из CSV в БД</a>
        </div>
    </div>
    <div style="margin-top: 20px;">
        <a href="tickets.php" style="display: inline-block; padding: 10px 15px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px;">
            Купить билеты в зоопарк
        </a>
    </div>
    <script>
        function validateForm() {
            let cage = document.getElementById("cage").value.trim();
            let conditions = document.getElementById("conditions").value.trim();

            if (!cage.match(/^\d+$/) || parseInt(cage) <= 0) {
                alert("Ошибка: номер клетки должен быть положительным числом!");
                return false;
            }

            if (conditions === "") {
                alert("Ошибка: заполните условия содержания!");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>