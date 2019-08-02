<?php
	$ds = DIRECTORY_SEPARATOR;

	$root = __DIR__ . $ds;
	$uploadroot = $root . 'upload' . $ds;
	
	$extoriginal = 'png';
	$extcropped = 'png';
	$extborderless = 'png';
	$extwatermarked = 'png';
	$extresized = 'png';
	
	$filenameoriginal = 'original.' . $extoriginal;
	$filenamecropped = 'cropped.' . $extcropped;
	$filenameborderless = 'borderless.' . $extborderless;
	$filenamewatermarked = 'watermarked.' . $extwatermarked;
	$filenamethumbnail = 'thumbnail.' . $extresized;
	
	$stamplocation = $root . 'Stamp.png';
?>