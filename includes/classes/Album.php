<?php

class Album {

	function Album($date, $webAddress, $title) {
	    $this->date = $date;
	    $this->webAddress = $webAddress;
	    $this->title = $title;
	}

	function addToDB() {
	    global $paths;

	    include $paths["home"]["filesystem"] . "/pictures/includes/dbconn.php";

	    $returnString = "<p>The query is: " . $this->getInsertQuery();
	    
	    if (mysqli_query($mysqli, $this->getInsertQuery())) {
		$returnString .= "<p>The query succeeded.";
	    }
	    else { $returnString .= "<p>The query failed."; }

	    if (mysqli_error($mysqli)) {
		$returnString .= "<p>MySQL error: " . mysqli_error($mysqli);
	    }
	    
	    $this->albumID = mysqli_insert_id($mysqli);
	    
	    $returnString .= "<p>The album ID is: " . $this->albumID;
	    
	    mysqli_close($mysqli);
	    return $returnString;
	
	}

	function getID() {
	    return $this->albumID;
	}
	
	function getInsertQuery() {
	    return ("INSERT INTO PictureLinks SET Title = '" . $this->getTitle() . "', Link = '" . $this->getWebAddress() . "', Date = " . $this->getDate());
	}
	
	function displayBasics() {
	    $returnString = "<p>Date: " . $this->getDate();
	    $returnString .= "<p>Address: " . $this->getWebAddress();
	    $returnString .= "<p>Title: " . $this->getTitle();
	
	    return $returnString;
	}
	
	function getAlbumID() { return $this->albumID; }
	function getDate() { return $this->date; }
	function getOrigFileName() { return $this->origFileName; }
	function getTitle() { return $this->title; }
	function getThumbnail() { return $this->thumbnail; }
	function getWebAddress() { return $this->webAddress; }
	
	function setAlbumID($albumID) { $this->albumID = $albumID; }
	function setOrigFileName($filename) { $this->origFileName = $fileName; }
	function setTitle($title) { $this->title = $title; }
	function setThumbnail($imageAddress) { $this->thumbnail = $imageAddress; }
	// function setWebAddress($webAddress) { $this->webAddress = $webAddress; }

}