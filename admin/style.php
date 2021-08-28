<?php

	// Page to change the style of the page (colors and fonts)
	// Style is saved in `style` table in database, using hue,
	// saturation, and three lightness values (primary, secondary,
	// and background).  Primary and secondary (optional) use the
	// same hue and saturation values, background has no saturation
	// and thus hue is irrelevant.  main.php uses the same hsl2rgb
	// function to produce hexadecimal rgb colors in css.  If a secondary
	// lightness is specified, a radial gradient is used instead of
	// a solid color.  Valid fonts and font families (including custom
	// Lesley font) are stored in the `font` and `fontfamily` tables,
	// where each font has a specified backup and family.  All styles
	// are saved, most recent is used, and styles can be recalled but
	// not deleted.  The javascript file is used to update live previews
	// of colors and fonts before saving.

	require_once 'database.php';
	require_once '../paths.php';
	require_once HSL2RGB['sys'];

	// If saving...
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		// Grab values
		$hue = $_POST['hue'];
		$saturation = $_POST['saturation'];
		$primarylightness = $_POST['primarylightness'];
		// Secondary lightness only neceasary if gradient is specifisd
		if (isset($_POST['gradient']))
			$secondarylightness = $_POST['secondarylightness'];
		else
			$secondarylightness = NULL;
		$backgroundlightness = $_POST['backgroundlightness'];
		$primaryfont = $_POST['primaryfont'];
		$secondaryfont = $_POST['secondaryfont'];

		// Post specified values to database
		$db = database::connect();
		$sql = 'INSERT INTO `style` (
			`hue`,
			`saturation`,
			`primarylightness`,
			`secondarylightness`,
			`backgroundlightness`,
			`primaryfont`,
			`secondaryfont`
			) VALUES (
			:hue,
			:saturation,
			:primarylightness,
			:secondarylightness,
			:backgroundlightness,
			:primaryfont,
			:secondaryfont);';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':hue', $hue, PDO::PARAM_INT);
		$stmt->bindValue(':saturation', $saturation, PDO::PARAM_INT);
		$stmt->bindValue(':primarylightness', $primarylightness, PDO::PARAM_STR);
		$stmt->bindValue(':secondarylightness', $secondarylightness, PDO::PARAM_STR);
		$stmt->bindValue(':backgroundlightness', $backgroundlightness, PDO::PARAM_STR);
		$stmt->bindValue(':primaryfont', $primaryfont, PDO::PARAM_INT);
		$stmt->bindValue(':secondaryfont', $secondaryfont, PDO::PARAM_INT);
		$stmt->execute();
		$db = NULL;

		// Return to main menu
		header('Location: ' . ADMIN['html'] . 'index.php');
	}
	if (($_SERVER['REQUEST_METHOD'] != "POST") || (($_SERVER['REQUEST_METHOD'] != "POST") && (!empty($errors)))) {

		// Get all fonts, their backup fonts, and font families
		$db = database::connect();
		$sql = "SELECT `fonts`.`id` AS `id`,
			`fonts`.`name` AS `name`,
			`backup`.`name` AS `backup`,
			`fontfamilies`.`name` AS `family`
			FROM fonts `fonts`
			LEFT JOIN `fonts` `backup`
			ON `fonts`.`backup`=`backup`.`id`
			LEFT JOIN `fontfamilies`
			ON `fonts`.`family`=`fontfamilies`.`id`
			ORDER BY `fonts`.`family` ASC,
			`fonts`.`name` ASC;";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$rows = $stmt->fetchAll();

		// Save all font indexes, names, and full css stylings
		$fontorder = array();
		$fontnames = array();
		$fontstyles = array();

		// Store information (note that $fontorder is used so buttons will be in order of query)
		foreach ($rows as $row) {
			array_push($fontorder, $row['id']);
			$fontnames[$row['id']] =  $row['name'];
			$fontstyles[$row['id']] = $row['name'] . ', ' . (is_null($row['backup']) ? '' : $row['backup'] . ', ') . $row['family'];
		}

		// Get previous styles
		$sql = "SELECT * FROM `style` ORDER BY `id` ASC;";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$styles = $stmt->fetchAll();

		// Get most recent style for filling values
		// unless one is specified in URL
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			foreach ($styles as $s) {
				if ($s['id'] == $id)
					$style = $s;
			}
		}
		else {
			$style = end($styles);
		}

		$db = NULL;

		// Display page

		$title = 'Change style';

		include 'cache.php';

		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>' . $title . '</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_LESLEY['html'] . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
		<script type="text/javascript" src="style.js"></script>
		<script type="text/javascript" src="https://www.google.com/recaptcha/api.js" async defer></script>
		<script type="text/javascript">
			var isHuman = function() {
				if (grecaptcha.getResponse() == "") {
					alert("Please prove you\'re not a robot by checking the box");
					return false;
				}
				else {
					return true;
				}
			};
		</script>
	</head>
	<body>
		<h1>' . $title . ':</h1>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="colorpicker" method="POST" onkeydown="return event.key != \'Enter\';"  onsubmit="return isHuman();">
			<h2>Previous styles</h2>
				<div class="dropdown">
					Select previous style &#9660;
					<div class="dropdown-content">';
		// Display each style in "previous styles"  menu as an icon
		foreach ($styles as $s) {
			$primaryrgb = HSLtoRGB($s['hue']/360, $s['saturation']/100, $s['primarylightness']/100);
			$primaryhex = RGBtoHEX((int)$primaryrgb['r'], (int)$primaryrgb['g'], (int)$primaryrgb['b']);
			$gradient = !is_null($s['secondarylightness']);
			if ($gradient) {
				$secondaryrgb = HSLtoRGB($s['hue']/360, $s['saturation']/100, $s['secondarylightness']/100);
				$secondaryhex = RGBtoHEX((int)$secondaryrgb['r'], (int)$secondaryrgb['g'], (int)$secondaryrgb['b']);
			}
			$backgroundrgb = HSLtoRGB(0, 0, $s['backgroundlightness']/100);
			$backgroundhex = RGBtoHEX((int)$backgroundrgb['r'], (int)$backgroundrgb['g'], (int)$backgroundrgb['b']);
			echo '
						<a href="style.php?id=' . $s['id'] . '">
							<div class="dropdown-preview" style="' . ($gradient ? 'background: radial-gradient(' . $secondaryhex . ', ' . $primaryhex . ');' : 'background-color: ' . $primaryhex . ';') . ' border: 10px solid ' . $backgroundhex . ';">
								<h1 style="font-family: ' . $fontstyles[$s['primaryfont']] . ';">
									Style #' . $s['id'] . '
								</h1>
							</div>
						</a>';
		}
		echo '
					</div>
				</div>
			<h2>Color scheme</h2>
				<h3>Primary color:</h3>
					<div class="colorpreview" id="primarycolorpreview"></div>
					<img src="slider.png" width=400 height=5>
					<br>
					<input type="range" name="hue" id="hue" min="0" max="360" value="' . $style['hue'] . '" onchange="updatecolors();">
					Hue
					<br>
					<input type="range" name="saturation" id="saturation" min="0" max="100" value="' . $style['saturation'] . '" onchange="updatecolors();">
					Saturation (0% = gray, 100% = full color)
					<br>
					<input type="range" name="primarylightness" id="primarylightness" min="0" max="100" value="' . $style['primarylightness'] . '" onchange="updatecolors();">
					Lightness (0% = black, 100% = white)
				<h3>Secondary color:</h3>
					<input type="checkbox" name="gradient" id="gradient" onchange="updategradient(); updatecolors();"' . (is_null($style['secondarylightness']) ? '' : ' checked') . '>
					(optional)
					<div class="colorpreview" id="secondarycolorpreview"></div>
					<input type="range" name="secondarylightness" id="secondarylightness" min="0" max="100" value="' . (is_null($style['secondarylightness']) ? $style['primarylightness'] : $style['secondarylightness']) . '" onchange="updatecolors();">
					<span id="secondarylightnesslabel">Lightness</span>
				<h3>Background color:</h3>
					<div class="colorpreview" id="backgroundcolorpreview"></div>
					<input type="range" name="backgroundlightness" id="backgroundlightness" min="0" max="100" value="' . $style['backgroundlightness'] . '" onchange="updatecolors();">
					Lightness
			<h2>Fonts</h2>';
		// Fonts and font stylings are stored in hidden values for javascript functions
		foreach ($fontnames as $id => $fontname) {
			echo '
				<input type="hidden" id="fontname.' . $id . '" value="' . $fontname . '">';
		}
		foreach ($fontstyles as $id => $fontstyle) {
			echo '
				<input type="hidden" id="fontstyle.' . $id . '" value="' . $fontstyle . '">';
		}
		echo '
				<div style="display: inline-block; padding-right: 25px;">
					<h3>Headers/buttons</h3>';
		// Primary font radio buttons
		foreach ($fontorder as $fontid) {
			echo '
					<div style="display: block;">
						<input type="radio" name="primaryfont" id="primaryfont.' . $fontid . '" value="' . $fontid . '" onchange="updateprimaryfont();"' . (($style['primaryfont'] == $fontid) ? ' checked' : '') . '>
						<label for="primaryfont.' . $fontid . '" style="font-family: ' . $fontstyles[$fontid] . ';">' . $fontnames[$fontid] . '</label>
					</div>';
		}
		echo '
				</div>
				<div style="display: inline-block;">
					<h3>Body text</h3>';
		// Secondary font radio buttons
		foreach ($fontorder as $fontid) {
			echo '
					<div style="display: block;">
						<input type="radio" name="secondaryfont" id="secondaryfont.' . $fontid . '" value="' . $fontid . '" onchange="updatesecondaryfont();"' . (($style['secondaryfont'] == $fontid) ? ' checked' : '' ) . '>
						<label for="secondaryfont.' . $fontid . '" style="font-family: ' . $fontstyles[$fontid] . ';">' . $fontnames[$fontid] . '</label>
					</div>';
		}
		// Preview fonts in context with color scheme:
		echo '
				</div>
			<h2>Preview:</h2>
			<div class="stylepreview" id="preview">
				<h1 id="primaryfontpreview">Heading:</h1>
				<p id="secondaryfontpreview">The quick brown fox jumps ovwr the lazy dog.  The quick brown fox jumps over the lazy dog.</p>
			</div>
			<div class="g-recaptcha" data-sitekey="6LdbgCscAAAAAFHelEq7Q2QsaIFlzfZlhraGu5_e"></div>
			<input type="submit" value="Submit">
		</form>
	</body>
</html>';
	}
?>
