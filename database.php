<?php

require_once 'credentials.php';

// Class to simply return a database connection
// Login credentials kept separately for publishing purposes
class database {

	public static function connect() {
		
		$credentials = credentials();

		$db =  new PDO('mysql:host=' . $credentials['HOST'] . ';dbname=' . $credentials['DATABASE'],
			$credentials['USERNAME'],
			$credentials['PASSWORD'],
			array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_EMULATE_PREPARES => false)); // Kick out errors and don't just return strings (ints as ints)
		return $db;
	}
}

?>
