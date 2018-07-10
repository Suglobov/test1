<?php

require_once "../snippet/testAccess.php";

require "../chunk/head.html";
echo displayNav(isAdmin());
require "../chunk/adminPanel.html";
require "../snippet/content.php";
require "../chunk/footer.html";

