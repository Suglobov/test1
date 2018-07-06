<?php

require_once  "../snippet/helper.php";

// подключение к базе
$pdo = include_once "../dbConnect/dbConnect.php";

$login = $_REQUEST['login'];
$password = $_REQUEST['password'];
$isTriedAuthorized = 0;
$isAuthorized = 0;

require "../snippet/helper.php";
// обработка сессий
//session_start();

//if (isset($_SESSION['login']) && $_SESSION['role'] === 1) {
if (isAdmin()) {
    redirectToAdminPanel();
}

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

$alert = "";
if ($isTriedAuthorized && !$isAuthorized) {
    $alert = "
<script>
    var alertShow = true;
    var alertContainer = [];
</script>";
}

require "../chunk/head.html";
echo $alert;
require "../chunk/login.html";
require "../chunk/footer.html";
