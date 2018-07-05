<?php
/**
 * Created by PhpStorm.
 * User: suglobov
 * Date: 05.07.18
 * Time: 11:27
 */
session_start();
var_dump($_SESSION);
if (!isset($_SESSION['login']) && $_SESSION['role'] !== 1) {
    redirect();
}

$exit = $_REQUEST['exit'];

if (isset($exit) && $exit == true) {
    session_destroy();
    redirect();
}


include "../chunk/head.php";

echo print_r($_REQUEST, 1);
$body = "test";
//echo $body;
?>

    <div class="uk-container">
        <nav class="uk-navbar-container" uk-navbar>
            <form method="post">
                <button class="uk-button uk-button-default" name="exit" value="true">EXIT</button>
            </form>
        </nav>
    </div>

<?php

include "../chunk/footer.php";

function redirect()
{
    header('Location: login.php');
    exit;
}