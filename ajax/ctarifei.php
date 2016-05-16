<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT * FROM ctarife WHERE `ID`=$posebno";
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
foreach($row as $xx => $yy) {
	$$xx=$yy;
}
$passhtml['ynaziv']=$naziv;
$passhtml['ysifra']=$sifra;
$passhtml['ystopa']=$stopa;
$passhtml['yid']=$ID;
echo json_encode($passhtml);
?>