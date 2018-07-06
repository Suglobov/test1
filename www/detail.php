<?php

if (!isset($_GET['id']) || !is_int((int)$_GET['id'])) {
    die();
}

require "../snippet/helper.php";

$id = (int)$_GET['id'];
//echo $id;

// подключение к базе
$pdo = include "../dbConnect/dbConnect.php";

$queryQ = "SELECT * FROM `content` WHERE id=?";
$query = $pdo->prepare($queryQ);
$query->execute([$id]);

$row = $query->fetch(PDO::FETCH_ASSOC);

require "../chunk/head.html";
$outContent = '
<nav class="uk-navbar-container uk-margin" uk-navbar>
    <a href="/" class="uk-button uk-button-default">
        На главную
    </a>
</nav>';

if ($row) {
    $outContent .= '
        <article
            class="uk-card uk-card-body uk-card-small uk-card-default uk-margin" >
            <div uk-grid>';
    if ($row['imagepath'] !== null) {
        $outContent .= '
                <div class="uk-width-1-6">
                    <img src="' . $row['imagepath'] . '" alt="" class="uk-width-1-1">
                </div>';
    }
    $outContent .= '
                <div class="uk-width-expand">
                    <div href="/detail.php?id=' . $row['id'] . '" class="uk-card-title">' . $row['title'] . '</div>
                    <div>' . htmlspecialchars_decode($row['textarea']) . '</div>
                </div>
            </div>
        </article>';
} else {
    $outContent .= 'ничего не найдено';
}

echo $outContent;

require "../chunk/footer.html";

if (!isAdmin()) {
    if ($row) {
        $queryQ = "SELECT * FROM `views` WHERE content_id=?";
        $query = $pdo->prepare($queryQ);
        $query->execute([$id]);
        $rowView = $query->fetch(PDO::FETCH_ASSOC);
        if ($rowView) {
            $queryQ = "UPDATE `views` SET count=? WHERE content_id=?";
            $query = $pdo->prepare($queryQ);
            $query->execute([$rowView['count'] + 1, $id]);
        } else {
            $queryQ = "INSERT `views` (content_id,count) VALUES (?,?)";
            $query = $pdo->prepare($queryQ);
            $query->execute([$id, 1]);
        }
    }
}