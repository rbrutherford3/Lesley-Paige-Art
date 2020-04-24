<?php

require_once '../paths.php';
require_once 'image.php';

// Class to hold and manipulate the file information for an artpiece
class artpiecefile {

	// members
	private $newupload;	// if new upload or pre-existing file

	private $filename;	// filename without extension
	private $md5;		// hash of file for identification
	private $rotation;	// Rotation (right angles, 0-270 degrees)
	private $leftcrop;	// # pixels from left side of original image to crop from
	private $rightcrop;	// " right side "
	private $topcrop;	// " top side "
	private $bottomcrop;	// " bottom side "

	private $paths;		// Array of image filepaths, including HTML versions

	private $formatted;	// Formatted image object for crop and rotate pages
	private $watermarked;	// Large (fixed max pixels) version of image with watermark on it for detailed viewing
	private $thumbnail;	// Thumbnail version of image, fits in fixed set of dimensions

	private $dimensions;	// Dimensions of original image
	private $formatteddimensions; // Dimensions of formatted image

	// constructor
	function __construct(bool $newupload, string $filename, string $md5 = NULL, int $rotation = NULL, int $leftcrop = NULL, 
		int $rightcrop = NULL, int $topcrop = NULL, int $bottomcrop = NULL) {
		$this->newupload = $newupload;
		$this->setfilename($filename);
		$this->setmd5($md5);
		$this->setrotation($rotation);
		$this->setleftcrop($leftcrop);
		$this->setrightcrop($rightcrop);
		$this->settopcrop($topcrop);
		$this->setbottomcrop($bottomcrop);
		$this->setpaths();
		$this->setdimensions();
		$this->createmd5();
	}

	// INITIALIZATION SCRIPTS

	// Set paths for image files, throw errors if original not found
	private function setpaths() {
		if ($this->newupload) {
			$results = glob(UPLOAD_ORIGINALS['sys'] . $this->filename . '.*');
			if (sizeof($results) == 1)
				$this->paths['original'] = $results[0];
			else
				throw new Exception('Class "artpiecefile" - setpaths function error: glob found more or less than 1 uploaded file');
		}
		else {
			$results = glob(ORIGINALS['sys'] . $this->filename . '.*');
			if (sizeof($results) == 1)
				$this->paths['original'] = $results[0];
			else
				throw new Exception('Class "artpiecefile" - setpaths function error: glob found more or less than 1 original file');

			$results = glob(WATERMARKED['sys'] . $this->filename . '.*');
			if (sizeof($results) == 1) {
				$this->paths['watermarked'] = $results[0];
				$this->paths['html']['watermarked'] = WATERMARKED['html'] . basename($results[0]);
			}

			$results = glob(THUMBNAILS['sys'] . $this->filename . '.*');
			if (sizeof($results) == 1) {
				$this->paths['thumbnail'] = $results[0];
				$this->paths['html']['thumbnail'] = THUMBNAILS['html'] . basename($results[0]);
			}
		}
	}

	public function setdimensions() {
		$this->dimensions = image::getdimensions($this->paths['original']);
	}

	// Create md5 (file hash) if it doesn't exist, otherwise verify match
	private function createmd5() {
		if (is_null($this->md5))
			$this->md5 = md5_file($this->paths['original']);
		else {
			if ($this->md5 != md5_file($this->paths['original']))
				throw new Exception('Class "artpiecefile" - setmd5 function error: md5 mismatch');
		}
	}

	// FUNCTIONS TO VALIDATE MEMBER VALUES PRIOR TO THEIR SETTING

	public static function validatefilename($filename) {
		 return is_string($filename);
	}

	public static function validatemd5($md5) {
		return ((is_string($md5) && ctype_xdigit($md5) && (strlen($md5) == 32)) || is_null($md5) || ($md5 === ''));
	}

	public static function validaterotation($rotation) {
		return ((artpiecefile::posint($rotation) && ((int)$rotation <= 270) && (((int)$rotation % 90) == 0)) || is_null($rotation) || ($rotation === ''));
	}

	public static function validatecrop($cropmargin) {
		return (artpiecefile::posint($cropmargin) || is_null($cropmargin) || ($cropmargin === ''));
	}

	public static function posint($number) {
		return ((ctype_digit($number) || is_int($number)) && ((int)$number >= 0));
	}

	// SET MEMBER VALUE FUNCTIONS

