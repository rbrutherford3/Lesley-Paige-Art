<?php
	session_start();
	require_once '../paths.php';
	require_once 'functions.php';
	$filename = $_SESSION['upload']['dirname'];
	$filepath = $_SESSION['upload']['dirpathds'];
	$imagick = new Imagick();
	$imagick->readImage($filepath . UPLOAD_FORMATTED . '.' . EXT);

	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$angle = $_POST['angle'];
		$imagick->rotateImage("#000", (int)$angle);
		$imagick->writeImage($filepath . UPLOAD_FORMATTED . '.' . EXT);
		header('Location: ' . ADMIN_HTML . 'crop.php');
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
		<link rel="stylesheet" type="text/css" href="' . CSS_MAIN_HTML . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_TEXT_HTML . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN_HTML . '">
		<script type="text/javascript" src="' . ADMIN_HTML . 'rotate.js"></script>
	</head>
	<body>
		<div class="page">
			<h1>Rotate piece:</h1>
			<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="rotateform" method="POST">
				<p>
					Click image to rotate, click button when complete
				</p>
				<div class="box-child">
					<img class="thickborder" src="' . UPLOAD_HTML . rawurlencode($filename) . '/' . rawurlencode(UPLOAD_FORMATTED . '.' . EXT) . '" id="image" width="' .  $dispW. '" height="' . $dispH . '" onclick="rotate();">
				</div>
				<input type="hidden" name="angle" id="angle" value=0>
				<div class="float">
					<input type="submit" name="submit" value="Apply Rotation">
				</div>
			</form>
		</div>
	</body>
</html>';
	}
?>