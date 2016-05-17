<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT * FROM kurs WHERE `ID`=$posebno";
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
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