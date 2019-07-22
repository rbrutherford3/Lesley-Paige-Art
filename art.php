<!-- 
Site designed by Robert Rutherford, 2014
 -->
 <?php
include 'connection.php';

$id = $_GET["id"];

$sql = <<<SQL
    SELECT *
    FROM `info`
    WHERE `id` = '$id' 
SQL;

if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

$row = $result->fetch_assoc();
$name = $row['name'];
$year = $row['year'];
$size = $row['size'];
$desc = $row['desc'];
$famlink = $row['fineartamerica'];
$etsylink = $row['etsy'];

$filename = $row['filename'];

echo	'	<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>Lesley Paige Art - ', $name, ' </title>
			<link href="css/bootstrap.min.css" rel="stylesheet">
			<link rel="stylesheet" type="text/css" href="css/main.css">
			<link rel="SHORTCUT ICON" href="img/favicon.ico">
			</head>
			<body>
				<div class = "container" style = "background-color: #000000;">';
include	"header.php";
echo	'			<div class = "row">
						<div class="page">
							<center>';
/* include "table-one.php";								
echo	'						<br> */
echo	'						<p>
									<a target="_blank" href = "img/art_big/', $filename, '.png">
										<img class = "art" src = "img/art/', $filename, '.gif" alt = "', $name, '">
									</a>
								</p>
								<p>
									<img class = "artlabel" src = "img/art_labels/', $filename, '.png" alt = "', $name, '">
								</p>
								<b>
									<p>
										', $year, '
									</p>
									<p>
										', $size, '
									</p>
								</b>
							</center>
							<p>
								', $desc, '
							</p>
							<center>
								<p>
									Purchase original, prints, or various other products of this piece from:
									<div class="btn-group" role="group" aria-label="..." style="margin-top: 5px; font-family: Century Gothic, sans-serif;">';
if (!is_null($famlink)) {
	echo 	'							<a target="_blank" href="', $famlink , '" type="button" class="btn btn-default" style="background: #EFEAFF; ">
											fineartamerica.com
										</a>';
}
if (!is_null($etsylink)) {
	echo	'							<a target="_blank" href="', $etsylink , '" type="button" class="btn btn-default" style="background: #EFEAFF; ">
											etsy.com
										</a>';
}
echo	'							</div>
								</p>
							</center>
						</div>
					</div>';
// include "footer.php";					
echo	'		</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
			<script src="js/bootstrap.min.js"></script>
			</body>';
?>