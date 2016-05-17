<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT * FROM proizvodi WHERE `ID`=$posebno";
$result=mysqli_query($mysqli,$sql) or die;
while ($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
}
$passhtml['yid']=$ID;
$passhtml['ysifra']=$sifra;
$passhtml['ysifkas']=$sifrakasa;
$passhtml['ybarcode']=$barcode;
$passhtml['ynaziv']=$naziv;
$passhtml['ylink']=$link;
$passhtml['ynamgrupa']=$namgrupa;
$passhtml['ygrupa']=explode(',',$grupa);
$passhtml['ybrend']=$brend;
$passhtml['ydobavljac']=$dobavljac;
$passhtml['yzapremina']=$zapremina;
$passhtml['ytezinaneto']=$tezinaneto;
$passhtml['ytezinabruto']=$tezinabruto;
$passhtml['ykolpak']=$kolpak;
$passhtml['yminzal']=$minzal;
$passhtml['ycartar']=$cartar;
$passhtml['ypdv']=$pdv;
$passhtml['yncenae']=$ncena;
$passhtml['ypcena']=$pcena;
echo json_encode($passhtml);
?>