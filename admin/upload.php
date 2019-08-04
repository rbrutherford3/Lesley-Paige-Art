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
			$_SESSION['extoriginal'] = $fileext;
			
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
				$filepath = $uploadpath . $filename;
				if (file_exists($filepath)) {
					die("This file or one with the same name previously uploaded, but not processed");
				}
				else {
					if (mkdir($filepath)) {
						$_SESSION['filepath'] = $filepath . $ds;
						if (move_uploaded_file($filetmp, $filepath . $ds . $filenameoriginal . $fileext)) {
							header("Location: resize.php");
							die();
						} else {
							die("There was an error uploading the image");
						}
					}
					else {
						die("There was an error creating the directory");
					}
				}
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
?>

<html>
   <body>
      <form action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" enctype="multipart/form-data">
         <input type="file" name="image">
         <input type="submit">
      </form>
   </body>
</html>