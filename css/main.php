<?php
	// Dynamically deliver css (get colors from database as HSL and
	// conveet to hex RGB for display.  Secondary color is optional
	// and provides a color scheme for a radial gradient

	header("Content-type: text/css; charset: UTF-8");

	require_once '../database.php';
	require_once '../paths.php';
	require_once HSL2RGB['sys'];

	$db = database::connect();
	$sql = 'SELECT `hue`, `saturation`, `primarylightness`, `secondarylightness`, `backgroundlightness` FROM `style` ORDER BY `id` DESC LIMIT 1;';
	$stmt = $db->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();
	$db = NULL;

	$primaryRGB = HSLtoRGB($row['hue']/360, $row['saturation']/100, $row['primarylightness']/100);
	$primary = RGBtoHEX((int)$primaryRGB['r'], (int)$primaryRGB['g'], (int)$primaryRGB['b']);

	$gradient = !is_null($row['secondarylightness']);
	if ($gradient) {
		$secondaryRGB = HSLtoRGB($row['hue']/360, $row['saturation']/100, $row['secondarylightness']/100);
		$secondary = RGBtoHEX((int)$secondaryRGB['r'], (int)$secondaryRGB['g'], (int)$secondaryRGB['b']);
		$both = $secondary . ', ' . $primary;
	}

	$backgroundRGB = HSLtoRGB(0, 0, $row['backgroundlightness']/100);
	$background = RGBtoHEX((int)$backgroundRGB['r'], (int)$backgroundRGB['g'], (int)$backgroundRGB['b']);

?>

bgcolor {
	background-color: <?php echo $background; ?>;
}

body {
	background-color: <?php echo $background; ?>;
	margin: 0px;
	padding: 0px;
	text-align: center;
}
<?php
	echo '
.carouselpage {';
	if ($gradient) {
		echo '
	background: ' . $primary . ';
	background: -webkit-radial-gradient(' . $both . ');
	background: -o-radial-gradient(' . $both . ');
	background: -moz-radial-gradient(' . $both . ');
	background: radial-gradient(' . $both . ');';
	}
	else {
		echo '
	background-color: ' . $primary . ';';
	}
	echo '
	overflow: auto;
}

.page {';
	if ($gradient) {
		echo '
	background: ' . $primary . '; /* For browsers that do not support gradients */
	background: -webkit-radial-gradient(' . $both . '); /* Safari 5.1 to 6.0 */
	background: -o-radial-gradient(' . $both . '); /* For Opera 11.6 to 12.0 */
	background: -moz-radial-gradient(' . $both . '); /* For Firefox 3.6 to 15 */
	background: radial-gradient(' . $both . '); /* Standard syntax */';
	}
	else {
		echo '
	background-color: ' . $primary . ';';
	}
	echo '
	padding: 20px;
	text-align: left;
}'; ?>

.header {
	background-color: <?php echo $primary; ?>;
	margin-bottom: 20px;
	padding-top: 20px;
	padding-bottom: 10px;
	text-align: center;
}

.arttable {
	width: 270px;
	height: 330px;
	position: relative;
	display: inline-block;
}

.artrow {
	width: 270px;
	height: 270px;
	display: table-row;
	float: middle;
}

.artcell {
	width: 270px;
	height: 270px;
	display: flex;
	align-items: center;
	justify-content: center;
}

.labelrow {
	width: 270px;
	height: 60px;
	display: table-row;
	float: middle;
}

.labelcell {
	width: 270px;
	height: 60px;
	display: flex;
	align-items: baseline;
	justify-content: center;
	text-align: center;
	flex-wrap: wrap;
}

.carouselContainer {
	margin-top: 20px;
	height: 330px;
	display: flex;
	align-items: center;
	justify-content: center;
}

img.fullsize {
	margin: 0px;
	padding: 0px;
	border: 0px;
	max-width: 100%;
	width: auto;
	height: auto;
}

img.header {
	margin-left: 20px;
	vertical-align: middle;
	display: inline-block;
}

img.header-mobile {
	text-align: center;
	vertical-align: middle;
}

img.art {
	display: block;
	border: 5px solid #000000;
	box-shadow: 5px 5px rgba(0, 0, 0, 0.3);
	margin: auto;
}

img.photo {
	border:5px solid #000000;
	box-shadow: 10px 10px rgba(0, 0, 0 0.3);
	margin-bottom: 20px;
}
