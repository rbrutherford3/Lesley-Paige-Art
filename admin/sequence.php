<?php
include_once 'reset.php'; // destroy session for good measure
require_once '../paths.php';
require_once 'connection.php';
require_once 'functions.php';

// "Sequence" form processing
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Only do anything if there were any changes
	if ($_POST['anychanged']) {

		// Grab counts
		$numpublished = $_POST['numpublished'];
		$numunpublished = $_POST['numunpublished'];
		$numentries = $numpublished + $numunpublished;

		// Because this process may require many update queries, perform them all at once at the end
		$db->beginTransaction();

		// Get all available ids
		$sqlids = "SELECT `id` FROM `info`;";
		$stmtids = $db->prepare($sqlids);
		if (!$stmtids->execute()) {
			die('Error executing id collection query: ' . $db->errorInfo());
		}
		$rowsids = $stmtids->fetchAll();

		// Because `sequence` is requires unique entries, those values must
		// first be set to null to avoid duplicate entry errors
		foreach ($rowsids as $rowid) {
			$id = $rowid['id'];
			$changed = $_POST['changed'][$id];
			if ($_POST['changed'][$id]) {
				$sqlnull = "UPDATE `info` SET `sequence` = NULL WHERE `id` = :id;";
				$stmtnull = $db->prepare($sqlnull);
				$stmtnull->bindValue(":id", $id, PDO::PARAM_INT);
				if (!$stmtnull->execute()) {
					die('Error executing query: ' . $db->errorInfo());
				}
			}
		}

		// Update all affected art pieces with their new sequence, or lack thereof
		foreach ($rowsids as $rowid) {
			$id = $rowid['id'];
			$changed = $_POST['changed'][$id];
			$sequence = $_POST['sequence'][$id];
			if ($_POST['changed'][$id]) {
				if (!empty($sequence)) {
					$sqlsequence = "UPDATE `info` SET `sequence` = :sequence WHERE `id` = :id;";
					$stmtsequence = $db->prepare($sqlsequence);
					$stmtsequence->bindValue(":sequence", $sequence, PDO::PARAM_INT);
					$stmtsequence->bindValue(":id", $id, PDO::PARAM_INT);
					if (!$stmtsequence->execute()) {
						die('Error executing query: ' . $db->errorInfo());
					}
				}
			}
		}

		// Perform previous queries, all at once
		$db->commit();
	}

	header('Location: ' . ADMIN['html'] . 'sequence.php');
	die();
}

