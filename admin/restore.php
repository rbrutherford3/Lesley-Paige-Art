<?php
    // Adjust PHP settings for larger files
    require_once '../paths.php';

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if ($_FILES['backupfile']['error'] == UPLOAD_ERR_OK) {
            
            // Save the file
            $filepath = RESTORE['sys'] . basename($_FILES['backupfile']['name']);
            move_uploaded_file($_FILES['backupfile']['tmp_name'], $filepath);

            // Run import of file in the background because it might take a while
            $cmd = "php -f " . ADMIN['sys'] . 'import.php ' . '"' . $filepath  . '"';
            $outputfile = RESTORE['sys'] . 'sysout';
            $pidfile = RESTORE['sys'] . 'pid';
            exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile));

            echo '<!DOCTYPE HTML>
<html>
    <head>
        <title>Restore site data</title>
        <link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
    </head>
    <body>
        <h2>Restoration process begun</h2>
        <p>
            <a href="' . ADMIN['html'] . 'index.php"><input type="button" value="Return to admin menu"></a>
        </p>
    </body>
</html>';
        }
        else
            die("There was an error uploading the .sql file: " . $_FILES['backupfile']['error']);
    }
    else {
		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>Restore site data</title>
		<link rel="stylesheet" type="text/css" href="' . CSS_ADMIN['html'] . '">
	</head>
	<body>
        <form method="post" enctype="multipart/form-data">
        <h2>Select file to restore to</h2>
        <p>
            <input type="file" name="backupfile" id="backupfile" accept=".tar.gz">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
        </form>
    </body>
</html>
';
    }
?>