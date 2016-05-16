<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT * FROM nabavka WHERE `ID`=$posebno";
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
}
$passhtml['yid']=$ID;
$passhtml['yncarine']=$ncarine;
$passhtml['ydatdostavnice']=$datdostavnice;
$passhtml['ydatprijemarobe']=$datprijemarobe;
$passhtml['ydobavljac']=$dobavljac;
$passhtml['ybrnarudzbenice']=$brnarudzbenice;
$passhtml['yskladiste']=$skladiste;
$passhtml['ykursbanka']=$kursbanka;
$passhtml['ykurssred']=$kurssred;
$passhtml['ykurscarine']=$kurscarine;
$passhtml['ytransport']=$transport;
$passhtml['yneptroskoviuk']=$neptroskoviuk;
$passhtml['yplaceno']=$placeno;
echo json_encode($passhtml);
?>