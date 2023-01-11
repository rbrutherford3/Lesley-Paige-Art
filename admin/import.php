<?php
    require_once '../paths.php';
    require_once 'database.php';
    require_once 'artpiece.php';

    // Get file path from script parameter
    if ($argc != 2)
        die("Invalid number of arguments provided.  Need 1 for archive location.");
    $filepath = $argv[1];

    $extractdir = RESTORE['sys'] . 'extract';

    // Create directory to store archive contents into
    if (file_exists($extractdir)) {
        if (is_dir($extractdir))
            deleteDir($extractdir);
        else
            unlink($extractdir);
    }
    mkdir($extractdir);

    // Move to new subdirectory
    $newfilepath = $extractdir . DIRECTORY_SEPARATOR . 'archive.tar.gz';
    rename($filepath, $newfilepath);

    // Decompress archive
    $p = new PharData($newfilepath);
    $p->decompress(); // creates /path/to/my.tar
    $tarfilepath = substr($newfilepath, 0, strlen($newfilepath)-3);

    // Extract contents of archive
    $phar = new PharData($tarfilepath);
    $phar->extractTo($extractdir);

    // Delete archive files
    unlink($newfilepath);
    unlink($tarfilepath);
    
    // Run the .sql file to import data and delete
    $sqlfilename = $extractdir . DIRECTORY_SEPARATOR . 'data.sql';
    if (!file_exists($sqlfilename))
        die('Missing data.sql');
    $sql = file_get_contents($sqlfilename);
    $db = database::connect();
    $errors = false;
    try {
        $db->exec($sql);
    }
    catch(Exception $e) {
        $errors = true;
        var_dump($e->getMessage());
    }
    if ($errors)
        die("There was an error executing the query");
    unlink($sqlfilename);

    // Move remaining files (should be images) to "upload/originals"
    $files = scandir($extractdir);
    foreach ($files as $file) {
        if (substr($file, 0, 1) != '.')
            rename($extractdir . DIRECTORY_SEPARATOR . $file, UPLOAD_ORIGINALS['sys'] . basename($file));
    }
    
    // Delete the now-empty extraction directory
    deleteDir($extractdir);

    // Get all the information from the `info` table for re-importing later
    $sql = "SELECT * FROM `info`;";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row;
    }

    // Delete the data in the `info` table
    $sql = "TRUNCATE `info`;";
    $errors = false;
    try {
        $db->exec($sql);
    }
    catch(Exception $e) {
        $errors = true;
        var_dump($e->getMessage());
    }
    if ($errors)
        die("There was an error executing the query");

    // Delete all the images and re-create their folders
    deleteDir(ORIGINALS['sys']);
    mkdir(ORIGINALS['sys']);
    deleteDir(WATERMARKED['sys']);
    mkdir(WATERMARKED['sys']);
    deleteDir(THUMBNAILS['sys']);
    mkdir(THUMBNAILS['sys']);

    // Re-import each image (necessary in order to apply cropping and rotation and add watermark)
    foreach ($rows as $row)
        add_image($row['name'], $row['width'], $row['height'], $row['year'], $row['sold'], $row['price'],
            $row['description'], $row['fineartamerica'], $row['etsy'], $row['sequence'],
            $row['leftcrop'], $row['rightcrop'], $row['topcrop'], $row['bottomcrop'], $row['rotation']);

    // Use artpiece class to add an image file and information to the database & website
    function add_image($name, $width, $height, $year, $sale, $price, $desc, $faaurl, $etsyurl, $sequence, 
            $leftcrop, $rightcrop, $topcrop, $bottomcrop, $rotation) {
        $filename = artpiece::createfilename($name);
        $artpiece = new artpiece();
        $artpiece->addfile($filename);
        $artpiece->getfile()->setcropall($leftcrop,$rightcrop,$topcrop,$bottomcrop);
        $artpiece->getfile()->setrotation($rotation);
        $artpiece->getfile()->createwatermarked();
        $artpiece->getfile()->createthumbnail();
        $artpiece->getfile()->writewatermarked();
        $artpiece->getfile()->writethumbnail();
        $artpiece->getfile()->destroywatermarked();
        $artpiece->getfile()->destroythumbnail();
        $artpiece->addinfo($name, $width, $height, $year, $sale, $price, $desc, $faaurl, $etsyurl, $sequence);
        $artpiece->movefiles();
        $artpiece->setdb();
        unset($artpiece);
    }
?>