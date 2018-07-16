<?php

if (!isset($_GET['id'])) {
    die();
} elseif (!is_int((int)$_GET['id']) && $_GET['id'] !== 'new') {
    die();
}

require_once "../snippet/testAccess.php";

// подключение к базе
$pdo = include_once "../dbConnect/dbConnect.php";

require "../snippet/testRequest.php";

if ($_GET['id'] === 'new') {
    $id = 'new';
} else {
    $id = (int)$_GET['id'];
}

$title = isset($_REQUEST['title']) ? $_REQUEST['title'] : null;
$textarea = isset($_REQUEST['textarea']) ? $_REQUEST['textarea'] : null;
$file = isset($_FILES['file']) ? $_FILES['file'] : null;
$file2 = isset($_REQUEST['file2']) ? $_REQUEST['file2'] : null;
$deleteNew = isset($_REQUEST['deleteNew']) ? $_REQUEST['deleteNew'] : null;

$errors = writeToDb(
    $id,
    $title,
    $textarea,
    $file,
    $file2,
    $deleteNew
);

$outContent = '';
if ($_GET['id'] === 'new') {
    $outContent .= displayEditForm(
        1,
        '',
        '',
        ''
    );
} else {
    $id = (int)$_GET['id'];
    $queryQ = "SELECT * FROM `content` WHERE id=?";
    $query = $pdo->prepare($queryQ);
    $query->execute([$id]);

    $row = $query->fetch(PDO::FETCH_ASSOC);

    $outContent = '';
    if ($row) {
        $textarea = htmlspecialchars_decode($row['textarea']);
        $outContent .= displayEditForm(
            '0',
            $row['title'],
            $textarea,
            $row['imagepath']
        );
    } else {
        $outContent .= 'ничего не найдено';
    }
}


require "../chunk/head.html";
echo displayNav(isAdmin());
echo displayErrors($errors);
echo $outContent;
require "../chunk/footer.html";
