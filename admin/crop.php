<?php

	// Page for cropping an artpiece, using formattted Imagick object.
	// Crop margins are set using buttons that trigger JavaScript functions
	// in crop.js that change hidden fields and draw lines on a canvas object.
	// The js scripts apply calculations to adjust the display lines on
	// the formatted Imagick object.  Radio buttons adjust the jump of the crop arrow buttons.
	// Hidden fields (name and value): correspond to original image orientation and size.
	// Button and hidden field IDs: correspond to orientation of formatted Imagick (display image)
	
	require_once '../paths.php';
	require_once 'artpiece.php';

	session_start();

	if (!isset($_SESSION['artpiece']))
		throw new Exception('Missing artpiece object at crop.php');
	elseif (is_null($_SESSION['artpiece']->getfile()))
		throw new Exception('Missing arptiece file object at crop.php');

	// This generic function takes an array and shifts the values $numtimes 'clockwise
	// No values are deleted, last value in array becomes the first
	function rotatearray($array, $numtimes) {
		$keys = array_keys($array);	// Hold keys in place
		for ($i=0; $i<$numtimes; $i++) {	// shift $numtimes
			// Iterate through array and swap neighboring valuea
			for ($j=0; $j<(sizeof($array)-1); $j++) {
				$save1 = $array[$keys[$j]];
				$save2 = $array[$keys[$j+1]];
				$array[$keys[$j]] = $save2;
				$array[$keys[$j+1]] = $save1;
			}
		}
		return $array;	// Result is shifted array
	}

	// After submitting, store values
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		// Get crop margins (relative to original unrotated and unresized image)
		$left = $_POST['left'];
		$right = $_POST['right'];
		$top = $_POST['top'];
		$bottom = $_POST['bottom'];

		// Store values in artpiecefile object
		$_SESSION['artpiece']->getfile()->setcropall($left, $right, $top, $bottom);
		header('Location: ' . ADMIN['html'] . 'confirmfiles.php');
		exit;
	}
	
	// Set up page
	else {
		// Create formatted Imagick object (resized and rotated)
		$_SESSION['artpiece']->getfile()->createformatted(false, true);
		
		// Get both original dimensions and formatted dimensions
		$dimensionsoriginal = $_SESSION['artpiece']->getfile()->getdimensions();
		$width = $dimensionsoriginal['width'];
		$height = $dimensionsoriginal['height'];
		$dimensionsformatted = $_SESSION['artpiece']->getfile()->getformatteddimensions();
		$widthformatted = $dimensionsformatted['width'];
		$heightformatted = $dimensionsformatted['height'];
		
		// Set dimensions of HTML container for cropping
		$containerwidth = $widthformatted + 160;
		$containerheight = $heightformatted + 160;
		$sideX = round($containerwidth/2-30);
		$sideY = round($containerheight/2-30);

		// We want to display the rotated image but use crop margins
		// based on the original unrotated image, so we use an associative
		// array to know which side of the oiginal matches the displayed side
		$sides = array('left'=>'left', 'bottom'=>'bottom', 'right'=>'right', 'top'=>'top');
		$rotation = $_SESSION['artpiece']->getfile()->getrotation();
		$rotation = (is_null($rotation) ? 0 : $rotation);
		$sides = rotatearray($sides, $rotation/90);
		
		// If the angle is 90 or 270, the rotated side lengths are inverted compared to the original
		if ($rotation % 180 == 0) {
			$widthrotated = $width;
			$heightrotated = $height;
		}
		else {
			$widthrotated = $height;
			$heightrotated = $width;
		}
		
		// Get previously defined crop margins,  if any
		$crop = $_SESSION['artpiece']->getfile()->getcrop();
		
		// Display page
		
		$title = $_SESSION['artpiece']->gettitle();
		if ($title)
			$title = 'Crop image "' . $title . '"';
		else
			$title = 'Crop image';

		include 'cache.php';

		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>' . $title . '</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_TEXT['html'] . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
		<script type="text/javascript" src="' . ADMIN['html'] . 'crop.js"></script>
	</head>
	<body>
		<h1>' . $title . ':</h1>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="cropform" method="POST" onkeydown="return event.key != \'Enter\';">
			<p>
				Use the arrows to change the crop margins.  The "adjustment" setting changes the step size of one arrow click.
			</p>
			<p>
				<input type="radio" name="stepsize" id="step100" value="100" checked>
				<label for="step100">Very large adjustment (every 100 pixels)</label>
				<br>
				<input type="radio" name="stepsize" id="step20" value="20">
				<label for="step20">Large adjustment (every 20 pixels)</label>
				<br>
				<input type="radio" name="stepsize" id="step10" value="10">
				<label for="step10">Medium adjustment (every 10 pixels)</label>
				<br>
				<input type="radio" name="stepsize" id="step5" value="5">
				<label for="step5">Small adjustment (every 5 pixels)</label>
				<br>
				<input type="radio" name="stepsize" id="step1" value="1">
				<label for="step1">Very small adjustment (every pixel)</label>
				<input type="hidden" id="trueWidthRotated" value="' . $widthrotated . '" />
				<input type="hidden" id="trueHeightRotated" value="' . $heightrotated . '" />
			</p>
			<p>
				<div class="grid-container">
					<div class="grid-item item-middle">
						<img class="thickborder below" src="' . $_SESSION['artpiece']->getfile()->getformattedHTML() . '" width="' .  $widthformatted . '" height="' . $heightformatted . '" id="image" alt="image" >
						<canvas class="above" id="canvas" width="' .  $widthformatted . '" height="' . $heightformatted . '"></canvas>
					</div>
					<div class="grid-item item-top">
						<input type="hidden" id="top" name="' . $sides['top'] . '" value="' . (is_null($crop[$sides['top']]) ? 0 : $crop[$sides['top']]) . '" />
						<input type="button" id="tdec" value="&#9650;" onclick="changecrop(this);" />
						<input type="button" id="tinc" value="&#9660;" onclick="changecrop(this);" />
					</div>
					<div class="grid-item item-bottom">
						<input type="hidden" id="bottom" name="' . $sides['bottom'] . '" value="' . (is_null($crop[$sides['bottom']]) ? 0 : $crop[$sides['bottom']]) . '" />
						<input type="button" id="binc" value="&#9650;" onclick="changecrop(this);" />
						<input type="button" id="bdec" value="&#9660;" onclick="changecrop(this);" />
					</div>
					<div class="grid-item item-left">
						<input type="hidden" id="left" name="' . $sides['left'] . '" value="' . (is_null($crop[$sides['left']]) ? 0 : $crop[$sides['left']]) . '" />
						<input type="button" id="ldec" value="&#9664;" onclick="changecrop(this);" />
						<input type="button" id="linc" value="&#9654;" onclick="changecrop(this);" />
					</div>
					<div class="grid-item item-right">
						<input type="hidden" id="right" name="' . $sides['right'] . '" value="' . (is_null($crop[$sides['right']]) ? 0 : $crop[$sides['right']]) . '" />
						<input type="button" id="rinc" value="&#9664;" onclick="changecrop(this);" />
						<input type="button" id="rdec" value="&#9654;" onclick="changecrop(this);" />
					</div>
				</div>
			</p>
			<p>
				<input type="submit" value="Apply Crop">
			</p>
		</form>
	</body>
</html>';
	$_SESSION['artpiece']->getfile()->destroyformatted();
	}
?>
