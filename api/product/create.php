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

	//instantiate product object
	$product = new Product($db);

	//instantiate category object
	$category = new Category($db);

	//get posted data
	$inputData = json_decode(file_get_contents("php://input"));


	//make sure data isn't empty
	if(
		!empty($inputData->category) &&
		!empty($inputData->name) &&
		!empty($inputData->price) &&
		!empty($inputData->brand) &&
		!empty($inputData->expireDate) &&
		!empty($inputData->stockStatus) &&
		!empty($inputData->specification)
	){
		//set product properties
		

		//set category id
		$category->categoryName = $inputData->category;
		if(!$category->getIdByName()){
	        // set response code - 503 service unavailable
	        http_response_code(503);	
	        //exit program, when it isn't valid
			die(json_encode(array("message" => "doesn't get a category id, check category name input")));				
		}

		//get an idle ID number for new product and check if got one
		if(!$idleProductID = $category->getNextProductID()){
	        // set response code - 503 service unavailable
	        http_response_code(503);	
	        //exit program, when it isn't valid
			die(json_encode(array("message" => "doesn't get an idle product id")));			
		}
		//check if the ID is valid to use
		if(substr($idleProductID, 0, 1) == (string)$category->categoryID){
			$product->id = $idleProductID;
		}else{
	        // set response code - 503 service unavailable
	        http_response_code(503);	
	        //exit program, when it isn't valid
			die(json_encode(array("message" => "Product ID is invalid, check your database")));
		}

		//set the reset of values needed
		$product->category = $category->categoryID;
		$product->name = $inputData->name;
		$product->price = $inputData->price;
		$product->brand = $inputData->brand;
		$product->expireDate = $inputData->expireDate;
		$product->stockStatus = $inputData->stockStatus;
		$product->specification = $inputData->specification;

		//create the product by the data input
		if($product->create()){			
	        // set response code - 201 created
	        http_response_code(201);
			echo json_encode(array("message" => "Product is created successfully"));

			//update category table after creating
			if(!$category->updateAfterCreating()){
		        // set response code - 503 service unavailable
		        http_response_code(503);	
		        //exit program, when it isn't valid
				die(json_encode(array("message" => "have trouble with updating category table")));				
			}

			//transfer image uplaoded and re-name it
			if(!$product->updateImg()){
		        // set response code - 503 service unavailable
		        http_response_code(503);	
		        //exit program, when it isn't valid
				die(json_encode(array("message" => "have trouble with moving image")));					
			}

		}else{

	        // set response code - 503 service unavailable
	        http_response_code(503);

			echo json_encode(array("message" => "Unable to created the product"));
		}
	}else{

	    // set response code - 400 bad request
	    http_response_code(400);

		echo json_encode(array("message" => "Unable to create the product, data input is incomplete"));
	}

?>