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
    $result = getListOfAlbums();
    
    $returnString = "<table border = '0'>";

    while ($row = mysqli_fetch_array($result)) {
	
	$returnString .= "<tr>";
	$returnString .= "<td><p><a target= '_new' href = '" . $row["Link"] . "'>" . $row["Title"] . "</a></p></td>";
	$returnString .= "<td><img src = '" . $row["imageURL"] . "'></td>";
	$returnString .= "<td><form action = 'evaluateSelection.php' method = 'post'>";
	$returnString .= "<input type = 'hidden' name = 'albumID' value = '" . $row["PictureLinkID"] . "'>";
	$returnString .= "<input type = 'submit' name = 'submit' value = 'change'>";
	$returnString .= "</form>";
	$returnString .= "</tr>";
    }

	$returnString .= "</tr>";
	
    return $returnString;
}


$this_page->setContent($content);

$this_page->display();