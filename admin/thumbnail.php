<?php
	session_start();
	require_once '../paths.php';
	$filepath = $_SESSION['upload']['dirpathds'];
	$imagick = new Imagick();
	$imagick->readImage($filepath . UPLOAD_CROPPED . '.' . EXT);
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
	$imagick->setImageFormat(EXT);
	$imagick->writeImage($filepath . UPLOAD_THUMBNAIL . '.' . EXT);
	header('Location: ' . ADMIN['html'] . 'artinfo.php');
	die();
?>