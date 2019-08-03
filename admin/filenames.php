<?php
	$ds = DIRECTORY_SEPARATOR;

	$root = __DIR__ . $ds;
	$uploadroot = $root . 'upload' . $ds;
	
	//$extoriginal = 'png';
	$ext = 'png';
	
	$filenameoriginal = 'original.'; //. $extoriginal;
	$filenameformatted = 'formatted.' . $ext;
	$filenamecropped = 'cropped.' . $ext;
	$filenamewatermarked = 'watermarked.' . $ext;
	$filenamethumbnail = 'thumbnail.' . $ext;
	
	$stamplocation = $root . 'Stamp.png';
?>