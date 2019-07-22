<!-- 
Site designed by Robert Rutherford, 2014
 -->
 <?php
include 'connection.php';

$names = array();
$rows = array();
$ids = array();

$sql = <<<SQL
    SELECT *
    FROM `info`
SQL;

if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

while ($row = $result->fetch_assoc()) {
	$rows[] = $row;
}

$n = count($rows);

for ($i=0; $i<$n; $i++) {
	$names[$i] = $rows[$i]['name'];
	$filenames[$i] = $rows[$i]['filename'];
	$ids[$i] = $rows[$i]['id'];
}

echo	'	<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>Lesley Paige Art</title>
			<link href="css/bootstrap.min.css" rel="stylesheet">
			<link rel="stylesheet" type="text/css" href="css/main.css">
			<link rel="SHORTCUT ICON" href="img/favicon.ico">
			</head>
			<body>
				<div class = "container" style = "background-color: #000000;">';
include	"header.php";
echo	'			<div class="hidden-xs">
						<div class = "row">
							<div class="page">';
								// <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">';
for ($i=0; $i<$n; $i++) {
include 'table-many.php';
}
//echo 	'						</div>
echo	'					</div>
						</div>
					</div>
					<div class="visible-xs">
						<div class = "row">
							<div class="carouselpage">
								<div id="myCarousel" class="carousel slide" data-ride="carousel" style="padding-top:0px;">
									<!-- Indicators -->
									<ol class="carousel-indicators">';

/* for ($i=0; $i<$n; $i++) {
	if ($i==0) {	
		echo 	'				<li data-target="#myCarousel" data-slide-to="', $ids[$i], '" class="active"></li>';
	}
	else {
		echo	'					<li data-target="#myCarousel" data-slide-to="', $ids[$i], '"></li>';
	}
} */
echo	'							</ol>

									<!-- Wrapper for slides -->
									<div class="carousel-inner" role="listbox">';
for ($i=0; $i<$n; $i++) {
	if ($i==0) {									
		echo 	'						<div class="item active">
											<div class="carouselContainer img-responsive center-block">';
		include 'table-many.php';
		echo	'							</div>
										</div>';
	}
	else {
		echo	'						<div class="item">
											<div class="carouselContainer img-responsive center-block">';
		include 'table-many.php';									
		echo	'							</div>
										</div>';
	}
}
									
echo	'							</div>

									<!-- Left and right controls -->
									<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
										<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
										<span class="sr-only">Previous</span>
									</a>
									<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
										<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
										<span class="sr-only">Next</span>
									</a>
								</div>
							</div>
						</div>
					</div>';
// include "footer.php";					
echo	'		</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
			<script src="js/bootstrap.min.js"></script>
			</body>';
?>