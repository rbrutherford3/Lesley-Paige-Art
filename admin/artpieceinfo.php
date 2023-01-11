<?php

require_once '../paths.php';

// Class to hold and manipulate user-defined information about an artpiece
class artpieceinfo {

	//members
	private $name;		// Name of piece
	private $width;		// Width of piece in inches (decimal)
	private $height;	// Height of piece in knches (decimal)
	private $year;		// Year piece was drawn (int)
	private $sold;		// Integer code for sold status (0 -> not for sale, 1 -> for sale, 2 -> sold)
	private $price;		// Price of piece in whole dollars (int)
	private $description;	// Description (long string)
	private $fineartamerica; // URL link for purchases (string)
	private $etsy;		// URL link for purchases (string) - DEPRECATED
	private $sequence;	// Order of piece in presentation

	//constructor
	function __construct(string $name, $width = NULL, $height = NULL,
		$year = NULL, $sold = NULL, $price = NULL, string $description = NULL, 
		string $fineartamerica = NULL, string $etsy = NULL, int $sequence = NULL) {
			$this->setname($name);
			$this->setwidth($width);
			$this->setheight($height);
			$this->setyear($year);
			$this->setsold($sold);
			$this->setprice($price);
			$this->setdescription($description);
			$this->setfineartamerica($fineartamerica);
			$this->setetsy($etsy);
			$this->setsequence($sequence);
	}

	// FUNCTIONS TO VALIDATE MEMBER VALUES (STATIC)

	public static function validatename($name) {
		 return (is_string($name) && (strlen($name) <= 50));
	}

	public static function validatedimension($dimension) {
		return ((is_numeric($dimension) && (ceil($dimension) > 0)) || is_null($dimension) || ($dimension === ''));
	}

	public static function validateyear($year) {
		return ((artpieceinfo::posint($year) && ((int)$year>1000) && ((int)$year<2100)) || is_null($year) || ($year === ''));
	}

	public static function validatedescription($description) {
		return (is_string($description) || is_null($description));
	}
	
	public static function validatesold($sold) {
		return ((artpieceinfo::posint($sold) && ((int)$sold <=2)) || is_null($sold) || ($sold === ''));
	}

	public static function validateprice($price) {
		return (artpieceinfo::posint($price) || is_null($price) || ($price === ''));
	}
	
	public static function posint($number) {
		return ((ctype_digit($number) || is_int($number)) && ((int)$number >= 0));
	}

	// This function checks the HTTP code returned from a URL to validate it
	// partly taken from : https://www.geeksforgeeks.org/how-to-check-the-existence-of-url-in-php/
	public static function validateurl($url) {
		if (is_null($url) || ($url === ''))
			return true;
		elseif (filter_var($url, FILTER_VALIDATE_URL)) {
			$headers = @get_headers($url);
			if ($headers && (strpos($headers[0], '200') || strpos($headers[0], '301')))
				return true;
			else
				return false;
		}
		else
			return false;
	}

	// FUNCTIONS TO SANITIZE MEMBER VALUES PRIOR TO THEIR SETTING

