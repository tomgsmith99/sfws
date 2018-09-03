<?php
error_reporting(0);

if ($_SERVER["DOCUMENT_ROOT"] === "/Applications/MAMP/htdocs") {
	error_reporting(E_ALL);
}

$_INCLUDES = $_SERVER['DOCUMENT_ROOT'] . "/sfws/includes/";

include $_INCLUDES . "dbconn.php";
include $_INCLUDES . "Page.php";

include $_SERVER["DOCUMENT_ROOT"] . "/sfws/familyCalendar/calendar_utils.php";

$mysqli = getdbconn("familyEvents");

$this_page = new Page();

$query = "SELECT * FROM events WHERE isActive = 1";

$query .= " ORDER BY EventDate ASC";

$result = mysqli_query($mysqli, $query);

$content = "<h3>Diffendorf Family Events - " . date("Y") . "</h3>";

$current_month = 0;

while ($row = mysqli_fetch_array($result)) {

	if ($row["Month"] != $current_month) {
		$current_month = $row["Month"];

		if ($current_month != 1) {
			$content .= "</table>";
			$content .= "<br>";
		}

		$content .= "<h3>" . $months[$current_month] . "</h3>";

		$content .= "<table border = '0'>";

		$current_day = 0;
	}

	$content .= "<tr>";

	if ($row["Day"] != $current_day) {
		$current_day = $row["Day"];
		$content .= "<td>" . ordinal($row["Day"]) . "</td>";
	}
	else {
		$content .= "<td></td>";
	}

	$content .= "<td>" . get_event_desc($row) . "</td>";

	$content .= "</tr>";
}

$this_page->setContent($content);
$this_page->display();