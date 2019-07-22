<?php
	if (!isset($_SESSION)) { session_start(); }
	if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
		
		$file_name = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
		$file_size = $_FILES['image']['size'];
		$file_tmp = $_FILES['image']['tmp_name'];
		$file_type = $_FILES['image']['type'];
		$file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));

		$_SESSION['filename'] = $file_name;
		
		//echo $file_tmp;
		
		$errors = array();
		$extensions = array("jpeg","jpg","png","tif","tiff","gif","bmp");
		$filetypes = array("image/jpeg","image/png","image/tiff","image/gif","image/bmp");
		
		if (in_array($file_ext,$extensions)=== false) {
			$errors[] = "extension not allowed, please choose a JPEG, PNG, TIF, GIF, or BMP file.";
		}
		
		if (in_array($file_type,$filetypes)=== false) {
			$errors[] = "filetype not allowed, please choose a JPEG, PNG, TIF, GIF, or BMP file.";
		}
		
		if ($file_size > 134217728) {
			$errors[] = 'File size must be no greater than 128MB';
		}
		
		if (empty($errors)==true) {
			$imagick = new Imagick();
			$imagick->readImage($file_tmp);
			autorotate($imagick);
			//$imagick->stripImage();
			//$d = $imagick->getImageGeometry();
			//$w = $d['width'];
			//$h = $d['height'];
			//$imagick->writeImage($_SERVER['DOCUMENT_ROOT'] . '/admin/upload/' . $file_name . '.jpg');
			//$_SESSION['image'] = new $Imagick();
			//$_SESSION['image'] = clone $imagick;
			$imagick->writeImage(__DIR__ . DIRECTORY_SEPARATOR . 'upload' .  DIRECTORY_SEPARATOR . $file_name . '.jpg');
			//move_uploaded_file($file_tmp,"upload/".$file_name);
			//echo 'File successfully uploaded';
			header("Location: cropform.php");
			die();
			//echo $imagick->getImageOrientation() . '<br>';
			//echo Imagick::ORIENTATION_LEFTBOTTOM . '<br>';
			//echo Imagick::ORIENTATION_TOPLEFT;
		}
		else {
			print_r($errors);
		}
	}
	else {
		echo 'Upload failure';
	}
	
	function autorotate(Imagick $image) {
		switch ($image->getImageOrientation()) {
		case Imagick::ORIENTATION_TOPLEFT:
			break;
		case Imagick::ORIENTATION_TOPRIGHT:
			$image->flopImage();
			break;
		case Imagick::ORIENTATION_BOTTOMRIGHT:
			$image->rotateImage("#000", 180);
			break;
		case Imagick::ORIENTATION_BOTTOMLEFT:
			$image->flopImage();
			$image->rotateImage("#000", 180);
			break;
		case Imagick::ORIENTATION_LEFTTOP:
			$image->flopImage();
			$image->rotateImage("#000", -90);
			break;
		case Imagick::ORIENTATION_RIGHTTOP:
			$image->rotateImage("#000", 90);
			break;
		case Imagick::ORIENTATION_RIGHTBOTTOM:
			$image->flopImage();
			$image->rotateImage("#000", 90);
			break;
		case Imagick::ORIENTATION_LEFTBOTTOM:
			$image->rotateImage("#000", -90);
			break;
		default: // Invalid orientation
			break;
	}
    $image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
    return $image;
}
?>