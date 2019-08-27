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
		$contW = $dispW + 160;
		$contH = $dispH + 160;
		$sideX = round($contW/2-30);
		$sideY = round($contH/2-30);
		//$buffer 
		if (isset($_SESSION['artinfo'])) {
			$title = $_SESSION['artinfo']['name'];
		}
		elseif (isset($_SESSION['database'])) {
			$title = $_SESSION['database']['name'];
		}
		elseif (isset($_SESSION['upload'])) {
			$title = $_SESSION['upload']['originalname'];
		}
		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>Crop image' . (isset($title) ? ' for ' . $title : '') . '</title>
		<link rel="stylesheet" type="text/css" href="/css/main.css">
		<link rel="stylesheet" type="text/css" href="/css/text.css">
		<link rel="stylesheet" type="text/css" href="admin.css">
		<script type="text/javascript" src="crop.js"></script>
	</head>
	<body>
		<div class="page">
			<h1>Crop piece:</h1>
			<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="cropform" method="POST" onkeydown="return event.key != \'Enter\';">
				<p>
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
				</p>
				<p>
					<div class="grid-container">
						<div class="grid-item item-middle">
							<img class="thickborder below" src="upload/' . rawurlencode($filename) . '/' . rawurlencode($filenameextformatted) . '" width="' .  $dispW . '" height="' . $dispH . '" id="image" alt="' . $filename . '">
							<canvas class="above" id="canvas" width="' .  $dispW . '" height="' . $dispH . '"></canvas>
						</div>
						<div class="grid-item item-top">
							<input type="number" name="top" id="top" step=20 value=0 min=0 max=' . $h . ' onchange="newline(\'t\')" />
						</div>
						<div class="grid-item item-bottom">
							<input type="number" name="bottom" id="bottom" step=20 value=0 min=0 max=' . $h . ' onchange="newline(\'b\')" />
						</div>
						<div class="grid-item item-left">
							<input type="number" name="left" id="left" step=20 value=0 min=0 max=' . $w . ' onchange="newline(\'l\')" />
						</div>
						<div class="grid-item item-right">
							<input type="number" name="right" id="right" step=20 value=0 min=0 max=' . $w . ' onchange="newline(\'r\')" />
						</div>
					</div>
				</p>
				<div class="float">
					<input type="submit" value="Submit">
				</div>
			</form>
		</div>
	</body>
</html>';
	}
?>