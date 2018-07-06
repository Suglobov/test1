<?php

require_once  "../snippet/helper.php";

if (!isAdmin()) {
    redirectToLogin();
}

testExit();

// подключение к базе
$pdo = include "../dbConnect/dbConnect.php";

// создание вспомогательной папки
$imagesDir = __DIR__ . "/image/";
if (!is_dir($imagesDir)) {
    if (!mkdir($imagesDir, 0777, true)) {
        $errors[] = 'Не удалось создать директорию для картинок в корне сайта';
    }
    chmod($imagesDir, 0777);
}
// ---

// обработка формы новой новости
$title = isset($_REQUEST['title']) ? trim($_REQUEST['title']) : null;
$textarea = isset($_REQUEST['textarea']) ? trim($_REQUEST['textarea']) : null;

// было ли сохранение
$isTriedSaved = (isset($title) && isset($textarea));
// успешное ли сохранение
$isSaved = 0;

//$success = [];
$errors = [];

if (isset($title) && mb_strlen($title) === 0) {
    $errors[] = "Заголово не должен быть пустым";
}
if (isset($textarea) && mb_strlen($textarea) === 0) {
    $errors[] = "Поле ввода не должен быть пустым";
}

if (mb_strlen($title) !== 0 && mb_strlen($textarea) !== 0) {
    $withImage = 0;
    if (mb_strlen($_FILES['file']['name']) > 0) {
        if ($_FILES['file']['error'] != 0) {
            $errors[] = "Файл передался с ошибкой";
        }
        if (preg_match("/^image/", $_FILES['file']['type'])) {
            $withImage = 1;
        } else {
            $errors[] = "Файл должен быть картинкой";
        }
    }
    if (empty($errors)) {
        if ($withImage) {
            $imageName = $_FILES['file']['name'];

            $relativeImageDirPath = date('Y/m');
            $imageDirPath = __DIR__ . "/image/" . $relativeImageDirPath . "/";

            $i = "";
            $prefix = "";
            while (file_exists($imageDirPath . $prefix . $imageName)) {
                $i = ($i === "") ? 0 : ++$i;
                $prefix = $i . "_";
            }

            $imagePath = $imageDirPath . $prefix . $imageName;
            $relativeImagePath = "/image/" . $relativeImageDirPath
                . "/" . $prefix . $imageName;

            move_uploaded_file($_FILES['file']['tmp_name'], $imagePath);
            if (!is_dir($imageDirPath)) {
                if (!mkdir($imageDirPath, 0777, true)) {
                    $errors[] = 'Не удалось создать директории для картинок';
                }
                chmod($imageDirPath, 0777);
            }
            echo $imageDirPath . PHP_EOL;
            echo $imagePath . PHP_EOL;
            echo $relativeImagePath . PHP_EOL;

        }
        $queryQ = "INSERT `content` (title,textarea,imagepath) VALUES (?,?,?)";
        $query = $pdo->prepare($queryQ);
        $queryRes = $query->execute(
            [
                htmlspecialchars($title),
                htmlspecialchars($textarea),
                $relativeImagePath]
        );

        if ($queryRes) {
            $success[] = "Запись успешная";
        } else {
            $errors[] = "В базу не получилось записать данные";
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
    }
}

// сообщение о успеху || из-за редиректа не работает
$alertSuccess = "";
if (!empty($success)) {
    $alertSuccess = "
<script>
    var alertSuccessShow = true;
    var alertSuccessContainer = ['\" . implode(\"','\", $success) . \"'];
</script>";
}

// сообщение о ошибках
$alert = "";
if (!empty($errors)) {
    $alert = "
<script>
    var alertShow = true;
    var alertContainer = ['" . implode("','", $errors) . "'];
</script>";
}
// ---

require "../chunk/head.html";
echo $alertSuccess;
echo $alert;
require "../chunk/adminPanel.html";
echo require "../snippet/content.php";
echo 'asdf';
require "../chunk/footer.html";

