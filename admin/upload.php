<?php
	session_start();
	include_once('filenames.php');
	include_once('connection.php');
	include_once('functions.php');
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_SESSION['upload']['dirname'])) {
			removefolder($_SESSION['upload']['dirpath'], true);
			unset($_SESSION['upload']);
		}
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
			if ($id != "new") {
				getdatabase($id, $db, "upload.php");
			}
		}
		if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {

			$filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
			$filesize = $_FILES['image']['size'];
			$filetmp = $_FILES['image']['tmp_name'];
			$filetype = $_FILES['image']['type'];
			$temp = explode('.',$_FILES['image']['name']);
			$fileext = strtolower(end($temp));

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
				$filenamenew = createfilename($filename);
				$filepath = $uploadpathds . $filenamenew;
				$filepathds = $filepath . $ds;
				if (file_exists($filepath)) {
					die("This file or one with the same name previously uploaded, but not processed");
				}
				else {
					if (mkdir($filepath)) {
						if (move_uploaded_file($filetmp, $filepathds . $filenameoriginal . '.' . $fileext)) {
							$_SESSION['upload']['originalname'] = $filename;
							$_SESSION['upload']['dirname'] = $filenamenew;
							$_SESSION['upload']['extoriginal'] = $fileext;
							$_SESSION['upload']['dirpath'] = $filepath;
							$_SESSION['upload']['dirpathds'] = $filepathds;
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
				die();
			}
		}
		else {
			echo 'Upload failure:<br>';
			echo $_FILES['image']['error'];
		}
	}
	else {
		if (isset($_SESSION['artinfo'])) {
			$options = false;
		}
		else {
			$options = true;
			$sql = "SELECT `name`, `id` FROM `info` ORDER BY `name` ASC;";
			$stmt = $db->prepare($sql);
			if(!$stmt->execute()) {
				die("Error executing general query: " . $db->errorInfo());
			}
			if ($stmt->rowCount() > 0) {
				$empty = false;
				$rows = $stmt->fetchAll();
			}
			else {
				$empty = true;
			}
		}
		if (isset($_SESSION['artinfo'])) {
			$title = $_SESSION['artinfo']['name'];
		}
		elseif (isset($_SESSION['database'])) {
			$title = $_SESSION['database']['name'];
		}
		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>Upload an image file' . (isset($title) ? ' for ' . $title : '') . '</title>
		<link rel="stylesheet" type="text/css" href="' . $cssmainpath . '">
		<link rel="stylesheet" type="text/css" href="' . $csstextpath . '">
		<link rel="stylesheet" type="text/css" href="' . $cssadminpath . '">
	</head>
	<body>
		<div class="page">
			<h1>Upload file:</h1>
			<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST" enctype="multipart/form-data">
				<p>
					<input type="file" name="image">
				</p>';
		if ($options && !$empty) {
			echo '
				<p>
					Select artpiece uploading, if not new:
					<br>
					<select name="id">
						<option value="new">***New Art Piece***</option>';
				foreach ($rows as $row) {
				echo '
						<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
			}
			echo '
					</select>
				</p>';
		}
		echo '
				<p>
					<input type="submit">
				</p>
			</form>
		</div>
	</body>
</html>';
	}
?>
