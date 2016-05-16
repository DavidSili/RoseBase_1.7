<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;
$skladiste = isset($_GET["skladiste"]) ? $_GET["skladiste"] : 0;
$osoba = isset($_GET["osoba"]) ? $_GET["osoba"] : 0;

$sql='SELECT partneri.ID partner, gpartnera.cena cena FROM partneri LEFT JOIN gpartnera ON partneri.gpartnera = gpartnera.ID WHERE partneri.ID = "'.$osoba.'"';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
$row=mysql_fetch_assoc($result);
$cena=$row['cena'];
switch ($cena) {
    case 1:
        $cena="0.00";
        break;
    case 2:
        $cena="20.00";
        break;
    case 3:
        $cena="25.00";
        break;
    case 4:
        $cena="30.00";
        break;
}

$sortostali="";
$sorttu="";
$imaproizvode=array();
if ($skladiste==0) {
$sql='SELECT ID FROM skladista WHERE status="da" ORDER BY ID ASC LIMIT 1';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
$row=mysql_fetch_assoc($result);
$skladiste=$row['ID'];
}

$sql="SELECT proizvod, max(nabcena) fnc FROM nabavkaitems GROUP BY proizvod";
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while ($row=mysql_fetch_assoc($result)) {
	$proizvod=$row['proizvod'];
	$fnc=$row['fnc'];
	$finc[$proizvod]=$fnc;
}

if ($posebno!="x") {
$sql="SELECT datprometa, bifr FROM prodaja WHERE ID=$posebno";
$result=mysql_query($sql) or die($sql.': '.mysql_error());
$row=mysql_fetch_assoc($result);
$datprometa=$row['datprometa'];
$bifr=$row['bifr'];

$sql="SELECT proizvod FROM prodajaitems WHERE prodaja=$posebno";
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while ($row=mysql_fetch_assoc($result)) {
	$proizvod=$row['proizvod'];
	$imaproizvode[]=$proizvod;
}

	$sql="SELECT proizvodi.sifra sifra,
		proizvodi.sifrakasa sifkas,
		proizvodi.pcena pcena,
		prodajaitems.kolicina kolicina,
		prodajaitems.rabat rabat,
		prodajaitems.zarada zarada,
		proizvodi.naziv naziv,
		proizvodi.pdv pdv,
        proizvodi.tezinaneto neto,
        proizvodi.tezinabruto bruto,
        zalihe.kolicina kolicinaz
	FROM proizvodi
	LEFT JOIN prodajaitems
		ON proizvodi.sifra = prodajaitems.proizvod
		AND prodajaitems.prodaja = $posebno
    INNER JOIN zalihe
        ON proizvodi.sifra = zalihe.proizvod
    WHERE zalihe.skladiste = $skladiste
	ORDER BY proizvodi.ID ASC";
}
else {
$datprometa=date('d.m.Y.');
$bifr=1;

	$sql="SELECT proizvodi.sifra sifra,
		proizvodi.sifrakasa sifkas,
		proizvodi.pcena pcena,
		proizvodi.naziv naziv,
		proizvodi.pdv pdv,
        proizvodi.tezinaneto neto,
        proizvodi.tezinabruto bruto,
        zalihe.kolicina kolicinaz
	FROM proizvodi
	INNER JOIN zalihe
		ON proizvodi.sifra = zalihe.proizvod
        WHERE zalihe.skladiste = $skladiste
	ORDER BY proizvodi.ID ASC";
	}
