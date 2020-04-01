<?php
	session_start();
	require_once '../paths.php';
	$filepath = $_SESSION['upload']['dirpathds'];
	$extoriginal = $_SESSION['upload']['extoriginal'];

	$scalefactor = 1/4;
	$imagick = new Imagick($filepath . UPLOAD_ORIGINAL . '.' . $extoriginal);
	$imagick->mergeImageLayers(imagick::LAYERMETHOD_UNDEFINED);
	autorotate($imagick);
	$dimensions = $imagick->getImageGeometry();
	$width = $dimensions['width'];
	$height = $dimensions['height'];
	$newwidth = round($width*$scalefactor);
	$newheight = round($height*$scalefactor);
	$imagick->resizeImage($newwidth, $newheight, imagick::FILTER_SINC, 1);
	$imagick->setImageFormat(EXT);
	$imagick->writeImage($filepath . UPLOAD_FORMATTED . '.' . EXT);
	header('Location: ' . ADMIN_HTML . 'rotate.php');
	die();

	function autorotate(Imagick $image) {
		switch ($image->getImageOrientation()) {
		case Imagick::ORIENTATION_TOPLEFT:
			break;
		case Imagick::ORIENTATION_TOPRIGHT:
			$image->flopImage();
			break;
		case Imagick::ORIENTATION_BOTTOMRIGHT:
			$image->rotateImage("#000", 180);
			break;
		case Imagick::ORIENTATION_BOTTOMLEFT:
			$image->flopImage();
			$image->rotateImage("#000", 180);
			break;
		case Imagick::ORIENTATION_LEFTTOP:
			$image->flopImage();
			$image->rotateImage("#000", -90);
			break;
		case Imagick::ORIENTATION_RIGHTTOP:
			$image->rotateImage("#000", 90);
			break;
		case Imagick::ORIENTATION_RIGHTBOTTOM:
			$image->flopImage();
			$image->rotateImage("#000", 90);
			break;
		case Imagick::ORIENTATION_LEFTBOTTOM:
			$image->rotateImage("#000", -90);
			break;
		default: // Invalid orientation
			break;
		}
		$image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
		return $image;
	}
?>