<?php

	// This page displays the previously saved user defined artpiece information
	// and then, upon confirmation that everything is good, commits all saved
	// information to the database and moves any image files as necessary
	// (necessary situations include new file upload or name change)

	require_once '../paths.php';
	require_once 'artpiece.php';

	session_start();

	if (!isset($_SESSION['artpiece']))
		throw new InvalidAgumentException('Confirm.php needs an artpiece object');
	if (is_null($_SESSION['artpiece']->getinfo()))
		throw new InvalidArgumentException('Confirm.php needs info object in artpiece object');
	if (is_null($_SESSION['artpiece']->getfile()))
		throw new InvalidArgumentException('Confirm.php needs file object in artpiece object');
	if (!$_SESSION['artpiece']->getfile()->iscomplete())
		throw new InvalidArgumentException('Confirm.php needs complete file object in artpiece object');

	// Upon confrmation, move files and commit information to database
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$_SESSION['artpiece']->movefiles();
		$_SESSION['artpiece']->setdb();
		header('Location: ' . ADMIN['html'] . 'sequence.php#' . $_SESSION['artpiece']->getid());
		exit;
	}
	
	// Display page
	else {
		// Gather saved information
		$all = $_SESSION['artpiece']->getall();
		$info = $all['info'];
		
		// Display page
		$title = $_SESSION['artpiece']->gettitle();
		if ($title)
			$title = 'Confirm information for "' . $title . '"';
		else
			$title = 'Confirm information';

		include 'cache.php';

		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>' . $title . '</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_TEXT['html'] . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
	</head>
	<body>
		<h1>' . $title . ':</h1>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="artinfoconfirm" method="POST">
			<p>
				<img src="' . $_SESSION['artpiece']->getfile()->getthumbnailHTML() . '">
			</p>
			<p>
				<b>Name:</b>
				<br>
				' . htmlspecialchars_decode($info['name']) . '
			</p>
			<p>
				<b>Year:</b>
				<br>
				' . (is_null($info['year']) ? '<i>none</i>' : strval($info['year'])) . '
			</p>
			<p>
				<b>Width:</b>
				<br>
				' . (is_null($info['width']) ? '<i>none</i>' : strval($info['width']) . ' inches') . '
			</p
			<p>
				<b>Height:</b>
				<br>
				' . (is_null($info['height']) ? '<i>none</i>' : strval($info['height']) . ' inches') . '
			</p>
			<p>
				<b>Sales status:</b>
				<br>
				' . (is_null($info['sold']) ? '<i>none</i>' : (($info['sold'] == 0) ? 'NOT FOR SALE' : (($info['sold'] == 1) ? 'FOR SALE' : (($info['sold'] == 2) ? 'SOLD' : '<i>INVALID OPTION</i>')))) . '
			</p>
			<p>
				<b>Price:</b>
				<br>
				' . (is_null($info['price']) ? '<i>none</i>' : '$' . strval($info['price'])) . '
			</p>
			<p>
				<b>Description:</b>
				<br>
				' . (is_null($info['description']) ? '<i>none</i>' : nl2br(htmlspecialchars($info['description']))) . '
			</p>
			<p>
				<b>etsy.com URL</b>
				<br>
				' . (is_null($info['etsy']) ? '<i>none</i>' : $info['etsy']) . '
			</p>
			<p>
				<b>fineartamerica.com URL</b>
				<br>
				' . (is_null($info['fineartamerica']) ? '<i>none</i>' : $info['fineartamerica']) . '
			</p>
			<p>
				<a href="' . ADMIN['html'] . 'editinfo.php"><input type="button" value="Re-edit"></a>
				<input type="submit" name="submit" value="Confirm">
			</p>
		</form>
	</body>
</html>';
	}
?>
