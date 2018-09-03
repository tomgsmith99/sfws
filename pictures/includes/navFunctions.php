<?php

function getAddAlbumButton() {
	$returnString = "<div id = 'albumButton'>";
	$returnString .= "<form action = 'addAnAlbum.php'>";
	$returnString .= "<p><input type = 'submit' name = 'submit' value = 'Add an album'></form></p></li>";
	$returnString .= "</div>";
	return $returnString;
}

function getUpdateThumbnailButton() {
	$returnString = "<div id = 'thumbnailButton'>";
	$returnString .= "<form action = 'updateThumbnail.php'>";
	$returnString .= "<p><input type = 'submit' name = 'submit' value = 'Update thumbnail'></form></p></li>";
	$returnString .= "</div>";
	return $returnString;
}

function getRightNav() {
	$returnString = "<div id = 'rightNav'>";
	$returnString .= getLogoutButton($_SERVER["PHP_SELF"]);

	if (adminUserIsAuthenticated()) { 
		$returnString .= getAddAlbumButton();
		$returnString .= getUpdateThumbnailButton();
	}

	$returnString .= "</div>";
	
	return $returnString;
}