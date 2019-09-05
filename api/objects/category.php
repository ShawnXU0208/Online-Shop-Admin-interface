<?php

	// show error reporting
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	class Category{
		//database connection and table name for Category object
		private $conn;
		private $tableName = "Categories";

		public $categoryID;
		public $categoryName;
		public $productNumber;
		public $lastProductID;

		//constructor with $db as database connection
		public function __construct($db){
			$this->conn = $db;
		}

		//function to fetch all categories in database
		public function allCategories(){
			// select query
			$query = "SELECT CategoryID, CategoryName FROM " . $this->tableName . ";";

			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$stmt->bind_result($this->categoryID, $this->categoryName);
			return $stmt;
		}

		public function getIdByName(){
			//select query
			$query = "SELECT CategoryID FROM " . $this->tableName . " WHERE CategoryName = ?;";

			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("s", $this->categoryName);
			$stmt->execute();
			$stmt->bind_result($this->categoryID);

			$stmt->store_result();
			$num = $stmt->num_rows();
			if($num == 1){
				$stmt->fetch();
				$stmt->free_result();
				return true;
			}else{
				$stmt->free_result();
				return false;
			}
		}

		public function getNameById(){
			//select query
			$query = "SELECT CategoryName FROM " . $this->tableName . " WHERE CategoryID = ?;";

			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $this->categoryID);
			$stmt->execute();
			$stmt->bind_result($this->categoryName);

			$stmt->store_result();
			$num = $stmt->num_rows();
			if($num == 1){
				$stmt->fetch();
				$stmt->free_result();
				return true;
			}else{
				$stmt->free_result();
				return false;
			}		
		}

		public function oneCategory(){
			//select query
			$query = "SELECT * FROM " . $this->tableName . " WHERE CategoryID = ?;";

			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $this->categoryID);
			$stmt->execute();
			$stmt->bind_result($this->categoryID, $this->categoryName, $this->productNumber, $this->lastProductID);
			return $stmt;
		}

		// used for paging products
		public function count(){
			$query = "SELECT ProductNumber FROM " . $this->tableName . " WHERE CategoryID = ?;";

			if($stmt = $this->conn->prepare($query)){
				$stmt->bind_param("i", $this->categoryID);
				$stmt->execute();
			}else{
			    $error = $this->conn->errno . ' ' . $this->conn->error;
			    die(json_encode(array("message" => $error)));				
			}
			$stmt->bind_result($this->productNumber);
			$stmt->fetch();
			$stmt->free_result();

			return $this->productNumber;
		}

		public function getNextProductID(){
			//select query
			$query = "SELECT LastProductID FROM " . $this->tableName . " WHERE CategoryID = ?;";

			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $this->categoryID);
			$stmt->execute();
			$stmt->bind_result($this->lastProductID);

			$stmt->store_result();
			$num = $stmt->num_rows();
			if($num == 1){
				$stmt->fetch();

				$nextID = $this->lastProductID + 1;
				$stmt->free_result();
				return $nextID;
			}else{
				$stmt->free_result();
				return false;
			}
		}

		public function updateAfterCreating(){

			//get product number
			$query = "SELECT ProductNumber FROM " . $this->tableName . " WHERE CategoryID = ?;";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $this->categoryID);
			$stmt->execute();
			$stmt->bind_result($this->productNumber);
			$stmt->store_result();
			if($stmt->num_rows() == 1){
				$stmt->fetch();
			}else{
				return false;
			}
			$stmt->free_result();

			//update query
			$query = "UPDATE " . $this->tableName . " SET ProductNumber = ?, LastProductID = ? WHERE CategoryID = ?;";

			$stmt = $this->conn->prepare($query);

			$newProductNumber = $this->productNumber + 1;
			$newProductID = $this->lastProductID + 1;
			$stmt->bind_param("iii", $newProductNumber, $newProductID, $this->categoryID);

			$stmt->execute();
			//check the number of rows that changed
			if($stmt->affected_rows == 1){
				return true;
			}else{
				return false;
			}

		}

		public function updateAfterDeleting(){
			//get product number
			$query = "SELECT ProductNumber FROM " . $this->tableName . " WHERE CategoryID = ?;";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $this->categoryID);
			$stmt->execute();
			$stmt->bind_result($this->productNumber);
			$stmt->store_result();
			if($stmt->num_rows() == 1){
				$stmt->fetch();
			}else{
				return false;
			}
			$stmt->free_result();

			//update query
			$query = "UPDATE " . $this->tableName . " SET ProductNumber = ? WHERE CategoryID = ?";

			$stmt = $this->conn->prepare($query);

			$newProductNumber = $this->productNumber - 1;
			$stmt->bind_param("ii", $newProductNumber, $this->categoryID);

			$stmt->execute();
			//check the number of rows that changed
			if($stmt->affected_rows == 1){
				return true;
			}else{
				return false;
			}

		}

	}

?>
