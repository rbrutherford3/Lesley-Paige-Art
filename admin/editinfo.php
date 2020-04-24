<?php

	// Page for editing user defined information for artpiece
	// This page can either be encountered after uploading a new image file
	// or by choosing an existing artpiece to edit (ID would be in URL)
	// This page DOES NOT add items to the database, it simply saves them to artpieceinfo

	require_once '../paths.php';
	require_once 'artpiece.php';

	session_start();

	if (!isset($_SESSION['artpiece']))
		$_SESSION['artpiece'] = new artpiece();

	// If a database ID is passed in thr URL, create artpiece image with it
	if (isset($_GET['id'])) {
		if (!is_null($_SESSION['artpiece']->getid())) {
			if ($_SESSION['artpiece']->getid() != (int)$_GET['id'])
				throw new Exception('Edit.php ID is different from existing session ID, please go back to main menu and refresh');
		}
		else
			$_SESSION['artpiece']->adddb((int)$_GET['id']);
	}

	// Assume no errors to begin
	$errors = $errorsname = $errorswidth = $errorsheight = $errorsyear =
		$errorssold = $errorsprice = $errorsdescription =
		$errorsfineartamerica = $errorsetsy = false;

	// Upon submission, validate inputs and save them
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		// Grab all fields
		$name = $_POST['name'];
		$width = $_POST['width'];
		$height = $_POST['height'];
		$year = $_POST['year'];
		$sold = $_POST['sold'];
		$price = $_POST['price'];
		$description = $_POST['description'];
		$fineartamerica = $_POST['fineartamerica'];
		$etsy = $_POST['etsy'];

		// Check for input errors (including cross-reference of unique database fields)
		$errorsname = $_SESSION['artpiece']->errorsname($name);
		$errorswidth = $_SESSION['artpiece']->errorsdimension($width);
		$errorsheight = $_SESSION['artpiece']->errorsdimension($height);
		$errorsyear = $_SESSION['artpiece']->errorsyear($year);
		$errorssold = $_SESSION['artpiece']->errorssold($sold);
		$errorsprice = $_SESSION['artpiece']->errorsprice($price);
		$errorsdescription = $_SESSION['artpiece']->errorsdescription($description);
		$errorsfineartamerica = $_SESSION['artpiece']->errorsfineartamerica($fineartamerica);
		$errorsetsy = $_SESSION['artpiece']->errorsetsy($etsy);

		// See if any errors occured
		$errors = $errorsname || $errorswidth || $errorsheight ||
			$errorsyear || $errorssold || $errorsprice || $errorsdescription ||
			$errorsfineartamerica || $errorsetsy;

		// Save information as an 'artpieceinfo' object inside 'artpiecefile' object if no errors
		if (!$errors) {
			$_SESSION['artpiece']->addinfo(
				$name, $width, $height, $year, $sold, $price,
				$description, $fineartamerica, $etsy);
			header('Location: ' . ADMIN['html'] . 'confirminfo.php');
			exit;
		}

	}
	
	// Display page (and errors, if they occured)
	if (($_SERVER['REQUEST_METHOD'] != "POST") || (($_SERVER['REQUEST_METHOD'] == "POST") && $errors)) {
		
		// Fill fields with previous values (if they exist) or blank ones (if they don't)
		if ($_SERVER['REQUEST_METHOD'] != "POST") {
			if (!is_null($_SESSION['artpiece']->getinfo())) {
				$name = $_SESSION['artpiece']->getinfo()->getname();
				$width = $_SESSION['artpiece']->getinfo()->getwidth();
				$height = $_SESSION['artpiece']->getinfo()->getheight();
				$year = $_SESSION['artpiece']->getinfo()->getyear();
				$sold = $_SESSION['artpiece']->getinfo()->getsold();
				$price = $_SESSION['artpiece']->getinfo()->getprice();
				$description = $_SESSION['artpiece']->getinfo()->getdescription();
				$fineartamerica = $_SESSION['artpiece']->getinfo()->getfineartamerica();
				$etsy = $_SESSION['artpiece']->getinfo()->getetsy();
			}
			else {
				// If a file was uploaded, use its file name as an initial name
				if (!is_null($_SESSION['artpiece']->getfile()))
					$name = $_SESSION['artpiece']->getfile()->getfilename();
				else
					$name = '';
				$width = 10;
				$height = 10;
				$year = date("Y");
				$sold = 0;
				$price = '';
				$description = '';
				$fineartamerica = '';
				$etsy = '';
			}
		}

		// HTML for diabling fields
		$disabledHTML = ' disabled="disabled"';
		$enabledHTML = '';

		// Only display price if item is for sale
		// (this same logic is applied to buttom chnages below using a JavaScript command)
		if ($sold == 1)
			$pricedisabled = $enabledHTML;
		else
			$pricedisabled = $disabledHTML;

		// If there is a problem with the image file (no file or not done processing)
		// then the user must attend to that first.  Disable all fields and other buttons.
		$disabled = $enabledHTML;
		if (is_null($_SESSION['artpiece']->getfile())) {
			$disabled = $disabledHTML;
			$pricedisabled = $disabledHTML;
		}
		else {
			if ($_SESSION['artpiece']->getfile()->iscomplete())
				$errorsfile = false;
			else {
				$errorsfile = 'Image file needs finishing';
				$disabled = $disabledHTML;
				$pricedisabled = $disabledHTML;
			}
		}
		
		// Display page
		
		$title = $_SESSION['artpiece']->gettitle();
		if ($title)
			$title = 'Edit artpiece "' . $title . '"';
		else
			$title = 'Edit artpiece';

		include 'cache.php';

		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>' . $title . '</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_TEXT['html'] . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
	</head>
	<body>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="artinfoform" method="POST">
		<h1>' . $title . '</h1>
		<h2>Edit image:<h2>';
		// If there's no image file, don't try to display one
		if (is_null($_SESSION['artpiece']->getfile())) {
			echo '
			<p>
				<a href="' . ADMIN['html'] . 'upload.php"><input type="button" value="Upload new image"></a>
				<span class="error">Need an image file</span>
			</p>';
		}
		else {
			echo '
			<p>
				<img src="' . $_SESSION['artpiece']->getfile()->getthumbnailHTML() . '">
			</p>
			<p>
				<a href="' . ADMIN['html'] . 'rotate.php"><input type="button" value="Edit rotation and crop margins"></a>
				' . ($errorsfile ? '<span class="error">' . $errorsfile . '</span>' : '') . '
			</p>
			<p>
				<a href="' . ADMIN['html'] . 'upload.php"><input type="button" value="Upload new image file"></a>
			</p>';
		}
		echo '
			<h2>Enter information:</h2>
			<p>
				<label for="name">Name of piece: </label>
				<br>
				<input type="text" name="name" id="name" value="' . $name . '" size="40"' . $disabled . ' required>
				' . ($errorsname ? '<span class="error">' . $errorsname . '</span>' : '') . '
			</p>
			<p>
				<label for="year">Year: </label>
				<br>
				<input type="number" name="year" id="year" step="1" value="' . $year . '" min="1975" max="' . date("Y") . '"' . $disabled . '>
				' . ($errorsyear ? '<span class="error">' . $errorsyear . '</span>' : '') . '
			</p>
			<p>
				<label for="width">Width: </label>
				<br>
				<input type="number" name="width" id="width" step="0.25" value="' . $width . '" min="0" max="100"' . $disabled . '>
				inches
				' . ($errorswidth ? '<span class="error">' . $errorswidth . '</span>' : '') . '
			</p>
			<p>
				<label for="height">Height: </label>
				<br>
				<input type="number" name="height" id="height" step="0.25" value="' . $height . '" min="0" max="100"' . $disabled . '>
				inches
				' . ($errorsheight ? '<span class="error">' . $errorsheight . '</span>' : '') . '
			</p>
			<p>
				<input type="radio" name="sold" id="notforsale" value="0" onclick="getElementById(\'price\').disabled = true;"' . $disabled . (is_null($sold) ? ' checked' : (($sold == 0) ? ' checked' : '')) . '>
				<label for="notforsale">Not For Sale</label>
				<br>
				<input type="radio" name="sold" id="forsale" value="1" onclick="getElementById(\'price\').disabled = false;"' . $disabled . (($sold == 1) ? ' checked' : '') . '>
				<label for="forsale">For Sale</label>
				<br>
				<input type="radio" name="sold" id="sold" value="2" onclick="getElementById(\'price\').disabled= true;"' . $disabled . (($sold == 2) ? ' checked' : '') . '>
				<label for="sold">Sold</label>
			</p>
			<p>
				<label for="price">Price: </label>
				<br>
				$<input type="number" name="price" id="price" step="1" value="' . $price . '" min="0"' . $pricedisabled . '>
				' . ($errorsprice ? '<span class="error">' . $errorsprice . '</span>' : '') . '
			</p>
			<p>
				<label for="description">Description:</label>
				<br>
				<textarea name="description" id="description" style="resize: none;" rows="10" cols="40"' . $disabled . '>' . htmlspecialchars_decode(htmlspecialchars_decode($description)) . '</textarea>
				' . ($errorsdescription ? '<span class="error">' . $errorsdescription . '</span>' : '') . '
			</p>
			<p>
				<label for="etsy">etsy.com URL:</label>
				<br>
				<input type="text" name="etsy" id="etsy" value="' . $etsy . '" size="40"' . $disabled . '>
				' . ($errorsetsy ? '<span class="error">' . $errorsetsy . '</span>' : '') . '
			</p>
				<p>
				<label for="fineartamerica">fineartamerica.com URL:</label>
				<br>
				<input type="text" name="fineartamerica" id="fineartamerica" value="' . $fineartamerica . '" size="40"' . $disabled . '>
				' . ($errorsfineartamerica ? '<span class="error">' . $errorsfineartamerica . '</span>' : '') . '
			</p>
			<input type="submit" name="submit" value="Save"' . $disabled . '>
		</form>
	</body>
</html>';
	}
?>
