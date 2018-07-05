<?php
/**
 * Created by PhpStorm.
 * User: suglobov
 * Date: 05.07.18
 * Time: 11:27
 */

// обработка сессий
session_start();

if (!isset($_SESSION['login']) && $_SESSION['role'] !== 1) {
    redirectToLogin();
}

$exit = $_REQUEST['exit'];

if (mb_strlen($exit) !== 0 && $exit == true) {
    session_destroy();
    redirectToLogin();
}

function redirectToLogin()
{
    header('Location: login.php');
    exit;
}

// ---
// подключение к базе
$pdo = include_once "../dbConnect/dbConnect.php";

// обработка формы новой новости
$title = $_REQUEST['title'];
$textarea = $_REQUEST['textarea'];

// было ли сохранение
$isTriedSaved = (isset($title) && isset($textarea));
// успешное ли сохранение
$isSaved = 0;

$errors = [];

if (isset($title) && mb_strlen($title) === 0) {
    $errors[] = "Заголово не должен быть пустым";
}
if (isset($textarea) && mb_strlen($textarea) === 0) {
    $errors[] = "Поле ввода не должен быть пустым";
}

if (mb_strlen($title) !== 0 && mb_strlen($textarea) !== 0) {
    echo "<pre>" . print_r($_FILES, 1) . "</pre>";
    if (mb_strlen($_FILES['file']['name']) > 0) {
        if ($_FILES['file']['error'] != 0) {
            $errors[] = "Файл передался с ошибкой";
        }
        if (preg_match("^image", $_FILES['file']['type'])) {
            $errors[] = "Файл передался с ошибкой";
        } else {
            echo 'img';
        }
    }
    if (empty($errors)) {
        echo 'ok';
    } else {
        echo 'ne ok';
    }
} else {

}
echo "<pre>";
//echo mb_strlen(null);

//echo isset($_REQUEST['title']);
//echo isset($title);
//echo mb_strlen($title);

//    echo $isTriedSaved;

//echo $title === null;
//echo empty('0');
//print_r($_FILES);
echo "</pre>";

$alert = "";
if (!empty($errors)) {
    $alert .= "<div class=\"uk-alert uk-alert-warning\">";
    foreach ($errors as $v) {
        $alert .= "<div>" . $v . "</div>";
    }
    $alert .= "</div>";
}
// ---

require "../chunk/head.php";

$body = "test";
//echo $body;

?>
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: "textarea",
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });
    </script>
    <script>
        window.addEventListener('load', function () {
            let time = setInterval(function () {
                let tinymceWarning = document.getElementById('mceu_31');
                if (tinymceWarning) {
                    clearTimeout(time);
                    tinymceWarning.style.display = 'none';
                }
            }, 100);
            let newNews = document.getElementById('new');
            let textarea = document.getElementById('textarea');
            let warningTextarea = document.getElementById('warningTextarea');
            newNews.addEventListener('submit', function (event) {
                if (textarea.value.length === 0) {
                    warningTextarea.classList.remove('uk-hidden');
                    // event.preventDefault();
                    // return false;
                } else {
                    warningTextarea.classList.add('uk-hidden');
                }
            });
        })
    </script>
    <div class="uk-container">
        <nav class="uk-navbar-container" uk-navbar>
            <form method="post">
                <button class="uk-button uk-button-danger" name="exit" value="true">
                    EXIT
                </button>
            </form>
        </nav>
        <?php echo $alert ?>
        <div class="uk-margin">
            <a class="uk-icon-link uk-icon-button"
               uk-icon="icon: plus-circle; ratio: 2"
               uk-toggle="target: #add-new"></a>
        </div>
        <div id="add-new" fhidden class="uk-card uk-card-body uk-card-default">
            <div class="uk-alert uk-hidden uk-alert-warning" id="warningTextarea">
                Поле ввода не должно быть пустым
            </div>
            <form id="new" method="post" enctype="multipart/form-data">
                <div class="uk-margin">
                    <input
                            class="uk-input uk-form-large"
                            type="text"
                            placeholder="Заголовок"
                            required
                            name="title">
                </div>
                <div class="uk-margin">
                    <textarea id="textarea" name="textarea"></textarea>
                </div>
                <div class="uk-margin">
                    <div uk-form-custom="target: true">
                        <input
                                type="file"
                                name="file"
                                accept="image/jpeg,image/png,image/gif">
                        <input
                                class="uk-input uk-form-width-medium"
                                type="text"
                                placeholder="Выберете файл картинки"
                                disabled>
                    </div>
                </div>
                <div class="uk-margin">
                    <button class="uk-button uk-button-primary">Сохранить</button>
                    <input
                            type="reset"
                            class="uk-button uk-button-default"
                            value="Сбросить">
                </div>
            </form>

        </div>
    </div>

<?php

require "../chunk/footer.php";

