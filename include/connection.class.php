<?php

/**
 * Connection Class
 * Purpose: To provide a database connection to the application
 * 
 * Information is processed securely from within this file, the database connection information
 * will not be made available to the outside world.
 * @author James P Smith
 * Adapted By: Tylor Faoro 
 */
class Connection{
	private $host;
	private $user;
	private $pass;
	private $db;
	

	//:: Constructor for the Database connection
	
	//:: LOCALHOST
	public function Connection(){
		$this->host = "localhost";		
		$this->user = "tfaoro";		
		$this->pass = "N0v3mber86";	
		$this->db	= "nigma_tylofaor";				
	}

	//:: LIVE SITE
/*	public function Connection(){
		$this->host = "ElegantWeddingsE.db.9878169.hostedresource.com";		
		$this->user = "ElegantWeddingsE";		
		$this->pass = "N0v3mber86!";	
		$this->db	= "ElegantWeddingsE";				
	}*/
	
//:: TEST SITE
	/*public function Connection(){
		$this->host = "ElegantWeddings.db.8425501.hostedresource.com";		
		$this->user = "ElegantWeddings";		
		$this->pass = "N0v3mber86!";	
		$this->db	= "ElegantWeddings";				
	}*/
	
	//:: Method for connecting to the database.
	public function connect(){
		
		$con=mysqli_connect("$this->host","$this->user","$this->pass","$this->db");
		
		// Check connection		
		if (mysqli_connect_errno()){
			echo "Error: " . mysqli_connect_error();
		}
		return $con;
	}
	
	
}
?>