	// taken from: https://stackoverflow.com/a/21032909
	public static function sanitizeurl($url) {
		if (($url === '') || is_null($url))
			return NULL;
		else {
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url); //set url
			curl_setopt($ch, CURLOPT_HEADER, true); //get header
			curl_setopt($ch, CURLOPT_NOBODY, true); //do not include response body
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //do not show in browser the response
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //follow any redirects
			curl_exec($ch);
			// added from: https://stackoverflow.com/q/1439040 to cat
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
			curl_close($ch);
			return str_replace('http://', 'https://', $newurl); //extract the url from the header response
		}
	}

	public static function sanitizestring($data) {
		return trim($data);
	}

	// FUNCTIONS TO SET MEMBER VALUES

	public function setname($name) {
		if ($this->validatename($name))
			$this->name = $this->sanitizestring($name);
		else
			throw new InvalidArgumentException('Class "artpieceinfo" - error setting `name` property as `' . strval($name) . '`: must be a string');
	}

	public function setwidth($width) {
		if ($this->validatedimension($width)) {
			if (($width === '') || is_null($width))
				$this->width = NULL;
			else
				$this->width = (float)$width;
		}
		else
			throw new InvalidArgumentException('Class "artpieceinfo" - error setting `width` property as `' . strval($width) . '`: must be a decimal or integer');
	}

	public function setheight($height) {
		if ($this->validatedimension($height)) {
			if (($height === '') || is_null($height))
				$this->height = NULL;
			else
				$this->height = (float)$height;
		}
		else
			throw new InvalidArgumentException('Class "artpieceinfo" - error setting `height` property as `' . strval($height) . '`: must be a decimal or integer');
	}

	public function setyear($year) {
		if ($this->validateyear($year)) {
			if (($year === '') || is_null($year))
				$this->year = NULL;
			else
				$this->year = (int)$year;
		}
		else
			throw new InvalidArgumentException('Class "artpieceinfo" - error setting `year` property as `' . strval($year) . '`: must be an integer between 1000 & 2100');
	}
	
	public function setsold($sold) {
		if ($this->validatesold($sold)) {
			if (($sold === '') || is_null($sold))
				$this->sold = NULL;
			else
				$this->sold = (int)$sold;
		}
		else
			throw new InvalidArgumentException('Class "artpieceinfo" - error setting `sold` property as `' . strval($sold) . '`');
	}
	
	public function setprice($price) {
		if ($this->validateprice($price)) {
			if (($price === '') || is_null($price))
				$this->price = NULL;
			else
				$this->price = (int)$price;
		}
		else
			throw new InvalidArgumentException('Class "artpieceinfo" - error setting `price` property as `' . strval($price) . '`');
	}

	public function setdescription($description) {
		if ($this->validatedescription($description)) {
			if (($description === '') || is_null($description))
				$this->description = NULL;
			else
				$this->description = $this->sanitizestring($description);
		}
		else
			throw new InvalidArgumentException('Class "artpieceinfo" - error setting `description` property as `' . strval($description) . '`: must be a string');
	}

	public function setfineartamerica($fineartamerica) {
		if ($this->validateurl($fineartamerica))
			$this->fineartamerica = $this->sanitizeurl($fineartamerica);
		else
			throw new InvalidArgumentException('Class "artpieceinfo" - error setting `fineartamerica` property as `' . strval($fineartamerica) . '`: not a valid URL');
	}

	public function setetsy($etsy) {
		if ($this->validateurl($etsy))
			$this->etsy = $this->sanitizeurl($etsy);
		else
			throw new InvalidArgumentException('Class "artpieceinfo" - error setting `etsy` property as `' . strval($etsy) . '`: not a valid URL');
	}

	public function setsequence($sequence) {
		if ($this->posint($sequence))
			$this->sequence = $sequence;
		else if (($sequence === '') || is_null($sequence))
			$this->sequence = NULL;
		else
			throw new InvalidArgumentException('Class "artpieceinfo" - error setting `sequence` property as `' . strval($sequence) . '`: must be a positive integer');
	}
	// FUNCTIONS TO GET MEMBER VALUE FUNCTIONS

	public function getname() {
		return $this->name;
	}

	public function getwidth() {
		return $this->width;
	}

	public function getheight() {
		return $this->height;
	}

	public function getyear() {
		return $this->year;
	}

	public function getsold() {
		return $this->sold;
	}

	public function getprice() {
		return $this->price;
	}
	
	public function getdescription() {
		return $this->description;
	}

	public function getfineartamerica() {
		return $this->fineartamerica;
	}

	public function getetsy() {
		return $this->etsy;
	}

	public function getsequence() {
		return $this->sequence;
	}

	public function getall() {
		$all['name'] = $this->name;
		$all['width'] = $this->width;
		$all['height'] = $this->height;
		$all['year'] = $this->year;
		$all['sold'] = $this->sold;
		$all['price'] = $this->price;
		$all['description'] = $this->description;
		$all['fineartamerica'] = $this->fineartamerica;
		$all['etsy'] = $this->etsy;
		$all['sequence'] = $this->sequence;
		return $all;
	}
}

?>
