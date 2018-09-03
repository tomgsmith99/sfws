<?php

session_start();

include "/var/www/html/sfws/includes/includes.php";
include $paths["home"]["filesystem"] . "/pictures/includes/includes.php";

$this_page = new Page();

$this_page->setTitle("Smith Family Web Site | Pictures");

$content .= "<h3>Pictures - Update thumbnail</h3>\n";

if (adminUserIsAuthenticated()) {
    $content .= getLogoutButton($_SERVER["PHP_SELF"]);

    $content .= getUI();

}
else {
    $content .= "<p>You must be logged in to use this page.</p>";
    $content .= getSocialLoginButton($_SERVER["PHP_SELF"]);
}

function getUI() {
    global $mysqli;

    $returnString = "<p>OK, so we're going to replace the following picture: </p>";
    
    $query = "SELECT * FROM PictureLinks WHERE PictureLinkID = " . $_POST["albumID"];

    $result = mysqli_query($mysqli, $query);

    $album = mysqli_fetch_array($result);

    $returnString .= "<table border = '1'>";
    $returnString .= "<tr>";
    $returnString .= "<td><p>" . $album["Title"] . "</p></td>";
    $returnString .= "<td><img src = '" . $album["imageURL"] . "'></td>";
    $returnString .= "</tr>";
    $returnString .= "</table>";
    
    $returnString .= "<form enctype = 'multipart/form-data' action = 'evaluateThumbnailUpload.php' method = 'post'>";
    $returnString .= "<input name = 'uploadedfile' type = 'file' /><br />\n";
    $returnString .= "<input type = 'hidden' name = 'albumID' value = '" . $album["PictureLinkID"] . "'>";
    $returnString .= "<input type = 'submit' value = 'Submit'>\n";
    $returnString .= "</form>";

    return $returnString;
}


$this_page->setContent($content);

$this_page->display();