<?php 
include_once "image.class.php";
class FileManager extends Database{
	public $fileTypes = array("gif", "jpeg", "jpg", "png");
	public $thumbnail;	
	public static $message = "";
	public static $validFile;
	public static $uploadClass;
	
	public $gallery_img_width;
	public $gallery_img_height;
	
	public $fileData;
	
	protected $file;
	protected $resizedFile = NULL;
	
	
	
	
	public function uploadForm($class = NULL){
		$this->uploadClass = $class;
		$markUp  = '<span class="error">'.self::$message.'</span>';
		if($this->uploadClass != NULL){
			$markUp .= '<form class="'.$this->uploadClass.'" action="#" method="POST" enctype="multipart/form-data" id="uploadForm"> ';
		}
		else{
			$markUp .= '<form action="#" method="POST" enctype="multipart/form-data" id="uploadForm"> ';
		}
		
		$markUp .= '<label for="newExist">New or Existing Album</label>';
		$markUp .= 'New: <input type="radio" id="albumNew" name="newExist" value="New Album" />';
		$markUp .= 'Exist: <input type="radio" id="albumExist" name="newExist" value="Existing Album" /><br />';
		
		$markUp .= '<label class="existGalName" for="existGalName">Choose Gallery to Add to: </label> ';
		$markUp .= '<select class="existGalName" name="existGalName">';
		$markUp .= $this->existSelect();
		$markUp .= '</select>';
		
		$markUp .= '<label class="galName" for="galName">Gallery Name: </label> ';
		$markUp .= '<input class="galName" type="text" name="galName" />';
		
		$markUp .= '<br /><br />';
		$markUp .= '<label for="image">Your Photo: </label>';
		$markUp .= '<input type="file" name="image" />';
		
		$markUp .= '<input type="submit" value="Upload >>" name="submit" />';
		$markUp .= '</form>';
		
		$markUp .= '<span class="error">'.$this->fileData.'</span>';
		
		return $markUp;
		
	}
	
	public function uploadProcess(){
		if( isset($_POST['submit']) ){
			$_SESSION['file']['main_name'] = $_POST['image'];
			$_SESSION['file']['thumb'] = $_POST['image'];
			$this->upload();
			return $this->uploadForm($this->uploadClass);	
		}
		else{
			//$this->upload();
			
			return $this->uploadForm($this->uploadClass);
		}
	}
	protected function existSelect(){
		$markUp = '';
		$sql = 'SELECT * ';
		$sql .= 'FROM album';
		
		$query = Database::executeQuery($sql);
		
		while($data = mysqli_fetch_array($query) ){
			$markUp .= '<option>'.$data['Name'].'</option>';
		}
		
		return $markUp;
	}
	
	/**
	 * Checks the database for duplicates of the current entry. This method is relatively abstract so it can be used
	 * with other functions in other programs.
	 * @author Tylor Faoro
	 * 
	 * 
	 * @param String $table The EXACT table name from the database
	 * @param String $fieldName the EXACT field name from the database
	 * @param String $desiredName The user desired name of an album
	 * 
	 * @return Integer Returns the value 1 or 0. 1 meaning the entry is unique; 0 a duplicate entry is present. 
	 */
	public function checkForDupes($table, $fieldName, $desiredName){
				
			$sql  = 'SELECT * ';
			$sql .= 'FROM '.$table.' ';
			$sql .= 'WHERE '.$fieldName.' = "'.$desiredName.'"';
			

			$check = Database::executeQuery($sql);

			// If 1 is returned: No Duplicates are present
			// If 0 is returned: There is a Duplicate entry in the database.
			if(mysqli_num_rows($check) == 0){
				return 1;
			}
			else{
				return 0;
			} 	
	}
	
