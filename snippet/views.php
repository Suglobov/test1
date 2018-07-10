<?php

// подключение к базе
$pdo = include "../dbConnect/dbConnect.php";

$queryQ = "SELECT c.id, c.title, c.textarea, c.imagepath FROM `views` AS v
JOIN `content` AS c ON c.id=v.content_id
WHERE v.last_date >= DATE_SUB(CURDATE(),INTERVAL 7 DAY)
ORDER BY v.count DESC
LIMIT 10";
$query = $pdo->prepare($queryQ);
$query->execute([]);

$outContent = "";
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $textarea = htmlspecialchars_decode($row['textarea']);
    $textarea = (mb_strlen($textarea) > 100)
        ? mb_strimwidth($textarea, 0, 97, "...")
        : $textarea;
    $outContent .= displayContent(
        $row['id'],
        $row['title'],
        $textarea,
        null,
        false,
        'a',
        false
    );
}
if ($outContent == "") {
    $outContent = "Пока пусто";
}
echo $outContent;