<?php

date_default_timezone_set("America/New_York");

$this_year = date("Y");

$months[1] = "January";
$months[2] = "February";
$months[3] = "March";
$months[4] = "April";
$months[5] = "May";
$months[6] = "June";
$months[7] = "July";
$months[8] = "August";
$months[9] = "September";
$months[10] = "October";
$months[11] = "November";
$months[12] = "December";

function get_announcement($row) {

	global $this_year;

	if ($row["EventType"] === "holiday") {
		return $row["EventAnnouncement"];
	}

	$ann = "Happy ";

	if (should_be_counted($row, $this_year - $row["Year"])) {
		$ann .= ordinal($num) . " ";
	}

	$ann .= $row["eventType"] . ", " . $row["EntityName"];

	return $ann;
}

function get_event_desc($row) {

	global $this_year;

	if ($row["EventType"] === "holiday") {
		return $row["EventDesc"];
	}
	else {

		$desc = $row["EntityName"] . "'s ";

		$num = $this_year - $row["Year"];

		if (should_be_counted($row, $num)) {
			$desc .= ordinal($num) . " ";
		}
		return $desc . $row["EventType"];
	}
}

function ordinal($number) {
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
	if ((($number % 100) >= 11) && (($number%100) <= 13))
		return $number. 'th';
	else
		return $number. $ends[$number % 10];
}

function should_be_counted($row, $num) {
	if ($row["EventType"] === "anniversary" || ( $row["EventType"] === "birthday" && $num < 30)) {
		return true;
	}
	return false;
}
