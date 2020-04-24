<?php

require_once 'database.php';
require_once 'artpieceinfo.php';
require_once 'artpiecefile.php';

// Class for holding and manipulating information for a single artpiece
// Members are separated into artpiecefile and artpieceinfo objects, old & new versions
// Separation comes from the separate treatment in pages, and also the
// 	fact that updating files and information are fairly independent
// Note that this class contains all database operations and oversees final
// 	file manipulations, as well as pre-validating properties through DB
class artpiece {

	// Member objects:
	private $oldfile;	// artpiecefile object for existing file (from database)
	private $oldinfo;	// artpiecinfo object for existing info (from database)
	private $newfile;	// artpiecefile object for newly uploaded object
	private $newinfo;	// artpieceinfo object for new art information

	private $file;		// reference to current artpiecefile object (new trumps old)
	private $info;		// reference to current artpieceinfo object (new trumps old)

	private $id;		// database ID (not set for new pieces until INSERT statement)

	// DATABASE FUNCTIONS

	// Grab informm from database and create oldfile and oldinfo objects
	//	(requires $id to be set)
	private function getdb() {
		if (!isset($this->id))
			throw new BadFunctionCallException('Class "artpiece" error: Cannot access database without an id');

		$db = database::connect();

		$sql = 'SELECT * FROM `info` WHERE `id` = :id;';

		$stmt = $db->prepare($sql);

		$stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

		$stmt->execute();
		if ($stmt->rowCount() == 0)
			throw new Exception('Class "artpiece" - getdb function error: No database entry with id=' . $this->id);
		else if ($stmt->rowCount() == 1)
			$row = $stmt->fetch();
		else
			throw new Exception('Class "artpiece" - getbd function error: Multiple database entries with same ID! :(');

		$db = NULL;

		$this->oldinfo = new artpieceinfo($row['name'], $row['width'],
			$row['height'], $row['year'], $row['sold'], $row['price'],
			$row['description'], $row['fineartamerica'], $row['etsy']);
		$this->oldfile = new artpiecefile(false, $row['filename'],
			$row['md5'], $row['rotation'], $row['leftcrop'],
			$row['rightcrop'], $row['topcrop'], $row['bottomcrop']);
		
		if (!isset($this->newinfo))
			$this->info = &$this->oldinfo;
		if (!isset($this->newfile))
			$this->file = &$this->oldfile;
	}

