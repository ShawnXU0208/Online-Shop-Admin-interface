<?php

	// required headers
	header('Content-Type: application/json');

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

	//get id
	try{
		if(!isset($_GET['id'])){
			throw new Exception("lack of ID number");
		}
		$product->id = $_GET['id'];

		//read the product's details
		$stmt = $product->readOne();
		if(!$stmt){
			throw new Exception("Have trouble with connecting database");
		}
	}
	catch(Exception $e){
		echo json_encode(array("message" => "Data Request Error: " . $e->getMessage()));
	}


	$stmt->store_result();
	$num = $stmt->num_rows();

	//check the number of product fetched.
	if($num == 1){
		//get details of product
		$stmt->fetch();

 		$category->categoryID = $product->category;
		if(!$category->getNameById()){
	        // set response code - 503 service unavailable
	        http_response_code(503);	
	        //exit program, when it isn't valid
			die(json_encode(array("message" => "doesn't get a category name, check category id input")));	
		}

		$productItem = array(
			"id" => $product->id,
			"category" => $category->categoryName,
			"name" => $product->name,
			"price" => $product->price,
			"brand" => $product->brand,
			"expire_date" => $product->expireDate,
			"stock_status" => $product->stockStatus,
			"specification"	=> $product->specification	
		);

	    // set response code - 200 OK
	    http_response_code(200);
		echo json_encode($productItem);

	}else{

	    // set response code - 404 Not found
	    http_response_code(404);

		echo json_encode(array("message" => "no records found"));
	}

	$stmt->free_result();

?>