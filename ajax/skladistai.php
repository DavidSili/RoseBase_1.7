<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT * FROM skladista WHERE `ID`=$posebno";
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
foreach($row as $xx => $yy) {
	$$xx=$yy;
}
$passhtml['yid']=$ID;
$passhtml['ynaziv']=$naziv;
$passhtml['yoosoba']=$oosoba;
$passhtml['yadresa']=$adresa;
$passhtml['ystatus']=$status;
echo json_encode($passhtml);
?>