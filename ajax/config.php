<?php
$host = "localhost";
$user = "root";
$passwordx = "";
$db_name = "petrasil_landofroses";
$link = mysql_connect($host, $user, $passwordx);
mysql_select_db($db_name) or die;
mysql_query("SET NAMES utf8") or die;
date_default_timezone_set('Europe/Belgrade');
?>