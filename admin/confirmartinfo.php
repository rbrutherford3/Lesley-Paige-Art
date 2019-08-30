<?php
	session_start();	
	include_once('filenames.php');
	include_once('connection.php');
	include_once('functions.php');
/* 	if (isset($_SESSION['id'])) {
		echo '<script>alert("' . $_SESSION['id'] . '");</script>';
	}
	else {
		echo '<script>alert("NO ID SET");</script>';
	}
	die(); */
	$name = $_SESSION['artinfo']['name'];
	$filename = $_SESSION['artinfo']['filename'];
	$extoriginal = $_SESSION['artinfo']['extoriginal'];
	$year = $_SESSION['artinfo']['year'];
	$width = $_SESSION['artinfo']['width'];
	$height = $_SESSION['artinfo']['height'];
	$desc = $_SESSION['artinfo']['desc'];
	$etsy = $_SESSION['artinfo']['etsy'];
	$fineartamerica = $_SESSION['artinfo']['fineartamerica'];
	$thumbnailHTML = $_SESSION['thumbnailHTML'];
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_SESSION['database'])) {
			$id = $_SESSION['database']['id'];
			$sql = "UPDATE `info`
				SET 
				`name` = :name, 
				`filename` = :filename, 
				`extoriginal` = :extoriginal, 
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
			$stmt->bindValue(":filename", $filename, PDO::PARAM_STR);
			$stmt->bindValue(":extoriginal", $extoriginal, PDO::PARAM_STR);
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
				`extoriginal`, 
				`year`, 
				`width`, 
				`height`, 
				`description`, 
				`etsy`, 
				`fineartamerica`)
				VALUES 
				(:name, 
				:filename, 
				:extoriginal, 
				:year, 
				:width, 
				:height, 
				:desc, 
				:etsy, 
				:fineartamerica);";
			$stmt = $db->prepare($sql);
			$stmt->bindValue(":name", $name, PDO::PARAM_STR);
			$stmt->bindValue(":filename", $filename, PDO::PARAM_STR);
			$stmt->bindValue(":extoriginal", $extoriginal, PDO::PARAM_STR);
			$stmt->bindValue(":year", $year, PDO::PARAM_INT);
			$stmt->bindValue(":width", $width, PDO::PARAM_STR);
			$stmt->bindValue(":height", $height, PDO::PARAM_STR);
			$stmt->bindValue(":desc", $desc, PDO::PARAM_STR);
			$stmt->bindValue(":etsy", $etsy, PDO::PARAM_STR);
			$stmt->bindValue(":fineartamerica", $fineartamerica, PDO::PARAM_STR);
			if(!$stmt->execute()) {
				die($db->errorInfo());
			}
			
			$sqlid = "SELECT LAST_INSERT_ID() AS `lastid`";
			$stmtid = $db->prepare($sqlid);
			if(!$stmtid->execute()) {
				die($db->errorInfo());
			}
			$rowid = $stmtid->fetch();
			$_SESSION['newid'] = $rowid['lastid'];
		}
		header("Location: movefiles.php");
		die();
	}
	else {
		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>Confirm information for "' . $_SESSION['artinfo']['name'] . '"</title>
		<link rel="stylesheet" type="text/css" href="/css/main.css">
		<link rel="stylesheet" type="text/css" href="/css/text.css">		
		<link rel="stylesheet" type="text/css" href="admin.css">		
		<script type="text/javascript" src="validateform.js"></script>
	</head>
	<body>
		<div class="page">
		<h1>Confirm information:</h1>
			<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="artinfoconfirm" method="POST">
				<p>
					<img src="' . $thumbnailHTML . '">
				</p>
				<p>
					' . $name . '
				</p>
				<p>
					' . $year . '
				</p>
				<p>
					' . floor($width) . ' ' . getfraction($width) . ' x ' . floor($height) . ' ' . getfraction($height) . ' inches
				</p>
				<p>
					' . $desc . '
				</p>
				<p>
					<b>
						etsy.com URL:
					</b>
					<br>
					' . (empty($etsy) ? '***none***' : $etsy) . '
				</p>
				<p>
					<b>
						fineartamerica.com URL:
					</b>
					<br>
					' . (empty($fineartamerica) ? '***none***' : $fineartamerica) . '
				</p>
				<input type="submit" name="submit">
			</form>
		</div>
	</body>
</html>';		
	}

?>