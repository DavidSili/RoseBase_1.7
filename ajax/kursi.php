<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT * FROM kurs WHERE `ID`=$posebno";
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
foreach($row as $xx => $yy) {
	$$xx=$yy;
}
$passhtml['ydatum']=$datum;
$passhtml['ykcar']=$kcar;
$passhtml['ykbank']=$kbank;
$passhtml['yksred']=$ksred;
$passhtml['yid']=$ID;
echo json_encode($passhtml);
?>