<?php

	// Main menu

	require_once '../paths.php';
	include_once 'reset.php'; // destroy session for good measure

	include 'cache.php';

	echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>Lesley Paige website admin area</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
	</head>
	<body>
		<h1>
			Admin area for lesleypaige.com
		</h1>
		<p>
			<a href="' . ADMIN['html'] . 'upload.php"><input type="button" value="Add new piece"></a>
		</p>
		<p>
			<a href="' . ADMIN['html'] . 'sequence.php"><input type="button" value="Edit existing pieces"></a>
		</p>
		<p>
			<a href="' . ADMIN['html'] . 'style.php"><input type="button" value="Change page style"></a>
		</p>
		<p>
			<a href="' . ADMIN['html'] . 'bio.php"><input type="button" value="Edit biography"></a>
		</p>
		<p>
			<a href="' . ADMIN['html'] . 'backup.php"><input type="button" value="Back up site data"></a>
		</p>
		<p>
			<a href="' . ADMIN['html'] . 'restore.php"><input type="button" value="Restore site data"  onclick="return confirm(\'This operation will overwrite existing data.  Continue?\');"></a>
		</p>
	</body>
</html>';

?>
