<?php

class Database{
	//database credentials
	private $dbServer = "localhost:8889";
	private $dbUsername = "root";
	private $dbPassword = "root";
	private $dbName = "JINTIAN_ONLINE_STORE";

	public $conn;

	//get database connection
	public function getConnection(){
		try{
			$this->conn = new mysqli($this->dbServer, $this->dbUsername, $this->dbPassword, $this->dbName);
		}
		catch(Exception $e){
			echo "Connection Error: " . $e->getMessage();
			$this->conn = null;
		}

		return $this->conn;
	}

}

?>