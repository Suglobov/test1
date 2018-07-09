<?php

// подключение к базе
$pdo = include "../dbConnect/dbConnect.php";

$queryQ = "SELECT * FROM `content` WHERE 1";
$query = $pdo->prepare($queryQ);
$query->execute([]);

$isAdminShow = isAdmin() && isAdminPage();

$outContent = '<div class="content">';
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $textarea = htmlspecialchars_decode($row['textarea']);
    $textarea = (mb_strlen($textarea) > 300)
        ? mb_strimwidth($textarea, 0, 297, "...")
        : $textarea;

    $outContent .= displayContent(
        $row['id'],
        $row['title'],
        $textarea,
        $row['imagepath'],
        $isAdminShow
    );
}
$outContent .= '</div>';

echo $outContent;