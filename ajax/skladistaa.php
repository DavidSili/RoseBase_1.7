<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT partneri.ulicaibr ulicaibr, partneri.mesto mesto, pobroj.broj pobroj FROM partneri LEFT JOIN pobroj ON partneri.mesto = pobroj.mesto WHERE `ID`=$posebno";
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
$ulicaibr=$row['ulicaibr'];
$mesto=$row['mesto'];
$pobroj=$row['pobroj'];
$adresa=$ulicaibr.'; '.$pobroj.' '.$mesto;

$passhtml['yadresa']=$adresa;
echo json_encode($passhtml);
?>