$debug=$sql;
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while ($row=mysql_fetch_assoc($result)) {
foreach($row as $xx => $yy) {
	$$xx=$yy;
}
	$bezpdva=ceil($pcena/((100+$pdv)/10000))/100;
	if (empty($bruto)) $tezina=$neto;
	else $tezina=$bruto;
	
	if (in_array($sifra, $imaproizvode)) {
		$kolicinazm=$kolicinaz+$kolicina;
		$sorttu.='<li class="ui-state-highlight" id="'.$sifra.'">
		<div id="id'.$sifra.'" class="idlist"></div>
		<input type="hidden" name="id'.$sifra.'" id="hid'.$sifra.'" />
		<div id="sifkas'.$sifra.'" class="sifkaslist">'.$sifkas.'</div>
		<div class="nazivlist" title="'.$naziv.'" style="text-align:left;">'.$naziv.'</div>
		<input class="kolicina" type="number" id="kolicina'.$sifra.'" name="kolicina'.$sifra.'" value="'.$kolicina.'" style="width:41px" min="0" max="'.$kolicinazm.'" onchange="kolitem(\''.$sifra.'\')"/>
		<div id="maxlist'.$sifra.'" class="maxlist">'.$kolicinaz.'</div>
		<div id="fnclist'.$sifra.'" class="fnclist">'.$finc[$sifra].'</div>
		<div id="pcenalist'.$sifra.'" class="pcenalist">'.$bezpdva.'</div>
		<div id="zaradalist'.$sifra.'" class="zaradalist">0.00</div>
		<div id="zaradaplist'.$sifra.'" class="zaradaplist">0.00</div>
		<input type="hidden" name="pcena'.$sifra.'" id="hpcena'.$sifra.'" value="'.$bezpdva.'" />
		<input type="hidden" name="tezina'.$sifra.'" id="tezina'.$sifra.'" value="'.$tezina.'" />
		<input type="hidden" name="tezinauk'.$sifra.'" id="tezinauk'.$sifra.'" />
		<input type="hidden" name="uctez'.$sifra.'" id="uctez'.$sifra.'" />
		<div id="ptisporukelist'.$sifra.'" class="ptisporukelist">0.00</div>
		<div id="pcenasplist'.$sifra.'" class="pcenasplist">'.$pcena.'</div>
		<input class="rabatlist" type="text" id="rabatlist'.$sifra.'" name="rabat'.$sifra.'" value="'.$rabat.'" style="width:41px" onchange="kolitem(\''.$sifra.'\')" />
		<div id="pdvlist'.$sifra.'" class="pdvlist">'.$pdv.'</div>
		<input type="hidden" name="pdvlist'.$sifra.'" id="hpdvlist'.$sifra.'" value="'.$pdv.'" />
		<div id="pdvvredlist'.$sifra.'" class="pdvvredlist">0.00</div>
		<div id="ukbezpopsapdvlist'.$sifra.'" class="ukbezpopsapdvlist">0.00</div>
		<div id="popustlist'.$sifra.'" class="popustlist">0.00</div>
		<div id="uksapopbezpdvlist'.$sifra.'" class="uksapopbezpdvlist">0.00</div>
		<div id="cenauklist'.$sifra.'" class="cenauklist">0.00</div>
		<div id="zaradauklist'.$sifra.'" class="zaradauklist">0.00</div>
		<input type="hidden" name="zaradauklist'.$sifra.'" id="zaradauklistx'.$sifra.'" value="'.$zarada.'" />
		</li>';
	}
	elseif ($kolicinaz>0) {
		$sortostali.='<li class="ui-state-highlight" id="'.$sifra.'">
		<div id="id'.$sifra.'" class="idlist"></div>
		<input type="hidden" name="id'.$sifra.'" id="hid'.$sifra.'" />
		<div id="sifkas'.$sifra.'" class="sifkaslist">'.$sifkas.'</div>
		<div class="nazivlist" title="'.$naziv.'" style="text-align:left;">'.$naziv.'</div>
		<input class="kolicina" type="number" id="kolicina'.$sifra.'" name="kolicina'.$sifra.'" value="0" style="width:41px" min="0" max="'.$kolicinaz.'" onchange="kolitem(\''.$sifra.'\')" />
		<div id="maxlist'.$sifra.'" class="maxlist">'.$kolicinaz.'</div>
		<div id="fnclist'.$sifra.'" class="fnclist">'.$finc[$sifra].'</div>
		<div id="pcenalist'.$sifra.'" class="pcenalist">'.$bezpdva.'</div>
		<div id="zaradalist'.$sifra.'" class="zaradalist">0.00</div>
		<div id="zaradaplist'.$sifra.'" class="zaradaplist">0.00</div>
		<input type="hidden" name="pcena'.$sifra.'" id="hpcena'.$sifra.'" value="'.$bezpdva.'" />
		<input type="hidden" name="tezina'.$sifra.'" id="tezina'.$sifra.'" value="'.$tezina.'" />
		<input type="hidden" name="tezinauk'.$sifra.'" id="tezinauk'.$sifra.'" />
		<input type="hidden" name="uctez'.$sifra.'" id="uctez'.$sifra.'" />
		<div id="ptisporukelist'.$sifra.'" class="ptisporukelist">0.00</div>
		<div id="pcenasplist'.$sifra.'" class="pcenasplist">'.$pcena.'</div>
		<input class="rabatlist" type="text" id="rabatlist'.$sifra.'" name="rabat'.$sifra.'" value="'.$cena.'" style="width:41px" onchange="kolitem(\''.$sifra.'\')" />
		<div id="pdvlist'.$sifra.'" class="pdvlist">'.$pdv.'</div>
		<input type="hidden" name="pdvlist'.$sifra.'" id="hpdvlist'.$sifra.'" value="'.$pdv.'" />
		<div id="pdvvredlist'.$sifra.'" class="pdvvredlist">0.00</div>
		<div id="ukbezpopsapdvlist'.$sifra.'" class="ukbezpopsapdvlist">0.00</div>
		<div id="popustlist'.$sifra.'" class="popustlist">0.00</div>
		<div id="uksapopbezpdvlist'.$sifra.'" class="uksapopbezpdvlist">0.00</div>
		<div id="cenauklist'.$sifra.'" class="cenauklist">0.00</div>
		<div id="zaradauklist'.$sifra.'" class="zaradauklist">0.00</div>
		<input type="hidden" name="zaradauklist'.$sifra.'" id="zaradauklistx'.$sifra.'" value="0.00" />
		</li>';
	}
}

$passhtml['ysortostali']=$sortostali;
$passhtml['ysorttu']=$sorttu;
$passhtml['ydatprometa']=$datprometa;
$passhtml['ybifr']=$bifr;
$passhtml['ydebug']=$debug;
echo json_encode($passhtml);
?>