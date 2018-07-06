<?php

/**
 * Проверяет авторизован админ или нет
 *
 * @return bool админ или нет
 */
function isAdmin()
{
    session_start();
    if (isset($_SESSION['login']) && $_SESSION['role'] == 1) {
        return true;
    }
    return false;
}

/**
 * Отправляет на страницу с логином
 *
 * @return void
 */
function redirectToLogin()
{
    header('Location: login.php');
    exit;
}

/**
 * Отправляет на страницу админ панели
 *
 * @return void
 */
function redirectToAdminPanel()
{
    header('Location: adminPanel.php');
    exit;
}

/**
 * Проверяет хочет ли пользователь выйти из админ панели
 * для выходна надо передать на страницу, где есть эта функция
 * get параметр exit=true
 *
 * @return void
 */
function testExit()
{
    $exit = $_REQUEST['exit'];

    if (mb_strlen($exit) !== 0 && $exit == 'true') {
        session_destroy();
        redirectToLogin();
    }
}

/**
 * Возвращает шаблон контента с нужными значениями
 *
 * @param int         $id        id в базе, нужен для редактирования
 * @param string      $title     заголовок
 * @param string      $textarea  поле ввода
 * @param null|string $imagepath путь до картинки
 * @param null|bool   $admin     админ ли это
 *
 * @return bool|string            готовый шаблон
 */
function displayContent($id, $title, $textarea, $imagepath = null, $admin = null)
{
    $classHidden = 'uk-hidden';
    $template = file_get_contents("../chunk/content.html");

    $template = str_replace('{{id}}', $id, $template);
    $template = str_replace('{{title}}', $title, $template);
    $template = str_replace('{{textarea}}', $textarea, $template);
    if ($imagepath) {
        $template = str_replace('{{displayImagepath}}', '', $template);
        $template = str_replace('{{imagepath}}', $imagepath, $template);
    } else {
        $template = str_replace('{{displayImagepath}}', $classHidden, $template);
        $template = str_replace('{{imagepath}}', '', $template);
    }
    if ($admin) {
        $template = str_replace('{{displayAdmin}}', '', $template);
    } else {
        $template = str_replace('{{displayAdmin}}', $classHidden, $template);
    }
    return $template;
}