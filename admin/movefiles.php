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
			removefile(ORIGINALS['sys'] . $filenamefulloriginalold);
			removefile(FORMATTED['sys'] . $filenamefullold);
			removefile(CROPPED['sys'] . $filenamefullold);
			removefile(WATERMARKED['sys'] . $filenamefullold);
			removefile(THUMBNAILS['sys'] . $filenamefullold);
		}
		else {
			if ($filenameold != $filename) {
				$filenamefulloriginal = $filename . '.' . $extoriginalold;

				movefile(ORIGINALS['sys'] . $filenamefulloriginalold, ORIGINALS['sys'] . $filenamefulloriginal, false);
				movefile(FORMATTED['sys'] . $filenamefullold, FORMATTED['sys'] . $filenamefull, false);
				movefile(CROPPED['sys'] . $filenamefullold, CROPPED['sys'] . $filenamefull, false);
				movefile(WATERMARKED['sys'] . $filenamefullold, WATERMARKED['sys'] . $filenamefull, false);
				movefile(THUMBNAILS['sys'] . $filenamefullold, THUMBNAILS['sys'] . $filenamefull, false);
			}
		}
	}

	if (isset($_SESSION['upload'])) {
		$uploadedpath = $_SESSION['upload']['dirpath'];
		$uploadedpathds = $_SESSION['upload']['dirpathds'];
		$extoriginal = $_SESSION['upload']['extoriginal'];
		$filenamefulloriginal = $filename . '.' . $extoriginal;

		movefile($uploadedpathds . UPLOAD_ORIGINAL . '.' . $extoriginal, ORIGINALS['sys'] . $filenamefulloriginal, true);
		movefile($uploadedpathds . UPLOAD_FORMATTED . '.' . EXT, FORMATTED['sys'] . $filenamefull, true);
		movefile($uploadedpathds . UPLOAD_CROPPED . '.' . EXT, CROPPED['sys'] . $filenamefull, true);
		movefile($uploadedpathds . UPLOAD_WATERMARKED . '.' . EXT, WATERMARKED['sys'] . $filenamefull, true);
		movefile($uploadedpathds . UPLOAD_THUMBNAIL . '.' . EXT, THUMBNAILS['sys'] . $filenamefull, true);

		removefolder($uploadedpath, true);
	}

	if (!isset($id)) {
		$id = $_SESSION['newid'];
	}

	session_destroy();
	$_SESSION = array();
	echo '<script>window.location = "' . ADMIN['html'] . 'sequence.php#' . $id . '";</script>';

?>
