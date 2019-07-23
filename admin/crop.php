<?php
	session_start();
	include_once('filenames.php');
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
	$imagick->readImage($uploadroot . $filename . $extoriginal); 
	$dimensions = $imagick->getImageGeometry();
	$width = $dimensions['width']; 
	$height = $dimensions['height'];
	$imagick->cropImage($width-$left-$right, $height-$top-$bottom, $left, $top);
	$imagick->writeImage($uploadroot . $filename . $extcropped); 
	//echo '<img src="upload/' . $filename . ' (cropped).jpg">';
	header("Location: overlay.php");
	die();
?>