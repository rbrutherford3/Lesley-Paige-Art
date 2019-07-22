<?php
	session_start();
	$filename = $_SESSION['filename'];
	$top = $_POST['top'];
	$bottom = $_POST['bottom'];
	$left = $_POST['left'];
	$right = $_POST['right'];
	
/* 	echo $top . '<br>';
	echo $bottom . '<br>';
	echo $left . '<br>';
	echo $right . '<br>'; */
	
	$imagick = new Imagick();
	$imagick->readImage(__DIR__ . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $filename . '.jpg'); 
	$dimensions = $imagick->getImageGeometry();
	$width = $dimensions['width']; 
	$height = $dimensions['height'];
	$imagick->cropImage($width-$left-$right, $height-$top-$bottom, $left, $top);
	$imagick->writeImage(__DIR__ . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $filename . ' (cropped).jpg'); 
	//echo '<img src="upload/' . $filename . ' (cropped).jpg">';
	header("Location: overlay.php");
	die();
?>