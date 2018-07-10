<?php

/**
 * Запись данных в базу
 *
 * @param int|string $id           id строки или new, для новой строки
 * @param string     $title        заголовок
 * @param string     $textarea     поле ввода
 * @param array      $file         массив с файлом из $_FILE
 * @param string     $oldImagePath путь до старой картинки
 * @param string     $delete       удаление новости если '1'
 *
 * @return array
 */
function writeToDb($id, $title, $textarea, $file, $oldImagePath, $delete = '0')
{
    global $pdo;

    $success = [];
    $errors = [];

    $title = isset($title) ? trim($title) : null;
    $textarea = isset($textarea) ? trim($textarea) : null;
    $oldImagePath = isset($oldImagePath) ? trim($oldImagePath) : null;
    $delete = isset($delete) ? trim($delete) : '0';

    if ($delete === '1') {
        $queryQ = "DELETE FROM `content` WHERE id=?";
        $queryA = [$id];
        $query = $pdo->prepare($queryQ);
        $queryRes = $query->execute($queryA);
        if ($queryRes) {
            $success[] = "Успешное удаление";
            header('Location: /adminPanel.php');
        } else {
            $errors[] = "Удаление не получилось";
        }
    }
    if (empty($errors)) {
        // было ли сохранение
        $isTriedSaved = (isset($title) && isset($textarea));

        if (isset($title) && mb_strlen($title) === 0) {
            $errors[] = "Заголово не должен быть пустым";
        }
        if (isset($textarea) && mb_strlen($textarea) === 0) {
            $errors[] = "Поле ввода не должен быть пустым";
        }
    }
    if (empty($errors) && $isTriedSaved) {
        $withImage = 0;

        // создание вспомогательной папки
        $imagesDir = getcwd() . "/image/";
        if (!is_dir($imagesDir)) {
            if (!mkdir($imagesDir, 0777, true)) {
                $errors[] = 'Не удалось
                 создать директорию для картинок в корне сайта';
            }
            chmod($imagesDir, 0777);
        }
        // ---
        // проверка файла
        if (mb_strlen($file['name']) > 0) {
            if ($file['error'] != 0) {
                $errors[] = "Файл передался с ошибкой";
            }
            if (preg_match("/^image/", $file['type'])) {
                $withImage = 1;
            } else {
                $errors[] = "Файл должен быть картинкой";
            }
        }
        // запись полей в базу
        if (empty($errors)) {
            $relativeImagePath = null;

            if ($withImage) {
                $imageName = $file['name'];

                $relativeImageDirPath = date('Y/m');
                $imageDirPath = getcwd() . "/image/" . $relativeImageDirPath . "/";

                $i = "";
                $prefix = "";
                while (file_exists($imageDirPath . $prefix . $imageName)) {
                    $i = ($i === "") ? 0 : ++$i;
                    $prefix = $i . "_";
                }

                $imagePath = $imageDirPath . $prefix . $imageName;
                $relativeImagePath = "/image/" . $relativeImageDirPath
                    . "/" . $prefix . $imageName;

                if (!is_dir($imageDirPath)) {
                    if (!mkdir($imageDirPath, 0777, true)) {
                        $errors[] = 'Не удалось создать директории для картинок';
                    }
                    chmod($imageDirPath, 0777);
                }
                move_uploaded_file($file['tmp_name'], $imagePath);
                chmod($imagePath, 0777);
            } else {
                if ($oldImagePath === "") {
                    $oldImagePath = null;
                }
                $relativeImagePath = $oldImagePath;
            }

            $queryRes = 0;
            if ($id === 'new') {
                $queryQ = "
                    INSERT `content`
                     (title,textarea,imagepath) VALUES (?,?,?)";
                $queryA = [
                    htmlspecialchars($title),
                    htmlspecialchars($textarea),
                    $relativeImagePath];
                $query = $pdo->prepare($queryQ);
                $queryRes = $query->execute($queryA);
            } elseif (is_int((int)$id)) {
                $queryQ = "
                      UPDATE `content` SET title=?, textarea=?, imagepath=?
                      WHERE id=?";
                $queryA = [htmlspecialchars($title),
                    htmlspecialchars($textarea),
                    $relativeImagePath,
                    $id];
                $query = $pdo->prepare($queryQ);
                $queryRes = $query->execute($queryA);
            } else {
                $errors[] = 'Не верный id';
            }

            if ($queryRes) {
                $success[] = "Запись успешная";
                header('Location: /adminPanel.php');
            } else {
                $errors[] = "В базу не получилось записать данные";
            }
        }
    }
    return $errors;
}
