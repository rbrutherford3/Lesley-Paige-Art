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
		<link rel="stylesheet" type="text/css" href="' . $cssmainpath . '">
		<link rel="stylesheet" type="text/css" href="' . $csstextpath . '">
		<link rel="stylesheet" type="text/css" href="' . $cssadminpath . '">
		<script type="text/javascript" src="rotate.js"></script>
	</head>
	<body>
		<div class="page">
			<h1>Rotate piece:</h1>
			<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="rotateform" method="POST">
				<p>
					Click image to rotate, click submit when complete
				</p>
				<div class="box-child">
					<img class="thickborder" src="upload/' . rawurlencode($filename) . '/' . rawurlencode($filenameextformatted) . '" id="image" width="' .  $dispW. '" height="' . $dispH . '" onclick="rotate();">
				</div>
				<input type="hidden" name="angle" id="angle" value=0>
				<div class="float">
					<input type="submit" name="submit">
				</div>
			</form>
		</div>
	</body>
</html>';
	}
?>