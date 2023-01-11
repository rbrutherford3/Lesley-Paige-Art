<?php
    require_once '../paths.php';

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $cmd = "php -f " . ADMIN['sys'] . 'export.php ' . $_POST['email'];
        $outputfile = BACKUP['sys'] . 'sysout';
        $pidfile = BACKUP['sys'] . 'pid';
        exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile));
        echo '<!DOCTYPE HTML>
        <html>
            <head>
                <title>Restore site data</title>
                <link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
            </head>
            <body>
                <h2>Backup process begun</h2>
                <p>
                    <a href="' . ADMIN['html'] . 'index.php"><input type="button" value="Return to admin menu"></a>
                </p>
            </body>
        </html>';
    }
    else {
		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>Restore site data</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
	</head>
	<body>
        <form method="post">
        <h2>Site Backup Tool</h2>
        <p>
            Because it may take a while to archive and compress all the images, 
            please enter your email address and we will e-mail you a link to download the site file
        </p>
        <p>
            <label for="email">Enter your email address:</label>
            <input type="text" name="email" id="email">
        </p>
        <p>
            <input type="submit" value="Begin Backup">
        </p>
        </form>
    </body>
</html>
';
    }
?>