<?php

	// Executing this file replaces all thumbnails and watermarked images
	// It will time out if executed on server, so it needs to be invoked on command line

	require_once 'artpiece.php';

	// Remove existing image files
	$results = glob(WATERMARKED['sys'] . '*');
	foreach ($results as $item)
		unlink($item);
	$results = glob(THUMBNAILS['sys'] . '*');
	foreach ($results as $item)
		unlink($item);

	// Get all artpieces from database
	$db = database::connect(); $sql = 'SELECT `id`, `name` FROM `info`;';
	$stmt = $db->prepare($sql);
	$rows = array();
	if ($stmt->execute()) {
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		    $rows[] = $row;
		}
	}
	$db = NULL;

	// Cycle through each artpiece and create a new image file
	foreach ($rows as $row) {
		$name = $row['name'];
		$id = $row['id'];
		echo $name . ":\n";
		$ap = new artpiece();
		$ap->adddb($id);
		echo "-watermarked...\n";
		$ap->getfile()->createwatermarked();
		$ap->getfile()->writewatermarked();
		$ap->getfile()->destroywatermarked();
		echo "-thumbnail...\n";
		$ap->getfile()->createthumbnail();
		$ap->getfile()->writethumbnail();
		$ap->getfile()->destroythumbnail();
		$ap = NULL;
	}
	echo "Done!\n";

?>
