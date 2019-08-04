<?php
	session_start();
	include_once('filenames.php');
	$filepath = $_SESSION['filepath'];
	$extoriginal = $_SESSION['extoriginal'];
	
	$scalefactor = 1/4;
	$imagick = new Imagick($filepath . $filenameoriginal . $extoriginal);
	autorotate($imagick);
	$dimensions = $imagick->getImageGeometry();
	$width = $dimensions['width'];
	$height = $dimensions['height'];
	$newwidth = round($width*$scalefactor);
	$newheight = round($height*$scalefactor);
	$imagick->resizeImage($newwidth, $newheight, imagick::FILTER_SINC, 1);
	$imagick->setImageFormat($ext);
	$imagick->writeImage($filepath . $filenameformatted);
	header("Location: crop.php");
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