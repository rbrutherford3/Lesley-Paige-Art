<?php
	session_start();
	include_once('filenames.php');
	include_once('functions.php');
	$filename = $_SESSION['upload']['dirname'];
	$filepath = $_SESSION['upload']['dirpathds'];
	$imagick = new Imagick();
	$imagick->readImage($filepath . $filenameextformatted);
	
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$angle = $_POST['angle'];
		$imagick->rotateImage("#000", (int)$angle);
		$imagick->writeImage($filepath . $filenameextformatted);
		header("Location: crop.php");
		die();
	}
	else {
		$d = $imagick->getImageGeometry();
		$w = $d['width']; 
		$h = $d['height'];
		$dispD = scaleimage($w, $h, 500, 500);
		$dispW = $dispD[0];
		$dispH = $dispD[1];
		echo '
	<html>
	<head>
		<link rel="stylesheet" type="text/css" href="main.css">
		<script type="text/javascript" src="rotate.js"></script>
	</head>
	<body>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="rotateform" method="POST">
			<p>
				Click image to rotate, click submit when complete
			</p>
			<div class="outer rotate">
				<div class="image">
					<img class="centered" src="upload/' . rawurlencode($filename) . '/' . rawurlencode($filenameextformatted) . '" id="image" width="' .  $dispW. '" height="' . $dispH . '" onclick="rotate();">
				</div>
			</div>
			<input type="hidden" name="angle" id="angle" value=0>
			<p>
				<input type="submit" name="submit">
			</p>
		</form>
	</body>
</html>';
	}
?>