	public function setfilename($filename) {
		if ($this->validatefilename($filename))
			$this->filename = $filename;
		else
			throw new InvalidArgumentException('Class "artpiecefile" - error setting `filename` property as `' . strval($filename) . ': must be a string');
	}

	public function setmd5($md5) {
		if ($this->validatemd5($md5))
			$this->md5 = $md5;
		else
			throw new InvalidArgumentException('Class "artpiecefile" - error setting `md5` property as `' . strval($md5) . ': must be a hex string of 32 characters');
	}

	public function setrotation($rotation) {
		if (($rotation === '') || is_null($rotation))
			$this->rotation = NULL;
		else {
			if ($this->validaterotation($rotation))
				$this->rotation = (int)$rotation;
			else
				throw new InvalidArgumentException('Class "artpiecefile" - error setting rotation` property as `' . strval($rotation) . ': must be right angles: 0, 90, 180, or 270');
		}
	}

	private static function setcrop($cropmargin) {
		if (($cropmargin === '') || is_null($cropmargin))
			return NULL;
		else {
			if (artpiecefile::validatecrop($cropmargin))
				return (int)$cropmargin;
			else
				throw new InvalidArgumentException('Class "artpiecefile" - error setting `crop` property as `' . strval($cropmargin) . ': must be non-negative integer');
		}
	}

	public function setleftcrop($cropmargin) {
		$this->leftcrop = $this->setcrop($cropmargin);
	}

	public function setrightcrop($cropmargin) {
		$this->rightcrop = $this->setcrop($cropmargin);
	}

	public function settopcrop($cropmargin) {
		$this->topcrop = $this->setcrop($cropmargin);
	}

	public function setbottomcrop($cropmargin) {
		$this->bottomcrop = $this->setcrop($cropmargin);
	}

	public function setcropall($leftcrop, $rightcrop, $topcrop, $bottomcrop) {
		$this->setleftcrop($leftcrop);
		$this->setrightcrop($rightcrop);
		$this->settopcrop($topcrop);
		$this->setbottomcrop($bottomcrop);
	}

	// GET MEMBER VALUE FUNCTIONS

	public function getdimensions() {
		return $this->dimensions;
	}

	public function getformatteddimensions() {
		return $this->formatteddimensions;
	}

	public function getfilename() {
		return $this->filename;
	}

	public function getmd5() {
		return $this->md5;
	}

	public function getrotation() {
		return $this->rotation;
	}

	public function getleftcrop() {
		return $this->leftcrop;
	}

	public function getrightcrop() {
		return $this->rightcrop;
	}

	public function gettopcrop() {
		return $this->topcrop;
	}

	public function getbottomcrop() {
		return $this->bottomcrop;
	}

	public function getcrop() {
		return ['left' => $this->leftcrop, 'right' => $this->rightcrop, 
			'top' => $this->topcrop, 'bottom' => $this->bottomcrop];
	}
	
	public function getall() {
		$all['filename'] = $this->filename;
		$all['md5'] = $this->md5;
		$all['rotation'] = $this->rotation;
		$all['leftcrop'] = $this->leftcrop;
		$all['rightcrop'] = $this->rightcrop;
		$all['topcrop'] = $this->topcrop;
		$all['bottomcrop'] = $this->bottomcrop;
		$all['paths'] = $this->paths;
		return $all;
	}
	
	// FUNCTIONS TO CHECK FOR COMPLETION OF CLASS PROPERTIES

	public function iscropcomplete() {
		return (isset($this->leftcrop) && isset($this->rightcrop) &&
			isset($this->topcrop) && isset($this->bottomcrop));
	}

	public function iscomplete() {
		return (isset($this->filename) && isset($this->md5) && isset($this->rotation) && $this->iscropcomplete());
	}

	// CREATE IMAGICK FOBJRCT FUNCTIONS

	// Create formatted Imagick object (for viewing while editing), crop and/or rotate, if applicable
	public function createformatted(bool $crop, bool $rotate) {
		$this->formatted = new Imagick($this->paths['original']);
		if ($crop && $this->iscropcomplete()) {
			$scalefactor = image::cropfitscale(
				$this->dimensions['width'],
				$this->dimensions['height'],
				$this->leftcrop,
				$this->rightcrop,
				$this->topcrop,
				$this->bottomcrop,
				FORMATTED_DIMENSION,
				FORMATTED_DIMENSION);
		}
		else
			$scalefactor = image::fitscale(
				$this->dimensions['width'],
				$this->dimensions['height'],
				FORMATTED_DIMENSION,
				FORMATTED_DIMENSION);
		image::scaleimage($this->formatted, $scalefactor);
		image::formatimage($this->formatted, EXT);
		if ($crop && $this->iscropcomplete())
			image::cropimage($this->formatted,
			$this->leftcrop,
			$this->rightcrop,
			$this->topcrop,
			$this->bottomcrop,
			$scalefactor);
		if ($rotate && isset($this->rotation))
			image::rotateimage($this->formatted, $this->rotation);
		$this->formatteddimensions = $this->formatted->getImageGeometry();
	}

