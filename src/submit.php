<?php

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $message = $_POST['message'] ?? '';

    if(!empty($name) && !empty($meassage)) {
        $stmt=$pdo->prepare('INSERT INTO messages (name, message) VALUES (?,?)');
        $stmt->execute([$name, $message]);
    }
}

header('Location:index.php');
exit;