<?php 

class resize
{
	//** class variables
	private $image; 
	private $width; 
	private $height; 
	private $imageResized; 

	public function __construct($fileName)
	{
		//open up the file
		$this->image = $this->openImage($fileName); 
		//*** get width and height
		$this->width = imagesx($this->image); 
		$this->height = imagesy($this->image); 
	}

	private function openImage($file)
	{
		//*** get extension
		$extension = strtolower(strrchr($file, '.')); 
		switch ($extension) {
			case '.jpeg': 
			case '.jpg':
				$img = @imagecreatefromjpeg($file); 
				break;
			case '.gif': 
				$img = @imagecreatefromgif($file); 
				break; 
			case '.png': 
				$img = @imagecreatefrompng($file); 
				break; 
			default:
				$img = false; 
				break;
		}
		return $img; 
	}

	public function resizeImage($newWidth, $newHeight, $option='auto')
	{
		$optionArray = $this -> getDimensions($newWidth,$newHeight,strtolower($option)); 
		$optimalWidth = optionArray['optimalWidth']; 
		$optimalHeight = optionArray['optimalHeight'];
		$this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight); 
		imagecopyresampled($this->imageResized,$this->image,0,0,0,0,$optimalWidth,$optimalHeight,$this->width,$this->height); 

		if ($option = 'crop')
		{
			$this->crop($optimalWidth,$optimalHeight,$newWidth,$newHeight); 
		}
	}

	private function getDimensions($newWidth,$newHeight,$option)
	{
		switch($option)
		{
			case 'exact': 
				$optimalWidth = $newWidth; 
				$optimalHeight = $newHeight; 
				break; 
			case 'portrait': 
				$optimalWidth = $this ->getSizeByFixedHeight($newHeight); 
				$optimalHeight = $newHeight; 
				break; 
			case 'landscape': 
				$optimalWidth = $newWidth; 
				$optimalHeight = $this->getSizeByFixedHeight($newWidth); 
				break; 
			case 'auto';
				$optionArray = $this->getSizeByAuto($newWidth,$newHeight);
					$optimalWidth = $optionArray['optimalWidth']; 
					$optimalHeight = $optionArray['optimalHeight']; 
				break;  
			case 'crop': 
				$optionArray = $this-> getOptimalCrop($newWidth,$newHeight); 
					$optimalWidth = $optionArray['optimalWidth']; 
					$optimalHeight = $optionArray['optimalHeight']; 
				break; 
		}
		return array('optimalWidth'=>$optimalWidth,'optimalHeight'=>$optimalHeight); 
	}

	private function getSizeByFixedHeight($newHeight)
	{
		$ratio = $this->width/$this->height; 
		$newWidth = $newHeight * $ratio; 
		return $newWidth; 
	}

	private function getSizeByFixedWidth($newWidth)
	{
		$ratio = $this->height/$this->width; 
		$newHeight = $newWidth * $ratio; 
		return $newHeight; 
	}

	private function getSizeByAuto($newWidth,$newHeight)
	{
		if ($this->height < $this->width){
			
		}
	}



?> 