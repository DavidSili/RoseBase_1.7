<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT * FROM brendovi WHERE `ID`=$posebno";
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
foreach($row as $xx => $yy) {
	$$xx=$yy;
}
$passhtml['yid']=$ID;
$passhtml['ynaziv']=$naziv;
$passhtml['yopis']=$opis;
$passhtml['ystatus']=$status;
echo json_encode($passhtml);
?>