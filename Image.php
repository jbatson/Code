<?php
/** IMAGE RESIZING HANDLER
 * @uses gd or image magick
 * @author: joseph batson
 * @date: 06/05/09
 * @compatibility: php4, php5
 */
class Image
{
	/**
	 * function magicResize($inputName, $outputName, $imageDirectory, $thumbDirectory, $createThumb, $imageSize, $pathToExe)
	 * 
	 * @return 
	 * @param object $inputName
	 * @param object $outputName
	 * @param object $imageDirectory
	 * @param object $thumbDirectory
	 * @param object $createThumb
	 * @param object $imageSize
	 * @param object $pathToExe
	 * 
	 * $inputName 		= example.jpg
	 * $outputName 		= newPhoto.jpg
	 * $imageDirectory 	= directory to main image
	 * $thumbDirectory 	= directory to save thumbnail
	 * $createThumb 	= true/false
	 * $imageSize 		= 600
	 * $pathToExe 		= path to image magick convert 	(/usr/bin/convert)
	 */
	
	
	function MagicResize($inputName, $outputName, $imageDirectory, $thumbDirectory, $createThumb, $imageSize, $pathToExe)
	{	
		if(is_executable($pathToExe))
		{
			$inputPhoto 	= $imageDirectory.$inputName;
			
			if($createThumb === false)
			{	
				$outputPhoto 	= $imageDirectory.$outputName;	
			}
			
			if($createThumb === true)
			{	
				$outputPhoto 	= $thumbDirectory.$outputName;	
			}			
			echo exec("$pathToExe -quality 80 -colorspace RGB -scale ".$imageSize."x".$imageSize." ".$inputPhoto." ".$outputPhoto);		
			
			if(file_exists($outputPhoto))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * function gdResize($inputName, $outputName, $imageDirectory, $thumbDirectory, $createThumb, $imageSize)
	 * 
	 * @return 
	 * @param object $inputName
	 * @param object $outputName
	 * @param object $imageDirectory
	 * @param object $thumbDirectory
	 * @param object $createThumb
	 * @param object $imageSize
	 * 
	 * $inputName 		= example.jpg
	 * $outputName 		= newPhoto.jpg
	 * $imageDirectory 	= directory to main image
	 * $thumbDirectory 	= directory to save thumbnail
	 * $createThumb 	= true/false
	 * $imageSize 		= 600
	 */
	
	function GdResize($inputName, $outputName, $imageDirectory, $thumbDirectory, $createThumb, $imageSize)
	{
		$inputPhoto 	= $imageDirectory.$inputName;
	
		if($createThumb === false)
		{	
			$outputPhoto 	= $imageDirectory.$outputName;	
		}
		
		if($createThumb === true)
		{	
			$outputPhoto 	= $thumbDirectory.$outputName;	
		}		
		
		if(file_exists($inputPhoto))
		{
			if(function_exists('gd_info'))
			{
				$imageData 		= getimagesize($inputPhoto);
				$imageWidth 	= $imageData[0];
				$imageHeight 	= $imageData[1];
				
				switch($imageData[2])
				{
					case 1:
						$imageType = 'IMG_GIF';
					break;
					case 2:
						$imageType = 'IMG_JPG';
					break;
					case 3:
						$imageType = 'IMG_PNG';
					break;
					case 4:
						$imageType = 'IMG_WBMP';
					break;															
				}
				
				$scaledWidth 	= $imageWidth;
				$scaledHeight 	= $imageHeight;
				
				while(($scaledWidth > $imageSize) || ($scaledHeight > $imageSize)) 
				{
					$scaledWidth = round($scaledWidth * .95);
					$scaledHeight = round($scaledHeight * .95);
				}
				
				switch($imageType)
				{
					case "IMG_GIF":
						$originalImage 		= imagecreatefromgif($inputPhoto);
						$scaledImage 		= imagecreatetruecolor($scaledWidth,$scaledHeight);
						imagecopyresized($scaledImage,$originalImage,0,0,0,0,$scaledWidth,$scaledHeight,$imageWidth,$imageHeight);
						imagejpeg($scaledImage,	$outputPhoto, 90);
						
						imagedestroy($originalImage);
						imagedestroy($scaledImage);
					break;
					case "IMG_JPG":
						$originalImage 		= imagecreatefromjpeg($inputPhoto);
						$scaledImage 		= imagecreatetruecolor($scaledWidth,$scaledHeight);
						imagecopyresized($scaledImage,$originalImage,0,0,0,0,$scaledWidth,$scaledHeight,$imageWidth,$imageHeight);
						imagejpeg($scaledImage,	$outputPhoto, 90);
						
						imagedestroy($originalImage);
						imagedestroy($scaledImage);
					break;
					case "IMG_PNG":
						$originalImage 		= imagecreatefrompng($inputPhoto);
						$scaledImage 		= imagecreatetruecolor($scaledWidth,$scaledHeight);
						imagecopyresized($scaledImage,$originalImage,0,0,0,0,$scaledWidth,$scaledHeight,$imageWidth,$imageHeight);
						imagejpeg($scaledImage,	$outputPhoto, 90);
						
						imagedestroy($originalImage);
						imagedestroy($scaledImage);
					break;
					case "IMG_WBMP":
						$originalImage 		= imagecreatefromwbmp($inputPhoto);
						$scaledImage 		= imagecreatetruecolor($scaledWidth,$scaledHeight);
						imagecopyresized($scaledImage,$originalImage,0,0,0,0,$scaledWidth,$scaledHeight,$imageWidth,$imageHeight);
						imagejpeg($scaledImage,	$outputPhoto, 90);
						
						imagedestroy($originalImage);
						imagedestroy($scaledImage);
					break;
				}
				
				return true;
							
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;	
		}
	}
}
?>