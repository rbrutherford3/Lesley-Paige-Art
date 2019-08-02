<?php
	session_start();
	include_once('filenames.php');
	$filepath = $_SESSION['filepath'];
	$imagick = new Imagick();
	$imagick->readImage($filepath . $filenamecropped);
	$dimensions = $imagick->getImageGeometry();
	$width = $dimensions['width']; 
	$height = $dimensions['height'];
	if ($height > $width) {
		$newheight = 250;
		$newwidth = (int)(250/$height*$width);
	}
	else {
		$newwidth = 250;
		$newheight = (int)(250/$width*$height);
	}
	$imagick->resizeImage($newwidth, $newheight, imagick::FILTER_SINC, 1);
	$imagick->setImageFormat($extresized);
	$imagick->writeImage($filepath . $filenamethumbnail);
	header("Location: artinfo.php");
	die();
?>