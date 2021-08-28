<?php

	// Page for editing biography

	require_once '../paths.php';
	require_once 'database.php';

	// Upon submission, save bio to database
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		// Grab input
		$bio = trim($_POST['bio']);
		$db = database::connect();
		$sql = "UPDATE `biography` SET `bio`=:bio;";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(":bio", $bio, PDO::PARAM_STR);
		if ($stmt->execute())
			header('Location: ' . ADMIN['html'] . 'index.php');
		else
			die('There was an error runnig the query [' . $db->errorInfo . ']');
		$db = NULL;
	}

	// Display page
	else {

		// Get current biography
		$db = database::connect();
		$sql = "SELECT `bio` FROM `biography`";
		$stmt = $db->prepare($sql);
		if (!$stmt->execute())
			die('There was an error running the query [' . $db->errorInfo() . ']');
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			$bio = $row['bio'];
		else
			$bio = 'No biography found';
		$db = NULL;

		// Display page

		$title = 'Edit biography';

		include 'cache.php';

		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>' . $title . '</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
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
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" name="bioform" method="POST" onsubmit="return isHuman();">
		<h1>' . $title . '</h1>
			<h3>Please separate paragraphs with a blank line</h3>
			<p>
				<textarea name="bio" id="bio" style="resize: none;" rows="30" cols="100">' . htmlspecialchars_decode(htmlspecialchars_decode($bio)) . '</textarea>
			</p>
			<div class="g-recaptcha" data-sitekey="6LdbgCscAAAAAFHelEq7Q2QsaIFlzfZlhraGu5_e"></div>
			<input type="submit" name="submit" value="Save">
		</form>
	</body>
</html>';
	}
?>
