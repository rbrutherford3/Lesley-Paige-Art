<?php
	session_start();
	include_once('filenames.php');
	include_once('connection.php');
	include_once('functions.php');
	if (isset($_GET['id'])) {
		$new = false;
		$id = $_GET['id'];
	}
	elseif (isset($_SESSION['database'])) {
		$new = false;
		$id = $_SESSION['database']['id'];
	}
	else {
		$new = true;
		$id = null;
	}
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if ($_POST['name'] != $_SESSION['artinfo']['nameold']) {
			$_SESSION['artinfo']['namechanged'] = true;
		}
		$_SESSION['artinfo']['name'] = $_POST['name'];
		$_SESSION['artinfo']['filename'] = createfilename($_SESSION['artinfo']['name']);
		$_SESSION['artinfo']['extoriginal'] = $_POST['extoriginal'];
		$_SESSION['artinfo']['year'] = $_POST['year'];
		$_SESSION['artinfo']['width'] = $_POST['width'];
		$_SESSION['artinfo']['height'] = $_POST['height'];
		$_SESSION['artinfo']['desc'] = ($_POST['desc'] == '' ? null : $_POST['desc']);
		$_SESSION['artinfo']['etsy'] = ($_POST['etsy'] == '' ? null : $_POST['etsy']);
		$_SESSION['artinfo']['fineartamerica'] = ($_POST['fineartamerica'] == '' ? null : $_POST['fineartamerica']);
		if (isset($_POST['submit'])) {
			checkunique($db, 'name', $_SESSION['artinfo']['name'], $id);
			checkunique($db, 'filename', $_SESSION['artinfo']['filename'], $id);
			checkunique($db, 'etsy', $_SESSION['artinfo']['etsy'], $id);
			checkunique($db, 'fineartamerica', $_SESSION['artinfo']['fineartamerica'], $id);
			header("Location: confirmartinfo.php");
		}
		elseif (isset($_POST['image_x'])) {
			// SAVE SESSION VARIABLES, IF NECESSARY
			header("Location: upload.php");
			die();
		}
		die();
	}
	else {
		// SET UP USER-DEFINED FIELDS:
		// if information previously entered, then repopulate fields with previous entries
		if (isset($_SESSION['artinfo']['name'])) {
			if (isset($_SESSION['infoerror'])) {
				echo '<script>alert("' . $_SESSION['infoerror'] . '");</script>';
				unset($_SESSION['infoerror']);
			}
			if ($_SESSION['artinfo']['namechanged']) {
				$name = $_SESSION['artinfo']['name'];
			}
			else {
				if ($new) {
					$_SESSION['artinfo']['nameold'] = $_SESSION['upload']['originalname'];
				}
				$name = $_SESSION['artinfo']['nameold'];
			}
			$year = $_SESSION['artinfo']['year'];
			$width = $_SESSION['artinfo']['width'];
			$height = $_SESSION['artinfo']['height'];
			$desc = $_SESSION['artinfo']['desc'];
			$etsy = $_SESSION['artinfo']['etsy'];
			$fineartamerica = $_SESSION['artinfo']['fineartamerica'];
		}
		else {
			if ($new) {
				if (isset($_SESSION['upload'])) {
					unset($_SESSION['database']); // necessary to avoid an overwrite disaster from poor page flow
					$name = $_SESSION['upload']['originalname'];
					$filename = $_SESSION['upload']['dirname'];
					$year = date("Y");
					$width = 10;
					$height = 10;
					$desc = "";
					$etsy = "";
					$fineartamerica = "";
				}
				else {
					session_destroy();
					die("No ID or filename provided");
				}
			}
			else {
				getdatabase($id, $db, "upload.php");
				$name = $_SESSION['database']['name'];
				$year = $_SESSION['database']['year'];
				$width = $_SESSION['database']['width'];
				$height = $_SESSION['database']['height'];
				$desc = $_SESSION['database']['description'];
				$etsy = $_SESSION['database']['etsy'];
				$fineartamerica = $_SESSION['database']['fineartamerica'];
			}
			$_SESSION['artinfo']['nameold'] = $name;
			$_SESSION['artinfo']['namechanged'] = false;
		}
		//SET UP FILE INFORMATION:
		if (isset($_SESSION['upload'])) {
			$extoriginal = $_SESSION['upload']['extoriginal'];
			$thumbnailHTML = 'upload/' . $_SESSION['upload']['dirname'] . '/' . $filenameextthumbnail;
			$_SESSION['thumbnailHTML'] = $thumbnailHTML;
		}
		elseif (isset($_SESSION['database'])) {
			$extoriginal = $_SESSION['database']['extoriginal'];
			$thumbnailHTML = '/img/thumbnails/' . $_SESSION['database']['filename'] . '.' . $ext;
			$_SESSION['thumbnailHTML'] = $thumbnailHTML;
		}
		else {
			session_destroy();
			die("Nothing uploaded and no existing piece referenced, please restart process");
		}
		if (isset($_SESSION['artinfo']['name'])) {
			$title = $_SESSION['artinfo']['name'];
		}
		elseif (isset($_SESSION['database'])) {
			$title = $_SESSION['database']['name'];
		}
		elseif (isset($_SESSION['upload'])) {
			$title = $_SESSION['upload']['originalname'];
		}
		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>Enter information' . (isset($title) ? ' for ' . $title : '') . '</title>
		<link rel="stylesheet" type="text/css" href="/css/main.css">
		<link rel="stylesheet" type="text/css" href="/css/text.css">		
		<link rel="stylesheet" type="text/css" href="admin.css">
		<script type="text/javascript" src="validateform.js"></script>
	</head>
	<body>
		<div class="page">
			<h1>Enter information for piece:</h1>
			<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="artinfoform" method="POST" onsubmit="return validateform();" onkeydown="return event.key != \'Enter\';">
				<p>
					<label for="image">Click image to replace and/or edit</label>
					<br>
					<input type="image" name="image" src="' . htmlspecialchars($thumbnailHTML) . '">
				</p>
				<p>
					<label for="name">Name of piece: </label>
					<br>
					<input type="text" name="name" id="name" value="' . $name . '" size="40" required>
					<input type="hidden" name="extoriginal" id="extoriginal" value="' . $extoriginal . '">
				</p>
				<p>
					<label for="year">Year: </label>
					<br>
					<input type="number" name="year" id="year" step="1" value="' . $year . '" min="1975" max="' . date("Y") . '">
				</p>
				<p>
					<label for="width">Width: </label>
					<br>
					<input type="number" name="width" id="width" step="0.25" value="' . $width . '" min="0" max="100">
					inches
				</p>
				<p>
					<label for="height">Height: </label>
					<br>
					<input type="number" name="height" id="height" step="0.25" value="' . $height . '" min="0" max="100">
					inches
				</p>
				<p>
					<label for="desc">Description:</label>
					<br>
					<textarea name="desc" id="desc" style="resize: none;" rows="10" cols="40">' . $desc . '</textarea>
				</p>
				<p>
					<label for="etsy">etsy.com URL:</label>
					<br>
					<input type="text" name="etsy" id="etsy" value="' . $etsy . '" size="40">
				</p>
				<p>
					<label for="etsy">fineartamerica.com URL:</label>
					<br>
					<input type="text" name="fineartamerica" id="fineartamerica" value="' . $fineartamerica . '" size="40">
				</p>
				<input type="submit" name="submit">
			</form>
		</div>
	</body>
</html>';
	}
	
	function checkunique($db, $fieldname, $value, $id) {
		if (is_null($id)) {
			$sql = "SELECT `id` FROM `info` WHERE `" . $fieldname . "` = :value;";			
		}
		else {
			$sql = "SELECT `id` FROM `info` WHERE `" . $fieldname . "` = :value AND id <> :id;";
		}
		$stmt = $db->prepare($sql);
		$stmt->bindValue(":value", $value, PDO::PARAM_STR);
		if (!is_null($id)) {
			$stmt->bindValue(":id", $id, PDO::PARAM_INT);
		}
		if(!$stmt->execute()) {
			die($db->errorInfo());
		}
		else {
			if ($stmt->rowCount() > 0) {
				$_SESSION['infoerror'] = $value . ' already exists as an entry for another artpiece, please try again.';
				if (is_null($id)) {	// to maintain URL only, not necessary if $_SESSION['id'] is set
					header("Location: artinfo.php");
				}
				else {
					header("Location: artinfo.php?id=" . $id);
				}
				die();
			}
		}
	}
?>