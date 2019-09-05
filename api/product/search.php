<?php
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

	//get keywords input
	$keywords = $_GET['s'];
	if(!isset($keywords)){
		$keywords = "";
	}

	//query products 
	$stmt = $product->search($keywords);
	$stmt->store_result();
	$num = $stmt->num_rows();

	//check number of records
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
			
			//extract associate array($row) to local variables
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
	    // set response code - 404 Not found
	    http_response_code(200);

		echo json_encode(array("message" => "no records found"));
	}

	$stmt->free_result();
?>