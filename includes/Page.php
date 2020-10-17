<?php

class Page {

	public function __construct() {
		global $config;

		$this->content = "";
		$this->css = "/sfws/includes/sfws.css";
		$this->home = "/sfws";
		$this->javascript = "";
		$this->template = $_SERVER["DOCUMENT_ROOT"] . "/sfws/includes/template.html";
		$this->title = "";

		$localNav  = "<p><a href = 'http://baseball.tomgsmith.com'>Baseball</a></p>\n<p>~</p>\n";
		$localNav .= "<p><a href = '/sfws/pictures/'>Pictures</a></p>\n<p>~</p>\n";
//		$localNav .= "<p><a href = '/sfws/about/'>About</a></p>\n<p>~</p>\n";
//		$localNav .= "<p><a href = '/sfws/familyCalendar/'>Calendar</a></p>\n<p>~</p>\n";
		$this->localNav = $localNav;
	}

	/*************** Content ********************/
	public function setContent($content) { $this->content = $content; }

	/*************** Javascript *****************/
	public function setJavascript($js) { $this->javascript = $js; }


	/*************** Title **********************/
	public function setTitle($title) { $this->title = $title; }

	public function getTitle() { return $this->title; }


	public function display() {
		global $config;

		// Open template and read contents into $page:
		$webPage = file_get_contents($this->template);

		// Replace in page variables:
		$webPage =str_replace('%CSS%', $this->css, $webPage);
		$webPage =str_replace('%HOME%', $this->home, $webPage);
		$webPage =str_replace('%TITLE%', $this->title, $webPage);
		$webPage =str_replace('%JAVASCRIPT%', $this->javascript, $webPage);
		$webPage =str_replace('%LEFT NAVIGATION%', $this->localNav, $webPage);
		$webPage =str_replace('%CONTENT%', $this->content, $webPage);

		echo $webPage;
	}
}