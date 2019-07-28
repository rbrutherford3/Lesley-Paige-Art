+<?php
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
		echo	'	
			<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>Lesley Paige Art', ($hasTitle ? ' - ' . $title : '') , '</title>
			<link href="css/bootstrap.min.css" rel="stylesheet">
			<link rel="stylesheet" type="text/css" href="css/main.css">
			<link rel="stylesheet" type="text/css" href="css/text.css">
			<link rel="SHORTCUT ICON" href="img/favicon.ico">
			</head>
			<body>
				<div class = "container" style = "background-color: #000000;">
					<div class = "row">
						<div class="header">
							<div class="hidden-xs" style="display:inline-block;">
								<img class="header" src="img/header/photo-circle.png">
								<img class="header" src="img/header/Title.png">
								<img class="header" src="img/header/Stamp.png">
							</div>
							<div class="visible-xs" style="display:inline-block;">
								<img class="header-mobile" src="img/header/Title-small.png">
							</div>
							<br>
							<div class="btn-group" role="group" aria-label="..." style="margin-top: 5px">
								<a target="_blank" href="http://fineartamerica.com/profiles/1-lesley-rutherford.html" type="button" class="btn btn-default" style="background: #EFEAFF; ">
									<span class="buttontext">
										fineartamerica.com
									</span>
								</a>
								<a target="_blank" href="https://www.etsy.com/people/LesleyPaige" type="button" class="btn btn-default" style="background: #EFEAFF; ">
									<span class="buttontext">
										etsy.com
									</span>
								</a>
							</div>
							<br>
							<div class="btn-group" role="group" aria-label="..." style="margin-top: 5px">
								<a href="index.php" type="button" class="btn btn-default" style="background: #EFEAFF;">
									<span class="buttontext">
										Art
									</span>
								</a>
								<a href="bio.php" type="button" class="btn btn-default" style="background: #EFEAFF; ">
									<span class="buttontext">
										Bio
									</span>
								</a>
								<a target="_blank" href="mailto:lesleypaigerutherford@gmail.com" type="button" class="btn btn-default" style="background: #EFEAFF; ">
									<span class="buttontext">
										E-mail
									</span>
								</a>
							</div>
						</div>
					</div>';
	}
?>