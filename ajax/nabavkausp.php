<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;
$sortostali="";
$sorttu="";
$imaproizvode=array();

if ($posebno!="x") {
$sql="SELECT proizvod FROM nabavkaitems WHERE nabavka=$posebno";
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
	$proizvod=$row['proizvod'];
	$imaproizvode[]=$proizvod;
}

	$sql="SELECT proizvodi.ncena ncena,
		proizvodi.kolpak kolpak,
		proizvodi.sifra sifra,
		nabavkaitems.kolicina kolicina,
		proizvodi.brend brend,
		proizvodi.naziv naziv
	FROM proizvodi
	LEFT JOIN nabavkaitems
		ON proizvodi.sifra = nabavkaitems.proizvod
		AND `nabavka`=$posebno
	ORDER BY proizvodi.ID ASC";
}
else {
	$sql="SELECT proizvodi.ncena ncena,
		proizvodi.kolpak kolpak,
		proizvodi.sifra sifra,
		proizvodi.brend brend,
		proizvodi.naziv naziv
	FROM proizvodi
	ORDER BY proizvodi.ID ASC";
	}
$debug=$sql;
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
foreach($row as $xx => $yy) {
	$$xx=$yy;
}

	if (in_array($sifra, $imaproizvode)) {
		$kolkut=$kolicina/$kolpak;
		$sorttu.='<li class="ui-state-highlight" id="'.$sifra.'">
		<div id="id'.$sifra.'" class="idlist"></div>
		<div id="ssifra'.$sifra.'" class="ssifralist">'.$sifra.'</div>
		<input type="hidden" name="id'.$sifra.'" id="hid'.$sifra.'" />
		<div class="nazivlist" title="'.$naziv.'" style="text-align:left">'.$naziv.'</div>
		<input class="kolkutlist" type="text" id="kolkut'.$sifra.'" value="'.$kolkut.'" style="width:41px" min="0" onchange="kolitem(\''.$sifra.'\')" />
		<input type="hidden" id="kolpak'.$sifra.'" value="'.$kolpak.'" />
		<input type="hidden" name="kolitem'.$sifra.'" id="kolitem'.$sifra.'" value="'.$kolicina.'" />
		<div id="kollist'.$sifra.'" class="kollist">'.$kolicina.'</div>
		<div id="ncenalist'.$sifra.'" class="ncenalist">'.$ncena.'</div>
		<input type="hidden" name="ncena'.$sifra.'" id="hncena'.$sifra.'" value="'.$ncena.'" />
		<div id="cenauklist'.$sifra.'" class="cenauklist" style="margin-right:0">0</div>
		</li>';
	}
	else {
		$sortostali.='<li class="ui-state-default" id="'.$sifra.'">
		<div id="id'.$sifra.'" class="idlist"></div>
		<div id="ssifra'.$sifra.'" class="ssifralist">'.$sifra.'</div>
		<input type="hidden" name="id'.$sifra.'" id="hid'.$sifra.'" />
		<div class="nazivlist" title="'.$naziv.'" style="text-align:left">'.$naziv.'</div>
		<input class="kolkutlist" type="text" id="kolkut'.$sifra.'" value="0" style="width:41px;height:18px" min="0" onchange="kolitem(\''.$sifra.'\')" />
		<input type="hidden" id="kolpak'.$sifra.'" value="'.$kolpak.'" />
		<input type="hidden" name="kolitem'.$sifra.'" id="kolitem'.$sifra.'" value="0" />
		<div id="kollist'.$sifra.'" class="kollist">0</div>
		<div id="ncenalist'.$sifra.'" class="ncenalist">'.$ncena.'</div>
		<input type="hidden" name="ncena'.$sifra.'" id="hncena'.$sifra.'" value="'.$ncena.'" />
		<div id="cenauklist'.$sifra.'" class="cenauklist" style="margin-right:0">0</div>
		</li>';
	}

}
$passhtml['ydebug']=$debug;
$passhtml['ysortostali']=$sortostali;
$passhtml['ysorttu']=$sorttu;
echo json_encode($passhtml);
?>