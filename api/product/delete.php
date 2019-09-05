<?php

	// show error reporting
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	// required headers
	header("Content-Type: application/json; charset=UTF-8");

	//include database and object files
	include_once "../config/database.php";
	include_once "../objects/product.php";
	include_once "../objects/category.php";

	//instantiate database
	$database = new Database();
	$db = $database->getConnection();

	//instantiate product obejct
	$product = new Product($db);

	//instantiate category object
	$category = new Category($db);

	//get posted data
	$inputData = json_decode(file_get_contents("php://input"));


	$product->id = $inputData->id;
	$category->categoryName = $inputData->category;
	if(!$category->getIdByName()){
        // set response code - 503 service unavailable
        http_response_code(503);	
        //exit program, when it isn't valid
		die(json_encode(array("message" => "doesn't get a category id, check category name input")));				
	}	

	// delete the product
	if($product->delete()){ 
	    // set response code - 200 ok
	    http_response_code(200);	 
	    echo json_encode(array("message" => "Product was deleted."));

	    //update category table after deleting
		if(!$category->updateAfterDeleting()){
	        // set response code - 503 service unavailable
	        http_response_code(503);	
	        //exit program, when it isn't valid
			die(json_encode(array("message" => "have trouble with updating category table")));				
		}

		//delete image
		if(!$product->deleteImg()){
	        // set response code - 503 service unavailable
	        http_response_code(503);	
	        //exit program, when it isn't valid
			die(json_encode(array("message" => "have trouble with deleting image")));				
		}
	}
	 
	// if unable to delete the product
	else{	 
	    // set response code - 503 service unavailable
	    http_response_code(503);

	    echo json_encode(array("message" => "Unable to delete product."));
	}
	
?>