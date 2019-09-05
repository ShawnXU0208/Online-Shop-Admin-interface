<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");


	//include database and object files
	include_once '../shared/utilities.php';
	include_once "../config/database.php";
	include_once "../config/core.php";
	include_once "../objects/product.php";
	include_once "../objects/category.php";


	//instantiate database
	$database = new Database();
	$db = $database->getConnection();

	//instantiate product obejct
	$product = new Product($db);

	//instantiate category object
	$category = new Category($db);

	//utilities
	$utilities = new Utilities();


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
		$stmt = $product->readPagingCategory($fromRecordNum, $recordsPerPage);
		if(!$stmt){
			throw new Exception("Have trouble with connecting database");
		}
	}
	catch(Exception $e){
		echo json_encode(array("message" => "Data Request Error: " . $e->getMessage()));
	}

	//query products, function parameters are from api/config/core.php
	$stmt->store_result();
	$num = $stmt->num_rows();


	//check records number
	if($num >= 0){
		//products array
		$productsArray = array();
		$productsArray["records"] = array();
		$productsArray["paging"] = array();

		while($stmt->fetch()){

	 		$category->categoryID = $product->category;
			if(!$category->getNameById()){
		        // set response code - 503 service unavailable
		        http_response_code(503);	
		        //exit program, when it isn't valid
				die(json_encode(array("message" => "doesn't get a category name, check category id input")));	
			}
			//extract associate array($row) to local variables
 			//extract($row);
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

		//including paging
		$totalRows = $category->count();

		$pageURL = $server_url . "/api/product/read_paging_category.php?category=" . $category->categoryName . "&";

		// $page: current page, from url GET parameter - location: api/config/core.php
		// $totalRows: the total number of records of products, get from count() method in api/object/product.php
		// $recordsPerPage: the number of products to display per page. - location: api/config/core.php
		// $pageURL: the unchanged part of URL with page number.
		$paging = $utilities->getPaging($page, $totalRows, $recordsPerPage, $pageURL);
		$productsArray["paging"] = $paging;

	    // set response code - 200 OK
	    http_response_code(200);
		echo json_encode($productsArray);

	}else{

	    // set response code - 404 Not found
	    http_response_code(404);

	    echo json_encode(array("message" => "No products found."));
	}


	$stmt->free_result();


?>