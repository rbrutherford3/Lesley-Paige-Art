<?php
	session_start();
	$filename = $_SESSION['filename'];
	$imagick1 = new Imagick();
	$imagick2 = new Imagick();
	$imagick1->readImage(__DIR__ . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $filename . ' (cropped).jpg');
	$imagick2->readImage(__DIR__ . DIRECTORY_SEPARATOR . 'Stamp.png');
	$dimensions1 = $imagick1->getImageGeometry();
	$width1 = $dimensions1['width']; 
	$height1 = $dimensions1['height'];
	$dimensions2 = $imagick2->getImageGeometry();
	$width2 = $dimensions2['width']; 
	$height2 = $dimensions2['height'];
	$scaleadjustment = 0.9;
	if (($width1/$width2) > ($height1/$height2)) {
		$scale = $height1/$height2*$scaleadjustment;
		$xdiff = (int)(($width1-$scale*$width2)/2);
		$ydiff = (int)($height1*(1-$scaleadjustment)/2);
	}
	else {
		$scale = $width1/$width2*$scaleadjustment;
		$xdiff = (int)($width1*(1-$scaleadjustment)/2);
		$ydiff = (int)(($height1-$scale*$height2)/2);
	}
	$imagick2->resizeImage((int)($scale*$width2), (int)($scale*$height2), Imagick::FILTER_GAUSSIAN, .5);
	$imagick2->evaluateImage(Imagick::EVALUATE_MULTIPLY, 0.5, Imagick::CHANNEL_ALPHA);
	$imagick1->compositeImage($imagick2, Imagick::COMPOSITE_DEFAULT, $xdiff, $ydiff);
	$imagick1->mergeImageLayers();
	$imagick1->writeImage(__DIR__ . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $filename . ' (cropped, stamp).jpg');
	//echo '<img src="upload/' . $filename . ' (cropped, stamp).jpg">';
	header("Location: index.html");
	die();
?>