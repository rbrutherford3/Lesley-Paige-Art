<?php
	
	// This file accepts new uploaded images (for new or existing artpieces)
	// It also allows the user to pick or delete an uploaded but unprocessed fle

	require_once '../paths.php';
	require_once 'artpiece.php';

	session_start();

	if (!isset($_SESSION['artpiece']))
		$_SESSION['artpiece'] = new artpiece();

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		
		// Taking out trash (leftover processed but unmoved files)
		$watermarked = glob(UPLOAD_WATERMARKED['sys'] . '*');
		$thumbnails = glob(UPLOAD_THUMBNAILS['sys'] . '*');
		foreach ($watermarked as $item)
			unlink($item);
		foreach ($thumbnails as $item)
			unlink($item);

		// Error array and 'deleted' flag determine if the upload form is shown again
		$errors = array();
		$deleted = false;
		
		// Delete previously uploaded file if selected, and raise 'deleted flag'
		if (isset($_POST['delete'])) {
			if (!isset($_POST['filechoice'])) {
				$errors[] = "No file selected for deletion";
			}
			else {
				unlink($_POST['filechoice']);
				$deleted = true;
			}
		}
		
		// Use previously uploaded file, if selected
		// Bypasses most error-checking of a new upload
		elseif (isset($_POST['select'])) {
			if (!isset($_POST['filechoice'])) {
				$errors[] = "No file selected for editing";
			}
			else {
				$filepath = $_POST['filechoice'];
				$errorsmd5 = $_SESSION['artpiece']->errorsmd5(md5_file($filepath));
				if ($errorsmd5) {
					$errors[] = "Selected file already exists in database";
				}
				else {
					$_SESSION['artpiece']->addfile(pathinfo($filepath)['filename']);
					header('Location: ' . ADMIN['html'] . 'rotate.php');
					exit;
				}
			}
		}
		
		// Process newly uploaded file
		else {
			if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
				// Grab all information from upload
				$filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
				$fileext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
				$filesize = $_FILES['image']['size'];
				$filetmp = $_FILES['image']['tmp_name'];
				$filetype = $_FILES['image']['type'];
				$filemd5 = md5_file($filetmp);	// get hash of file for cross-checking database

				// Define acceptable filetypea amd extensions
				$extensions = array("jpeg","jpg","png","tif","tiff","gif","bmp");
				$filetypes = array("image/jpeg","image/png","image/tiff","image/gif","image/bmp");

				// 'Screen' file against acceptable parameters
				if (in_array($fileext,$extensions) === false)
					$errors[] = "File extension not allowed, please choose a JPEG, PNG, TIF, GIF, or BMP file.";
				if (in_array($filetype,$filetypes) === false)
					$errors[] = "File type not allowed, please choose a JPEG, PNG, TIF, GIF, or BMP file.";
				if ($filesize > 64000000)
					$errors[] = "File size too big, must be no greater than 64MB";
				if ($_SESSION['artpiece']->errorsmd5($filemd5))
					$errors[] = $_SESSION['artpiece']->errorsmd5($filemd5);
					
				// Rename file, move it, and begin processing image if no errors
				if (empty($errors)==true) {
					$filenamedest = artpiece::createfilename($filename);
					$filepathdest = UPLOAD_ORIGINALS['sys'] . $filenamedest . '.' . $fileext;
					if (file_exists($filepathdest))
						$errors[] = "File by same name already uploaded but not processed (see list below)";
					else {
						if (move_uploaded_file($filetmp, $filepathdest)) {
							$_SESSION['artpiece']->addfile($filenamedest);
							header('Location: ' . ADMIN['html'] . 'rotate.php');
							exit;
						}
						else
							$errors[] = "Error moving file";
					}
				}
			}
			else
				$errors[] = "Upload failure: " . $_FILES['image']['error'];
		}
	}
	
	// Display page if not submitting, but also if no errors were found or if a deletion was chosen
	if (($_SERVER['REQUEST_METHOD'] != "POST") || (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($errors) || $deleted)))  {
		
		// Compile list of uploaded but unprocessed images for selection
		$uploadedfiles = glob(UPLOAD_ORIGINALS['sys'] . '*');
		if (sizeof($uploadedfiles) > 0)
			$uploads = true;
		else
			$uploads = false;
			
		// Begin displaying page
		$title = $_SESSION['artpiece']->gettitle();
		if ($title)
			$title = 'Create new image file for "' . $title . '"';
		else
			$title = 'Create new image file';

		include 'cache.php';
		
		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>' . $title . '</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
	</head>
	<body>
		<h1>' . $title . '</h1>';
		if (!empty($errors)) {	// Display errors if they occured
			echo '
		<span class="error">
			<h2>Problems occured:</h2>';
			foreach ($errors as $error) {
				echo '
			<li>' . $error . '</li>';
			}
			echo '
		</span>';
		}
		echo '
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST" enctype="multipart/form-data">
			<h2>Upload file:</h2>
			<p>
				<input type="file" name="image" onchange="getElementById(\'upload\').disabled = false;">
			</p>
			<p>
				<input type="submit" name="upload" id="upload" value="Upload" disabled="disabled">
			</p>';
		if ($uploads) {	// Display uploaded but unprocessed files if they exist
			echo '
			<h2>Select previously uploaded file:</h2>';
			foreach ($uploadedfiles as $file) {
				echo  '
			<p>
				<input type="radio" name="filechoice" id="' . $file . '" value="' . $file . '" onchange="getElementById(\'delete\').disabled = false; getElementById(\'select\').disabled = false;">
				<label for="' . $file . '">' . basename($file) . '</label>
			</p>';
			}
			echo '
			<p>
				<input type="submit" name="delete" id="delete" value="Delete" onclick="return confirm(\'Are you sure you want to delete this file?\');" disabled="disabled">
				<input type="submit" name="select" id="select" value="Select" disabled="disabled">
			</p>';
		}
		echo '
		</form>
	</body>
</html>';
	}
?>
