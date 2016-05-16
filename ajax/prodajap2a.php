<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;
$profe = isset($_GET["prof"]) ? $_GET["prof"] : 0;

$passhtml=array();
if ($posebno=="x") {
$sql='SELECT COUNT(ID) fakt FROM prodaja WHERE brracuna IS NOT NULL AND brracuna <>""';
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
$fakt=$row['fakt'];

$sql='SELECT COUNT(ID) prof FROM prodaja WHERE brpracuna IS NOT NULL AND brracuna =""';
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
$prof=$row['prof'];
}
else {
$sql='SELECT COUNT(ID) fakt FROM prodaja WHERE kupac="'.$posebno.'" AND brracuna IS NOT NULL AND brracuna <>""';
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
$fakt=$row['fakt'];

$sql='SELECT COUNT(ID) prof FROM prodaja WHERE kupac="'.$posebno.'" AND brpracuna IS NOT NULL AND brracuna =""';
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
$prof=$row['prof'];
}

if ($posebno=="x") {
$sql='SELECT SUM(popust) popust, SUM(zarada) zarada, SUM(bezpopusta) bezpopusta, SUM(bezpdva) bezpdva, SUM(iznospdv) iznospdv, SUM(zauplatu) zauplatu FROM prodaja';
}
else $sql='SELECT SUM(popust) popust, SUM(zarada) zarada, SUM(bezpopusta) bezpopusta, SUM(bezpdva) bezpdva, SUM(iznospdv) iznospdv, SUM(zauplatu) zauplatu FROM prodaja WHERE kupac="'.$posebno.'"';

if ($posebno=='x' AND $profe==0) $sql.= ' WHERE brpracuna IS NOT NULL';
elseif ($posebno!='x' AND $profe==0) $sql.=' AND brpracuna IS NOT NULL';

$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
}

$bezpdva=number_format($bezpdva, 2, '.', ',');
$bezpopusta=number_format($bezpopusta, 2, '.', ',');
$iznospdv=number_format($iznospdv, 2, '.', ',');
$zauplatu=number_format($zauplatu, 2, '.', ',');
$popust=number_format($popust, 2, '.', ',');
$zarada=number_format($zarada, 2, '.', ',');
$passhtml['info']='<div style="width:110px;float:left;font-weight:bold">Br. faktura:<br/>Br. profaktura<br/>Bez PDVa:<br/>Bez popusta:</div><div style="width:80px;float:left;text-align:right;margin-right:10px;padding-right:10px;height:90px;border-right: solid 1px #000">'.$fakt.'<br/>'.$prof.'<br/>'.$bezpdva.'<br/>'.$bezpopusta.'</div><div style="width:80px;float:left;font-weight:bold">Iznos PDVa:<br/>Uplaćeno:<br/>Popust:<br/>Zarada:</div><div style="width:80px;float:left;text-align:right;height:90px;border-right: solid 1px #000;padding-right:10px">'.$iznospdv.'<br/>'.$zauplatu.'<br/>'.$popust.'<br/>'.$zarada.'</div><div style="width:85px;float:left;font-weight:bold;margin-left:10px;overflow:auto;height:90px;border-right: solid 1px #000">';

$fk=0;
$pk=0;
if ($posebno=="x" OR $posebno==84) {
$passhtml['info'].='<center><b>Pazari</b></center>';
$sql="SELECT `ID`,`datprometa` FROM prodaja WHERE kupac='84' ORDER BY `datprometa` DESC";
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$datum=date('d.m.Y.',strtotime($datprometa));
	$passhtml['info'].='<a href="ajax/fakturapa.php?posebno='.$ID.'&redova=0" target="_blank">'.$datum.'</a><br/>';
	$fk++;
}
if ($fk>0) $passhtml['info']= substr($passhtml['info'], 0, -5);
$passhtml['info'].='</div>';
$sql="SELECT `ID`,`brpracuna` FROM prodaja WHERE kupac=$posebno ORDER BY `brpracuna` DESC";
$result=mysql_query($sql) or die;

}
else {
$passhtml['info'].='<center><b>Fakture</b></center>';
$sql="SELECT `ID`,`brracuna` FROM prodaja WHERE `brracuna` IS NOT NULL AND `brracuna` !='' AND kupac=$posebno ORDER BY `brracuna` DESC";
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$passhtml['info'].='<a href="ajax/fakturapa.php?posebno='.$ID.'&redova=0" target="_blank">'.$brracuna.'</a><br/>';
	$fk++;
}
if ($fk>0) $passhtml['info']= substr($passhtml['info'], 0, -5);
$passhtml['info'].='</div><div style="width:85px;float:left;font-weight:bold;margin-left:10px;overflow:auto;height:90px"><center><b>Profakture</b></center>';
$sql="SELECT `ID`,`brpracuna` FROM prodaja WHERE kupac=$posebno ORDER BY `brpracuna` DESC";
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$passhtml['info'].='<a href="ajax/profakturapa.php?posebno='.$ID.'&redova=0" target="_blank">'.$brpracuna.'</a><br/>';
	$pk++;
}
if ($pk>0) $passhtml['info']= substr($passhtml['info'], 0, -5);
$passhtml['info'].='</div>';

}
$passhtml['sve']='<table style="font-size:12" border="1"><tr><th>Šifra</th><th>Vrsta robe</th><th>Količina</th></tr>';

if ($posebno=="x") {
$sql='SELECT prodajaitems.proizvod sifra, proizvodi.naziv naziv, SUM(prodajaitems.kolicina) kolicina FROM prodajaitems LEFT JOIN prodaja ON prodajaitems.prodaja = prodaja.ID LEFT JOIN proizvodi ON prodajaitems.proizvod = proizvodi.sifra';
}
else {
$sql='SELECT prodajaitems.proizvod sifra, proizvodi.naziv naziv, SUM(prodajaitems.kolicina) kolicina FROM prodajaitems LEFT JOIN prodaja ON prodajaitems.prodaja = prodaja.ID LEFT JOIN proizvodi ON prodajaitems.proizvod = proizvodi.sifra WHERE kupac="'.$posebno.'"';
}
if ($posebno=='x' AND $profe==0) $sql.= ' WHERE brpracuna IS NOT NULL';
elseif ($posebno!='x' AND $profe==0) $sql.=' AND brpracuna IS NOT NULL';

$sql.= ' GROUP BY sifra ORDER BY proizvodi.ID ASC';
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$passhtml['sve'].='<tr><td>'.$sifra.'</td><td>'.$naziv.'</td><td>'.$kolicina.'</td></tr>';
}
$passhtml['sve'].='</table>';
$sql='SELECT partneri.ime ime, partneri.prezime prezime, prodaja.kupac kupac FROM prodaja LEFT JOIN partneri ON prodaja.kupac = partneri.ID WHERE partneri.ID = '.$posebno;
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
$ime=$row['ime'];
$prezime=$row['prezime'];

$passhtml['ko']=$ime.' '.$prezime;
echo json_encode($passhtml);
?>