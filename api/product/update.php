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

	if(
		!empty($inputData->id) &&
		!empty($inputData->category) &&
		!empty($inputData->name) &&
		!empty($inputData->price) &&
		!empty($inputData->brand) &&
		!empty($inputData->expireDate) &&
		!empty($inputData->stockStatus) &&
		!empty($inputData->updateImg) &&
		!empty($inputData->specification)
	){
		//set product ID that to be edited
		$product->id = $inputData->id;


		//set category id
		$category->categoryName = $inputData->category;
		if(!$category->getIdByName()){
	        // set response code - 503 service unavailable
	        http_response_code(503);	
	        //exit program, when it isn't valid
			die(json_encode(array("message" => "doesn't get a category id, check category name input")));				
		}

		//set product properties to be changed
		$product->category = $category->categoryID;
		$product->name = $inputData->name;
		$product->price = $inputData->price;
		$product->brand = $inputData->brand;
		$product->expireDate = $inputData->expireDate;
		$product->stockStatus = $inputData->stockStatus;
		$product->specification = $inputData->specification;

		if($product->update()){
			// set response code - 200 ok
		    http_response_code(200);
		    echo json_encode(array("success" => "true"));

		    if($inputData->updateImg == "true"){
		    	if(!$product->updateImg()){
	    		    // set response code - 503 service unavailable
			        http_response_code(503);	
			        //exit program, when it isn't valid
					die(json_encode(array("message" => "have trouble with moving image")));		
		    	}
		    }

		}else{
		    // set response code - 503 service unavailable
		    http_response_code(503);
		    echo json_encode(array("success" => "false"));
		}
	}
?>