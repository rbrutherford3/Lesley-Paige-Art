<!-- 
Site designed by Robert Rutherford, 2014 - 2019
 -->
 <?php

require_once 'paths.php';
require_once 'database.php';
require_once 'header.php';
require_once 'footer.php';

$db = database::connect();

$id = $_GET["id"];

$sql = "SELECT *
    FROM `info`
    WHERE `id` = :id;";
$stmt = $db->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
if(!$stmt->execute()){
    die('There was an error running the query [' . $db->errorInfo() . ']');
}
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$name = $row['name'];
$year = $row['year'];
$widthInteger = floor($row['width']);
$widthFraction = getFraction($row['width']);
$heightInteger = floor($row['height']);
$heightFraction = getFraction($row['height']);
$desc = $row['description'];
$famlink = $row['fineartamerica'];
$etsylink = $row['etsy'];
$filename = $row['filename'];

switch($row['sold']) {
	case 0:
		$sold = 'Not for sale';
		$price = NULL;
		break;
	case 1:
		$sold = 'For sale';
		$price = '$' . $row['price'];
		break;
	case 2:
		$sold = 'Sold';
		$price = NULL;
		break;
	default:
		$soldstatus = NULL;
		$price = NULL;
}


headerHTML($name);
echo '
			<div class = "row">
				<div class="page">
					<center>';
/* include "table-one.php";
echo	'						<br> */
echo '
						<p>
							<a href = "' . ROOT['html'] . 'artfull.php?id=' . $id . '">
								<img class = "art" src = "' . THUMBNAILS['html'] . $filename . '.' . EXT . '" alt = "', $name, '">
							</a>
						</p>
						<h1>', $name, '</h1>
						<b>
							<p>
								', $year, '
							</p>
							<p>
								', $widthInteger, $widthFraction, ' x ', $heightInteger, $heightFraction, ' inches
							</p>';
if (!is_null($sold)) {
	echo '
							<p>
								' . $sold . (is_null($price) ? '' : ': ' . $price);
	if (!is_null($price)) {
		echo '
								<br>
								Please email for purchase information';
	}
	echo '
							</p>';
}
echo '
						</b>
					</center>
					<p>
						', $desc, '
					</p>';
if (!is_null($famlink)) {
	echo '
					<center>
						<p>
							Purchase original, prints, or various other products of this piece from:
							<div class="btn-group" role="group" aria-label="..." style="margin-top: 5px; font-family: Century Gothic, sans-serif;">
								<a target="_blank" href="', $famlink , '" type="button" class="btn btn-default" style="background: #EFEAFF; ">
									<span class="buttontext">
										fineartamerica.com
									</span>
								</a>';
// if (!is_null($etsylink)) {
//	echo '
//								<a target="_blank" href="', $etsylink , '" type="button" class="btn btn-default" style="background: #EFEAFF; ">
//									<span class="buttontext">
//										etsy.com
//									</span>
//								</a>';
	echo '
							</div>
						</p>
					</center>';
}
echo '
				</div>
			</div>';
footerHTML();

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
