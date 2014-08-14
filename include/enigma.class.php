<?php 

class Enigma extends Database{
	public $pageValue;
	public $pageHeader;
	

	public function __construct(){
		$this->pageValue = $_GET['page'];
	}	
	
	public function drawHeading(){
		$sql  = 'SELECT pageHeader ';
		$sql .= 'FROM	page ';
		$sql .= 'WHERE 	pageID = '.$this->pageValue.' ';
		
		$result = $this->executeQuery( $sql );		
		
		foreach($result as $x){
			$this->pageHeader = $x['pageHeader'];
		}
		
		
		return $this->pageHeader;
		
		
	
	}




}



?>