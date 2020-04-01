<?php
	session_start();
	require_once '../paths.php';
	require_once 'functions.php';
	$filename = $_SESSION['upload']['dirname'];
	$filepath = $_SESSION['upload']['dirpathds'];
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$top = $_POST['top'];
		$bottom = $_POST['bottom'];
		$left = $_POST['left'];
		$right = $_POST['right'];

		$imagick = new Imagick();
		$imagick->readImage($filepath . UPLOAD_FORMATTED . '.' . EXT);
		$dimensions = $imagick->getImageGeometry();
		$width = $dimensions['width'];
		$height = $dimensions['height'];
		$imagick->cropImage($width-$left-$right, $height-$top-$bottom, $left, $top);
		$imagick->writeImage($filepath . UPLOAD_CROPPED . '.' . EXT);
		//echo '<img src="upload/' . $filename . ' (cropped).jpg">';
		header('Location: ' . ADMIN_HTML . 'overlay.php');
		die();
	}
	else {
		$imagick = new Imagick();
		$imagick->readImage($filepath . UPLOAD_FORMATTED . '.' . EXT);
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
		<link rel="stylesheet" type="text/css" href="' . CSS_MAIN_HTML . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_TEXT_HTML . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN_HTML . '">
		<script type="text/javascript" src="' . ADMIN_HTML . 'crop.js"></script>
	</head>
	<body>
		<div class="page">
			<h1>Crop piece:</h1>
			<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="cropform" method="POST" onkeydown="return event.key != \'Enter\';">
				<p>
					<input type="radio" name="stepsize" id="step20" value="20" onchange="newstepsize()" checked>
					<label for="step20">Very Coarse adjustment (every 20 pixels)</label>
					<br>
					<input type="radio" name="stepsize" id="step10" value="10" onchange="newstepsize()">
					<label for="step10">Coarse adjustment (every 10 pixels)</label>
					<br>
					<input type="radio" name="stepsize" id="step5" value="5" onchange="newstepsize()">
					<label for="step5">Fine adjustment (every 5 pixels)</label>
					<br>
					<input type="radio" name="stepsize" id="step1" value="1" onchange="newstepsize()">
					<label for="step1">Very fine adjustment (every pixel)</label>
					<br>
					<input type="hidden" id="trueWidth" value=' . $w . ' />
					<input type="hidden" id="trueHeight" value=' . $h . ' />
				</p>
				<p>
					<div class="grid-container">
						<div class="grid-item item-middle">
							<img class="thickborder below" src="' . UPLOAD_HTML . rawurlencode($filename) . '/' . rawurlencode(UPLOAD_FORMATTED . '.' . EXT) . '" width="' .  $dispW . '" height="' . $dispH . '" id="image" alt="' . $filename . '">
							<canvas class="above" id="canvas" width="' .  $dispW . '" height="' . $dispH . '"></canvas>
						</div>
						<div class="grid-item item-top">
							<input type="hidden" id="top" name="top" value="0" />
							<input type="button" id="tdec" value="&#9650;" onclick="changecrop(this);" />
							<input type="button" id="tinc" value="&#9660;" onclick="changecrop(this);" />
							
						</div>
						<div class="grid-item item-bottom">
							<input type="hidden" id="bottom" name="bottom" value="0" />
							<input type="button" id="binc" value="&#9650;" onclick="changecrop(this);" />
							<input type="button" id="bdec" value="&#9660;" onclick="changecrop(this);" />
						</div>
						<div class="grid-item item-left">
							<input type="hidden" id="left" name="left" value="0" />
							<input type="button" id="ldec" value="&#9668;" onclick="changecrop(this);" />
							<input type="button" id="linc" value="&#9658;" onclick="changecrop(this);" />
						</div>
						<div class="grid-item item-right">
							<input type="hidden" id="right" name="right" value="0" />
							<input type="button" id="rinc" value="&#9668;" onclick="changecrop(this);" />
							<input type="button" id="rdec" value="&#9658;" onclick="changecrop(this);" />
						</div>
					</div>
				</p>
				<div class="float">
					<input type="submit" value="Apply Crop">
				</div>
			</form>
		</div>
	</body>
</html>';
	}
?>