<!-- 
Site designed by Robert Rutherford, 2014 - 2019
 -->
 <?php

require_once 'paths.php';
require_once 'database.php';
require_once 'header.php';
require_once 'footer.php';

headerHTML('Bio');

// Grab biography from database
$db = database::connect();
$sql = "SELECT bio FROM biography;";
$stmt = $db->prepare($sql);
if(!$stmt->execute())
    die('There was an error running the query [' . $db->errorInfo() . ']');
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$bio = $row['bio'];

// Normalize line endings and convert all
// line-endings to UNIX format
$bio = str_replace("\r\n", "\n", $bio);
$bio = str_replace("\r", "\n", $bio);
// Don't allow out-of-control blank lines
// Convert line breaks into HTML paragraph tags
$bio = preg_replace("/\n{2,}/", "\n\t\t\t\t\t</p>\n\t\t\t\t\t<p>\n\t\t\t\t\t\t", $bio);

// Display page
echo '
			<div class = "row">
				<div class = "page">
					<center>
						<img class="photo" src = "' . PHOTO_FULL['html'] . '">
					</center>
					<p>
						' . $bio . '
					</p>
				</div>
			</div>';
footerHTML();
?>
