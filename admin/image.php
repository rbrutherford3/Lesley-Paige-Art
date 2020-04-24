<?php

// Generic class containing most Imagick imag le manipulation functions
class image {
	
	// INITIAL IMAGE CORRECTION FUNCTIONS
	
	// Get dimensions of image before loading it
	public static function getdimensions(string $filepath): array {
		$image = new Imagick();
		$image->pingImage($filepath);
		$dimensions = $image->getImageGeometry();
		$orientation = $image->getImageOrientation();
		$image->destroy();
		// If image orientation is inverted, then so are the dimensions
		if ($orientation >= imagick::ORIENTATION_LEFTTOP)
			return array('width' => $dimensions['height'],
				'height' => $dimensions['width']);
		else
			return $dimensions;
	}

	// Some initial formatting for new Imagick object
	public static function formatimage(Imagick $image, string $format): void {
		$image->setImageFormat($format);
		$image->mergeImageLayers(imagick::LAYERMETHOD_UNDEFINED);
		image::autorotate($image);
	}

	// Function to correct an image who's rotation value is innacurate
	// Taken from https://stackoverflow.com/a/14989870 or a similar post
	private static function autorotate(Imagick $image): void {
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
		default: // Invalid orientation break;
		}
		$image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
	}

	// RESIZING CALCULATION FUNCTIONS

	// Return scale factor for fitting given dimensions into given target dimensions
	public static function fitscale(int $width, int $height, int $targetwidth, int $targetheight): float {
		if (($height/$width) > ($targetheight/$targetwidth))
				return $targetheight/$height;
		else
				return $targetwidth/$width;
	}

	// Return scale factor to fit an image to a maximum number of total pixels (w*h)
	public static function areascale(int $width, int $height, int $maxpixels): float {
		if (($width*$height) >= $maxpixels)
			return sqrt($maxpixels/($width*$height));
		else
			return 1;
	}

	// RESIZING CALCULATION FUNCTIONS FOR PRE-CROP

	// Return scale factor applied before cropping to yield a later cropped image fittig the target dimensions
	public static function cropfitscale(int $width, int $height, int $leftcrop, int $rightcrop, int $topcrop, int $bottomcrop, int $targetwidth, int $targetheight): float {
		$cropwidth = $width - $leftcrop - $rightcrop;
		$cropheight = $height - $topcrop - $bottomcrop;
		return image::fitscale($cropwidth, $cropheight, $targetwidth, $targetheight);
	}

	// Return scale factor applied before copping to yield a cropped image with the given maximum pixels (cropped width x cropped height)
	public static function cropareascale(int $width, int $height, int $leftcrop, int $rightcrop, int $topcrop, int $bottomcrop, int $maxpixels): float {
		$cropwidth = $width - $leftcrop - $rightcrop;
		$cropheight = $height - $topcrop - $bottomcrop;
		return image::areascale($cropwidth, $cropheight, $maxpixels);
	}

	// RESIZING FUNCTIONS

	// Fit an image (while preserving aspect ratio) into a specific set of dimensions
	public static function fitimage(Imagick $image, int $targetwidth, int $targetheight): void {
		$image->scaleImage($targetwidth, $targetheight, true);
	}

	// Resize an svg (fit method) manually through its markup becuase Imagick doesn't resize .svg files correctly
	//taken from: https://stackoverflow.com/a/13484470
	private static function fitsvg(string $svg, int $targetwidth, int $targetheight): string {
		// Find dimensions in .svg file
		$reW = '/(.*<svg[^>]* width=")([\d.]+pt)(.*)/si';
		$reH = '/(.*<svg[^>]* height=")([\d.]+pt)(.*)/si';
		if ((preg_match($reW, $svg, $mw) && preg_match($reH, $svg, $mh))) {
			$width = floatval($mw[2]);
			$height = floatval($mh[2]);
		}
		else {
			$reW = '/(.*<svg[^>]* width=")([\d.]+px)(.*)/si';
			$reH = '/(.*<svg[^>]* height=")([\d.]+px)(.*)/si';
			if ((preg_match($reW, $svg, $mw) && preg_match($reH, $svg, $mh))) {
				$width = floatval($mw[2]);
				$height = floatval($mh[2]);
			}
			else
				return false; // Return false if no dimensions found
		}
		$width = floatval($mw[2]);
		$height = floatval($mh[2]);

		// Scale to make width and height big enough
		$scalefactor = image::fitscale((int)$width, (int)$height, $targetwidth, $targetheight);
		$newdimensions = array('width' => $scalefactor*$width, 'height' => $scalefactor*$height);

		// Replace dimensions
		$svg = preg_replace($reW, "\${1}{$newdimensions['width']}px\${3}", $svg);
		$svg = preg_replace($reH, "\${1}{$newdimensions['height']}px\${3}", $svg);
		
		return $svg;
	}

	// Scale an image based on a ratio
	public static function scaleimage(Imagick $image, float $scalefactor): void {
		$dimensions = $image->getImageGeometry();
		$width = $dimensions['width'];
		$height = $dimensions['height'];
		$newwidth = (int)($width*$scalefactor);
		$newheight = (int)($height*$scalefactor);
		$image->scaleImage($newwidth, $newheight, true);
	}

	// IMAGE MANIPULATION FUNCTIONS

	// Add a watermark to an image
	// $transparency: 0-1, how opaque the watermark is against the background (0 = invisible, 1 = opaque)
	// $fillratio: 0-1, how much area of the image the watermark should occupy (0 = invisible, 1 = spans lowest dimension of image)
	public static function watermarkimage(Imagick $image, string $watermarkpath, float $transparency, float $fillratio): void {
		$imagedimensions = $image->getImageGeometry();
		$watermarkext = strtolower(pathinfo($watermarkpath, PATHINFO_EXTENSION));
		$watermark = new Imagick();
		$watermark->setBackgroundColor(new ImagickPixel('transparent'));
		
		// Treat svg files differently because imagick doesn't scale them well
		if ($watermarkext == 'svg') {
			$svgdata = file_get_contents($watermarkpath);
			$svgdata = image::fitsvg($svgdata, $imagedimensions['width']*$fillratio, $imagedimensions['height']*$fillratio);
			$watermark->readImageBlob($svgdata);
		}
		else {
			$watermark->readImage($watermarkpath);
			image::fitimage($watermark, (int)($imagedimensions['width']*$fillratio), (int)($imagedimensions['height']*$fillratio));
		}
		
		// Apply transparency to watermark
		$watermark->evaluateImage(Imagick::EVALUATE_MULTIPLY, $transparency, Imagick::CHANNEL_ALPHA);
		$watermarkdimensions = $watermark->getImageGeometry();
		
		// Get start position for watermark and overlay on image
		$startx = (int)(($imagedimensions['width'] - $watermarkdimensions['width'])/2);
		$starty = (int)(($imagedimensions['height'] - $watermarkdimensions['height'])/2);
		$image->compositeImage($watermark, Imagick::COMPOSITE_DEFAULT, $startx, $starty);
		$watermark->destroy();
	}

	// Crop an image to the margins provided
	public static function cropimage(Imagick $image, int $leftcrop, int $rightcrop, int $topcrop, int $bottomcrop, float $scalecrop = NULL): void {
		$dimensions = $image->getImageGeometry();
		$width = $dimensions['width'];
		$height =$dimensions['height'];
		
		// Scale crop margins if a scale factor is provided (if image was pre-scaled)
		if (is_null($scalecrop)) {
			$cropwidth = $width - $leftcrop - $rightcrop;
			$cropheight = $height - $topcrop - $bottomcrop;
			$x = $leftcrop;
			$y = $topcrop;
		}
		else {
			$cropwidth = $width - ceil($scalecrop*$leftcrop)-ceil($scalecrop*$rightcrop);
			$cropheight = $height - ceil($scalecrop*$topcrop)-ceil($scalecrop*$bottomcrop);
			$x = ceil($scalecrop*$leftcrop);
			$y = ceil($scalecrop*$topcrop);
		}
		
		// Crop image
		$image->cropImage($cropwidth, $cropheight, $x, $y);
	}

	// Rotate an image $angle degrees
	public static function rotateimage(Imagick $image, int $angle) {
		$image->rotateImage("#000", (int)$angle);
	}
}

?>
