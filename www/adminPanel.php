<?php

require_once "../snippet/testAccess.php";

// подключение к базе
$pdo = include "../dbConnect/dbConnect.php";

//// создание вспомогательной папки
//$imagesDir = __DIR__ . "/image/";
//if (!is_dir($imagesDir)) {
//    if (!mkdir($imagesDir, 0777, true)) {
//        $errors[] = 'Не удалось создать директорию для картинок в корне сайта';
//    }
//    chmod($imagesDir, 0777);
//}
//// ---

//require "../snippet/testRequest.php";
//$errors = writeToDb(
//    $id,
//    $_REQUEST['title'],
//    $_REQUEST['textarea'],
//    $_FILES['file'],
//    $_REQUEST['file2']
//);

require "../chunk/head.html";
echo displayNav(isAdmin());
echo displayErrors($errors);
require "../chunk/adminPanel.html";
require "../snippet/content.php";
require "../chunk/footer.html";