	// Create watermarked Imagick object, crop margins and rotation must be set
	public function createwatermarked() {
		if ($this->iscomplete()) {
			$scalefactor = image::cropareascale(
				$this->dimensions['width'],
				$this->dimensions['height'],
				$this->leftcrop,
				$this->rightcrop,
				$this->topcrop,
				$this->bottomcrop,
				MAX_PIXELS);
			$this->watermarked = new Imagick($this->paths['original']);
			image::scaleimage($this->watermarked, $scalefactor);
			image::formatimage($this->watermarked, EXT);
			image::cropimage($this->watermarked,
				$this->leftcrop,
				$this->rightcrop,
				$this->topcrop,
				$this->bottomcrop,
				$scalefactor);
			image::rotateimage($this->watermarked, $this->rotation);
			image::watermarkimage($this->watermarked, STAMP_SVG['sys'], WATERMARK_TRANSPARENCY, WATERMARK_FILL);
		}
		else
			throw new BadFunctionCallException('Class "artpiecefile" - watermark creation function error: not all properties are set');
	}

	// Create thumbnail Imagick object, crop margins and rotation must be set
	public function createthumbnail() {
		if ($this->iscomplete()) {
			$scalefactor = image::cropfitscale(
				$this->dimensions['width'],
				$this->dimensions['height'],
				$this->leftcrop,
				$this->rightcrop,
				$this->topcrop,
				$this->bottomcrop,
				THUMBNAIL_DIMENSION,
				THUMBNAIL_DIMENSION);
			$this->thumbnail = new Imagick($this->paths['original']);
			image::scaleimage($this->thumbnail, $scalefactor);
			image::formatimage($this->thumbnail, EXT);
			image::cropimage($this->thumbnail,
				$this->leftcrop,
				$this->rightcrop,
				$this->topcrop,
				$this->bottomcrop,
				$scalefactor);
			image::rotateimage($this->thumbnail, $this->rotation);
		}
		else
			throw new BadFunctionCallException('Class "artpiecefile" - thumbnail creation function error: not all properties are set');
	}

	// GET IMAGE HTML FUNCTIONS (Image blobs returned if Imagick object exists, file path returned otherwise)

	public function getformattedHTML() {
		if (isset($this->formatted))
			return 'data:image/' . EXT . ';base64,' .  base64_encode($this->formatted->getimageblob());
		else
			return NOT_FOUND_LARGE['html'];
		// Formatted Imagick object is not saved as a file
	}

	public function getwatermarkedHTML() {
		if (isset($this->watermarked))
			return 'data:image/' . EXT . ';base64,' .  base64_encode($this->watermarked->getimageblob());
		elseif (isset($this->paths['html']['watermarked']))
			return $this->paths['html']['watermarked'] . '?' . time(); // time added to ensure no cache
		else
			return NOT_FOUND_LARGE['html'];
	}

	public function getthumbnailHTML() {
		if (isset($this->thumbnail))
			return 'data:image/' . EXT . ';base64,' .  base64_encode($this->thumbnail->getimageblob());
		elseif (isset($this->paths['html']['thumbnail']))
			return $this->paths['html']['thumbnail'] . '?' . time(); // time added to ensure no cache
		else
			return NOT_FOUND['html'];
	}

	// FILE MANIPULATION FUNCTIONS (CREATE AND DESTROY FILES);

	public static function deletefile(string $path) {
		if (file_exists($path))
			unlink($path);
		else
			throw new InvalidArgumentException('Class "artpiecefile" - deletefile function error: no file at path `' . $path . '`');
	}

	// Delete all image files with option to delete original
	public function deletefiles(bool $deleteoriginal) {
		if ($deleteoriginal) {
			$this->deletefile($this->paths['original']);
			unset($this->paths['original']);
		}
		if (isset($this->paths['watermarked'])) {
			$this->deletefile($this->paths['watermarked']);
			unset($this->paths['watermarked']);
			unset($this->paths['html']['watermarked']);
		}
		if (isset($this->paths['thumbnail'])) {
			$this->deletefile($this->paths['thumbnail']);
			unset($this->paths['thumbnail']);
			unset($this->paths['html']['thumbnail']);
		}
	}

