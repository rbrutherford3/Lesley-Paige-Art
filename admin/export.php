<?php

    include_once 'database.php';
    require_once '../paths.php';
    require_once 'email.php';

        // Get file path from script parameter
    if ($argc != 2)
        die("Invalid number of arguments provided.  Need 1 for email address.");
    $emailaddress = $argv[1];

    // Define paths and create backup directory
    $sqlfilename = "data.sql";
    $sqlfilepath = BACKUP['sys'] . $sqlfilename;

    // Backup database
    $db = database::connect();
    $tables = array();
    $sqlDump = backup_tables($db, $tables);
    file_put_contents($sqlfilepath, $sqlDump);

    // Create archive name based on date and time
    date_default_timezone_set('US/Eastern');
	$timestamp = date("YmdHis");
    $archivefilename = "lesley" . $timestamp;
    $archivefilepath = BACKUP['sys'] .  $archivefilename;

    // Initialize archive
    $archive = new PharData($archivefilepath . '.tar');

    // Save current directory to go back to later
    $saved_dir = getcwd();

    // Archive all the original image files
    chdir(ORIGINALS['sys']);
    foreach (scandir(ORIGINALS['sys']) as $file) {
        if ((strpos($file, '.') != 0) && (!is_dir($file))) {
            $archive->addfile($file);
        }
    }

    // Include the database backup in the archive and delete it
    chdir(substr(BACKUP['sys'], 0, -1));

    $url = str_replace("\\",'/',"http://".$_SERVER['HTTP_HOST'].substr(getcwd(),strlen($_SERVER['DOCUMENT_ROOT']))) . '/' . $archivefilename . '.tar.gz';

    $archive->addfile($sqlfilename);
    unlink($sqlfilepath);

    // Compress the archive and delete the uncompressed archive
    $archive->compress(Phar::GZ);
    unlink($archivefilepath . '.tar');

    // Go back to original directory
    chdir($saved_dir);

    str_replace("\\",'/',"http://".$_SERVER['HTTP_HOST'].substr(getcwd(),strlen($_SERVER['DOCUMENT_ROOT'])));

    sendemail($emailaddress, "Lesley Paige Site archive", $url);

    // Delete everything after download
    #register_shutdown_function('unlink', $archivefilepath . '.tar.gz');
    #register_shutdown_function('rmdir', $backupdir);

    // Send the download
    # sendfile($archivefilepath . '.tar.gz');

    // Function to create sql dump string from a database connection
    function backup_tables($DBH, $tables) {

        $DBH->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL );

        //Script Variables
        $compression = false;
        $BACKUP_PATH = "";
        $nowtimename = time();
        $output = '';

        //create/open files
        /* if ($compression) {
        $zp = gzopen($BACKUP_PATH.$nowtimename.'.sql.gz', "a9");
        } else {
        $handle = fopen($BACKUP_PATH.$nowtimename.'.sql','a+');
        } */

        //array of all database field types which just take numbers 
        $numtypes=array('tinyint','smallint','mediumint','int','bigint','float','double','decimal','real','bit');

        //get all of the tables
        if(empty($tables)) {
            $pstm1 = $DBH->query('SHOW TABLES');
            while ($row = $pstm1->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',',$tables);
        }

        //cycle through the table(s)

        foreach($tables as $table) {
            $result = $DBH->query("SELECT * FROM $table");
            $num_fields = $result->columnCount();
            $num_rows = $result->rowCount();

            $return="";
            //uncomment below if you want 'DROP TABLE IF EXISTS' displayed
            $return.= 'DROP TABLE IF EXISTS `'.$table.'`;'; 

            //table structure
            $pstm2 = $DBH->query("SHOW CREATE TABLE $table");
            $row2 = $pstm2->fetch(PDO::FETCH_NUM);
            $ifnotexists = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $row2[1]);
            $return.= "\n\n".$ifnotexists.";\n\n";

            $output.=$return;
            /* if ($compression) {
            gzwrite($zp, $return);
            } else {
            fwrite($handle,$return);
            } */
            $return = "";

            //insert values
            if ($num_rows){
                $return= 'INSERT INTO `'."$table"."` (";
                $pstm3 = $DBH->query("SHOW COLUMNS FROM $table");
                $count = 0;
                $type = array();

                while ($rows = $pstm3->fetch(PDO::FETCH_NUM)) {

                    if (stripos($rows[1], '(')) {
                        $type[$table][] = stristr($rows[1], '(', true);
                    } 
                    else {
                        $type[$table][] = $rows[1];
                    }

                    $return.= "`".$rows[0]."`";
                    $count++;
                    if ($count < ($pstm3->rowCount())) {
                        $return.= ", ";
                    }
                }

                $return.= ")".' VALUES';

                $output.=$return;
                /* if ($compression) {
                gzwrite($zp, $return);
                } else {
                fwrite($handle,$return);
                } */
                $return = "";
            }
            $count =0;
            while($row = $result->fetch(PDO::FETCH_NUM)) {
                $return= "\n\t(";

                for($j=0; $j<$num_fields; $j++) {

                    //$row[$j] = preg_replace("\n","\\n",$row[$j]);

                    if (isset($row[$j])) {

                        //if number, take away "". else leave as string
                        if (in_array($type[$table][$j], $numtypes)) {
                            $return.= $row[$j]; 
                        }
                        else {
                            $return.= $DBH->quote($row[$j]);
                        }

                    } 
                    else {
                        $return.= 'NULL';
                    }
                    if ($j<($num_fields-1)) {
                        $return.= ',';
                    }
                }
                $count++;
                if ($count < ($result->rowCount())) {
                    $return.= "),";
                } 
                else {
                    $return.= ");";
                }

                $output.=$return;
                /* if ($compression) {
                gzwrite($zp, $return);
                } else {
                fwrite($handle,$return);
                } */
                $return = "";
            }
            $return="\n\n-- ------------------------------------------------ \n\n";
            $output.=$return;
            /* if ($compression) {
            gzwrite($zp, $return);
            } else {
            fwrite($handle,$return);
            } */
            $return = "";
        }

        $error1= $pstm2->errorInfo();
        $error2= $pstm3->errorInfo();
        $error3= $result->errorInfo();
        echo $error1[2];
        echo $error2[2];
        echo $error3[2];

        /* if ($compression) {
        gzclose($zp);
        } else {
        fclose($handle);
        } */
        return $output;
    }

    // Mirror database into 'dummy' database, modifying personal information only
    function mirrorDB($db, $sqlDump) {

        $sqlList = "SHOW TABLES;";
        
        //Prepare our SQL statement,
        $stmtList = $db->prepare($sqlList);
        
        //Execute the statement.
        $stmtList->execute();
        
        //Fetch the rows from our statement.
        $tables = $stmtList->fetchAll(PDO::FETCH_NUM);
        
        //Loop through our table names.
        foreach($tables as $table){
            //echo '<script>alert("' . $table . '");</script>?';
            //Print the table name out onto the page.
            $sqlDrops[] =  "DROP TABLE IF EXISTS " . $table[0] . ";";
        }

        // Execute table drops
        foreach($sqlDrops as $sqlDrop) {
            $stmtDrop = $db->prepare($sqlDrop);
            $stmtDrop->execute();
            $stmtDrop->closeCursor();
        }

        // Execute sql dump, effectively copying information into dummy databse
        $sqlTransfer = $sqlDump;
        $stmtTransfer = $db->prepare($sqlTransfer);
        $stmtTransfer->execute();
        $stmtTransfer->closeCursor();

    }

    // Save output file (taken from: https://stackoverflow.com/a/2882523/3130769)
    function sendfile($file) {
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }
?>
