<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT * FROM partneri WHERE `ID`=$posebno";
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
foreach($row as $xx => $yy) {
	$$xx=$yy;
}
$passhtml['ygpartnera']=$gpartnera;
$passhtml['yime']=$ime;
$passhtml['yprezime']=$prezime;
$passhtml['ypol']=$pol;
$passhtml['yulicaibr']=$ulicaibr;
$passhtml['ymesto']=$mesto;
$passhtml['ydrzava']=$drzava;
$passhtml['yfirma']=$firma;
$passhtml['ypib']=$pib;
$passhtml['ymaticni']=$maticni;
$passhtml['ytelefon']=$telefon;
$passhtml['ymobilni']=$mobilni;
$passhtml['yemail']=$email;
$passhtml['yid']=$ID;
echo json_encode($passhtml);
?>