	// Write watermarked image file to appropriate path and set path propeties
	// Throws error if Imagick object doesn't exist and overwrites by default
	public function writewatermarked() {
		if (!isset($this->watermarked))
			throw new BadFunctionCallException('Class `artpiecefile` error: Trying to write watermaked image file without Imagick object');
		if (isset($this->paths['watermarked']))
			$this->deletefile($this->paths['watermarked']);
		else {
			if ($this->newupload) {
				$this->paths['watermarked'] = UPLOAD_WATERMARKED['sys'] . $this->filename . '.' . EXT;
				$this->paths['html']['watermarked'] = UPLOAD_WATERMARKED['html'] . $this->filename . '.' . EXT;
			}
			else {
				$this->paths['watermarked'] = WATERMARKED['sys'] . $this->filename . '.' . EXT;
				$this->paths['html']['watermarked'] = WATERMARKED['html'] . $this->filename . '.' . EXT;
			}
		}
		$this->watermarked->writeImage($this->paths['watermarked']);
	}

	// Write thumbnail image file to appropriate path and set path propeties
	// Throws error if Imagick object doesn't exist and overwrites by default
	public function writethumbnail() {
		if (!isset($this->thumbnail))
			throw new BadFunctionCallException('Class `artpiecefile` error: Trying to write thumbnail image file without Imagick object');
		if (isset($this->paths['thumbnail']))
			$this->deletefile($this->paths['thumbnail']);
		else {
			if ($this->newupload) {
				$this->paths['thumbnail'] = UPLOAD_THUMBNAILS['sys'] . $this->filename . '.' . EXT;
				$this->paths['html']['thumbnail'] = UPLOAD_THUMBNAILS['html'] . $this->filename . '.' . EXT;
			}
			else {
				$this->paths['thumbnail'] = THUMBNAILS['sys'] . $this->filename . '.' . EXT;
				$this->paths['html']['thumbnail'] = THUMBNAILS['html'] . $this->filename . '.' . EXT;
			}
		}
		$this->thumbnail->writeImage($this->paths['thumbnail']);
	}

	// Move existing image files to new location, given new filename
	// Does not move files if new paths are the same as old
	// Throws errors of paths aren't set or file(s) exist at destination
	public function movefiles(string $newfilename) {
		if (!(isset($this->paths['original']) &&
			isset($this->paths['watermarked']) &&
			isset($this->paths['thumbnail'])))
			throw new Exception('Class "artpiecefile" - movefiles function: cannot move files without paths set');
		$originalpath = $this->paths['original'];
		$neworiginalpath = ORIGINALS['sys'] . $newfilename . '.' . pathinfo($originalpath)['extension'];
		$newwatermarkedpath = WATERMARKED['sys'] . $newfilename . '.' . EXT;
		$newthumbnailpath = THUMBNAILS['sys'] . $newfilename . '.' . EXT;
		if ($this->paths['original'] != $neworiginalpath) {
			if (file_exists($neworiginalpath))
				throw new Exception('Class "artpiecefile" - movefile function: file at detination ' . $neworiginalpath);
			rename($this->paths['original'], $neworiginalpath);
		}
		if ($this->paths['watermarked'] != $newwatermarkedpath) {
			if (file_exists($newwatermarkedpath))
				throw new Exception('Class "artpiecefile" - movefile function: file at detination ' . $newwatermarkedpath);
			rename($this->paths['watermarked'], $newwatermarkedpath);
		}
		if ($this->paths['thumbnail'] != $newthumbnailpath) {
			if (file_exists($newthumbnailpath))
				throw new Exception('Class "artpiecefile" - movefile function: file at detination ' . $newthumbnailpath);
			rename($this->paths['thumbnail'], $newthumbnailpath);
		}
	}
	
	// FUNCTIONS TO DESTROY IMAGICK OBJECTS

	public function destroyformatted() {
		$this->formatted->destroy();
		unset($this->formatted);
	}

	public function destroywatermarked() {
		$this->watermarked->destroy();
		unset($this->watermarked);
	}

	public function destroythumbnail() {
		$this->thumbnail->destroy();
		unset($this->thumbnail);
	}
}

?>
