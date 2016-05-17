<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT * FROM gpartnera WHERE `ID`=$posebno";
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
foreach($row as $xx => $yy) {
	$$xx=$yy;
}
$passhtml['ynaziv']=$naziv;
$passhtml['ycena']=$cena;
$passhtml['yid']=$ID;
echo json_encode($passhtml);
?>