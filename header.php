<?php

require_once 'paths.php';

function headerHTML() {
	if (!func_num_args()) {
		$hasTitle = false;
	}
	elseif (func_num_args() == 1) {
		$hasTitle = true;
		$title = func_get_arg(0);
	}
	else {
		die("Too many arguments passed to header function");
	}
	echo '
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Lesley Paige Art', ($hasTitle ? ' - ' . $title : '') , '</title>
		<link href="' . BOOTSTRAP_CSS['html'] . '" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="' . CSS_MAIN['html'] . '?' . time() . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_LESLEY['html'] . '?' . time() . '">
		<link rel="stylesheet" type="text/css" href="' . CSS_TEXT['html'] . '?' . time() . '">
		<link rel="SHORTCUT ICON" href="' . FAVICON['html'] . '">
	</head>
	<body>
		<div class = "container bgcolor">
			<div class = "row">
				<div class="header">
					<div class="hidden-xs" style="display:inline-block;">
						<img src="' . PHOTO['html'] . '">
						<img class="title" src="' . TITLE['html'] . '">
						<img src="' . STAMP['html'] . '">
					</div>
					<div class="visible-xs" style="display:inline-block;">
						<img class="title header-mobile" src="' . TITLE['html'] . '">
					</div>
					<br>
					<div class="btn-group" role="group" aria-label="..." style="margin-top: 5px">
						<a href="' . ROOT['html'] . 'index.php" type="button" class="btn btn-default" style="background: #EFEAFF;">
							<span class="buttontext">
								Art
							</span>
						</a>
						<a href="' . ROOT['html'] . 'bio.php" type="button" class="btn btn-default" style="background: #EFEAFF; ">
							<span class="buttontext">
								Bio
							</span>
						</a>
						<a target="_blank" href="mailto:lesleypaigeart@gmail.com" type="button" class="btn btn-default" style="background: #EFEAFF; ">
							<span class="buttontext">
								E-mail
							</span>
						</a>
						<a target="_blank" href="https://fineartamerica.com/profiles/1-lesley-rutherford.html" type="button" class="btn btn-default" style="background: #EFEAFF; ">
							<span class="buttontext">
								fineartamerica.com
							</span>
						</a>
						<!--
						<a target="_blank" href="https://www.etsy.com/people/LesleyPaige" type="button" class="btn btn-default" style="background: #EFEAFF; ">
							<span class="buttontext">
								etsy.com
							</span>
						</a>
						-->
						<a target="_blank" href="admin/index.php" type="button" class="btn btn-default" style="background: #FF4040; ">
							<span class="buttontext">
								ADMIN DEMO
							</span>
						</a>
					</div>
				</div>
			</div>';
}

?>
