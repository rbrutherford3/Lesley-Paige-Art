<?php
	function createfilename($filename) {
		return strtolower(preg_replace('/[^A-Za-z0-9]/', '', $filename)); // Removes special chars.
	}
		
	function getfraction($dimension) {
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

	function scaleimage($w, $h) {
		if ($w > $h) {
			if ($w > 500) {
				$dispW = 500;
				$dispH = (int)(500/$w*$h);
			}
			else {
				$dispW = $w;
				$dispH = $y;
			}
		}
		else {
			if ($h > 500) {
				$dispH = 500;
				$dispW = (int)(500/$h*$w);
			}
			else {
				$dispW = $w;
				$dispH = $h;
			}
		}
		return array($dispW, $dispH);
	}
	
	function movefile($frompath, $topath, $overwrite) {
		if (!file_exists($frompath)) {
			die('File not found!: ' . $frompath);
		}
		if (file_exists($topath)) {
			if ($overwrite) {
				removefile($topath);
			}
			else {
				die('File exists at: ' . $topath);
			}
		}
		rename($frompath, $topath);
	}
	
	function removefile($path) {
		if (!unlink($path)) {
			die('Attempted to delete file but it was not found, or deletion failed');
		}
	}
	
	function removefolder($path, $recursive) {
		$deletefolder = true;
		$items = scandir($path);
		foreach($items as $item) {
			$itempath  = $path . DIRECTORY_SEPARATOR . $item;
			//echo $ITEM;
			if (($item != '.') && ($item != '..')) {
				if (is_dir($itempath)) {
					//echo "D: " . $itempath . "<br>";
					if ($recursive) {
						removefolder($itempath, true);
					}
					else {
						// cannot delete a non-empty folder, and cannot delete a sub-folder without recursion
						$deletefolder = false;
					}
				}
				elseif (is_file($itempath)) {
					//echo "F: " . $itempath . "<br>";
					removefile($itempath);
				}
			}
		}
		if ($deletefolder) {
			if (!rmdir($path)) {
				die('Attempted to delete folder but it was not found, was non-empty, or deletion failed');
			}
		}
	}
	
	function getdatabase($id, $db, $page) {
		$_SESSION['database']['id'] = $id;
		$sql = "SELECT * FROM `info` WHERE `id` = :id;";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(":id", $id, PDO::PARAM_INT);
		if(!$stmt->execute()) {
			die("Error executing specific query: " . $db->errorInfo());
		}
		if ($stmt->rowCount() > 0) {
			$row = $stmt->fetch();
		}
		else {
			die("No database entry with id=" . $id);
		}
		$_SESSION['database']['sequence'] = $row['sequence'];
		$_SESSION['database']['name'] = $row['name'];
		$_SESSION['database']['filename'] = $row['filename'];
		$_SESSION['database']['extoriginal'] = $row['extoriginal'];
		$_SESSION['database']['width'] = $row['width'];
		$_SESSION['database']['height'] = $row['height'];
		$_SESSION['database']['year'] = $row['year'];
		$_SESSION['database']['description'] = $row['description'];
		$_SESSION['database']['fineartamerica'] = $row['fineartamerica'];
		$_SESSION['database']['etsy'] = $row['etsy'];
	}
?>