	public function upload(){
		$GalleryImage = new Image();
	
		
		$temp = explode(".", $_FILES['image']['name']);
		$extension = end($temp);
		
	
		$galleryName = $_POST['galName'];
		$existGalleryName = $_POST['existGalName'];
		$trueGalName = '';
		
		if($galleryName == '' || $galleryName == NULL && $existGalleryName != '' || $existGalleryName != NULL){
			$galType = 'exist';
			$trueGalName = $existGalleryName;
		}
		if($existGalleryName == '' || $existGalleryName == NULL && $galleryName != '' || $galleryName != NULL){
			$galType = 'new';
			$trueGalName = $galleryName;
		}
		
		switch($galType){
			case "new":
				$dupeCheck = $this->checkForDupes("album", "Name", $trueGalName );
				if( $dupeCheck == 1 ){
					Database::insertInto("album",array("Name"), array($trueGalName));
				}
				else{
					$this->fileData = 'That album already exists. Please try a different name.';
					return $this->fileData;				
				}				
							
			break;
			
			case "exist":
			break;
			
			case "delete":
			break;
			
			default:
			break;
		}
		
	
		if ((($_FILES['image']['type'] == "image/gif")
		|| ($_FILES['image']['type'] == "image/jpeg")
		|| ($_FILES['image']['type'] == "image/jpg")
		|| ($_FILES['image']['type'] == "image/pjpeg")
		|| ($_FILES['image']['type'] == "image/x-png")
		|| ($_FILES['image']['type'] == "image/png"))
		&& ($_FILES['image']['size'] <= 15728640)
		&& in_array($extension, $this->fileTypes)){
			
			if ($_FILES['image']["error"] > 0){				  
				$this->fileData = "Return Code: " . $_FILES['image']["error"] . "<br>";
			}
			else{
				$this->fileData  = $_FILES['image']['name'] . " was uploaded successfully.<br>";

			
				if (!file_exists('../Galleries')) {
					mkdir('../Galleries', 0777, true);
				}
				if (!file_exists("../Galleries/" . $trueGalName)) {
					mkdir("../Galleries/" . $trueGalName, 0777, true);
				}
				if ( !file_exists("../Galleries/" . $trueGalName . "/thumbnails") ){
					mkdir("../Galleries/" . $trueGalName . "/thumbnails", 0777, true);
				}
				if ( !file_exists("../Galleries/" . $trueGalName . "/gallery") ){
					mkdir("../Galleries/" . $trueGalName . "/gallery", 0777, true);
				}
				
				if (file_exists("../Galleries/" . $trueGalName ."/resize_". $_FILES['image']['name'])){					
					$this->fileData = $_FILES['image']['name'] . " already exists. ";
					
				}
				else{
					
					move_uploaded_file($_FILES['image']['tmp_name'],
					"../Galleries/" . $trueGalName . "/" . $_FILES['image']['name']);
					$this->fileData .= "Stored in: " . "Galleries/" . $trueGalName ."/original_". $_FILES['image']['name'];
					
					$GalleryImage->load("../Galleries/" . $trueGalName . "/" . $_FILES['image']['name'] );
					$GalleryImage->resize(500,500);
					$GalleryImage->save("../Galleries/" . $trueGalName . "/gallery/gallery_".$_FILES['image']['name']);						
					
					$GalleryImage->load("../Galleries/" . $trueGalName . "/" . $_FILES['image']['name'] );
					$GalleryImage->resize(200,200);
					$GalleryImage->save("../Galleries/" . $trueGalName . "/thumbnails/thumb_" . $_FILES['image']['name']);						

				}
			}
		}
		else{
		  $this->fileData = "Invalid file";
		}
		
		return $this->fileData;
	}
	
