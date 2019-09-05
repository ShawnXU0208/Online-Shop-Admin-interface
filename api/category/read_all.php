<?php
	// show error reporting
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	// required headers
	header("Content-Type: application/json; charset=UTF-8");

	//include database and object files
	include_once "../config/database.php";
	include_once "../objects/category.php";


	//instantiate database
	$database = new Database();
	$db = $database->getConnection();

	//instantiate category obejct
	$category = new Category($db);


	try{
		$stmt = $category->allCategories();
		if(!$stmt){
			throw new Exception("Have trouble with connecting database");
		}
	}
	catch(Eception $e){
		echo json_encode(array("message" => "Data Request Error: " . $e->getMessage()));
	}

	$stmt->store_result();
	$num = $stmt->num_rows();

	if($num > 0){
		$categoriesArray = array();
		$categoriesArray["records"] = array();

		while($stmt->fetch()){
			$categoryItem = array(
				"categoryID" => $category->categoryID,
				"categoryName" => $category->categoryName
			);

			$categoriesArray["records"][] = $categoryItem;
		}

	    // set response code - 200 OK
	    http_response_code(200);
		echo json_encode($categoriesArray);

	}else{
	    // set response code - 404 Not found
	    http_response_code(404);

		echo json_encode(array("message" => "no records found"));
	}

	$stmt->free_result();
?>