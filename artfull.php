<?php

include 'connection.php';
include 'header.php';
include 'footer.php';

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
echo '<img class="fullsize" src="img/watermarked/', $filename, '.png">';
footerHTML();

?>