<?php 

/**
 * Image
 *
 * @file: image.class.php
 * @author: Simon Jarvis
 * @copyright: 2006 Simon Jarvis
 * @date: 08/11/06
 * @link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/gpl.html
 *
 * The first example below will load a file named picture.jpg resize it to 250 pixels wide and 400 pixels high and resave it as picture2.jpg
 *
 *
 *   include('image.class.php');
 *   $image = new Image();
 *   $image->load('picture.jpg');
 *   $image->resize(250,400);
 *   $image->save('picture2.jpg');
 *
 */
class Image {
	private $image;
	private $image_type;

		
	/**
	 *
	 * @param object $filename
	 */
	public function load( $filename ) {
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if ($this->image_type == IMAGETYPE_JPEG) {
			$this->image = imagecreatefromjpeg($filename);
		}
		elseif ($this->image_type == IMAGETYPE_GIF) {
			$this->image = imagecreatefromgif($filename);
		}
		elseif ($this->image_type == IMAGETYPE_PNG) {
			$this->image = imagecreatefrompng($filename);
		}
	}
	

	/**
	 *
	 * @param object $filename
	 * @param object $image_type [optional]
	 * @param object $compression [optional]
	 * @param object $permissions [optional]
	 * @return
	 */
	public function save( $filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null ) {
		$success = false;
		
		if ($image_type == IMAGETYPE_JPEG) {
			$success = imagejpeg($this->image, $filename, $compression);
		}
		elseif ($image_type == IMAGETYPE_GIF) {
			$success = imagegif($this->image, $filename);
		}
		elseif ($image_type == IMAGETYPE_PNG) {
			$success = imagepng($this->image, $filename);
		}
		
		if ($permissions != null) {
			$success = chmod($filename, $permissions);
		}
		
		return $success;
	}
	
	/**
	 *
	 * @param object $image_type [optional]
	 */
	public function output( $image_type = IMAGETYPE_JPEG ) {
		if ($image_type == IMAGETYPE_JPEG) {
			imagejpeg($this->image);
		}
		elseif ($image_type == IMAGETYPE_GIF) {
			imagegif($this->image);
		}
		elseif ($image_type == IMAGETYPE_PNG) {
			imagepng($this->image);
		}
	}
	
	/**
	 *
	 * @return
	 */
	public function getWidth() {
		return imagesx($this->image);
	}
	
	/**
	 *
	 * @return
	 */
	public function getHeight() {
		return imagesy($this->image);
	}
	
	/**
	 *
	 * @param object $height
	 */
	public function resizeToHeight( $height ) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
	}
	
	/**
	 *
	 * @param object $width
	 * @return
	 */
	public function resizeToWidth( $width ) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width, $height);
	}
	
	/**
	 *
	 * @param object $scale
	 */
	public function scale( $scale ) {
		$width = $this->getWidth() * $scale / 100;
		$height = $this->getheight() * $scale / 100;
		$this->resize($width, $height);
	}
	
	/**
	 *
	 * @param object $width
	 * @param object $height
	 */
	public function resize( $width, $height ) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}
	
	
}
?>
