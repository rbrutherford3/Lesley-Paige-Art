<!-- 
Site designed by Robert Rutherford, 2014 - 2020
 -->
<?php

require_once 'paths.php';
require_once 'database.php';
require_once 'header.php';
require_once 'footer.php';

$names = array();
$rows = array();
$ids = array();

$db = database::connect();

$sqlshuffle = "SELECT `shuffle` FROM `shuffle`;";
$stmtshuffle = $db->prepare($sqlshuffle);
if(!$stmtshuffle->execute())
    die('There was an error running the query [' . $db->errorInfo() . ']');
if ($rowshuffle = $stmtshuffle->fetch(PDO::FETCH_ASSOC))
	$shuffle = $rowshuffle['shuffle'];
else
	die('Database entry for `shuffle` not found');

if ($shuffle > 0)
	$sql = "SELECT *
		FROM `info`
		WHERE `sequence` IS NOT NULL
		ORDER BY RAND();";	
else
	$sql = "SELECT *
		FROM `info`
		WHERE `sequence` IS NOT NULL
		ORDER BY `sequence` ASC;";
$stmt = $db->prepare($sql);
if(!$stmt->execute()){
    die('There was an error running the query [' . $db->errorInfo() . ']');
}
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$rows[] = $row;
}

$n = count($rows);

for ($i=0; $i<$n; $i++) {
	$names[$i] = $rows[$i]['name'];
	$filenames[$i] = $rows[$i]['filename'];
	$ids[$i] = $rows[$i]['id'];
}

headerHTML();
echo '
			<div class="hidden-xs">
				<div class = "row">
					<div class="page">';
								// <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">';
for ($i=0; $i<$n; $i++) {
include 'table-many.php';
}
//echo 	'						</div>
echo '
					</div>
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
echo '
							</ol>
							<!-- Wrapper for slides -->
							<div class="carousel-inner" role="listbox" style="height: 375px;">';
for ($i=0; $i<$n; $i++) {
	if ($i==0) {
		echo '
								<div class="item active">
									<div class="carouselContainer img-responsive center-block">';
		include 'table-many.php';
		echo '
									</div>
								</div>';
	}
	else {
		echo '
								<div class="item">
									<div class="carouselContainer img-responsive center-block">';
		include 'table-many.php';
		echo '
									</div>
								</div>';
	}
}
echo '
							</div>
							<!-- Left and right controls -->
							<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
								<span class="glyphicon glyphicon-chevron-left" aria-hidden="true">
								</span>
								<span class="sr-only">
									Previous
								</span>
							</a>
							<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
								<span class="glyphicon glyphicon-chevron-right" aria-hidden="true">
								</span>
								<span class="sr-only">
									Next
								</span>
							</a>
						</div>
					</div>
				</div>
			</div>';
footerHTML();
?>
