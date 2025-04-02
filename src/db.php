<?php 

$host='mysql';
$db='testdb';
$user='user';
$pass='userpass';

$char='utf8mb4';

$conn="mysql:host=$host;dbname=$db;charset=$char";
$options=[
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($conn, $user, $pass);
} catch(PDOException $e) {
    die('Ошибка подключения: '. $e->getMessage());
}