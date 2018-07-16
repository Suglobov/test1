<?php

/**
 * Проверяет является ли страница разрешнной только для админа
 *
 * @return bool админская или нет страница
 */
function isAdminPage()
{
    $adminPages = ["/adminPanel.php", "/edit.php"];
    foreach ($adminPages as $v) {
        if (strripos($_SERVER['PHP_SELF'], $v) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * Проверяет авторизован админ или нет
 *
 * @return bool админ или нет
 */
function isAdmin()
{
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION['login']) && $_SESSION['role'] == 1) {
        return true;
    }
    return false;
}

/**
 * Проверяет, является ли страница, страницей логина
 *
 * @return bool на "логине" или нет
 */
function isLoginPage()
{
    return $_SERVER['PHP_SELF'] === '/login.php';
}

/**
 * Отправляет на страницу с логином
 *
 * @return void
 */
function redirectToLogin()
{
    header('Location: ./login.php');
    exit;
}

/**
 * Отправляет на страницу админ панели
 *
 * @return void
 */
function redirectToAdminPanel()
{
    header('Location: ./adminPanel.php');
    exit;
}

/**
 * Производит отмену авторизации
 *
 * @param string $exit если 1, то выход
 *
 * @return void
 */
function testExit($exit)
{
    if (mb_strlen($exit) !== 0 && $exit === '1') {
        if (!isset($_SESSION)) {
            session_start();
            session_destroy();
        }
        redirectToLogin();
    }
}

/**
 * Возвращает шаблон контента с нужными значениями
 *
 * @param int         $id              id в базе, нужен для редактирования
 * @param string      $title           заголовок
 * @param string      $textarea        поле ввода
 * @param null|string $imagepath       путь до картинки
 * @param bool        $displayTextarea отображать ли поле ввода
 * @param string      $titleTag        тэг у заголовка
 * @param bool        $displayAdmin    админ ли это
 *
 * @return bool|string            готовый шаблон
 */
function displayContent(
    $id,
    $title,
    $textarea,
    $imagepath = null,
    $displayTextarea = true,
    $titleTag = 'a',
    $displayAdmin = false
)
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
    if ($displayTextarea) {
        $template = str_replace('{{displayTextarea}}', '', $template);
    } else {
        $template = str_replace('{{displayTextarea}}', $classHidden, $template);
    }
    $template = str_replace('{{titleTag}}', $titleTag, $template);
    if ($displayAdmin) {
        $template = str_replace('{{displayAdmin}}', '', $template);
    } else {
        $template = str_replace('{{displayAdmin}}', $classHidden, $template);
    }


    return $template;
}

/**
 * Выводит форму для редактирования
 *
 * @param string      $new       новая новость или нет
 * @param string      $title     заголовок
 * @param string      $textarea  поле ввода
 * @param null|string $imagepath путь до картинки
 *
 * @return bool|mixed|string
 */
function displayEditForm($new, $title, $textarea, $imagepath = null)
{
    $classHidden = 'uk-hidden';
    $template = file_get_contents("../chunk/editForm.html");

    $template = str_replace('{{title}}', $title, $template);
    $template = str_replace('{{textarea}}', $textarea, $template);
    if ($imagepath) {
        $template = str_replace('{{imagepath}}', $imagepath, $template);
        $template = str_replace('{{additionalFields}}', '', $template);
    } else {
        $template = str_replace('{{imagepath}}', '', $template);
        $template = str_replace('{{additionalFields}}', $classHidden, $template);
    }
    if ($new) {
        $template = str_replace('{{delete}}', $classHidden, $template);
    } else {
        $template = str_replace('{{delete}}', '', $template);
    }


    return $template;
}

/**
 * Выводит навигацию
 *
 * @param boolean $admin админ или нет
 *
 * @return bool|mixed|string
 */
function displayNav($admin)
{
    $classHidden = 'uk-hidden';
    $template = file_get_contents("../chunk/nav.html");

    if ($admin) {
        $template = str_replace('{{admin}}', '', $template);
        $template = str_replace('{{neAdmin}}', $classHidden, $template);
    } else {
        $template = str_replace('{{admin}}', $classHidden, $template);
        $template = str_replace('{{neAdmin}}', '', $template);
    }

    return $template;
}

/**
 * Выводит ошибки, если они есть
 *
 * @param array $errors массив с ошибками
 *
 * @return bool|mixed|string
 */
function displayErrors($errors)
{
    $classHidden = 'uk-hidden';
    $template = file_get_contents("../chunk/errors.html");

    if (empty($errors)) {
        $template = str_replace('{{hidden}}', $classHidden, $template);
    } else {
        $out = "";
        foreach ($errors as $v) {
            $out .= '<div>' . $v . '</div>';
        }
        $template = str_replace('{{hidden}}', '', $template);
        $template = str_replace('{{errors}}', $out, $template);
    }

    return $template;
}