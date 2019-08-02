<?php
	session_start();
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$_SESSION['name'] = $_POST['name'];
		$_SESSION['filenamenew'] = createfilename($_SESSION['name']);
		$_SESSION['year'] = $_POST['year'];
		$_SESSION['width'] = $_POST['width'];
		$_SESSION['height'] = $_POST['height'];
		$_SESSION['desc'] = ($_POST['desc'] == '' ? null : $_POST['desc']);
		$_SESSION['etsy'] = ($_POST['etsy'] == '' ? null : $_POST['etsy']);
		$_SESSION['fineartamerica'] = ($_POST['fineartamerica'] == '' ? null : $_POST['fineartamerica']);
		header("Location: confirmartinfo.php");
		die();
	}
	else {
		include_once('filenames.php');
		include_once('connection.php');
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$_SESSION['id'] = $id;
			$sql = "SELECT * FROM `info` WHERE `id` = :id";
			$stmt = $db->prepare($sql);
			$stmt->bindValue(":id", $id, PDO::PARAM_INT);
			if(!$stmt->execute()) {
				die($db->errorInfo());
			}
			if ($stmt->rowCount() > 0) {
				$row = $stmt->fetch();
			}
			else {
				die("No entry exists with that ID");
			}
			$name = $row['name'];
			$_SESSION['filename'] = $row['filename']; // need to hold the original filename in case it changes and a move is needed
			$thumbnail = '/img/thumbnails/' . $row['filename'] . '.' . $extresized;
			$_SESSION['thumbnail'] = $thumbnail;
			$year = $row['year'];
			$width = $row['width'];
			$height = $row['height'];
			$desc = $row['description'];
			$etsy = $row['etsy'];
			$fineartamerica = $row['fineartamerica'];
		}
		else {
			if (isset($_SESSION['filename'])) {
				unset($_SESSION['id']); // necessary to avoid overwriting an existing record
				$filename = $_SESSION['filename'];
				$name = $filename;
				$thumbnail = 'upload/' . $filename . '/' . $filenamethumbnail;
				$_SESSION['thumbnail'] = $thumbnail;
				$year = date("Y");
				$width = 10;
				$height = 10;
				$desc = "";
				$etsy = "";
				$fineartamerica = "";
			}
			else {
				die("No ID or filename provided");
			}
		}
		echo '
<html>
	<head>
		<script type="text/javascript" src="validateform.js"></script>
	<invlude
	</head>
	<body>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="artinfoform" method="POST" onsubmit="return validateform();" onkeydown="return event.key != \'Enter\';">
			<p>
				<img src="' . $thumbnail . '">
			</p>
			<p>
				<label for="name">Name of piece: </label>
				<br>
				<input type="text" name="name" id="name" value="' . $name . '" size="40">
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
	</body>
</html>';
	}

	function createfilename($filename) {
		return preg_replace('/[^A-Za-z0-9]/', '', $filename); // Removes special chars.
	}
?>