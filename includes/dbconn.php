<?php

function getDBconn($dbname) {

	$dbpassword = getenv("DB_PASSWORD");
	$dbhost = getenv("DB_HOST");
	$dbusername = getenv("DB_USERNAME");
	$dbport = getenv("DB_PORT");

	$mysqli = new mysqli($dbhost, $dbusername, $dbpassword, $dbname, $dbport);

	if ($mysqli->connect_error) {
		echo "<p>could not connect to db.";

		die('Connect Error (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
	}
	else {
		// echo "<p>The db connection worked.";
	}

	return $mysqli;
}