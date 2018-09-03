<?php
error_reporting(0);

if ($_SERVER["DOCUMENT_ROOT"] === "/Applications/MAMP/htdocs") {
	error_reporting(E_ALL);
}

session_start();

include $_SERVER["DOCUMENT_ROOT"] . "/sfws/includes/dbconn.php";
include $_SERVER["DOCUMENT_ROOT"] . "/sfws/includes/Page.php";
include $_SERVER["DOCUMENT_ROOT"] . "/sfws/pictures/includes/navFunctions.php";
include $_SERVER["DOCUMENT_ROOT"] . "/sfws/pictures/includes/getListOfAlbums.php";
include $_SERVER["DOCUMENT_ROOT"] . "/sfws/pictures/includes/authentication.php";

$mysqli = getdbconn("Pictures");

$this_page = new Page();

$this_page->setTitle("Smith Family Web Site | Pictures");

$content .= "<h3>Pictures</h3>";

$content .= checkForUserSession();

if (isset($_SESSION['user'])) { 

	if ($_SESSION['fname']) {
		$content .= "<p>Welcome, <b>" . $_SESSION['fname'] . "</b>!</p>";
	}

	$content .= getRightNav();
	$content .= getPictureGallery();

}

function getPictureGallery() {
	$result = getListOfAlbums();

	$content .= "<table border = '0'>\n";

	$pictureCount = 0;

	while ($row = mysqli_fetch_array($result)) {
		if ($pictureCount === 0){ $content .= "<tr style='vertical-align:top'>\n"; }

		$content .= "<td style='width:33%' align = 'center'>";
		$content .= "<table border = '0'>\n";
		$content .= "<tr>\n";
			$content .= "<td align = 'center'><a target = '_blank' href = '";
				$content .= $row['Link'];
				$content .= "'>";
				$content .= "<img src = '";
				$content .= $row['imageURL'];
			$content .= "'></td>";
		$content .= "</tr>\n";

		$content .= "<tr>\n";
			$content .= "<td align = 'center'><a target = '_blank' href = '";
				$content .= $row['Link'];
				$content .= "'>";
				$content .= $row['Title'];
				$content .= "</a>";
			$content .= "</td>";
		$content .= "</tr>\n";
		$content .= "</table>";
		$content .= "</td>";

		$pictureCount++;

		if ($pictureCount == 3){
			$content .= "</tr>\n";
			$pictureCount = 0;
		}
	}

	$content .= "</table>\n";
	
	return $content;
}

function checkForUserSession() {
	if (empty($_SESSION['user'])) { 

		$returnString = "<p>Welcome to the Smith Family Web Site Pictures page.</p>";

		$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

		if (empty($password)) {
			// $user is empty and $password is empty.
			// User's first visit to the page in this session.
			$returnString .= getLoginForm();
		}
		else { // user has entered a generic password

			if (strcasecmp($password, "Henry") === 0) { 
				$_SESSION['user'] = "anonymous";
			}
			else {
				$returnString .= "<p>Sorry, wrong password.</p>";
				$returnString .= getLoginForm();
			}
		}
	}
	return $returnString;
}

function getLoginForm(){

	$returnString = "<p>To view pictures, you need to log in.</p>";

	// $returnString .= "<p>There are two ways to log in:</p>";

	// $returnString .= "<div id = 'niceList'>";

	// $returnString .= "<ol>";

	// $returnString .= "<li><span>1.</span><p>You can log in using an existing web account, such as Yahoo, Google, or Facebook.</p>";

	// $returnString .= getSocialLoginButton($_SERVER["PHP_SELF"]);

	// $returnString .= "</li>";

	// $returnString .= "<li><span>2.</span><p>Or,

	$returnString .= "<p>Enter a password below. The password is the first name of Tom and Suzanne's younger child.</p><form action = '$PHP_SELF' method = 'post'><p>Password: <input type = 'password' name = 'password'></p><p><input type = 'submit' name = 'submit' value = 'Submit'></p></form></li>";

	// $returnString .= "</ol>";

	// $returnString .= "</div>";

	return $returnString;
}

/***********************************/
$this_page->setContent($content);
$this_page->display();
