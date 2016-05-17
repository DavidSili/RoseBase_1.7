<?php
$mysqli = mysqli_connect("localhost", "root", "", "rosebase") or die;
$mysqli->query("SET NAMES 'utf8'") or die;
date_default_timezone_set('Europe/Belgrade');
?>