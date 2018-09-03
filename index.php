<?php

$_INCLUDES = $_SERVER['DOCUMENT_ROOT'] . "/sfws/includes/";

include $_INCLUDES . "Page.php";
include $_INCLUDES . "dbconn.php";

include $_SERVER["DOCUMENT_ROOT"] . "/sfws/familyCalendar/calendar_utils.php";

$mysqli = getdbconn("familyEvents");

/***********************************************/

$config["cloudfront"] = getenv("CLOUDFRONT");

$thisPage = new Page();

$thisPage->setTitle("Tom Smith's web site");

$content = "<h3>Tom Smith's web site</h3>";

$today = date("z");

$yesterday = $today - 1;

$content .= "<table border = '0' width = '100%'>\n";

$content .= "<tr>\n";

$content .= "<td valign = 'top'>\n";

$content .= "<p><a href = '/baseball/'>Diffendorf Family Fantasy Baseball League</a></p>\n";

// Number of upcoming date items to show
$numberOfItems = 5;

$query = "SELECT * FROM events WHERE (EventDate > $yesterday AND isActive = 1) ORDER BY EventDate LIMIT 0, $numberOfItems";

$result = mysqli_query($mysqli, $query);

$already_done = 0;

while ($row = mysqli_fetch_array($result)) {

	$eventDateDayMo = $row['Day'] . $row['Month'];

	$todayDateDayMo = date("jn");

	if ($todayDateDayMo == $eventDateDayMo) {
		$dateSection .= "<h3>" . get_announcement($row) . "</h3>\n";
	}
	else {

		if ($already_done == 0) { $dateSection = "<p><b>Upcoming dates</b></p>\n"; $already_done = 1; }

		$days_left = date("z", mktime(0, 0, 0, $row['Month'], $row['Day'], date("Y"))) - date("z");

		$event_string = get_event_desc($row) . " " . $row["DateString"];

		if ($days_left == 1) { $dateSection .= "<p>Tomorrow is " . $event_string . "</p>\n"; }
		else { $dateSection .= "<p>$days_left days until " . $event_string . "</p>\n"; }
	}
}

$content .= "<a href = '/sfws/pictures/' target = '_blank'><img src = '" . $config["cloudfront"] . "homePage/SamAndHenryFall2015IMG_1511.jpg' border = '1' alt = ''></a>\n";

$content .= "</td>\n";

$content .= "</tr>\n";

$content .= "</table>\n";

$content .= $dateSection;

$thisPage->setContent($content);

$thisPage->display();