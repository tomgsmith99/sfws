<?php

session_start();

include "/var/www/html/sfws/includes/includes.php";
include $paths["home"]["filesystem"] . "/pictures/includes/includes.php";

$this_page = new Page();

$this_page->setTitle("Smith Family Web Site | Pictures");

$content .= "<h3>Pictures - Update thumbnail</h3>\n";

if (adminUserIsAuthenticated()) {
    $content .= getRightNav();

    $content .= getUI();

}
else {
    $content .= "<p>You must be logged in to use this page.</p>";
    $content .= getSocialLoginButton($_SERVER["PHP_SELF"]);
}

function getUI() {
    global $mysqli;

    $thisThumbnail = new Thumbnail($_POST["albumID"]);

    $returnString .= $thisThumbnail->renameTempFile();

    $returnString .= "<hr width = 75% align = 'center'>";

    $returnString .= $thisThumbnail->createThumbnail();

    $returnString .= "<hr width = 75% align = 'center'>";

    $returnString .= $thisThumbnail->uploadThumbnailToS3();

    $returnString .= "<hr width = 75% align = 'center'>";

    $returnString .= $thisThumbnail->writePathToDB();

    return $returnString;
}


$this_page->setContent($content);

$this_page->display();