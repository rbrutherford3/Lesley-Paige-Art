<?php
	session_start();
	include_once('filenames.php');
	include_once('functions.php');
	$filename = $_SESSION['upload']['dirname'];
	$filepath = $_SESSION['upload']['dirpathds'];
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$top = $_POST['top'];
		$bottom = $_POST['bottom'];
		$left = $_POST['left'];
		$right = $_POST['right'];
		
	/* 	echo $top . '<br>';
		echo $bottom . '<br>';
		echo $left . '<br>';
		echo $right . '<br>'; */
		
		$imagick = new Imagick();
		$imagick->readImage($filepath . $filenameextformatted);
		$dimensions = $imagick->getImageGeometry();
		$width = $dimensions['width']; 
		$height = $dimensions['height'];
		$imagick->cropImage($width-$left-$right, $height-$top-$bottom, $left, $top);
		$imagick->writeImage($filepath . $filenameextcropped); 
		//echo '<img src="upload/' . $filename . ' (cropped).jpg">';
		header("Location: overlay.php");
		die();
	}
	else {
		$imagick = new Imagick();
		$imagick->readImage($filepath . $filenameextformatted);
		$d = $imagick->getImageGeometry();
		$w = $d['width']; 
		$h = $d['height'];
		$dispD = scaleimage($w, $h, 500, 500);
		$dispW = $dispD[0];
		$dispH = $dispD[1];
		//$buffer 
		echo '
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="main.css">
		<script type="text/javascript" src="crop.js"></script>
	</head>
	<body>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="cropform" method="POST" onkeydown="return event.key != \'Enter\';">
		<input type="radio" name="stepsize" id="step20" value="20" onchange="newstepsize()" checked>
		<label for="step20">Very Coarse adjustment (every 20px)</label>
		<br>
		<input type="radio" name="stepsize" id="step10" value="10" onchange="newstepsize()">
		<label for="step10">Coarse adjustment (every 10px)</label>
		<br>
		<input type="radio" name="stepsize" id="step5" value="5" onchange="newstepsize()">
		<label for="step5">Fine adjustment (every 5px)</label>
		<br>
		<input type="radio" name="stepsize" id="step1" value="1" onchange="newstepsize()">
		<label for="step1">Very fine adjustment (every 1px)</label>
		<br>
		<input type="hidden" id="trueWidth" value=' . $w . ' />
		<input type="hidden" id="trueHeight" value=' . $h . ' />
		<div class="outer crop">
			<div class="image middle">
				<img class="centered" src="upload/' . rawurlencode($filename) . '/' . rawurlencode($filenameextformatted) . '" width="' .  $dispW . '" height="' . $dispH . '" id="image" alt="' . $filename . '">
				<canvas class="canvas centered" id="canvas" width="' .  $dispW . '" height="' . $dispH . '"></canvas>
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
	}
?>