<?php

class Thumbnail {

	function Thumbnail($albumID) {
	    global $paths;
	
	    $this->origName = $_FILES['uploadedfile']['name'];
	    $this->tempName = $_FILES['uploadedfile']['tmp_name'];
	    $this->albumID = $albumID;
	    $this->newName = $this->albumID . "_" . $this->origName;
	    
	    $this->path["orig"]["web"] = $paths["thumbnails"]["web"] . "/" . $this->origName;
	    $this->path["orig"]["filesystem"] = 
		$paths["thumbnails"]["filesystem"] . "/" . $this->origName;
	    
	    $this->path["final"]["filesystem"] = 
		$paths["thumbnails"]["filesystem"] . "/" . $this->newName;
	
	    $this->path["final"]["web"] = 
		$paths["thumbnails"]["web"] . "/" . $this->newName;

	    $this->keyname = ltrim($this->path["final"]["web"], "/");
	    
	    $this->path["url"]["s3"] = $paths["s3"] . $this->path["final"]["web"];
	    $this->path["url"]["cloudfront"] = $paths["cloudfront"] . $this->path["final"]["web"];

	}

	function getTempName() { return $this->tempName; }
	function getOrigName() { return $this->origName; }
	function getURL() { return $this->path["url"]["cloudfront"]; }

	function getAlbumID() { return $this->albumID; }
	
	function renameTempFile() {
	    $returnString = "";

	    if (file_exists($this->getTempName())) {
	
		$returnString .= "<p>The temp file seems to exist.";
		
		$returnString .= "<p>Attempting to rename " . $this->getTempName() . " to " . $this->path["orig"]["filesystem"];
	
		if (rename($this->getTempName(), $this->path["orig"]["filesystem"])) {
		    $returnString .= "<p><b>Success!</b></p>";
		}
		else { $returnString .= "<p><b>Failed!</b></p>"; }
	    }
	    else { $returnString .= "<p>The tmp file does not seem to exist on the server."; }

	    return $returnString;
	}

	function createThumbnail() {

	    $dimension = 144; // width, length of thumbnail

	    ini_set('memory_limit', '-1');

	    // Create an image from it so we can do the resize

	    $returnString = "<p>Attempting to create a jpeg from " . $this->path["orig"]["filesystem"];
	    
	    $src = imagecreatefromjpeg($this->path["orig"]["filesystem"]);

	    if ($src) { $returnString .= "<p>Created image."; }
	    else { $returnString .= "<p>Did not create image."; }

	    $thumbnailWidth = $dimension;
	    $thumbnailHeight = $dimension;

	    $tmp = imagecreatetruecolor($thumbnailWidth,$thumbnailHeight);
	    
	    if ($tmp) { $returnString .= "<p>Created tmp image successfully." ;}
	    else { $returnString .= "<p>Could not create tmp image."; }
	    
	    // Capture the original size of the uploaded image
	    list($width,$height) = getimagesize($this->path["orig"]["filesystem"]);

	    // this line actually does the image resizing, copying from the original
	    // image into the $tmp image
	    if (imagecopyresampled($tmp,$src,0,0,0,0,$thumbnailWidth,$thumbnailHeight,$width,$height)) {
		$returnString .= "<p>Image copy resampled worked."; }
	    else { $returnString .= "<p>The image copy/resample did not work."; }

	    if (imagejpeg($tmp, $this->path["final"]["filesystem"], 100)) {
		$returnString .= "<p>Created final jpg successfully"; }
	    else { $returnString .= "<p>Could not create final jpg."; }

	    imagedestroy($tmp);

	    $returnString .= "<p>The final full filesystem path is: " . $this->path["final"]["filesystem"];
	    $returnString .= "<p>The final web path is: " . $this->path["final"]["web"];

	    return $returnString;

	}

	function uploadThumbnailToS3() {
	    global $paths;

	    require $paths["includes"] . "/composer/vendor/autoload.php";

	    putenv("AWS_ACCESS_KEY_ID=AKIAJTG24RKPHBICCIAQ");
	    putenv("AWS_SECRET_ACCESS_KEY=");

	    $sharedConfig = [
		'region'  => 'us-west-2',
		'version' => 'latest',
		'S3' => [
		    'region' => 'us-west-2'
		]
	    ];

	    // Create an SDK class used to share configuration across clients.

	    $sdk = new Aws\Sdk($sharedConfig);

	    // Create an Amazon S3 client using the shared configuration data.
	    $client = $sdk->createS3();

	    if ($client) { $returnString .= "<p>AWS client created successfully."; }
	    else { $returnString .= "<p>failed to create client."; }
	
	    $fullPath = $this->path["final"]["filesystem"];

	    $result = $client->putObject(array(
		// 'Bucket'       => "tomgsmith99-testbucket",
		'Bucket'       => "tomgsmith99-sfws",
		// 'Key'          => $keyname,
		'Key'	=> $this->keyname,
		// 'SourceFile'   => $fullPath,
		'SourceFile'	=> $fullPath,
		'ACL'          => 'public-read',
		'StorageClass' => 'REDUCED_REDUNDANCY'
	    ));

	    if ($result["ObjectURL"]) {
		$returnString .= "<p>Image uploaded to AWS successfully: <a href = '" . $result["ObjectURL"] . "'>" . $result["ObjectURL"] . "</a></p>";
		
		$returnString .= "<p>The cloudfront URL should be: <a href = '" . $this->path["url"]["cloudfront"] . "'>" . $this->path["url"]["cloudfront"] . "</a></p>";
	    }
	    else { $returnString .= "<p>Error uploading image to AWS."; }
	    
	    return $returnString;
	}
	
	function writePathToDB() {
	    global $paths;
	    
	    include $paths["home"]["filesystem"] . "/pictures/includes/dbconn.php";

	    $query = "UPDATE PictureLinks SET imageURL = '" . $this->path["url"]["cloudfront"] . "' WHERE PictureLinkID = " . $this->getAlbumID();

	    $returnString .= "<p>query: " . $query;

	    mysqli_query($mysqli, $query);

	    $returnString .= "<p>" . mysqli_error($mysqli);
	    
	    return $returnString;

	}
}