<?php
require_once('WarningErrorHandler.php');
class Upload
{
	public function upload_pic($propic="propic", $id="-1"){
		if($propic=="propic"){
			$target_dir = $propic.'/'.$_POST["writer_id_img"].'.gif';
		}else{
			$target_dir = $propic.'/'.$_POST["writer_id_img"].'_'.$id.'.gif';
		}
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// Check if image file is a actual image or fake image
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				//echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				//echo "File is not an image.";
				$uploadOk = 0;
			}
		
		// Check if file already exists
		if (file_exists($target_file)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 5000000) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			//echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			$source = $_FILES["fileToUpload"]["tmp_name"];
			$destination = $target_dir;
			$quality = 20;
			if ($this->compressor($source, $destination, $quality)) {
				echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. Reload page to see changes.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}
	public function compressor($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return true;
	}
	
	public function upload_picV2($propic="propic", $id="-1", $writer_id_img, $imgurl, $base64data){
		$target_dir = $propic.'/'.$writer_id_img.'_'.$id.'.gif';
		
		$this->base64_to_jpeg($base64data, $target_dir);

	}
	
	
	function base64_to_jpeg($base64_string, $output_file) {
	    $ifp = fopen($output_file, "wb"); 
	
	    $data = explode(',', $base64_string);
	
	    fwrite($ifp, base64_decode($data[1])); 
	    fclose($ifp); 
	
	    return $output_file; 
	}
	
	public function compressor2($sourcePath, $destination, $image) {//not used
		$image_location = $sourcePath . "/" . $image;
		$thumb_destination = $destination . "/t" . $image;
		$compression_type = Imagick::COMPRESSION_JPEG;
	  
		$im = new Imagick($image_location);
		$thumbnail = $im->clone;

		$thumbnail->setImageCompression($compression_type);
		$thumbnail->setImageCompressionQuality(40);
		$thumbnail->stripImage();
		$thumbnail->thumbnailImage(100,null);
		$thumbnail->writeImage($thumb_destination); 
	  }
}

?> 