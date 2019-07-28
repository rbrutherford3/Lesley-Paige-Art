<?php
	session_start();
	include_once('filenames.php');
	if (!isset($_SESSION['filename'])) {
		// Upload hasn't occured (go to form)
	}
	else {
		$filename = $_SESSION['filename'];
		if (file_exists($uploadroot . $filename . $extorignal) {
			if (file_exists($uploadroot . $filename . $filenamecropped) {
				if (file_exists($uploadroot . $filename . $filenameborderless) {
					if (file_exists($uploadroot . $filename . $filenamewatermarked) {
						if (file_exists($uploadroot . $filename . $filenameresized) {
							// All operations are complete (has database entry been done?
						}
						else {
							// Resize the image
						}
					}
					else {
						// Watermark the image
					}
				}
				else {
					// Perform second crop (go to form)
				}
			}
			else {
				// Perford first crop (go to form)
			}
		}
		else {
			// Upload hasn't occured (go to form)
		}
	}
	
?>