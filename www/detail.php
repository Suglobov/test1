<?php

if (!isset($_GET['id']) || !is_int((int)$_GET['id'])) {
    die();
}

require_once "../snippet/testAccess.php";

$id = (int)$_GET['id'];

// подключение к базе
$pdo = include_once "../dbConnect/dbConnect.php";

$queryQ = "SELECT * FROM `content` WHERE id=?";
$query = $pdo->prepare($queryQ);
$query->execute([$id]);

$row = $query->fetch(PDO::FETCH_ASSOC);


$outContent = '';
if ($row) {
    $textarea = htmlspecialchars_decode($row['textarea']);

    $outContent .= displayContent(
        $id,
        $row['title'],
        $textarea,
        $row['imagepath'],
        true,
        'div',
        false
    );
} else {
    $outContent .= 'ничего не найдено';
}

require "../chunk/head.html";
echo displayNav(isAdmin());
echo $outContent;


if (!isAdmin()) {
    if ($row) {
        $queryQ = "SELECT * FROM `views` WHERE content_id=?";
        $query = $pdo->prepare($queryQ);
        $query->execute([$id]);
        $rowView = $query->fetch(PDO::FETCH_ASSOC);
        $dateNow = new DateTime();
        if ($rowView) {
            $dataDb = new DateTime($rowView['last_date']);
            $interval = date_diff($dateNow, $dataDb);
            $count = 1;
            if ($interval->format("%d") <= 7) {
                $count = $rowView['count'] + 1;
            }
//            echo "<pre>";
//            echo $interval->format("%d");
//            echo "</pre>";
            $queryQ = "UPDATE `views` SET count=?,last_date=? WHERE content_id=?";
            $query = $pdo->prepare($queryQ);
            $query->execute([$count, $dateNow->format("Y-m-d H:i:s"), $id]);
        } else {
            $queryQ = "INSERT `views` (count,last_date,content_id) VALUES (?,?,?)";
            $query = $pdo->prepare($queryQ);
            $query->execute([1, $dateNow->format("Y-m-d H:i:s"), $id]);
        }
    }
}
require "../chunk/footer.html";