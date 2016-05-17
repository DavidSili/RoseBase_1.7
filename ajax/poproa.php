<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$passhtml=array();

$sql='SELECT ID, naziv, ncena, pcena FROM proizvodi WHERE sifra = "'.$posebno.'"';
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
$ID=$row['ID'];
$naziv=$row['naziv'];
$ncena=$row['ncena'];
$pcena=$row['pcena'];
$ncena=number_format($ncena, 2, '.', ',');

$sql='SELECT SUM(kolicina) kolicina, SUM(kolicina*nabcena) nabcenasve FROM nabavkaitems WHERE proizvod = "'.$posebno.'"';
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
$nabkol=$row['kolicina'];
$nabcenasve=$row['nabcenasve'];
$nabcenasvef=number_format($nabcenasve, 2, '.', ',');
$nabcena=$nabcenasve/$nabkol;
$nabcena=number_format($nabcena, 2, '.', ',');

$sql='SELECT SUM( kolicina ) kolicina, SUM( kolicina * mpbezpdv * ( 100 + pdv ) * ( 100 - rabat ) /10000 ) pcena, SUM( rabat * kolicina ) popust FROM prodajaitems WHERE proizvod ="'.$posebno.'"';
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
$prodkol=$row['kolicina'];
$popusti=$row['popust'];
$prodcenasve=$row['pcena'];
$propopusti=$popusti/$prodkol;
$propopusti=number_format($propopusti, 2, '.', ',');
$prodcenasvef=number_format($prodcenasve, 2, '.', ',');
$prodcena=$prodcenasve/$prodkol;
$prodcena=number_format($prodcena, 2, '.', ',');

$ukzarada=$prodcenasve-$nabcenasve;
$ukzaradaf=number_format($ukzarada, 2, '.', ',');
$prozarada=$prodcena-$nabcena;
$prozarada=number_format($prozarada, 2, '.', ',');
$pprozarada=$prozarada/$nabcena*100;
$pprozarada=number_format($pprozarada, 2, '.', ',');

$passhtml['gore']='<div style="float:left;min-width:200px;max-width:300px;height:120px;padding:5px 10px 0 0"><b>ID: </b>'.$ID.'<br><b>Naziv: </b>'.$naziv.'<br><b>Nabavna cena: </b>'.$ncena.' €<br><b>Prodajna cena: </b>'.$pcena.' RSD<br><b>Nabavljeno proizvoda: </b>'.$nabkol.'<br><b>Prodato proizvoda: </b>'.$prodkol.'</div><div style="float:left;min-width:220px;max-width:300px;height:120px;padding-top:5px"><b>FNC ukupno: </b>'.$nabcenasvef.' RSD<br><b>Ukupna prodajna cena: </b>'.$prodcenasvef.' RSD<br><b>Ukupna zarada: </b>'.$ukzaradaf.' RSD<br><b>Prosečna FNC: </b>'.$nabcena.' RSD<br><b>Prosečna prodajna cena: </b>'.$prodcena.' RSD<br><b>Prosečna zarada po komadu: </b>'.$prozarada.' RSD<br><b>Prosečni popusti: </b>'.$propopusti.' %<br><b>Procenat zarade: </b>'.$pprozarada.' %</div>';

$passhtml['ostalo']='<table border="1" style="font-size:12;text-align:right"><tr style="text-align:center"><th>Datum</th><th>Količina</th><th>Cena</th><th title="*Ukoliko je nabavka, onda ovo polje ne važi">Rabat*</th><th title="*Ukoliko je nabavka, onda ovo polje ne važi">PDV*</th><th>Ukupno</th><th title="*Ukoliko je nabavka, onda ovo polje ne važi">Profaktura*</th><th title="*Ukoliko je nabavka, onda ovo polje ne važi">Faktura*</th></tr>';

$sql='(SELECT nabavkaitems.kolicina kolicina, nabavkaitems.nabcena cena, nabavka.datprijemarobe datum, "" pdv, "" rabat, "1" marker, "" prodaja, "" brracuna, "" brpracuna FROM nabavkaitems LEFT JOIN nabavka ON nabavkaitems.nabavka = nabavka.ID WHERE proizvod = "'.$posebno.'") UNION (SELECT prodajaitems.kolicina kolicina, (prodajaitems.mpbezpdv*(prodajaitems.pdv+100)*(100-prodajaitems.rabat)/10000) cena, prodaja.datprometa datum, prodajaitems.pdv pdv, prodajaitems.rabat rabat, "2" marker, prodajaitems.prodaja prodaja, prodaja.brracuna brracuna, prodaja.brpracuna brpracuna FROM prodajaitems LEFT JOIN prodaja ON prodajaitems.prodaja = prodaja.ID WHERE proizvod =  "'.$posebno.'") ORDER BY datum DESC';
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {
$kolicina=$row['kolicina'];
$cena=$row['cena'];
$datum=$row['datum'];
$pdv=$row['pdv'];
$rabat=$row['rabat'];
$marker=$row['marker'];
$prodaja=$row['prodaja'];
$brpracuna=$row['brpracuna'];
$brracuna=$row['brracuna'];
$ukupno=$kolicina*$cena;

if ($datum != "0000-00-00") $datum=date('d.m.Y.',strtotime($datum));
	else $datum="";
$cena=number_format($cena, 2, '.', ',');
$ukupno=number_format($ukupno, 2, '.', ',');

$passhtml['ostalo'].='<tr  bgcolor="#';
if ($marker==1) $passhtml['ostalo'].='bcf';
elseif ($brpracuna == "") $passhtml['ostalo'].='bfa';
else  $passhtml['ostalo'].='dfc';
$passhtml['ostalo'].='"><td>'.$datum.'</td><td>'.$kolicina.'</td><td>'.$cena.'</td><td>'.$rabat.'</td><td>'.$pdv.'</td><td>'.$ukupno.'</td><td>';
if ($brpracuna != "") $passhtml['ostalo'].='<a href="ajax/profakturapa.php?posebno='.$prodaja.'&redova=0" target="_blank">'.$brpracuna.'</a>';
$passhtml['ostalo'].='</td><td>';
if ($brracuna != NULL AND $brracuna != "") $passhtml['ostalo'].='<a href="ajax/fakturapa.php?posebno='.$prodaja.'&redova=0" target="_blank">'.$brracuna.'</a>';
$passhtml['ostalo'].='</td></tr>';

}
$passhtml['ostalo'].='</table>';
echo json_encode($passhtml);
?>