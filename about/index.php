<?php

include $_SERVER["DOCUMENT_ROOT"] . "/sfws/includes/Page.php";

$this_page = new Page();

/************************************/

$this_page->setTitle("Smith Family Web Site | About");

$content = file_get_contents("content.html");

$this_page->setContent($content);

$this_page->display();
