<?php
	session_start();
	include_once('filenames.php');
	$filepath = $_SESSION['upload']['dirpathds'];
	$imagick = new Imagick();
	$imagick->readImage($filepath . $filenameextcropped);
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
	$imagick->writeImage($filepath . $filenameextthumbnail);
	header("Location: artinfo.php");
	die();
?>