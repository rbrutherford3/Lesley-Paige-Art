<?php
	session_start();
	require_once '../paths.php';
	require_once 'functions.php';
	$filename = $_SESSION['artinfo']['filename'];
	$filenamefull = $filename . '.' . EXT;

	if (isset($_SESSION['database'])) {
		$id = $_SESSION['database']['id'];
		$filenameold = $_SESSION['database']['filename'];
		$filenamefullold = $filenameold . '.' . EXT;
		$extoriginalold = $_SESSION['database']['extoriginal'];
		$filenamefulloriginalold = $filenameold . '.' . $extoriginalold;

		if (isset($_SESSION['upload'])) {
			removefile(ORIGINALS . $filenamefulloriginalold);
			removefile(FORMATTED . $filenamefullold);
			removefile(CROPPED . $filenamefullold);
			removefile(WATERMARKED . $filenamefullold);
			removefile(THUMBNAILS . $filenamefullold);
		}
		else {
			if ($filenameold != $filename) {
				$filenamefulloriginal = $filename . '.' . $extoriginalold;

				movefile(ORIGINALS . $filenamefulloriginalold, ORIGINALS . $filenamefulloriginal, false);
				movefile(FORMATTED . $filenamefullold, FORMATTED . $filenamefull, false);
				movefile(CROPPED . $filenamefullold, CROPPED . $filenamefull, false);
				movefile(WATERMARKED . $filenamefullold, WATERMARKED . $filenamefull, false);
				movefile(THUMBNAILS . $filenamefullold, THUMBNAILS . $filenamefull, false);
			}
		}
	}

	if (isset($_SESSION['upload'])) {
		$uploadedpath = $_SESSION['upload']['dirpath'];
		$uploadedpathds = $_SESSION['upload']['dirpathds'];
		$extoriginal = $_SESSION['upload']['extoriginal'];
		$filenamefulloriginal = $filename . '.' . $extoriginal;

		movefile($uploadedpathds . UPLOAD_ORIGINAL . '.' . $extoriginal, ORIGINALS . $filenamefulloriginal, true);
		movefile($uploadedpathds . UPLOAD_FORMATTED . '.' . EXT, FORMATTED . $filenamefull, true);
		movefile($uploadedpathds . UPLOAD_CROPPED . '.' . EXT, CROPPED . $filenamefull, true);
		movefile($uploadedpathds . UPLOAD_WATERMARKED . '.' . EXT, WATERMARKED . $filenamefull, true);
		movefile($uploadedpathds . UPLOAD_THUMBNAIL . '.' . EXT, THUMBNAILS . $filenamefull, true);

		removefolder($uploadedpath, true);
	}

	if (!isset($id)) {
		$id = $_SESSION['newid'];
	}

	session_destroy();
	$_SESSION = array();
	echo '<script>window.location = "' . ADMIN_HTML . 'sequence.php#' . $id . '";</script>';

?>
