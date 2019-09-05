<?php
	// show error reporting
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	//home page URL
	$server_url = "http://localhost:8888/jintian";

	//page given in URL parameter, default is 1
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}

	//set number of records per page 
	$recordsPerPage = 8;

	//calculate for the query LIMIT clause
	$fromRecordNum = ($recordsPerPage * $page) - $recordsPerPage;

	//image uploaded temp location
	$imageTempLocation = "../../admin_app/app/image_upload/temp_image.jpg";

	//image resource location
	$imagesLocation = "../../resource/image/";

?>