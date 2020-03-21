<?php
	$ds = DIRECTORY_SEPARATOR;

	$rootpath = __DIR__ . $ds;
	$uploadpath = $rootpath . 'upload';
	$uploadpathds = $uploadpath . $ds;

	//$extoriginal = 'png';
	$ext = 'png';

	$filenameoriginal = 'original'; //. $extoriginal;
	$filenameextformatted = 'formatted.' . $ext;
	$filenameextcropped = 'cropped.' . $ext;
	$filenameextwatermarked = 'watermarked.' . $ext;
	$filenameextthumbnail = 'thumbnail.' . $ext;

	$imgpath = dirname(__DIR__, 1) . $ds . 'img' . $ds;
	$imgpathHTML = "../img/";

	$originalspath = $imgpath . 'originals' . $ds;
	$formattedpath = $imgpath . 'formatted' . $ds;
	$croppedpath = $imgpath . 'cropped' . $ds;
	$watermarkedpath = $imgpath . 'watermarked' . $ds;
	$thumbnailspath = $imgpath . 'thumbnails' . $ds;

	$formattedpathHTML = $imgpathHTML . 'formatted/';
	$thumbnailspathHTML = $imgpathHTML . 'thumbnails/';

	$stamplocation = $rootpath . 'Stamp.png';

	$csspath = '../css';
	$cssmainpath = $csspath . '/main.css';
	$csstextpath = $csspath . '/text.css';
	$cssadminpath = 'admin.css';
?>
