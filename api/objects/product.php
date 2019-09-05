<?php
	// show error reporting
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	class Product{
		//database connection and table name for product object
		private $conn;
		private $tableName = "Products";

		//product object properties
		public $id;
		public $category;
		public $name;
		public $price;
		public $brand;
		public $expireDate;
		public $stockStatus;
		public $specification;

		private $imageTempLocation = "../../admin_app/app/image_upload/temp_image.jpg";
		private $imagesLocation = "../../resource/image/";

		//constructor with $db as database connection
		public function __construct($db){
			$this->conn = $db;
		}

		private function sanitize($onlySanitizeID = false){
			if($onlySanitizeID){
				$this->id = htmlspecialchars(strip_tags($this->id));
			}else{
				$this->id = htmlspecialchars(strip_tags($this->id));
				$this->category = htmlspecialchars(strip_tags($this->category));
				$this->name = htmlspecialchars(strip_tags($this->name));		
				$this->price = htmlspecialchars(strip_tags($this->price));
				$this->brand = htmlspecialchars(strip_tags($this->brand));
				$this->expireDate = htmlspecialchars(strip_tags($this->expireDate));
				$this->stockStatus = htmlspecialchars(strip_tags($this->stockStatus));
				$this->specification = htmlspecialchars(strip_tags($this->specification));
			}
		}

		//function for reading all the products, return a sql query result.
		public function read(){

			//select all product query
			$query = "SELECT * FROM " . $this->tableName . ";";
			$stmt = null;

			try{
				$stmt = $this->conn->prepare($query);
				$stmt->execute();
				$stmt->bind_result(
					$this->id, 
					$this->category, 
					$this->name, 
					$this->price, 
					$this->brand, 
					$this->expireDate, 
					$this->stockStatus, 
					$this->specification
				);
			}
			catch(Exception $e){
				return false;
			}

			return $stmt;
		}

		public function create(){
			//insert query
			//$query = "INSERT INTO " . $this->tableName . " VALUES(?, (SELECT CategoryID FROM Categories WHERE CategoryID = ?), ?, ?, ?, ?, ?);";
			$query = "INSERT INTO " . $this->tableName . " VALUES(?, ?, ?, ?, ?, ?, ?, ?);";

			try{
				$stmt = $this->conn->prepare($query);

				//sanitize input
				$this->sanitize();

				$stmt->bind_param(
					"iisdssis", 
					$this->id, 
					$this->category, 
					$this->name, 
					$this->price, 
					$this->brand, 
					$this->expireDate, 
					$this->stockStatus, 
					$this->specification
				);

				if(!$stmt->execute()){
					throw new Exception("have trouble with inserting new data, check your input");
				}
				return true;
			}
			catch(Exception $e){
				return false;
			}
		}

		public function readOne(){
			//select one product by id qeury
			$query = "SELECT * FROM " . $this->tableName . " WHERE ProductID = ?;";
			$stmt = null;

			try{
				$stmt = $this->conn->prepare($query);
				$stmt->bind_param("i", $this->id);
				$stmt->execute();
				$stmt->bind_result(
					$this->id, 
					$this->category, 
					$this->name, 
					$this->price, 
					$this->brand, 
					$this->expireDate, 
					$this->stockStatus, 
					$this->specification
				);
			}
			catch(Exception $e){
				return false;
			}

			return $stmt;
		}

		public function readCategory(){
			//select query
			$query = "SELECT * FROM " .$this->tableName . " WHERE Category = ?;";
			$stmt = null;

			try{
				$stmt = $this->conn->prepare($query);
				$stmt->bind_param("i", $this->category);
				$stmt->execute();
				$stmt->bind_result(
					$this->id, 
					$this->category, 
					$this->name, 
					$this->price, 
					$this->brand, 
					$this->expireDate, 
					$this->stockStatus, 
					$this->specification
				);
			}
			catch(Exception $e){
				return false;
			}

			return $stmt;
		}

		public function update(){
			//update query
			$query = "UPDATE " . $this->tableName . 
						" SET 
							Category = ?,
							ProductName = ?,
							ProductPrice = ?,
							ProductBrand = ?,
							ProductExpireDate = ?,
							StockStatus = ?,
							Specification = ?
						  WHERE 
						  	ProductID = ?;";


			try{
				$stmt = $this->conn->prepare($query);

				//sanitize input
				$this->sanitize();

				$stmt->bind_param(
					"isdssisi", 
					$this->category, 
					$this->name, 
					$this->price, 
					$this->brand, 
					$this->expireDate, 
					$this->stockStatus, 
					$this->specification, 
					$this->id
				);

				if(!$stmt->execute()){
					throw new Exception("have trouble with updating new data, check your input");
				}	
				return true;			
			}
			catch(Exception $e){
				return false;
			}
		}

		public function delete(){
			//delete query
			$query = "DELETE FROM ". $this->tableName . " WHERE ProductID = ?";
			$stmt = $this->conn->prepare($query);

			//sanitize input ID
			$this->sanitize(true);

			$stmt->bind_param("i", $this->id);

			//execute query
			if($stmt->execute()){
				return true;
			}else{
				return false;
			}
		}

		public function search($keyword){
			//search query
			$query = "SELECT * FROM " . $this->tableName . " WHERE ProductName LIKE ? OR ProductBrand LIKE ?;";

			$stmt = $this->conn->prepare($query);

			//sanitize keyword input
			$keyword = htmlspecialchars(strip_tags($keyword));
			$keyword = "%{$keyword}%";

			$stmt->bind_param("ss", $keyword, $keyword);

			//execute query
			$stmt->execute();
			$stmt->bind_result(
					$this->id, 
					$this->category, 
					$this->name, 
					$this->price, 
					$this->brand, 
					$this->expireDate, 
					$this->stockStatus, 
					$this->specification
			);

			return $stmt;
		}


		public function readPaging($fromRecordNum, $recordsPerPage){
			//select query
			$query = "SELECT * FROM " . $this->tableName . " LIMIT ?, ?;";

			$stmt = $this->conn->prepare($query);

			$stmt->bind_param("ii", $fromRecordNum, $recordsPerPage);
			//execute query
			$stmt->execute();
			$stmt->bind_result(
					$this->id, 
					$this->category, 
					$this->name, 
					$this->price, 
					$this->brand, 
					$this->expireDate, 
					$this->stockStatus, 
					$this->specification
			);

			return $stmt;
		}

		public function readPagingCategory($fromRecordNum, $recordsPerPage){
			//select query
			$query = "SELECT * FROM " . $this->tableName . " WHERE Category = ? LIMIT ?, ?;";

			if($stmt = $this->conn->prepare($query)){
				$stmt->bind_param("iii", $this->category, $fromRecordNum, $recordsPerPage);
				//execute query
				$stmt->execute();
			}else{
			    $error = $this->conn->errno . ' ' . $this->conn->error;
			    die(json_encode(array("message" => $error)));	
			}

			$stmt->bind_result(
					$this->id, 
					$this->category, 
					$this->name, 
					$this->price, 
					$this->brand, 
					$this->expireDate, 
					$this->stockStatus, 
					$this->specification
			);

			return $stmt;
		}


		// used for paging products
		public function count(){
			$query = "SELECT COUNT(*) as total_rows FROM " . $this->tableName . ";";

			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$stmt->bind_result($totalRows);
			$stmt->fetch();
			$stmt->free_result();

			return $totalRows;
		}


		// used for creating new image for the product
		public function updateImg(){
			//transfer image uplaoded and re-name it
			$newLocation = $this->imagesLocation . $this->id . '.jpg';

			if(rename($this->imageTempLocation, $newLocation)){
				return true;							
			}else{
				return false;
			}
		}

		//used for deleting image for the product
		public function deleteImg(){
			$location = $this->imagesLocation . $this->id . ".jpg";

			if(unlink($location)){
				return true;
			}else{
				return false;
			}
		}



	}
?>