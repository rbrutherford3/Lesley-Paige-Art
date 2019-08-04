<?php
	session_start();
	include_once('filenames.php');
	$filename = $_SESSION['filename'];
	$filepath = $_SESSION['filepath'];
	$imagick = new Imagick();
	$imagick->readImage($filepath . $filenameformatted);
	
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$angle = $_POST['angle'];
		$imagick->rotateImage("#000", (int)$angle);
		$imagick->writeImage($filepath . $filenameformatted);
		header("Location: crop.php");
		die();
	}
	else {
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
					<img class="centered" src="upload/' . rawurlencode($filename) . '/' . rawurlencode($filenameformatted) . '" id="image" width="' .  $dispW. '" height="' . $dispH . '" onclick="rotate();">
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