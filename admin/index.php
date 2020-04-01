<?php

require_once '../paths.php';
include_once 'reset.php'; // destroy session for good measure
header('Location: ' . ADMIN['html'] . 'sequence.php');
die();

?>
