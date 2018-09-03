<?php

session_start();

include "/var/www/html/sfws/includes/includes.php";
include $paths["home"]["filesystem"] . "/pictures/includes/includes.php";


$this_page = new Page();

$this_page->setTitle("Smith Family Web Site | Pictures");

$content .= "<h3>Pictures - Add an album</h3>\n";

if (adminUserIsAuthenticated()) {
    
    $content .= getRightNav();
    
    $content .= getPictureUploadForm();

}
else {
    $content .= "<p>You must be logged in to use this page.</p>";
    $content .= getSocialLoginButton($_SERVER["PHP_SELF"]);
}

function getPictureUploadForm() {

    $startYear = 2000;
    
    $cal_info = cal_info(0);
    $months = $cal_info["months"];

    $returnString = "<form enctype = 'multipart/form-data' action = 'evaluateAlbum.php' method = 'post'>";

    $returnString .= "<p>Title of album: <input type = 'text' name = 'Title'></input>\n";

    $returnString .= "<p>Address: <input type = 'text' name = 'Link' size = '64'></input>\n";

    $returnString .= "<p>Year: <select name = 'Year' size = '4'>";

    $this_year = date("Y");

    for ($i = $this_year; $i >= $startYear; $i--) {
	$returnString .= "<option>$i</option>";
    }

    $returnString .= "</select>";

    $returnString .= "<p>Month: <select name = 'Month' size = '4'>";

    for ($i = 1; $i <= sizeof($months); $i++) {
        $returnString .= "<option value = '$i'>" . $months[$i] . "</option>";
    }

    $returnString .= "</select>";

    $returnString .= "<p>Day: <select name = 'day'>";
    for ($i=1; $i <= 31; $i++) {
       $returnString .= "<option value = '$i'>$i</option>";
    }

    $returnString .= "</select>";

    $returnString .= "<p>Thumbnail (will be resized on server): ";

    $returnString .= "<input name = 'uploadedfile' type = 'file' /><br />\n";

    $returnString .= "<input type = 'submit' value = 'Submit'>\n";

    $returnString .= "</form>";
    
    return $returnString;
}

$this_page->setContent($content);

$this_page->display();