<?php
/**
 * Created by PhpStorm.
 * User: suglobov
 * Date: 04.07.18
 * Time: 16:26
 */

$pdo = require_once "../dbConnect/dbConnect.php";

session_start();

$login = $_REQUEST['login'];
$password = $_REQUEST['password'];
$isTriedAuthorized = 0;
$isAuthorized = 0;

if (isset($_SESSION['login']) && $_SESSION['role'] === 1) {
    echo 'ss', PHP_EOL;
    redirect();
}

if (mb_strlen($login) !== 0 && mb_strlen($password) !== 0) {
    $isTriedAuthorized = 1;

    $queryQ = "SELECT login,password,role FROM manager WHERE login = ?";
    $query = $pdo->prepare($queryQ);
    $query->execute([$login]);

    $row = $query->fetch(PDO::FETCH_ASSOC);

    $isAuthorized = password_verify($password, $row['password']);

    if ($isAuthorized) {
        session_start();
        var_dump($_SESSION);

        $_SESSION['login'] = $login;
        $_SESSION['role'] = $row['role'];

        redirect();
    }
}


include "../chunk/head.php";
//include "../chunk/login.php";

$alert = "";
if ($isTriedAuthorized && !$isAuthorized) {
    $alert = "
        <div class=\"uk-alert-warning\">
            Ошибка в логине или пароле.
        </div>";
}

$body = "
    <div class=\"uk-container\">
        " . $alert . "
        <form method=\"post\">
            <fieldset class=\"uk-fieldset\">
                <legend class=\"uk-legend\">Логинопороль</legend>
    
                <div class=\"uk-margin\">
                    <input class=\"uk-input\" name=\"login\" type=\"text\" placeholder=\"Логин\" required>
                </div>
                <div class=\"uk-margin\">
                    <input class=\"uk-input\" name=\"password\" type=\"password\" placeholder=\"Пароль\" required>
                </div>
                <button class=\"uk-button uk-button-default\">Го</button>
            </fieldset>
        </form>
    </div>";


echo $body;
include "../chunk/footer.php";

function redirect()
{
    header('Location: adminPanel.php');
    exit;
}