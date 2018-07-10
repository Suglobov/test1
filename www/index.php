<?php

require_once "../snippet/testAccess.php";

require "../chunk/head.html";
echo displayNav(isAdmin());
?>
    <h1>Бложик</h1>
    <div uk-grid>
        <div class="uk-width-2-3">
            <?php require "../snippet/content.php"; ?>
        </div>
        <div class="uk-width-1-3">
            <h3>Топ просмотров</h3>
            <?php require "../snippet/views.php"; ?>
        </div>
    </div>
<?php require "../chunk/footer.html";