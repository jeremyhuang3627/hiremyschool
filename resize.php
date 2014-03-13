<?php

class resize{
	private $image; 
	private $height; 
	private $width; 
	private $imageResized; 
	private $filename;
	private $extension; 

	public function __construct($pic_dir){
		$this->image = $this->openImage($pic_dir); 
		$this->height = imagesy($this->image); 
		$this->width = imagesx($this->image); 
		$this->filename = basename($pic_dir); 
	}

	public function openImage($pic_dir){
		$extenion = end(explode(".", $pic_dir)); 
		switch($extenion){
			case 'jpeg': 
			case 'jpg': 
				$img = @imagecreatefromjpeg($pic_dir); 
				break; 
			case 'png':
				$img = @imagecreatefrompng($pic_dir);  
				break; 
			case 'gif': 
				$img = @imagecreatefromgif($pic_dir); 
				break; 
			default: 
				$img = false; 
				break; 
		}
		return $img; 
	}

	public function resizeImage($newHeight,$newWidth){
		if ($this->width>=$this->height){ 
			$ratio = $this->height/$this->width; 
			$resizeWidth = $newWidth;
			$resizeHeight = $resizeWidth * $ratio; 
		}else{
			$ratio = $this->width/$this->height; 
			$resizeHeight = $newHeight;
			$resizeWidth = $resizeHeight * $ratio; 
		}
		$this->imageResized = imagecreatetruecolor($resizeWidth, $resizeHeight); 
		imagecopyresampled($this->imageResized,$this->image, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $this->width, $this->height);

		$thumbnail_path  = "uploads/thumbs/".$this->filename."_thumb.jpeg";
		//then save this image
		imagejpeg($this->imageResized,$thumbnail_path, 100);
		imagedestroy($this->imageResized); 
		return $thumbnail_path;
	}
}
?>