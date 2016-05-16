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
		proizvodi.naziv naziv,
		proizvodi.tezinaneto neto,
		proizvodi.tezinabruto bruto,
		ctarife.sifra csifra,
		ctarife.stopa cstopa,
		proizvodi.pcena pcena,
		proizvodi.pdv pdv
	FROM proizvodi
	LEFT JOIN nabavkaitems
		ON proizvodi.sifra = nabavkaitems.proizvod
		AND `nabavka`=$posebno
	LEFT JOIN ctarife
		ON proizvodi.cartar = ctarife.ID
	ORDER BY proizvodi.ID ASC";
}
else {
	$sql="SELECT proizvodi.ncena ncena,
		proizvodi.kolpak kolpak,
		proizvodi.sifra sifra,
		proizvodi.brend brend,
		proizvodi.naziv naziv,
		proizvodi.tezinaneto neto,
		proizvodi.tezinabruto bruto,
		ctarife.sifra csifra,
		ctarife.stopa cstopa,
		proizvodi.pcena pcena,
		proizvodi.pdv pdv
	FROM proizvodi
	LEFT JOIN ctarife
		ON proizvodi.cartar = ctarife.ID
	ORDER BY proizvodi.ID ASC";
	}
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
foreach($row as $xx => $yy) {
	$$xx=$yy;
}
	$bezpdva=ceil($pcena/((100+$pdv)/10000))/100;
	$distsa=ceil($pcena*80)/100;
	$distbez=ceil($bezpdva*80)/100;

	if (in_array($sifra, $imaproizvode)) {
		$kolkut=$kolicina/$kolpak;
		$sorttu.='<li class="ui-state-highlight" id="'.$sifra.'">
		<div id="ssifra'.$sifra.'" class="ssifralist">['.$sifra.'] </div>
		<div id="id'.$sifra.'" class="idlist"></div>
		<input type="hidden" name="id'.$sifra.'" id="hid'.$sifra.'" />
		<div class="nazivlist" title="'.$naziv.'">'.$naziv.'</div>
		<input class="kolkutlist" type="number" id="kolkut'.$sifra.'" name="kolkut'.$sifra.'" value="'.$kolkut.'" style="width:41px" min="0" onchange="kolitem(\''.$sifra.'\')" />
		<input type="hidden" name="kolpak'.$sifra.'" id="kolpak'.$sifra.'" value="'.$kolpak.'" />
		<input type="hidden" name="kolitem'.$sifra.'" id="kolitem'.$sifra.'" value="'.$kolicina.'" />
		<div id="kollist'.$sifra.'" class="kollist">'.$kolicina.'</div>
		<div id="ncenalist'.$sifra.'" class="ncenalist">'.$ncena.'</div>
		<input type="hidden" name="ncena'.$sifra.'" id="hncena'.$sifra.'" value="'.$ncena.'" />
		<div id="cenauklist'.$sifra.'" class="cenauklist">0</div>
		<div id="netolist'.$sifra.'" class="netolist">'.$neto.'</div>
		<div id="netouklist'.$sifra.'" class="netouklist">0</div>
		<div id="brutolist'.$sifra.'" class="brutolist">'.$bruto.'</div>
		<div id="brutouklist'.$sifra.'" class="brutouklist">0</div>
		<div id="tezinaplist'.$sifra.'" class="tezinaplist">0%</div>
		<input type="hidden" name="tezinap'.$sifra.'" id="htezinap'.$sifra.'" />
		<div id="cenaplist'.$sifra.'" class="cenaplist">0%</div>
		<div id="csifralist'.$sifra.'" class="csifralist">'.$csifra.'</div>
		<div id="cstopalist'.$sifra.'" class="cstopalist">'.$cstopa.'</div>
		<input type="hidden" name="cstopa'.$sifra.'" id="hcstopa'.$sifra.'" value="'.$cstopa.'" />
		<div id="pcarcenalist'.$sifra.'" class="pcarcenalist">0</div>
		<div id="ukcarcenalist'.$sifra.'" class="ukcarcenalist">0</div>
		<div id="pdcenalist'.$sifra.'" class="pdcenalist">0</div>
		<div id="ukdcenalist'.$sifra.'" class="ukdcenalist">0</div>
		<div id="ptrocarlist'.$sifra.'" class="ptrocarlist">0</div>
		<div id="uktrocarlist'.$sifra.'" class="uktrocarlist">0</div>
		<div id="ptrotralist'.$sifra.'" class="ptrotralist">0</div>
		<input type="hidden" name="transportiznos'.$sifra.'" id="htransportiznos'.$sifra.'" />
		<div id="uktrotralist'.$sifra.'" class="uktrotralist">0</div>
		<div id="potrolist'.$sifra.'" class="potrolist">0</div>
		<input type="hidden" name="neptroskovi'.$sifra.'" id="hneptroskovi'.$sifra.'" />
		<div id="ukotrolist'.$sifra.'" class="ukotrolist">0</div>
		<div id="fnclist'.$sifra.'" class="fnclist">0</div>
		<input type="hidden" name="nabcena'.$sifra.'" id="hnabcena'.$sifra.'" />
		<div id="pcblist'.$sifra.'" class="pcblist">'.$bezpdva.'</div>
		<input type="hidden" name="mpbezpdv'.$sifra.'" id="hmpbezpdv'.$sifra.'" value="'.$bezpdva.'" />
		<div id="pcslist'.$sifra.'" class="pcslist">'.$pcena.'</div>
		<input type="hidden" name="mpsapdv'.$sifra.'" id="hmpsapdv'.$sifra.'" value="'.$pcena.'" />
		<div id="cdblist'.$sifra.'" class="cdblist">'.$distbez.'</div>
		<div id="cdslist'.$sifra.'" class="cdslist">'.$distsa.'</div>
		<div id="razlist'.$sifra.'" class="razlist">0</div>
		<input type="hidden" name="razlika'.$sifra.'" id="hrazlika'.$sifra.'" />
		<div id="marzalist'.$sifra.'" class="marzalist">0</div>
		<input type="hidden" name="marza'.$sifra.'" id="hmarza'.$sifra.'" />
		<div id="zar2list'.$sifra.'" class="zar2list">0</div>
		<div id="zar3list'.$sifra.'" class="zar3list">0</div>
		<div id="pdvlist'.$sifra.'" class="pdvlist">'.$pdv.'</div>
		<input type="hidden" name="pdv'.$sifra.'" id="hpdv'.$sifra.'" value="'.$pdv.'" />
		<div id="ccarinalist'.$sifra.'" class="ccarinalist" style="margin-right:0">0</div>
		</li>';
	}
	else {
		$sortostali.='<li class="ui-state-default" id="'.$sifra.'">
		<div id="ssifra'.$sifra.'" class="ssifralist">['.$sifra.'] </div>
		<div id="id'.$sifra.'" class="idlist"></div>
		<input type="hidden" name="id'.$sifra.'" id="hid'.$sifra.'" />
		<div class="nazivlist" title="'.$naziv.'">'.$naziv.'</div>
		<input class="kolkutlist" type="number" id="kolkut'.$sifra.'" name="kolkut'.$sifra.'" value="0" style="width:41px;height:18px" min="0" onchange="kolitem(\''.$sifra.'\')" />
		<input type="hidden" name="kolpak'.$sifra.'" id="kolpak'.$sifra.'" value="'.$kolpak.'" />
		<input type="hidden" name="kolitem'.$sifra.'" id="kolitem'.$sifra.'" value="0" />
		<div id="kollist'.$sifra.'" class="kollist">0</div>
		<div id="ncenalist'.$sifra.'" class="ncenalist">'.$ncena.'</div>
		<input type="hidden" name="ncena'.$sifra.'" id="hncena'.$sifra.'" value="'.$ncena.'" />
		<div id="cenauklist'.$sifra.'" class="cenauklist">0</div>
		<div id="netolist'.$sifra.'" class="netolist">'.$neto.'</div>
		<div id="netouklist'.$sifra.'" class="netouklist">0</div>
		<div id="brutolist'.$sifra.'" class="brutolist">'.$bruto.'</div>
		<div id="brutouklist'.$sifra.'" class="brutouklist">0</div>
		<div id="tezinaplist'.$sifra.'" class="tezinaplist">0%</div>
		<input type="hidden" name="tezinap'.$sifra.'" id="htezinap'.$sifra.'" />
		<div id="cenaplist'.$sifra.'" class="cenaplist">0%</div>
		<div id="csifralist'.$sifra.'" class="csifralist">'.$csifra.'</div>
		<div id="cstopalist'.$sifra.'" class="cstopalist">'.$cstopa.'</div>
		<input type="hidden" name="cstopa'.$sifra.'" id="hcstopa'.$sifra.'" value="'.$cstopa.'" />
		<div id="pcarcenalist'.$sifra.'" class="pcarcenalist">0</div>
		<div id="ukcarcenalist'.$sifra.'" class="ukcarcenalist">0</div>
		<div id="pdcenalist'.$sifra.'" class="pdcenalist">0</div>
		<div id="ukdcenalist'.$sifra.'" class="ukdcenalist">0</div>
		<div id="ptrocarlist'.$sifra.'" class="ptrocarlist">0</div>
		<div id="uktrocarlist'.$sifra.'" class="uktrocarlist">0</div>
		<div id="ptrotralist'.$sifra.'" class="ptrotralist">0</div>
		<input type="hidden" name="transportiznos'.$sifra.'" id="htransportiznos'.$sifra.'" />
		<div id="uktrotralist'.$sifra.'" class="uktrotralist">0</div>
		<div id="potrolist'.$sifra.'" class="potrolist">0</div>
		<input type="hidden" name="neptroskovi'.$sifra.'" id="hneptroskovi'.$sifra.'" />
		<div id="ukotrolist'.$sifra.'" class="ukotrolist">0</div>
		<div id="fnclist'.$sifra.'" class="fnclist">0</div>
		<input type="hidden" name="nabcena'.$sifra.'" id="hnabcena'.$sifra.'" />
		<div id="pcblist'.$sifra.'" class="pcblist">'.$bezpdva.'</div>
		<input type="hidden" name="mpbezpdv'.$sifra.'" id="hmpbezpdv'.$sifra.'" value="'.$bezpdva.'"/>
		<div id="pcslist'.$sifra.'" class="pcslist">'.$pcena.'</div>
		<input type="hidden" name="mpsapdv'.$sifra.'" id="hmpsapdv'.$sifra.'" value="'.$pcena.'" />
		<div id="cdblist'.$sifra.'" class="cdblist">'.$distbez.'</div>
		<div id="cdslist'.$sifra.'" class="cdslist">'.$distsa.'</div>
		<div id="razlist'.$sifra.'" class="razlist">0</div>
		<input type="hidden" name="razlika'.$sifra.'" id="hrazlika'.$sifra.'" />
		<div id="marzalist'.$sifra.'" class="marzalist">0</div>
		<input type="hidden" name="marza'.$sifra.'" id="hmarza'.$sifra.'" />
		<div id="zar2list'.$sifra.'" class="zar2list">0</div>
		<div id="zar3list'.$sifra.'" class="zar3list">0</div>
		<div id="pdvlist'.$sifra.'" class="pdvlist">'.$pdv.'</div>
		<input type="hidden" name="pdv'.$sifra.'" id="hpdv'.$sifra.'" value="'.$pdv.'" />
		<div id="ccarinalist'.$sifra.'" class="ccarinalist" style="margin-right:0">0</div>
		</li>';
	}

}

$passhtml['ysortostali']=$sortostali;
$passhtml['ysorttu']=$sorttu;
echo json_encode($passhtml);
?>