// "Sequence" form HTML
else {

	// Grab information for all pieces of artwork
	$sql = "SELECT `id`, `sequence`, `name`, `filename` FROM `info` ORDER BY `sequence` IS NULL, `sequence` ASC, `name` ASC;";
	$stmt = $db->prepare($sql);
	if(!$stmt->execute()) {
		die("Error executing general query: " . $db->errorInfo());
	}
	$numentries = $stmt->rowCount();
	if ($stmt->rowCount() > 0) {
		$rows = $stmt->fetchAll();
	}
	else {
		echo '<script>alert("Database is empty");</script>';
	}

	// Tally the number of published vs. unpublished
	$numpublished = 0;
	$numunpublished = 0;
	foreach ($rows as $row) {
		if (is_null($row['sequence'])) {
			$numunpublished++;
		}
		else {
			$numpublished++;
		}
	}

	// Validate the count
	if ($numpublished + $numunpublished <> $numentries) {
		die("There was an error with the entry count");
	}

	// Header info and beginning of form
	echo '<!DOCTYPE html>
<html>
	<head>
		<title>Edit art pieces</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_MAIN['html'] . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_TEXT['html'] . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
		<script type="text/javascript" src="' .  ADMIN['html'] . 'sequence.js"></script>
	</head>
	<body>
		<div class="page">
			<h1>Edit artwork (click a title to edit an individual piece):</h1>
			<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="sequence" method="POST">
				<input type="hidden" name="numpublished" id="numpublished" value="' . $numpublished . '">
				<input type="hidden" name="numunpublished" id="numunpublished" value="' . $numunpublished . '">
				<input type="hidden" name="anychanged" id="anychanged" value="0">';

	$count=0;
	$invisibleHTML = ' style="display: none;"';
	$visibleHTML = ' style="display: block;"';
	//$visibleHTML = '';

	// Still need a "published" container even if there were no published items
	if ($numpublished == 0) {
		echo '
				<div id="published">
				<h2>
					Published pieces:
				</h2>
				</div>
				<hr>';
	}

	// Display each piece of art, along with appropriate buttons
	foreach ($rows as $row) {
		$count++;

		// Set initial button visibility
		if (($count == 1) || ($count > $numpublished)) {
			$upbuttonvisibility = $invisibleHTML;
		}
		else {
			$upbuttonvisibility = $visibleHTML;
		}
		if ($count >= $numpublished) {
			$downbuttonvisibility = $invisibleHTML;
		}
		else {
			$downbuttonvisibility = $visibleHTML;
		}
		if ($count <= $numpublished) {
			$publishbuttonvisibility = $invisibleHTML;
			$unpublishbuttonvisibility = $visibleHTML;
		}
		else {
			$publishbuttonvisibility = $visibleHTML;
			$unpublishbuttonvisibility = $invisibleHTML;
		}

		// Save information from database and create thumbnail path
		$id = $row['id'];
		$name = $row['name'];
		$sequence = $row['sequence'];
		$filename = $row['filename'];
		$thumbnail = THUMBNAILS['sys'] . $filename . '.' . EXT;
		$thumbnailHTML = THUMBNAILS['html'] . $filename . '.' . EXT;

		// Create "published"/"unpublished" containers if first of each type
		if (($count == 1) && ($numpublished > 0)) {
			echo '
				<div id="published">
				<h2>
					Published pieces:
				</h2>';
		}
		if (($count == $numpublished + 1) && ($numunpublished > 0)) {
			echo '
				<div id="unpublished">
				<h2>
					Unpublished pieces:
				</h2>';
		}

		// It is possible that there are gaps in the sequence which should be corrected
		if (($count <= $numpublished) && ($count <> $sequence)) {
			$mismatch = true;
		}
		else {
			$mismatch = false;
		}

		// Display artpiece and buttons and link buttons to javascript actions.
		// Note that the hidden fields contain the data to be submitted.
		echo '
					<div class="item" id="' . $id . '">
						<input type="hidden" name="sequence[' . $id . ']" id="sequence' . $id . '" value="' . ($count > $numpublished ? '' : $count ) . '">
						<input type="hidden" name="changed[' . $id . ']" id="changed' . $id . '" value="' . ($mismatch ? '1' : '0') . '">
						<div class="thumbnail">
							<a href="' . ADMIN['html'] . 'artinfo.php?id=' . $id . '">
								<div>
									<img class="thumbnail" src="' . $thumbnailHTML . '">
								</div>
								<div>
									' . $name . '
								</div>
							</a>
						</div>
						<div class="buttons">
							<div class="button">
								<input type="button" id="up' . $id . '" onclick="swaporder(this,true);" class="updown" value="MOVE UP"' . $upbuttonvisibility . '>
							</div>
							<div class="button">
								<input type="button" id="publish' . $id . '" onclick="swappublished(this, true);" value="Publish" ' . $publishbuttonvisibility . '>
								<input type="button" id="unpublish' . $id . '" onclick="swappublished(this, false);" value="Unpublish" ' . $unpublishbuttonvisibility . '>
							</div>
							<div class="button">
								<input type="button" id="down' . $id . '" onclick="swaporder(this,false);" class="updown" value="MOVE DOWN"' . $downbuttonvisibility . '>
							</div>
						</div>
					</div>';

		// Close "published"/"unpublished" containers if last of each type
		if (($count == $numpublished) && ($numpublished > 0)) {
			echo '
				</div>
				<hr>';
		}
		if (($count == $numpublished + $numunpublished) && ($numunpublished > 0)) {
			echo '
				</div>';
		}
	}

	// Still need an "unpublished" container even if there were no unpublished items
	if ($numunpublished == 0) {
		echo '
				<div id="unpublished">
					<h2>
						Unpublished pieces:
					</h2>
				</div>';
	}

	// Float the submit button so it is always visible
	echo '
				<div class="float">
					<a href="' . ADMIN['html'] . 'upload.php"><input type="button" value="Add new artpiece"></a>
					<br>
					<input type="submit" id="submit" value="Submit unsaved changes" ' . $invisibleHTML . '>
				</div>
			</form>
		</div>
	</body>
</html>';
}

?>
