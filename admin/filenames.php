<?php
	$ds = DIRECTORY_SEPARATOR;

	$rootpath = __DIR__ . $ds;
	$uploadpath = $rootpath . 'upload' . $ds;
	
	//$extoriginal = 'png';
	$ext = 'png';
	
	$filenameoriginal = 'original.'; //. $extoriginal;
	$filenameformatted = 'formatted.' . $ext;
	$filenamecropped = 'cropped.' . $ext;
	$filenamewatermarked = 'watermarked.' . $ext;
	$filenamethumbnail = 'thumbnail.' . $ext;
	
	$imgpath = dirname(__DIR__, 1) . $ds . 'img' . $ds;
	
	$originalspath = $imgpath . 'originals' . $ds;
	$formattedpath = $imgpath . 'formatted' . $ds;
	$croppedpath = $imgpath . 'cropped' . $ds;
	$watermarkedpath = $imgpath . 'watermarked' . $ds;
	$thumbnailspath = $imgpath . 'thumbnails' . $ds;
	
	$stamplocation = $rootpath . 'Stamp.png';
?>