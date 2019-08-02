<?php
	session_start();	
	include_once('filenames.php');
/* 	if (isset($_SESSION['id'])) {
		echo '<script>alert("' . $_SESSION['id'] . '");</script>';
	}
	else {
		echo '<script>alert("NO ID SET");</script>';
	}
	die(); */
	$name = $_SESSION['name'];
	$filename = $_SESSION['filename'];
	$filepath = $_SESSION['filepath'];
	$filenamenew = $_SESSION['filenamenew'];
	$year = $_SESSION['year'];
	$width = $_SESSION['width'];
	$height = $_SESSION['height'];
	$desc = $_SESSION['desc'];
	$etsy = $_SESSION['etsy'];
	$fineartamerica = $_SESSION['fineartamerica'];
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		include_once('connection.php');
		if (isset($_SESSION['id'])) {
			$id = $_SESSION['id'];
			$sql = "UPDATE `info`
				SET 
				`name` = :name, 
				`filename` = :filename, 
				`year` = :year, 
				`width` = :width, 
				`height` = :height, 
				`description` = :desc, 
				`etsy` = :etsy, 
				`fineartamerica` = :fineartamerica 
				WHERE
				`id` = :id;";
			$stmt = $db->prepare($sql);
			$stmt->bindValue(":name", $name, PDO::PARAM_STR);
			$stmt->bindValue(":filename", $filenamenew, PDO::PARAM_STR);
			$stmt->bindValue(":year", $year, PDO::PARAM_INT);
			$stmt->bindValue(":width", $width, PDO::PARAM_STR);
			$stmt->bindValue(":height", $height, PDO::PARAM_STR);
			$stmt->bindValue(":desc", $desc, PDO::PARAM_STR);
			$stmt->bindValue(":etsy", $etsy, PDO::PARAM_STR);
			$stmt->bindValue(":fineartamerica", $fineartamerica, PDO::PARAM_STR);
			$stmt->bindValue(":id", $id, PDO::PARAM_INT);
			if(!$stmt->execute()) {
				die($db->errorInfo());
			}
		}
		else {
			$sql = "INSERT INTO `info` 
				(`name`, 
				`filename`, 
				`year`, 
				`width`, 
				`height`, 
				`description`, 
				`etsy`, 
				`fineartamerica`)
				VALUES 
				(:name, 
				:filename, 
				:year, 
				:width, 
				:height, 
				:desc, 
				:etsy, 
				:fineartamerica);";
			$stmt = $db->prepare($sql);
			$stmt->bindValue(":name", $name, PDO::PARAM_STR);
			$stmt->bindValue(":filename", $filenamenew, PDO::PARAM_STR);
			$stmt->bindValue(":year", $year, PDO::PARAM_INT);
			$stmt->bindValue(":width", $width, PDO::PARAM_STR);
			$stmt->bindValue(":height", $height, PDO::PARAM_STR);
			$stmt->bindValue(":desc", $desc, PDO::PARAM_STR);
			$stmt->bindValue(":etsy", $etsy, PDO::PARAM_STR);
			$stmt->bindValue(":fineartamerica", $fineartamerica, PDO::PARAM_STR);
			if(!$stmt->execute()) {
				die($db->errorInfo());
			}
		}
		header("Location: upload.php");
		die("SUCCESS");
	}
	else {

		echo '
<html>
	<head>
		<script type="text/javascript" src="validateform.js"></script>
	<invlude
	</head>
	<body>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="artinfoconfirm" method="POST">
			<p>
				<img src="' . $filepath . '">
			</p>
			<p>
				' . $name . '
			</p>
			<p>
				' . $year . '
			</p>
			<p>
				' . floor($width) . ' ' . getFraction($width) . ' x ' . floor($height) . ' ' . getFraction($height) . ' inches
			</p>
			<p>
				' . $desc . '
			</p>
			<p>
				etsy.com URL: 
				<br>
				' . $etsy . '
			</p>
			<p>
				fineartamerica.com URL:
				<br>
				' . $fineartamerica . '
			</p>
			<input type="submit" name="submit">
		</form>
	</body>
</html>';		
	}
	
	function getFraction($dimension) {
		$quarters = ($dimension-floor($dimension))/0.25;
		switch($quarters){
			case 1:
				return ' &frac14';
				break;
			case 2: 
				return ' &frac12';
				break;
			case 3:
				return ' &frac34';
				break;
			default:
				return '';
		}
	}

?>