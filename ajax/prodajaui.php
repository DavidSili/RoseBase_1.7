<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT * FROM prodaja WHERE `ID`=$posebno";
$result=mysqli_query($mysqli,$sql) or die ($sql.': '.mysql_error);
while ($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
}
$passhtml['yid']=$ID;
$passhtml['ykupac']=$kupac;
$passhtml['ybrpracuna']=$brpracuna;
$passhtml['ybrracuna']=$brracuna;
$passhtml['ybrizvoda']=$brizvoda;
$passhtml['yrok']=$rok;
$passhtml['ydatprometa']=$datprometa;
$passhtml['ybifr']=$bifr;
$passhtml['ynacdost']=$nacdost;
$passhtml['ybrracunau']=$brracunau;
$passhtml['ypozivnb']=$pozivnb;
$passhtml['yskladiste']=$skladiste;
$passhtml['ytisporuke']=$tisporuke;
$passhtml['ybezpopusta']=$bezpopusta;
$passhtml['ypopust']=$popust;
$passhtml['ybezpdva']=$bezpdva;
$passhtml['yiznospdv']=$iznospdv;
$passhtml['yzauplatu']=$zauplatu;
echo json_encode($passhtml);
?>