	public function drawGallery($dir, $id, $caption = null){
		$imageClass = new Image();	
		$ignore = array(".", "..", "gallery", "thumbnails");	
		$directory = USER_FILE.$dir;
		$photographer = '';
		
		
		$originals = scandir($directory);
		$thumber = scandir($directory."/thumbnails");
		$galImages = scandir($directory."/gallery");
		
		$sql  = 'SELECT * ';
		$sql .= 'FROM album ';
		$sql .= 'WHERE name = "'.$dir.'"';
		
		$query = $this->executeQuery($sql);
		
		while($photo = mysqli_fetch_array($query)){
			$photographer = $photo['photographer'];
			
		}
		
	
		$markUp = '';
		
		//$markUp .= '<h2 class="gallery-title">'.$dir.'</h2>';
		$markUp .= '<div id="headerRibbon"><h2 class="subRibbon"><span class="subRibbon-content">'.$dir.'</span></h2></div>';
		//$markUp .= $photographer;
		//$markUp .= '<span class="left-arrow"><img class="galPrev" src="images/prev.png" /></span>';
		$markUp .= '<div id="'.$id.'"class="thumb-scroller galBlock">';
		
		$markUp .= '<ul class="ts-list">';
		foreach($originals as $images){							


			if( !in_array($images, $ignore) ){

					//data-lightbox="image-'.$dir.'"			
				$markUp .= '<li data-caption-align="top" data-caption-effect="fade" >
								<a target="_blank" title="'.$caption.'" class="gallery" href="'.$directory.'/'.$images.'" data-lightbox="image-'.$dir.'" data-caption="TEST" data-title="test" name="test">
									<img class="thumb" src="'.$directory.'/thumbnails/thumb_'.$images.'" >
								</a>
							
							</li>';
				
			}
				
							
		}

		$markUp .= '</ul>';	
		$markUp .= '<span class="photographer">'.$photographer.'</span>';
		$markUp .= '</div>';
		//$markUp .= '<span class="right-arrow"><img class="galNext" src="images/next.png"</span>';
		return $markUp;	
		
	}

	//:: Displays all gallery's
	public function getAlbums(){
				
		$sql  = "SELECT * ";
		$sql .= "FROM album ";
		
		
		$query = Database::executeQuery($sql);
		
		
		$markUp  = '<div id="gallery">';
		
		while($album = mysqli_fetch_array($query)){
			
			$albumID = $this->getAlbumId($album['Name']);
			
			$markUp .= '<div id="galleryContainer">';
			$markUp .= '<div class="albumTitle">'.$album['Name'].'</div>';
			$markUp .= '<div class="thumbnail"><a href="index.php?page='.$this->albumName.'&action=viewAlbum&id='.$albumID.'"><img class="thumbnail" src="'. IMG . 'photoGallery.png" /></a></div>';
			$markUp .= '<div class="noDecoration">'.$this->getToolBox($albumID).'</div>';
			$markUp .= '</div>';

		}
		 
		$markUp .= '</div>';
		
		return $markUp;
		
		
	}
	
	//:: Retrieves the Album ID from the Database from the Album Name.

	protected function getAlbumId($albumName) {
		$sql  = "SELECT ID ";
		$sql .= "FROM album ";
		$sql .= 'WHERE Name = "' . $albumName . '"';
	
		$query = Database::executeQuery($sql);
		while ($album = mysqli_fetch_array($query)) {
	
			$id = $album['ID'];

		}
		return $id;
	}
	
	//:: Displays the "Toolbox" on the "Manage Gallery Page"
	protected function getToolBox($id){
		$markUp  = '<div class="toolbox noDecoration">';
		$markUp .= '<a href="index.php?page='.$this->albumName.'&action=viewAlbum&id='.$id.'"><img class="icon" src="'. IMG . 'viewIcon.png" /></a>';
		$markUp .= '<a href="index.php?page='.$this->albumName.'&action=modifyAlbum&id='.$id.'"><img class="icon" src="'. IMG . 'editIcon.png" /></a>';
		$markUp .= '<a href="index.php?page='.$this->albumName.'&action=deleteAlbum&id='.$id.'"><img class="icon" src="'. IMG . 'deleteIcon.png" /></a>';
		$markUp .= '</div>';
		
		return $markUp;
	}
	

}

?>