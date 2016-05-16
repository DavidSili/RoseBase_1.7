<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;
$sorttu="";
$imaproizvode=array();
$svesifre="";
$sviID="";
$svekon="";
$sql='SELECT ID, konsignacija FROM prodaja WHERE konsignacija != "" AND (brracuna!="" OR kupac="84")';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while ($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$sviID.=$ID.',';
	$svekon.=$konsignacija.',';
}

$sql='
	SELECT sifra, naziv, SUM(kolicina) kolicina, kolicinaz, ncena FROM ((SELECT proizvodi.ID pID,
		proizvodi.sifra sifra,
		prodajaitems.kolicina kolicina,
		proizvodi.ncena ncena,
		proizvodi.naziv naziv,
		zalihe.kolicina kolicinaz
	FROM prodaja
	LEFT JOIN prodajaitems
		ON prodaja.ID = prodajaitems.prodaja
	LEFT JOIN proizvodi
		ON prodajaitems.proizvod = proizvodi.sifra
    LEFT JOIN zalihe
        ON zalihe.skladiste = "2" AND zalihe.proizvod = prodajaitems.proizvod
	WHERE prodaja.brracuna != "" AND prodaja.konsignacija !="" AND prodaja.konsignacija IS NOT NULL) 
UNION ALL
(SELECT proizvodi.ID pID,
		proizvodi.sifra sifra,
		prodajaitems.kolicina kolicina,
		proizvodi.ncena ncena,
		proizvodi.naziv naziv,
		zalihe.kolicina kolicinaz
	FROM prodaja
	LEFT JOIN prodajaitems
		ON prodaja.ID = prodajaitems.prodaja
	LEFT JOIN proizvodi
		ON prodajaitems.proizvod = proizvodi.sifra
    LEFT JOIN zalihe
        ON zalihe.skladiste = "2" AND zalihe.proizvod = prodajaitems.proizvod
	WHERE prodaja.kupac = "84"  AND prodaja.konsignacija !="" AND prodaja.konsignacija IS NOT NULL) ) t_union
        GROUP BY t_union.sifra
	ORDER BY t_union.pID ASC';

$debug=$sql;
$rb=1;
$ukpredmeta=0;
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while ($row=mysql_fetch_assoc($result)) {
foreach($row as $xx => $yy) {
	$$xx=$yy;
}
	$sorttu.='<div id="red'.$sifra.'" class="redlist">
		<input type="text" name="id'.$sifra.'" id="id'.$sifra.'" class="idlist" value="'.$rb.'" readonly/>
		<div id="sifra'.$sifra.'" class="sifprolist">'.$sifra.'</div>
		<div class="nazivlist" title="'.$naziv.'" style="text-align:left;">'.$naziv.'</div>
		<input class="kolicinalist" type="text" id="kolicina'.$sifra.'" name="kolicina'.$sifra.'" value="'.$kolicina.'" style="width:41px" min="0" readonly/>
		<div id="maxlist'.$sifra.'" class="maxlist">'.$kolicinaz.'</div>
		<div id="ncena'.$sifra.'" class="ncenalist">'.$ncena.'</div>
		<input type="number" id="korekcija'.$sifra.'" value="0" class="korekcijalist" onchange="kolitem(\''.$sifra.'\')"/>
		<input type="text" name="smarza'.$sifra.'" id="smarza'.$sifra.'" class="smarzalist" value="0" readonly>
		<input type="text" name="pojcenabez'.$sifra.'" id="pojcenabez'.$sifra.'" class="pojcenabezlist" readonly>
		<input type="text" name="pojcenasa'.$sifra.'" id="pojcenasa'.$sifra.'" class="pojcenasalist" readonly>
		<input type="text" name="ukcena'.$sifra.'" id="ukcena'.$sifra.'" class="ukcenalist" readonly>
	</div>';
	$svesifre.=$sifra.',';
	$rb++;
	$ukpredmeta=$ukpredmeta+$kolicina;
}
$svesifre=substr($svesifre, 0, -1);
$sviID=substr($sviID, 0, -1);
$svekon=substr($svekon, 0, -1);
$passhtml['ysvesifre']=$svesifre;
$passhtml['ysviID']=$sviID;
$passhtml['ysvekon']=$svekon;
$passhtml['yukpredmeta']=$ukpredmeta;
$passhtml['ysorttu']=$sorttu;
$passhtml['ydebug']=$debug;
echo json_encode($passhtml);
?>