	// Add or update information from artpiecefile and artpieceinfo objects to database
	function setdb() {
		$db = database::connect();

		if (isset($this->id)) {
			$sql = 'UPDATE `info` SET
			`name` = :name, `filename` = :filename, `width` = :width,
			`height` = :height, `year` = :year, `sold` = :sold,
			`price` = :price, `description` = :description,
			`fineartamerica` = :fineartamerica, `etsy` = :etsy, 
			`md5` = :md5, `rotation` = :rotation, `leftcrop` = :leftcrop,
			`rightcrop` = :rightcrop, `topcrop` = :topcrop,
			`bottomcrop` = :bottomcrop
			WHERE
			`id` = :id;';
		}
		else {
			$sql = 'INSERT INTO `info` (
			`name`, `filename`, `width`, `height`, `year`, `sold`,
			`price`, `description`, `fineartamerica`, `etsy`, `md5`,
			`rotation`, `leftcrop`, `rightcrop`, `topcrop`, `bottomcrop`
			) VALUES (
			:name, :filename, :width, :height, :year, :sold,
			:price, :description, :fineartamerica, :etsy, :md5,
			:rotation, :leftcrop, :rightcrop, :topcrop, :bottomcrop
			);';
		}
		
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':name', $this->info->getname(), PDO::PARAM_STR);
		$stmt->bindvalue(':filename', $this->createfilename($this->info->getname()), PDO::PARAM_STR);
		$stmt->bindValue(':width', $this->info->getwidth(), PDO::PARAM_STR);
		$stmt->bindValue(':height', $this->info->getheight(), PDO::PARAM_STR);
		$stmt->bindValue(':year', $this->info->getyear(), PDO::PARAM_INT);
		$stmt->bindvalue(':sold', $this->info->getsold(), PDO::PARAM_INT);
		$stmt->bindvalue(':price', $this->info->getprice(), PDO::PARAM_INT);
		$stmt->bindValue(':description', $this->info->getdescription(), PDO::PARAM_STR);
		$stmt->bindValue(':fineartamerica', $this->info->getfineartamerica(), PDO::PARAM_STR);
		$stmt->bindValue(':etsy', $this->info->getetsy(), PDO::PARAM_STR);
		$stmt->bindValue(':md5', $this->file->getmd5(), PDO::PARAM_STR);
		$stmt->bindValue(':rotation', $this->file->getrotation(), PDO::PARAM_INT);
		$stmt->bindValue(':leftcrop', $this->file->getleftcrop(), PDO::PARAM_INT);
		$stmt->bindValue(':rightcrop', $this->file->getrightcrop(), PDO::PARAM_INT);
		$stmt->bindValue(':topcrop', $this->file->gettopcrop(), PDO::PARAM_INT);
		$stmt->bindValue(':bottomcrop', $this->file->getbottomcrop(), PDO::PARAM_INT);

		if (isset($this->id))
			$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
		$stmt->execute();
		if (!isset($this->id))
			$this->id = $db->lastInsertId();

		$db = NULL;
	}

	// Function to preliminarily check if a value for a unique database field already exists
	// There is an option to include the ID for this object or not (if it exists)
	// Example where you would want to exclude the ID: checking for a unique name
	// Example where you would want to inclide it: checking the hash of a new file 
	private function checkexistsdb(string $fieldname, $value, bool $checkself) {
		$db = database::connect();
		if (isset($this->id) && !$checkself)
			$sql = 'SELECT `name` FROM `info` WHERE `' . $fieldname . '` = :value AND id <> :id;';
		else
			$sql = 'SELECT `name` FROM `info` WHERE `' . $fieldname . '` = :value;';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(":value", $value, PDO::PARAM_STR);
		if (isset($this->id) && !$checkself)
			$stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			$row = $stmt->fetch();
			$db = NULL;
			return $row['name'];
		}
		else
			$db = NULL;
			return false;
	}

	// FUNCTIONS TO VALIDATE INPUTS & CHECK AGAINST EXISTING DATABASE RECORDS

	public function errorsname($name) {
		if (!artpieceinfo::validatename($name))
			return 'Invalid name `' . $name . '`';
		elseif ($dbname = $this->checkexistsdb('name', artpieceinfo::sanitizestring($name), false))
			return 'Duplicate name exists in database for entry `' . $dbname . '`';
		elseif ($dbname = $this->checkexistsdb('filename', $this->createfilename($name), false))
			return 'Duplicate filename `' . $this->createfilename($name) . '` exists in database for entry `' . $dbname . '`';
		else
			return false;	// for code simplicity, false means 'no problems'
	}

	public function errorsdimension($dimension) {
		if (!artpieceinfo::validatedimension($dimension))
			return 'Invalid dimension `' . strval($dimension) . '`';
		else
			return false;	// for code simplicity, false means 'no problems'
	}

	public function errorsyear($year) {
		if (!artpieceinfo::validateyear($year))
			return 'Invalid year `' . strval($year) . '`';
		else
			return false;	// for code simplicity, false means 'no problems'
	}

	public function errorssold($sold) {
		if (!artpieceinfo::validatesold($sold))
			return 'Invalid `sold` setting `' . strval($sold) . '`';
		else
			return false;
	}

	public function errorsprice($price) {
		if (!artpieceinfo::validateprice($price))
			return 'Invalid price `' . strval($price) . '`';
		else
			return false;
	}

	public function errorsdescription($description) {
		if (!artpieceinfo::validatedescription($description))
			return 'Invalid description `' . $description . '`';
		else
			return false;	// for code simplicity, false means 'no problems'
	}

	public function errorsfineartamerica($fineartamerica) {
		if (!artpieceinfo::validateurl($fineartamerica))
			return 'Invalid URL `' . $fineartamerica . '` (need to include `http://`, etc - please copy and paste)';
		elseif ($dbname = $this->checkexistsdb('fineartamerica', artpieceinfo::sanitizeurl($fineartamerica), false))
			return 'Duplicate URL for fineartamerica.com exists in database for entry `' . $dbname . '`';
		else
			return false;	// for code simplicity, false means 'no problems'
	}

	public function errorsetsy($etsy) {
		if (!artpieceinfo::validateurl($etsy))
			return 'Invalid URL `' . $etsy . '` (need to include `http://`, etc - please copy and paste)';
		elseif ($dbname = $this->checkexistsdb('etsy', artpieceinfo::sanitizeurl($etsy), false))
			return 'Duplicate URL for etsy.com exists in database for entry `' . $dbname . '`';
		else
			return false;	// for code simplicity, false means 'no problems'
	}

	public function errorsmd5($md5) {
		if (!artpiecefile::validatemd5($md5))
			return 'Invalid md5 `' . $md5 . '`';
		if ($dbname = $this->checkexistsdb('md5', $md5, true))
			return 'Duplicate md5 exists in database for entry `' . $dbname . '`';
		else
			return false;	// for code simplicity, false means 'no problems'
	}

	public function errorsrotation($rotation) {
		if (!artpiecefile::validaterotation($rotation))
			return 'Invalid rotation `' . $rotation . '`';
		else
			return false;	// for code simplicity, false means 'no problems'
	}

	public function errorscrop($crop) {
		if (!artpiecefile::validatecrop($crop))
			return 'Invalid crop `' . $crop . '`';
		else
			return false;	// for code simplicity, false means 'no problems'
	}

	// FUNCTIONS TO ADD MEMBER OBJECTS

	// Add objects from database using an ID value
	function adddb(int $id) {
		if (isset($this->id))
			throw new Exception('Class "artpiece" - addb function error: database ID is already set');
		else {
			$this->id = $id;
			$this->getdb();
		}
	}

	// Add a newly uploaded image file (after cross-checking database)
	function addfile($filename) {
		$file = new artpiecefile(true, $filename);
		if ($dbname = $this->checkexistsdb('md5', $file->getmd5(), true))
			throw new Exception('Class "artpiece" - addfile function error: md5 exists in database for entry `' . $dbname . '`');
		else {
			$this->newfile = $file;
			$this->file = &$this->newfile;
		}
	}

	// Add newly entered artpiece information (after cross-checking database)
	function addinfo($name, $width = NULL, $height = NULL, $year = NULL, $sold = NULL, $price = NULL, $description = NULL, $fineartamerica = NULL, $etsy = NULL) {
		$info = new artpieceinfo($name, $width, $height, $year, $sold, $price, $description, $fineartamerica, $etsy);
		if ($dbname = $this->checkexistsdb('name', $info->getname(), false))
			throw new Exception('Class "artpiece" - addinfo function error: name exists in database for entry `' . $dbname . '`');
		elseif ($dbname = $this->checkexistsdb('filename', $this->createfilename($info->getname()), false))
			throw new Exception('Class "artpiece" - addinfo function error: filename `' . $this->createfilename($info->getname()) . '` exists in database for entry `' . $dbname . '`');
		elseif ($dbname = $this->checkexistsdb('fineartamerica', $info->getfineartamerica(), false))
			throw new Exception('Class "artpiece" - addinfo function error: fineartamerica URL exists in database for entry `' . $dbname . '`');
		elseif ($dbname = $this->checkexistsdb('etsy', $info->getetsy(), false))
			throw new Exception('Class "artpiece" - addinfo function error: etsy URL exists in database for entry `' . $dbname . '`');
		else {
			$this->newinfo = $info;
			$this->info = &$this->newinfo;
		}
	}

	// GET MEMBER VALUE FUNCTIONS
	
	public function getid() {
		return $this->id;
	}

	public function getfile() {
		return $this->file;
	}

	public function getinfo() {
		return $this->info;
	}

	// For getting the title and header text in displaying pages
	public function gettitle() {
		if (isset($this->info))
			return $this->info->getname();
		elseif (isset($this->file))
			return $this->file->getfilename();
		else
			return false;
	}

	function getall() {
		if (isset($this->info))
			$info = $this->info->getall();
		else
			$info = null;
		if (isset($this->file))
			$file = $this->file->getall();
		else
			$file = null;
		$all['info'] = $info;
		$all['file'] = $file;
		return $all;
	}

	// FINISH FILES

	// See if a file exists
	private static function fileexists($dir, $filename) {
		return (sizeof(glob($dir . $filename . '.*')) > 0);
	}

	// See if any image files exist with the given name
	private static function fileexistsimg($filename) {
		return (artpiece::fileexists(ORIGINALS['sys'], $filename) ||
			artpiece::fileexists(WATERMARKED['sys'], $filename) ||
			artpiece::fileexists(THUMBNAILS['sys'], $filename));
	}

	// Move image files to their correct location given a new filename
	function movefiles() {
		// Existing files need to be deleted if an upload occured
		if (isset($this->oldfile) && isset($this->newfile)) {
			$this->oldfile->deletefiles(true);
		}
		
		// Get the filename from the current user-defined name
		$newfilename = $this->createfilename($this->info->getname());
		
		// Move files if their new, regardless
		if (isset($this->newfile)) {
			if ($this->fileexistsimg($newfilename))
				throw new Exception('Class "artpiece" - writefiles function: Files named `' . $newfilename . '` found at destination');
			$this->file->movefiles($newfilename);
		}
		
		// If there's only old existing files, they should only be moved if there was a filename change
		elseif (isset($this->oldfile)) {
			if ($this->oldfile->getfilename() != $newfilename) {
				if ($this->fileexistsimg($newfilename))
					throw new Exception('Class "artpiece" - writefiles function: files named `' . $newfilename . '` found at destination');
				$this->file->movefiles($newfilename);
			}
		}
	}

	// Create a filename from a user-defined artpiece name (lowercase letters and numbers only)
	public static function createfilename($name) {
		return strtolower(preg_replace('/[^A-Za-z0-9]/', '', $name)); // Removes special chars.
	}

}

?>
