<?php

$siteName = "sfws";

$paths["home"]["web"] = "/" . $siteName;
$paths["home"]["filesystem"] = "/var/www/html" . $paths["home"]["web"];
$paths["includes"] = $paths["home"]["filesystem"] . "/includes";
$paths["classes"] = $paths["includes"] . "/classes";
$paths["thumbnails"]["web"] = $paths["home"]["web"] . "/images/thumbnails";

$paths["thumbnails"]["filesystem"] = $paths["home"]["filesystem"] . "/images/thumbnails";

$paths["cloudfront"] = "http://d3hlvihzs7kpz1.cloudfront.net";
$paths["s3"] = "https://s3-us-west-2.amazonaws.com";

$adminID = "http://www.facebook.com/profile.php?id=1624323223";