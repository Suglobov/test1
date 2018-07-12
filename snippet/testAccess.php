<?php

require_once "../snippet/helper.php";

if (isset($_REQUEST) && isset($_REQUEST['exit'])) {
    testExit($_REQUEST['exit']);
}

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