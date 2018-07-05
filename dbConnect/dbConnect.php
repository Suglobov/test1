<?php
/**
 * Created by PhpStorm.
 * User: suglobov
 * Date: 04.07.18
 * Time: 17:36
 */

$db   = 'blog';
$user = 'root';
$pass = '123';
$charset = 'utf8';
$port = '3306';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $opt);
} catch (PDOException $e) {
    die('Подключение не удалось: ' . $e->getMessage());
}

return $pdo;