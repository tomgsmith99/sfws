<?php

session_start();

$_SESSION["destURL"] = $_POST["destURL"];

include "/var/www/html/sfws/includes/includes.php";

$this_page = new Page();

$this_page->setTitle("Smith Family Web Site | Login");

$janrainJavascript = trim(file_get_contents("janrainJavascript.html"));

$this_page->setJavascript($janrainJavascript);

$content .= "<h3>User log-in</h3>";

$content .= "<p>The Smith Family Web Site uses Janrain Engage to allow our users to log in using one of their existing web accounts: ";

$content .= "<div id = 'janrainEngageEmbed'></div>";

$this_page->setContent($content);

$this_page->display();