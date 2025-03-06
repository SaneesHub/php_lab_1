<?php
    session_start();
    if (!isset($_SESSION['count'])){
        $_SESSION['count']=0;
    }


    $_SESSION['count']++;

    setcookie("user", "Даниил", time()+3600);

    $title = "PHP web";
    $menu=[
        "Home" => "#home", 
        "About" => "#about",
        "Contact" => "#contact",
    ];

    $message = "";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $name = htmlspecialchars( $_POST['name']  ?? '');
        $email = htmlspecialchars($_POST['email'] ?? '');
        var_dump($name);
        if (!empty($name) && !empty($email)){
            $message="Спасибо, $name! Ваш email: $email";
        } else {
            $meassage =" Пожалуйста, заполните данные!";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        nav ul{
            list-style: none;
            display: flex;
        }
        .container{
            width: 80%;
            margin: auto;
        }
        form{
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php $title ?></h1>
        <nav>
            <ul>
                <?php foreach($menu as $name => $link): ?>
                    <li><a href="<?= $link ?>"></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Количество посещений сайта: <?php $_SESSION['count'];?></h2>
        <p></p>

        <h3>Форма отправки сообщения</h3>
        <form method="post">
            <label for="">Имя: <input type="text" name ="name"></label>
            <label for="">Почта: <input type="text" name="email"> </label>
            <button type="submit">Отправить</button>
        </form>

        <p><? $message ?></p>
    </div> 
</body>
</html>