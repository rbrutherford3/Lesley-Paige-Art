<?php

require_once 'paths.php';
require_once 'connection.php';
require_once 'header.php';
require_once 'footer.php';

$id = $_GET['id'];
$sql = "SELECT `name`, `filename` FROM `info` WHERE `id` = :id;";
$stmt = $db->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
if(!$stmt->execute()){
    die('There was an error running the query [' . $db->errorInfo() . ']');
}
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$name = $row['name'];
$filename = $row['filename'];

headerHTML($name);
echo '<img class="fullsize" src="', WATERMARKED_HTML, $filename, '.', EXT, '">';
footerHTML();

?>