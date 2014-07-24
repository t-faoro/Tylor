<?php 

/**
 * Authentication Class
 * 
 * FileName: authentication.class.php
 * Purpose: To handle all authentication methods of sites through sessions.
 * Date: June 24, 2013 
 * 
 * @author Tylor Faoro
 *
*/
class Authentication extends Database {
	public $is_logged_in;


	/**
	 * Activates Initial variables
	*/
	public function __construct(){
		$this->is_logged_in = FALSE;		
	
	}
	
	/**
	 * This method will set sessions upon successful authentication. The sessions will manage
	 * user data by determining what level of clearance they possess.
	 * 
	 * @author Tylor Faoro
	 *
	 * @param mixed $query - A completed query sent from the Check Login Method
	*/
	public function sessionSetter($query){
		
		while($row = mysqli_fetch_assoc($query)){
			$_SESSION['authenticated'] = TRUE;
			$_SESSION['id'] = $row['mem_id'];
			$_SESSION['name'] = $row['mem_name'];			
			$_SESSION['uName'] = $row['mem_email'];
			$_SESSION['site'] = $row['mem_SiteName'];			
		
		}		
		
	}
	
	public function drawLogin(){			
		
		$this->checkAuth();	
	}
	
	/**
	 * Draws the login form to the front end. 
	 *  - basename($_SERVER['PHP_SELF']) => Determines what the current page is for use by $_POST
	 *    for post-back validation.
	 *
	 * @author Tylor Faoro
	 *
	 * @return Mixed $markUp - Completed HTML markup for the front end.
	*/
	public function loginForm(){
		$currentPage = basename($_SERVER['PHP_SELF']);
		
			
		$markUp  = '<div id="loginContainer" >';
		
		$markUp .= '<div id="loginForm" >';
		$markUp .= '<form method="POST" action='.$currentPage.'>';
		$markUp .= '<h3>Login to admin area</h3>';
		$markUp .= '<label for="uName">Username: </label>';
		$markUp .= '<input type="text" name="user" /><br />';
		$markUp .= '<label for="pW">Password:</label>';
		$markUp .= '<input type="password" name="pass" /><br /><br />';
		$markUp .= '<input type="submit" name="login" value="Login" />';
		$markUp .= '</form>';
		$markUp .= '</div>';
		
		$markUp .= '</div>';
		
		
		return $markUp;
		
	}
	
	/**
	 * PRIVATE METHOD 
	 *
	 * Simply manages post-back validation, and calls the the Private Function 'checkLogin'
	 * 
	 * @author Tylor Faoro
	*/
	private function checkAuth(){
		
		if( isset($_POST['login']) ){
			$username = $_POST['user'];
			$password = $_POST['pass'];
			
			$this->checkLogin($username, $password);
		
		}
		else{
			
			echo $this->loginForm();
		}
	
	}
	
	/**
	 * The Method that will be used by the Front End to determine if the user is logged in. This is achieved
	 * through sessions, if ALL of the session variables are set then the user must be logged in.
	 *
	 * @author Tylor Faoro
	*/
	public function authUser() {
	
		if(  isset($_SESSION['authenticated']) && isset($_SESSION['id']) && isset($_SESSION['name']) && isset($_SESSION['uName']) ){ 
			return true;
		}
		else{
			 return false;
			 
		}
	}
	
	/** 
	 * PRIVATE METHOD
	 *
	 * Checks the provided login information against data contained within the site's database. This method also directs
	 * users to where they need to be. If authentication is successful the user will be sent to the respective admin area.
	 * Otherwise, the user will be redirected back to the login area.
	 * 
	 * @author Tylor Faoro
	 *
	 * !! IMPORTANT !!
	 *   - Parameters MUST be sanitized before being sent to the database, this prevents SQL Injection.
	 *	 - The password is hashed via MD5 within this function. DO NOT sent MD5 data to this function, or the password in the database
	 *     will be an undesireable result.
	 *
	 * @param String $email - The username entered into the login form.
	 * @param String $password - The password entered into the login form. 
	*/
	private function checkLogin($email, $password){				

			Database::dbConnect();
			$user = Database::sanitize($email);
			$pw   = Database::sanitize( MD5($password ) );
			Database::dbClose();
			
			$sql  = 'SELECT * ';
			$sql .= 'FROM members ';
			$sql .= 'WHERE mem_email = "'.$user.'" ' ;
			$sql .= ' AND ';
			$sql .= 'mem_password = "'.$pw.'" ';
			
			$row = mysqli_num_rows(Database::executeQuery($sql));
			$query = Database::executeQuery($sql);
			
			//:: Login Failed
			if($row <= 0 || $row > 1){
				$this->is_logged_in = FALSE;
				echo $this->loginForm();
				
			}
			//:: Login Successful
			if($row == 1){				
				$this->sessionSetter($query);				
				$this->is_logged_in = TRUE;
				header('location: managePages.php');
					
			}		
			
			//:: This else is very simply for an unforseen circumstance, nothing more.
			else{
				$this->is_logged_in = FALSE;
			}
					
	}
	
	
	/**
	 * Logs the user out of the administration area. This method will unset sessions containing data and 
	 * set $_SESSION['authenticated'] to false; indicating the user is no longer logged in.
	 *
	 * @author Tylor Faoro
	*/
	public function logout(){
			$_SESSION['authenticated'] = FALSE;
			unset($_SESSION['id']);
			unset($_SESSION['name']);
			unset($_SESSION['uName']);
			header('location: index.php');	
	}

}

?>