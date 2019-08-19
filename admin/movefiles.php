<?php
	session_start();
	include_once('filenames.php');
	include_once('functions.php');
	$filename = $_SESSION['artinfo']['filename'];
	$filenamefull = $filename . '.' . $ext;
	
	if (isset($_SESSION['database'])) {
		$id = $_SESSION['database']['id'];
		$filenameold = $_SESSION['database']['filename'];
		$filenamefullold = $filenameold . '.' . $ext;
		$extoriginalold = $_SESSION['database']['extoriginal'];
		$filenamefulloriginalold = $filenameold . '.' . $extoriginalold;
		
		if (isset($_SESSION['upload'])) {
			removefile($originalspath . $filenamefulloriginalold);
			removefile($formattedpath . $filenamefullold);
			removefile($croppedpath . $filenamefullold);
			removefile($watermarkedpath . $filenamefullold);
			removefile($thumbnailspath . $filenamefullold);
		}
		else {
			if ($filenameold != $filename) {
				$filenamefulloriginal = $filename . '.' . $extoriginalold;
				
				movefile($originalspath . $filenamefulloriginalold, $originalspath . $filenamefulloriginal, false);
				movefile($formattedpath . $filenamefullold, $formattedpath . $filenamefull, false);
				movefile($croppedpath . $filenamefullold, $croppedpath . $filenamefull, false);
				movefile($watermarkedpath . $filenamefullold, $watermarkedpath . $filenamefull, false);
				movefile($thumbnailspath . $filenamefullold, $thumbnailspath . $filenamefull, false);
			}
		}
	}
	
	if (isset($_SESSION['upload'])) {
		$uploadedpath = $_SESSION['upload']['dirpath'];
		$uploadedpathds = $_SESSION['upload']['dirpathds'];
		$extoriginal = $_SESSION['upload']['extoriginal'];
		
		$filenamefulloriginal = $filename . '.' . $extoriginal;
		
		movefile($uploadedpathds . $filenameoriginal . '.' . $extoriginal, $originalspath . $filenamefulloriginal, true);
		movefile($uploadedpathds . $filenameextformatted, $formattedpath . $filenamefull, true);
		movefile($uploadedpathds . $filenameextcropped, $croppedpath . $filenamefull, true);
		movefile($uploadedpathds . $filenameextwatermarked, $watermarkedpath . $filenamefull, true);
		movefile($uploadedpathds . $filenameextthumbnail, $thumbnailspath . $filenamefull, true);
		
		removefolder($uploadedpath, true);
	}
	
	if (!isset($id)) {
		$id = $_SESSION['newid'];
	}
	
	session_destroy();
	echo '<script>window.location = "sequence.php#' . $id . '";</script>';
	
?>