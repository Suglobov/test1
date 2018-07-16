<?php

require_once "../snippet/testAccess.php";

// подключение к базе
$pdo = include_once "../dbConnect/dbConnect.php";

$login = isset($_REQUEST['login']) ? $_REQUEST['login'] : null;
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
$isTriedAuthorized = 0;
$isAuthorized = 0;


if (mb_strlen($login) !== 0 && mb_strlen($password) !== 0) {
    $isTriedAuthorized = 1;

    $queryQ = "SELECT `login`,`password`,`role` FROM manager WHERE login = ?";
    $query = $pdo->prepare($queryQ);
    $query->execute([$login]);

    $row = $query->fetch(PDO::FETCH_ASSOC);

    $isAuthorized = password_verify($password, $row['password']);

    if ($isAuthorized) {
        session_start();
        var_dump($_SESSION);

        $_SESSION['login'] = $login;
        $_SESSION['role'] = $row['role'];

        redirectToAdminPanel();
    }
}
// ---

$errors = "";
if ($isTriedAuthorized && !$isAuthorized) {
    $errors = displayErrors(["Ошибка в логине или пароле."]);
}

require "../chunk/head.html";
echo displayNav(isAdmin());
echo $errors;
require "../chunk/login.html";
require "../chunk/footer.html";
