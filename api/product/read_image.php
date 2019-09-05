<?php
	// show error reporting
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	// required headers
	header("Content-Type: image/jpg");

	if(isset($_GET['LoadImg'])){
		$fileLocation = "../../resource/image/" . $_GET['LoadImg'];
		$imageFile = file_get_contents($fileLocation);

	    // set response code - 200 OK
	    http_response_code(200);
		echo base64_encode($imageFile);
	}
?>