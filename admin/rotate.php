<?php

	// Page to allow user to rotate art piece.  User clicks image
	// which calls a JavaScript function to rotate it 90 degrees in place
	// and record the newly rotated value in a hidden field, which
	// is added to the stored artpiecefile value on submission

	require_once '../paths.php';
	require_once 'artpiece.php';

	session_start();

	if (!isset($_SESSION['artpiece']))
		throw new Exception('Missing artpiece object at rotate.php');
	elseif (is_null($_SESSION['artpiece']->getfile()))
		throw new Exception('Missing arptiece file object at rotate.php');

	// Process submitted rotated image
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$angle = $_POST['angle'];	// Get angle 
		
		// Add rotation to storef rotation (if it exists) and only allow 0, 90, 180, or 270 degree values
		if (!is_null($_SESSION['artpiece']->getfile()->getrotation()))
			$angle = (($angle + $_SESSION['artpiece']->getfile()->getrotation()) % 360);
		$_SESSION['artpiece']->getfile()->setrotation($angle);
		
		header('Location: ' . ADMIN['html'] . 'crop.php');
		exit;
	}
	
	// Display page
	else {
		// Get formatted image (already rotated per stored value, if applicable) and dimensions
		$_SESSION['artpiece']->getfile()->createformatted(false, true);
		$dimensions = $_SESSION['artpiece']->getfile()->getformatteddimensions();
		$width = $dimensions['width'];
		$height = $dimensions['height'];
		
		// Display page
		
		$title = $_SESSION['artpiece']->gettitle();
		if ($title)
			$title = 'Rotate image "' . $title . '"';
		else
			$title = 'Rotate image';

		include 'cache.php';

		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>' . $title . '</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
		<script type="text/javascript" src="' . ADMIN['html'] . 'rotate.js"></script>
	</head>
	<body>
		<h1>' . $title . ':</h1>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="rotateform" method="POST">
			<p>
				Click image to rotate, click button when complete
			</p>
			<div class="center-image" style="width: ' . (FORMATTED_DIMENSION+10) . 'px; height: ' . (FORMATTED_DIMENSION+10) . 'px">
				<img src="' . $_SESSION['artpiece']->getfile()->getformattedHTML() . '" id="image" width="' . $width . '" height="' . $height . '" onclick="rotate(90);" style="border: 1px solid black;">
			</div>
			<input type="hidden" name="angle" id="angle" value=0>
			<p>
				<input type="submit" name="submit" value="Apply Rotation">
			</p>
		</form>
	</body>
</html>';
	$_SESSION['artpiece']->getfile()->destroyformatted();
	}
?>
