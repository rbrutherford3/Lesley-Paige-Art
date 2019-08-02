<?php
	session_start();
	include_once('filenames.php');
    if($_SERVER['REQUEST_METHOD'] == "POST") {
		if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
			
			$filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
			$filesize = $_FILES['image']['size'];
			$filetmp = $_FILES['image']['tmp_name'];
			$filetype = $_FILES['image']['type'];
			$temp = explode('.',$_FILES['image']['name']);
			$fileext = strtolower(end($temp));

			$_SESSION['filename'] = $filename;
			
			//echo $filetmp;
			
			$errors = array();
			$extensions = array("jpeg","jpg","png","tif","tiff","gif","bmp");
			$filetypes = array("image/jpeg","image/png","image/tiff","image/gif","image/bmp");
			
			if (in_array($fileext,$extensions)=== false) {
				$errors[] = "extension not allowed, please choose a JPEG, PNG, TIF, GIF, or BMP file.";
			}
			
			if (in_array($filetype,$filetypes)=== false) {
				$errors[] = "filetype not allowed, please choose a JPEG, PNG, TIF, GIF, or BMP file.";
			}
			
			if ($filesize > 134217728) {
				$errors[] = 'File size must be no greater than 128MB';
			}
			
			if (empty($errors)==true) {
				$imagick = new Imagick();
				$imagick->readImage($filetmp);
				autorotate($imagick);
				$imagick->setImageFormat($extoriginal);
				$filepath = $uploadroot . $filename;
				// Note that 'filename' is really a directory name in this case:
				// 'filename' refers to the original name of the file uploaded, and 'filename' 
				// is also used in artinfo.php for existing pieces, so it was not changed
				if (file_exists($filepath)) {
					die("File previously uploaded");
				}
				else {
					mkdir($filepath);
					$_SESSION['filepath'] = $filepath . $ds;
				}
				$imagick->writeImage($_SESSION['filepath'] . $filenameoriginal);
				header("Location: crop.php");
				die();
			}
			else {
				print_r($errors);
			}
		}
		else {
			echo 'Upload failure:<br>';
			echo $_FILES['image']['error'];
		}
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

<html>
   <body>
      <form action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" enctype="multipart/form-data">
         <input type="file" name="image">
         <input type="submit">
      </form>
   </body>
</html>