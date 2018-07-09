<?php

require_once "../snippet/helper.php";

testExit($_REQUEST['exit']);

$isAdmin = isAdmin();

if (isLoginPage()) {
    if ($isAdmin) {
        redirectToAdminPanel();
    }
}

if (isAdminPage()) {
    if (!$isAdmin) {
        redirectToLogin();
    }
}