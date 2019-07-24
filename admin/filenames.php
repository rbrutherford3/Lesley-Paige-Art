<?php
	$root = __DIR__ . DIRECTORY_SEPARATOR;
	$uploadroot = $root . 'upload' . DIRECTORY_SEPARATOR;
	
	$extoriginal = 'png';
	$extcropped = 'png';
	$extborderless = 'png';
	$extwatermarked = 'png';
	$extresized = 'png';
	
	$filenameoriginal = '.' . $extoriginal;
	$filenamecropped = ' (cropped).' . $extcropped;
	$filenameborderless = ' (borderless).' . $extborderless;
	$filenamewatermarked = ' (watermarked).' . $extwatermarked;
	$filenameresized = ' (resized).' . $extresized;
	
	$stamplocation = $root . 'Stamp.png';
?>