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

$errors = writeToDb(
    $id,
    $_REQUEST['title'],
    $_REQUEST['textarea'],
    $_FILES['file'],
    $_REQUEST['file2'],
    $_REQUEST['deleteNew']
);


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
