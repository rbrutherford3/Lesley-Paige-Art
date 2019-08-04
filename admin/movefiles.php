<?php
	session_start();
	include_once('filenames.php');
	$filename = $_SESSION['filename'];
	$filenamenew = $_SESSION['filenamenew'];
	
	if (isset($_SESSION['id'])) {
		if ($filename != $filenamenew) {
			// Original image upload could have one of many file extensions:
			$results = glob($originalspath . $filename . '.*');
			$numresults = sizeof($results);
			if ($numresults == 0) {
				die('Originally uploaded image file not found!');
			}
			elseif ($numresults == 1) {
				$pathoriginal = $results[0];
				$extoriginal = pathinfo($pathoriginal, PATHINFO_EXTENSION);
				$pathdestination = $originalspath . $filenamenew . '.' . $extoriginal;
				movefile($pathoriginal, $pathdestination);
			}
			else {
				die('Multiple files of different types found with originally uploaded filename!');
			}
			
			$filenamefull = $filename . '.' . $ext;
			$filenamefullnew = $filenamenew . '.' . $ext;
			movefile($formattedpath . $filenamefull, $formattedpath . $filenamefullnew);
			movefile($croppedpath . $filenamefull, $croppedpath . $filenamefullnew);
			movefile($watermarkedpath . $filenamefull, $watermarkedpath . $filenamefullnew);
			movefile($thumbnailspath . $filenamefull, $thumbnailspath . $filenamefullnew);
		}
	}
	else {
		$filepath = $_SESSION['filepath'];
		$extoriginal = $_SESSION['extoriginal'];
		movefile($filepath . $filenameoriginal . $extoriginal, $originalspath . $filenamenew . '.' . $extoriginal);
		movefile($filepath . $filenameformatted, $formattedpath . $filenamenew . '.' . $ext);
		movefile($filepath . $filenamecropped, $croppedpath . $filenamenew . '.' . $ext);
		movefile($filepath . $filenamewatermarked, $watermarkedpath . $filenamenew . '.' . $ext);
		movefile($filepath . $filenamethumbnail, $thumbnailspath . $filenamenew . '.' . $ext);
		if (rmdir($uploadpath . $filename)) {
			// done
		}
		else {
			die('Could not delete upload folder, possibly due to other file in it');
		}
		header("Location: movefiles.php");
		die();
	}
	
	function movefile($frompath, $topath) {
		if (!file_exists($frompath)) {
			die('File not found!: ' . $frompath);
		}
		if (file_exists($topath)) {
			die('File exists at: ' . $topath);
		}
		rename($frompath, $topath);
	}
?>