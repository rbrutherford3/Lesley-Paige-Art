<?php

	// This page creates image files out of the watermarked and thumbnail Imagivk objects
	// First, it displays them to the user for confirmation

	require_once '../paths.php';
	require_once 'artpiece.php';

	session_start();

	if (!isset($_SESSION['artpiece']))
		throw new Exception('Missing artpiece object at confirmfiles.php');
	elseif (is_null($_SESSION['artpiece']->getfile()))
		throw new Exception('Missing arptiece file object at confirmfiles.php');
	else {
		$_SESSION['artpiece']->getfile()->createwatermarked();
		$_SESSION['artpiece']->getfile()->createthumbnail();
	}

	// If everything is okay, write the filss amd move on
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$_SESSION['artpiece']->getfile()->writewatermarked();
		$_SESSION['artpiece']->getfile()->writethumbnail();
		$_SESSION['artpiece']->getfile()->destroywatermarked();
		$_SESSION['artpiece']->getfile()->destroythumbnail();
		header('Location: ' . ADMIN['html'] . 'editinfo.php');
		exit;
	}
	
	// Display page
	else {
		$title = $_SESSION['artpiece']->gettitle();
		if ($title)
			$title = 'Confirm final images for "' . $title . '"';
		else
			$title = 'Confirm final images';

		include 'cache.php';

		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>' . $title . '</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
	</head>
	<body>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="artinfoform" method="POST" onkeydown="return event.key != \'Enter\';">
			<h1>' . $title . '</h1>
			<h2>Thumbnail:</h2>
			<p>
				<img src="' . $_SESSION['artpiece']->getfile()->getthumbnailHTML() . '" alt="thumbnail">
			</p>
			<h2>Watermarked:</h2>
			<p>
				<img src="' . $_SESSION['artpiece']->getfile()->getwatermarkedHTML() . '" alt="watermarked">
			</p>
			<a href="' . ADMIN['html'] . 'rotate.php"><input type="button" value="Re-do"></a>
			<input type="submit" name="submit" value="Save">
		</form>
	</body>
</html>';
	}
	$_SESSION['artpiece']->getfile()->destroywatermarked();
	$_SESSION['artpiece']->getfile()->destroythumbnail();
?>
