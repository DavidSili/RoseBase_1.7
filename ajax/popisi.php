<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$imaproizvode=array();
$sql="SELECT proizvod FROM zalihe WHERE skladiste=$posebno AND kolicina > 0";
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
	$proizvod=$row['proizvod'];
	$imaproizvode[]=$proizvod;
}

$sortostali="";
$sorttu="";

$sql="SELECT proizvodi.sifrakasa sifkas, proizvodi.sifra sifra, zalihe.kolicina kolicina, proizvodi.brend brend, proizvodi.naziv naziv FROM proizvodi LEFT JOIN zalihe ON proizvodi.sifra = zalihe.proizvod AND `skladiste`=$posebno ORDER BY proizvodi.sifrakasa ASC";
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
foreach($row as $xx => $yy) {
	$$xx=$yy;
}

	if (in_array($sifra, $imaproizvode)) {
		$sorttu.='<li class="ui-state-highlight" id="'.$sifra.'" title="'.$naziv.'"><div class="contkas">'.$sifkas.' |</div><div class="contel">'.$naziv.'</div><input class="contd" type="number" name="element'.$sifra.'" value="'.$kolicina.'" style="width:50px" min="0" /><div class="contc">('.$kolicina.')</div></li>';
	}
	else {
		$sortostali.='<li class="ui-state-default" id="'.$sifra.'" title="'.$naziv.'"><div class="contkas">'.$sifkas.' |</div><div class="contel">'.$naziv.'</div><input class="contd" type="number" name="element'.$sifra.'" value="0" style="width:50px" min="0" /><div class="contc">(0)</div></li>';
	}

}

$passhtml['ysortostali']=$sortostali;
$passhtml['ysorttu']=$sorttu;
echo json_encode($passhtml);
?>