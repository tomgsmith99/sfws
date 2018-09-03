<?php

session_start();

include "/var/www/html/sfws/includes/includes.php";
include $paths["home"]["filesystem"] . "/pictures/includes/getAlbumButton.php";

include $paths["classes"] . "/Album.php";
include $paths["classes"] . "/Thumbnail.php";

$this_page = new Page();

$this_page->setTitle("Smith Family Web Site | Evaluate new album");

$content = "<h3>Pictures - Evaluate a new album upload</h3>\n";
/************************************/

if (adminUserIsAuthenticated()) {
    $content .= "<div id = 'rightNav'>";
    $content .= getLogoutButton($_SERVER["PHP_SELF"]);
    
    $content .= getAddAlbumButton();
    $content .= "</div>";

}

else {
    $content .= getLoginMessage($_SERVER["PHP_SELF"]);
    $this_page->setContent($content);
    $this_page->display();
}

$date = makeDate($_POST['Year'], $_POST['Month'], $_POST['day']);

$thisAlbum = new Album($date, $_POST['Link'], $_POST['Title']);

$content .= $thisAlbum->displayBasics();

$content .= "<hr width = 75% align = 'center'>";

$content .= $thisAlbum->addToDB();

$content .= "<hr width = 75% align = 'center'>";

$thisThumbnail = new Thumbnail($thisAlbum->getID());

$content .= $thisThumbnail->renameTempFile();

$content .= "<hr width = 75% align = 'center'>";

$content .= $thisThumbnail->createThumbnail();

$content .= "<hr width = 75% align = 'center'>";

$content .= $thisThumbnail->uploadThumbnailToS3();

$content .= "<hr width = 75% align = 'center'>";

$content .= $thisThumbnail->writePathToDB();

function makeDate($year, $month, $day) {
    $rawDate = array($year, $month, $day);

    foreach ($rawDate as $value) {
	if ($value < 10) { $date .= "0" . $value; }
	else { $date .= $value; }
    }
    
    return $date;
}

$this_page->setContent($content);

$this_page->display();