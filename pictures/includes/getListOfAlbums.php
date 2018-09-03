<?php

function getListOfAlbums() {

	global $mysqli;

	$query = "SELECT * FROM PictureLinks";

	$query .= " ORDER BY Date DESC";

	$result = mysqli_query($mysqli, $query);

	return $result;
}