<?php

// Functions to convert HSL colorspace to RGB and hex (used in main css
// and style admin pages) - database stores colors in HSL

// Taken from: https://stackoverflow.com/a/32977705/3130769
function RGBtoHEX ($r, $g, $b) {
	return sprintf("#%02x%02x%02x", $r, $g, $b);
}

// Take from: https://stackoverflow.com/a/20440417/3130769
function HSLtoRGB ($h, $s, $l) {
	$r = $l;
	$g = $l;
	$b = $l;
	$v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
	if ($v > 0) {
		$m;
		$sv;
		$sextant;
		$fract;
		$vsf;
		$mid1;
		$mid2;
		$m = $l + $l - $v;
		$sv = ($v - $m ) / $v;
		$h *= 6.0;
		$sextant = floor($h);
		$fract = $h - $sextant;
		$vsf  = $v * $sv * $fract;
		$mid1 = $m + $vsf;
		$mid2 = $v  - $vsf;
		switch ($sextant) {
			case 0:
				$r = $v;
				$g = $mid1;
				$b = $m;
				break;
			case 1:
				$r = $mid2;
				$g = $v;
				$b = $m;
				break;
			case 2:
				$r = $m;
				$g = $v;
				$b = $mid1;
				break;
			case 3:
				$r = $m;
				$g = $mid2;
				$b = $v;
				break;
			case 4:
				$r = $mid1;
				$g = $m;
				$b = $v;
				break;
			case 5:
				$r = $v;
				$g = $m;
				$b = $mid2;
				break;
		}
	}
	return array('r' => $r * 255.0, 'g' => $g * 255.0, 'b' => $b * 255.0);
}

?>
