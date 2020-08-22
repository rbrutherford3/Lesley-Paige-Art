<?php
	// Dynamically deliver text css page.  Fonts, font backups, and
	// font families are stored in database.  Note that the custom
	// font "Lesley" has a smaller appearance and thus is resized

	header("Content-type: text/css; charset: UTF-8");

	require_once '../database.php';

	$db = database::connect();
	$sql = 'SELECT
		pf.`name` AS `primaryfont`,
		pfb.`name` AS `primaryfontbackup`,
		pff.`name` AS `primaryfontfamily`,
		sf.`name` AS `secondaryfont`,
		sfb.`name` AS `secondaryfontbackup`,
		sff.`name` AS `secondaryfontfamily`
		FROM `style` s
		LEFT JOIN `fonts` pf
		ON s.`primaryfont`=pf.`id`
		LEFT JOIN `fonts` pfb
		ON pf.`backup`=pfb.`id`
		LEFT JOIN `fontfamilies` pff
		ON pf.`family`=pff.`id`
		LEFT JOIN `fonts` sf
		ON s.`secondaryfont`=sf.`id`
		LEFT JOIN `fonts` sfb
		ON sf.`backup`=sfb.`id`
		LEFT JOIN `fontfamilies` sff
		ON sf.`family`=sff.`id`
		ORDER BY s.`id` DESC LIMIT 1;';
	$stmt = $db->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();
	$db = NULL;

	$primaryfont = $row['primaryfont'];
	$secondaryfont = $row['secondaryfont'];
	$primaryfontcss = $row['primaryfont'] . ', ' . (is_null($row['primaryfontbackup']) ? '' : $row['primaryfontbackup'] . ', ') . $row['primaryfontfamily'];
	$secondaryfontcss = $row['secondaryfont'] . ', ' . (is_null($row['secondaryfontbackup']) ? '' : $row['secondaryfontbackup'] . ', ') . $row['secondaryfontfamily'];
?>

h1 {
	font-family: <?php echo $primaryfontcss; ?>;
	font-size: <?php echo ($primaryfont == 'Lesley' ? '2.5em' : '2em'); ?>;
	color: black;
	margin: 0px;
	padding: 5px;
	word-spacing: -0.1em
}

h2 {
	font-family: <?php echo $primaryfontcss; ?>;
	font-size: <?php echo ($primaryfont == 'Lesley' ? '2em' : '1.5em'); ?>;
	color: black;
	word-spacing: -0.1em
}

.buttontext {
	font-family: <?php echo $primaryfontcss; ?>;
	font-size: <?php echo ($primaryfont == 'Lesley' ? '1.5em' : '1em'); ?>;
	font-weight: bold;
	color: black;
	line-height: 25px;
}

p {
	font-family: <?php echo $secondaryfontcss; ?>;
	font-size: <?php echo ($secondaryfont == 'Lesley' ? '1.5em' : '1em'); ?>;
	color: black;
	display: run-in;
}

a:link, a:visited, a:hover, a:active {
	color: black;
	text-decoration: none;
}
