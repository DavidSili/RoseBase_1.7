<?php
include '../config.php';
$posebno1 = isset($_GET["posebno1"]) ? $_GET["posebno1"] : 0;
$posebno2 = isset($_GET["posebno2"]) ? $_GET["posebno2"] : 0;

if ($posebno1!=$posebno2) {
$imaproizvode=array();
$sort1="";
$sort2="";

$koluk=array();
$sql="SELECT proizvod, sum(kolicina) kolicina FROM zalihe WHERE skladiste=$posebno1 OR skladiste=$posebno2 GROUP BY proizvod";
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$koluk[$proizvod]=$kolicina;
}

$kol1=array();
$sql="SELECT proizvod, kolicina FROM zalihe WHERE skladiste=$posebno1";
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$kol1[$proizvod]=$kolicina;
}
$kol2=array();
$sql="SELECT proizvod, kolicina FROM zalihe WHERE skladiste=$posebno2";
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$kol2[$proizvod]=$kolicina;
}

$sql="SELECT zalihe.proizvod, proizvodi.naziv FROM zalihe LEFT JOIN proizvodi ON proizvodi.sifra = zalihe.proizvod WHERE (zalihe.skladiste=$posebno1 OR zalihe.skladiste=$posebno2) AND kolicina > 0 GROUP BY zalihe.proizvod ORDER BY proizvodi.ID";
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
	$proizvod=$row['proizvod'];
	$naziv=$row['naziv'];
	
	if (isset($proizvod)) {
		if (array_key_exists($proizvod,$kol1)==false) $kol1[$proizvod]=0;
		if (array_key_exists($proizvod,$kol2)==false) $kol2[$proizvod]=0;
		$sort1.='<li class="ui-state-default" id="'.$proizvod.'"><div class="contel">'.$naziv.' ('.$kol1[$proizvod].')</div><input class="contd" type="number" name="elementa'.$proizvod.'" id="elementa'.$proizvod.'" value="'.$kol1[$proizvod].'" style="width:50px" min="0" max="'.$koluk[$proizvod].'" onchange="slajder(\''.$proizvod.'\',2,'.$koluk[$proizvod].','.$kol1[$proizvod].')" /><div class="contc"><input type="range" id="rangea'.$proizvod.'" min="0" max="'.$koluk[$proizvod].'" value="'.$kol1[$proizvod].'" onchange="slajder(\''.$proizvod.'\',1,'.$koluk[$proizvod].','.$kol1[$proizvod].')" style="width:130px" /></div><div class="razlika" id="razlikaa'.$proizvod.'"></div></li>';
		$sort2.='<li class="ui-state-highlight" id="'.$proizvod.'"><div class="contel">'.$naziv.' ('.$kol2[$proizvod].')</div><input class="contd" type="number" name="elementb'.$proizvod.'" id="elementb'.$proizvod.'" value="'.$kol2[$proizvod].'" style="width:50px" min="0" max="'.$koluk[$proizvod].'" onchange="slajder(\''.$proizvod.'\',4,'.$koluk[$proizvod].','.$kol2[$proizvod].')"/><div class="contc"><input type="range" id="rangeb'.$proizvod.'" min="0" max="'.$koluk[$proizvod].'" value="'.$kol2[$proizvod].'" onchange="slajder(\''.$proizvod.'\',3,'.$koluk[$proizvod].','.$kol2[$proizvod].')" style="width:130px" /></div><div class="razlika" id="razlikab'.$proizvod.'"></div></li>';
	}

}

$passhtml['ysort1']=$sort1;
$passhtml['ysort2']=$sort2;
echo json_encode($passhtml);
}
?>