<?php
	// show error reporting
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	// required headers
	header("Access-Control-Allow-Origin: *");
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

	//get category
	try{
		if(!isset($_GET['category'])){
			throw new Exception("lack of category name");
		}
		$category->categoryName = $_GET['category'];
		if(!$category->getIdByName()){
	        // set response code - 503 service unavailable
	        http_response_code(503);	
	        //exit program, when it isn't valid
			die(json_encode(array("message" => "doesn't get a category id, check category name input")));				
		}
		$product->category = $category->categoryID;

		//read the product's details
		$stmt = $product->readCategory();
		if(!$stmt){
			throw new Exception("Have trouble with connecting database");
		}
	}
	catch(Exception $e){
		echo json_encode(array("message" => "Data Request Error: " . $e->getMessage()));
	}

	$stmt->store_result();
	$num = $stmt->num_rows();
	if($num > 0){
		$productsArray = array();
		$productsArray["records"] = array();

		while($stmt->fetch()){

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
 				"stock_status" => $product->stockStatus
 			);
			$productsArray["records"][] = $productItem;
		}

	    // set response code - 200 OK
	    http_response_code(200);
		echo json_encode($productsArray);		
	}else{
	    // set response code - 200 OK
	    http_response_code(200);

		echo json_encode(array("message" => "no records found"));		
	}

	$stmt->free_result();
?>