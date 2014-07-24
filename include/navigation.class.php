<?php
include_once "database.class.php";
Class Navigation extends Database {
	private $navID;
	private $items = array();
	private $nav;
	private $navLink = array();
	private $rowCount;
	private $navName;
	
	
	
	public function __construct($id, $name){
		$this->navID = $id;
		$this->navName = $name;
				
	}
	
	public function addMenuItem($menuItems = array()){
		$this->items .= $menuItems; 
	}
	
	
	
	public function buildNav(){
		$currentPage = "";
		$class = "";
		$class2 = "";	
		
		if($_GET['page'] != null){
			
			$currentPage = basename($_SERVER['PHP_SELF'])."?page=".$_GET['page'];
			$currentPage2 = $_GET['page'];
		}
		else{
			$currentPage = "/";
		}			
		
		$sql  = 'SELECT * ';
		$sql .= 'FROM '.$this->navName.' ';
		
		$query = Database::executeQuery($sql);
		
		$test = mysqli_num_rows($query);

		
		$markUp = '<ul>';
		
		while($row = mysqli_fetch_array($query)){
			
			$this->navName = $row['navName'];
			$this->navLink = $row['navLink'];
			if($currentPage == $this->navLink || $currentPage2 == $this->navLink){
				$class = "selected";
			}
			else{
				$class = "nav";
			}
			
			if(current($row) == 1){
				$class2 = "first";
				
			}
			elseif(current($row) == $test){
				$class2 = "last";
			}
			
			else{
				$class2 = NULL;
			}
			if($class2 != NULL){
				$markUp .= '<li class="'.$class.'"><a class="'.$class2.' navLink" rel="tab" href="'.$this->navLink.'">'.$this->navName.'</a></li>';
			}
			else{
				$markUp .= '<li class="'.$class.'"><a class="navLink" rel="tab" href="'.$this->navLink.'">'.$this->navName.'</a></li>';
			}

		}
		
		$markUp .= '</ul>';		


		return $markUp;		
		
	}
	

	
	
}






?>