<?php
	session_start();
	include_once('filenames.php');
	//$imagick = new Imagick();
	//$imagick = clone $_SESSION['image'];
	$filename = $_SESSION['filename'];
	$imagick = new Imagick();
	$imagick->readImage(__DIR__ . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $filename . '.jpg');
	//echo '<script>alert("' . Imagick::ORIENTATION_TOPLEFT . '");</script>';
	//echo '<script>alert("' . $imagick->getImageOrientation() . '");</script>';
	//$imagick->setImageOrientation(imagick::ORIENTATION_UNDEFINED);
	$d = $imagick->getImageGeometry();
	$w = $d['width']; 
	$h = $d['height'];
	if ($w > $h) {
		if ($w > 500) {
			$dispW = 500;
			$dispH = (int)(500/$w*$h);
		}
		else {
			$dispW = $w;
			$dispH = $y;
		}
	}
	else {
		if ($h > 500) {
			$dispH = 500;
			$dispW = (int)(500/$h*$w);
		}
		else {
			$dispW = $w;
			$dispH = $h;
		}
	}
	//$buffer 
	echo '
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="main.css">
		<script type="text/javascript" src="lines.js"></script>
	</head>
	<body>
		<form action="crop.php" name="cropform" method="POST" onkeydown="return event.key != \'Enter\';">
		<input type="radio" name="stepsize" value="20" onchange="newstepsize()" checked>Very Coarse adjustment (every 20px)<br>
		<input type="radio" name="stepsize" value="10" onchange="newstepsize()">Coarse adjustment (every 10px)<br>
		<input type="radio" name="stepsize" value="5" onchange="newstepsize()">Fine adjustment (every 5px)<br>
		<input type="radio" name="stepsize" value="1" onchange="newstepsize()">Very fine adjustment (every 1px)<br>
		<input type="hidden" id="trueWidth" value=' . $w . ' />
		<input type="hidden" id="trueHeight" value=' . $h . ' />
		<div class="cropcontainer">
			<div class="cropimage">
				<img src="upload/' . rawurlencode($filename) . '.jpg" width="' .  $dispW. '" height="' . $dispH . '" id="image" alt="' . $filename . '">
				<canvas id = "canvas" width="' .  $dispW. '" height="' . $dispH . '"></canvas>
			</div>
			<div class="side top">
				<div class="input">
					<input type="number" name="top" id="top" step=20 value=0 min=0 max=' . $h . ' onchange="newline(\'t\')" />
				</div>
			</div>
			<div class="side bottom">
				<div class="input">
					<input type="number" name="bottom" id="bottom" step=20 value=0 min=0 max=' . $h . ' onchange="newline(\'b\')" />
				</div>
			</div>
			<div class="side left">
				<div class="input">
					<input type="number" name="left" id="left" step=20 value=0 min=0 max=' . $w . ' onchange="newline(\'l\')" />
				</div>
			</div>
			<div class="side right">
				<div class="input">
					<input type="number" name="right" id="right" step=20 value=0 min=0 max=' . $w . ' onchange="newline(\'r\')" />
				</div>
			</div>
		</div>
		<input type = "submit" />
		</form>
	</body>
</html>';
	//$x = $w/4;
	//$y = $h/4;
	//$imagick->cropImage(2*$x, 2*$y, $x, $y);
	//$imagick->writeImage($_SERVER['DOCUMENT_ROOT'] . '/admin/upload/cropped.jpg'); 
	
?>