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
	</body>
</html>';

?>
