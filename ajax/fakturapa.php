﻿<?php
	session_start();
	$uri=$_SERVER['REQUEST_URI'];
	$pos = strrpos($uri, "/");
	$url = substr($uri, $pos+1);
	if ($_SESSION['loggedin'] != 1 OR $_SESSION['level'] < 3 ) {
		header("Location: login.php?url=$url");
		exit;
	}
	else {
	include '../config.php';
	$level=$_SESSION['level'];
	$user=$_SESSION['user'];
	}

?>
<html>
<head profile="http://www.w3.org/2005/20/profile">
<link rel="icon"
	  type="image/png"
	  href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title id="Timerhead">Faktura - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<style type="text/css">
#header {
	position:absolute;
	top:10px;
	left:0px;
	right:0px;
	font-size:12;
	padding:3px;
	display:block;
}
#desniwrap {
	margin-bottom:10px;
	font-size:12;
	padding:3px;
	display:block;
}
#footer {
	font-size:12;
	padding:3px;
	display:block;
	margin-top:5px;
}
table, th, td {
	border: 1px solid black;
	border-collapse:collapse;
	border-spacing:0px;
	padding:1px 3px;
	font-size:12;
}
td {
	text-align:right;
}
</style>
<meta name="robots" content="noindex">
</head>
<body>
<?php
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;
$redova = isset($_GET["redova"]) ? $_GET["redova"] : 0;
$redovi=explode(',',$redova);
$brstranica=count($redovi);

if ($posebno!=0) $sql="SELECT * FROM prodaja WHERE `ID`=$posebno";
else $sql="SELECT * FROM prodaja WHERE `brracuna` IS NOT NULL AND `brracuna` !='' ORDER BY `ID` DESC LIMIT 1";
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
foreach($row as $xx => $yy) {
	$$xx=$yy;
}

$sql='SELECT naziv nazivsklad FROM skladista WHERE ID="'.$skladiste.'"';
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
$nazivsklad=$row['nazivsklad'];
if ($nazivsklad != "Pančevo" OR $nazivsklad != "Biofresh") {
	$sql='SELECT skladista.naziv nazivsklad, skladista.adresa adresasklad, partneri.ime imeosobe, partneri.prezime prezimeosobe, partneri.gpartnera gpartneraf, partneri.ulicaibr ulicaibrf, partneri.mesto mestof, pobroj.broj pobrojf, partneri.drzava drzavaf, partneri.firma firmaf, partneri.pib pibf, partneri.maticni maticnif, partneri.telefon telefonf, partneri.email emailf FROM skladista LEFT JOIN partneri ON skladista.oosoba = partneri.ID LEFT JOIN pobroj ON partneri.mesto = pobroj.mesto WHERE skladista.ID="'.$skladiste.'"';
	$result=mysqli_query($mysqli,$sql) or die;
	$row=$result->fetch_assoc();
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
}

$sql='SELECT SUM(kolicina) kontkol FROM prodajaitems WHERE prodaja="'.$posebno.'"';
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
$kontkol=$row['kontkol'];

$sql='SELECT partneri.gpartnera gpartnera, partneri.ime ime, partneri.prezime prezime, partneri.ulicaibr ulicaibr, partneri.mesto mesto, partneri.firma firma, partneri.pib pib, partneri.telefon telefon, pobroj.broj pobroj FROM partneri LEFT JOIN pobroj ON partneri.mesto = pobroj.mesto WHERE partneri.ID="'.$kupac.'"';
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
foreach($row as $xx => $yy) {
	$$xx=$yy;
}

$datprometa=date('d.m.Y.',strtotime($datprometa));
$rok=date('d.m.Y.',strtotime($rok));

if ($gpartnera> 4 AND $gpartnera<9) $levibox='<b>'.$firma.'</b><br/><br/>'.$ulicaibr.'<br/>'.$pobroj.' '.$mesto.'<br/>PIB: '.$pib;
else $levibox='<b>'.$ime.' '.$prezime.'</b><br/><br/>'.$ulicaibr.'<br/>'.$pobroj.' '.$mesto.'<br/>tel: '.$telefon;

$danas=date('d.m.Y.');
$desnibox='<b>Račun-Otpremnica broj: '.$brracuna.'</b><br/><br/>Rok plaćanja: '.$rok.'<br/>Datum prometa: '.$datprometa.'<br/><br/><b>TR: '.$brracunau.'<br/>Poziv: '.$pozivnb.'</b>';
$sve='';
$ukpdv=0;
$ukcena=0;
$ukosnovica=0;
if ($brstranica==1) {
$sve.='<table style="font-size:12" border="1"><tr style="background:#ddd"><th style="width:16px">Br.</th><th style="width:50px">Šifra proizvoda</th><th>Vrsta robe - usluga</th><th style="width:20px">Kol.</th><th>JM</th><th style="width:68px">MPC</th><th style="width:40px">R %</th><th style="width:74px">Osnovica</th><th style="width:62px">PDV</th><th style="width:74px">Vred. sa PDV</th></tr>';

$sql='SELECT prodajaitems.iduprodaji iduprodaji, prodajaitems.proizvod proizvod, prodajaitems.kolicina kolicina, prodajaitems.mpbezpdv mpbezpdv, prodajaitems.rabat rabat, prodajaitems.pdv pdv, proizvodi.naziv naziv, proizvodi.pcena pcena, proizvodi.sifrakasa sifkas FROM prodajaitems LEFT JOIN proizvodi ON prodajaitems.proizvod = proizvodi.sifra WHERE prodajaitems.prodaja="'.$ID.'" ORDER BY prodajaitems.iduprodaji ASC';
$result=mysqli_query($mysqli,$sql) or die;
while ($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$ppdv=($kolicina*$mpbezpdv*(100-$rabat)/100)*$pdv/100;
	$ppcena=$kolicina*$pcena*(100-$rabat)/100;
	$osnovica=$ppcena-$ppdv;
	$ukpdv=$ukpdv+$ppdv;
	$ukcena=$ukcena+$ppcena;
	$ukosnovica=$ukosnovica+$osnovica;
	$pcena=number_format($pcena, 2, ',', '.');
	$osnovica=number_format($osnovica, 2, ',', '.');
	$ppdv=number_format($ppdv, 2, ',', '.');
	$ppcena=number_format($ppcena, 2, ',', '.');
	$sve.='<tr><td style="text-align:center">'.$iduprodaji.'</td><td style="text-align:center">'.$proizvod.'</td><td style="text-align:left;max-width:300px">'.$naziv.'</td><td style="text-align:center">'.$kolicina.'</td><td style="text-align:center">kom</td><td>'.$pcena.' RSD</td><td>'.$rabat.' %</td><td>'.$osnovica.' RSD</td><td>'.$ppdv.' RSD</td><td>'.$ppcena.' RSD</td></tr>';
}
$sve.='</table>';
}
else {
	$staro=0;
	foreach($redovi as $xx => $yy) {
		$stranicabr=$xx+1;
		$sve[$stranicabr]='<table style="font-size:12" border="1"><tr style="background:#ddd"><th stlye="width:16px">Br.</th><th style="width:50px">Šifra proizvoda</th><th>Vrsta robe - usluga</th><th style="width:20px">Kol.</th><th>JM</th><th style="width:68px">MPC</th><th style="width:40px">R %</th><th style="width:74px">Osnovica</th><th style="width:68px">PDV</th><th style="width:74px">Vred. sa PDV</th></tr>';

		if ($stranicabr==1) {
			$sql='SELECT prodajaitems.iduprodaji iduprodaji, prodajaitems.proizvod proizvod, prodajaitems.kolicina kolicina, prodajaitems.mpbezpdv mpbezpdv, prodajaitems.rabat rabat, prodajaitems.pdv pdv, proizvodi.naziv naziv, proizvodi.pcena pcena, proizvodi.sifrakasa sifkas FROM prodajaitems LEFT JOIN proizvodi ON prodajaitems.proizvod = proizvodi.sifra WHERE prodajaitems.prodaja="'.$ID.'" ORDER BY prodajaitems.iduprodaji ASC LIMIT '.$yy;
		$prosli=$xx;
		}
		else {
		$prosli=$xx;
		$sql='SELECT prodajaitems.iduprodaji iduprodaji, prodajaitems.proizvod proizvod, prodajaitems.kolicina kolicina, prodajaitems.mpbezpdv mpbezpdv, prodajaitems.rabat rabat, prodajaitems.pdv pdv, proizvodi.naziv naziv, proizvodi.pcena pcena, proizvodi.sifrakasa sifkas FROM prodajaitems LEFT JOIN proizvodi ON prodajaitems.proizvod = proizvodi.sifra WHERE prodajaitems.prodaja="'.$ID.'" ORDER BY prodajaitems.iduprodaji ASC LIMIT '.$staro.','.$yy;
		}
		$result=mysqli_query($mysqli,$sql) or die;
		$redbroj=0;
		while ($row=$result->fetch_assoc()) {
			foreach($row as $xx => $yy) {
				$$xx=$yy;
			}
		
		$ppdv=($kolicina*$mpbezpdv*(100-$rabat)/100)*$pdv/100;
		$ppcena=$kolicina*$pcena*(100-$rabat)/100;
		$osnovica=$ppcena-$ppdv;
		$ukpdv=$ukpdv+$ppdv;
		$ukcena=$ukcena+$ppcena;
		$ukosnovica=$ukosnovica+$osnovica;
		$pcena=number_format($pcena, 2, ',', '.');
		$osnovica=number_format($osnovica, 2, ',', '.');
		$ppdv=number_format($ppdv, 2, ',', '.');
		$ppcena=number_format($ppcena, 2, ',', '.');
		$sve[$stranicabr].='<tr><td style="text-align:center">'.$iduprodaji.'</td><td style="text-align:center">'.$proizvod.'</td><td style="text-align:left;max-width:300px">'.$naziv.'</td><td style="text-align:center">'.$kolicina.'</td><td style="text-align:center">kom</td><td>'.$pcena.' RSD</td><td>'.$rabat.' %</td><td>'.$osnovica.' RSD</td><td>'.$ppdv.' RSD</td><td>'.$ppcena.' RSD</td></tr>';
			}
		$sve[$stranicabr].='</table>';
		$staro=$staro+$redovi[$prosli];
		
	}
	$desnibox='<b>Račun otpremnica: '.$brracuna.'</b><br/><br/><b>Rok plaćanja: '.$rok.'</b><br/>Datum prometa: '.$datprometa.'<br/><br/><b>TR: '.$brracunau.'</b><br/><b>Poziv: '.$pozivnb.'</b>';


}

	if ($bezpopusta=="" OR $bezpopusta==0) $popust="0";
	else $popust=$bezpopusta-$zauplatu;

	$bezpopusta=number_format($bezpopusta, 2, ',', '.');
	$popust=number_format($popust, 2, ',', '.');
	$bezpdva=number_format($ukosnovica, 2, ',', '.');
	$iznospdv=number_format($ukpdv, 2, ',', '.');
	$zauplatu=number_format($ukcena, 2, ',', '.');

$dolbox=$bezpopusta.' RSD<br/>'.$popust.' RSD<br/>'.$bezpdva.' RSD<br/>'.$iznospdv.' RSD<br/>'.$zauplatu.' RSD';

foreach($redovi as $xx => $yy) {

	if (isset($emailf)==false) $emailf="";
	if (isset($telefonf)==false) $telefonf="";
	if (isset($ulicaibrf)==false) $ulicaibrf="";
	if (isset($maticnif)==false) $maticnif="";
	if (isset($pobrojf)==false) $pobrojf="";
	if (isset($mestof)==false) $mestof="";
	if (isset($drzavaf)==false) $drzavaf="";
	if (isset($firmaf)==false) $firmaf="";
	if (isset($pibf)==false) $pibf="";
	if (isset($imeosobe)==false) $imeosobe="";
	if (isset($prezimeosobe)==false) $prezimeosobe="";
	
	if ($brstranica==1) {
		echo '<div id="header">
	<div style="overflow:hidden">';
	
		if ($nazivsklad == "Pančevo") echo '<img src="../images/logoLoR.jpg" style="float:left;height:100px"/><div style="float:right;text-align:right">LAND OF ROSES DOO Pančevo,<br/>26000, ul. Karađorđeva 30<br/>Matični broj: 20945508<br/>PIB: 108173517<br/>Tel: +381 60 8080 613<br/>br. računa: 220-130624-03<br/>web: www.bugarska-ruza.rs <br/>e-mai: office@bugarska-ruza.rs</div>';
		elseif ($nazivsklad == "Biofresh") echo '<img src="../images/logoBF.png" style="float:left;height:100px"/><div style="float:right;text-align:right">Bio Fresh doo Pančevo,<br/>26000, ul. Karađorđeva 30<br/>Matični broj: 21060534<br/>PIB: 108751622<br/>Tel: +381 60 8080 613<br/>br. računa: 180-1281210042203-84<br/>web: www.bugarska-ruza.rs <br/>e-mai: office@bugarska-ruza.rs</div>';
		elseif ($gpartneraf > 4 AND $gpartneraf < 9) {
		echo '<div style="float:right;text-align:right">'.$firmaf.'<br/>'.$pobrojf.' '.$mestof.', '.$ulicaibrf;
			if ($drzavaf!="Srbija") echo $mestof.', '.$drzavaf.'<br/>';
		echo '<br/>Matični broj: '.$maticnif.'<br/>PIB: '.$pibf.'<br/>Tel: '.$telefonf.'<br/>e-mai: '.$emailf.'</div>';
		}
		else {
		echo '<div style="float:right;text-align:right">'.$imeosobe.' '.$prezimeosobe.'<br/>'.$pobrojf.' '.$mestof.', '.$ulicaibrf;
			if ($drzavaf!="Srbija") echo $mestof.', '.$drzavaf.'<br/>';
		echo '<br/>Tel: '.$telefonf.'<br/>e-mai: '.$emailf.'</div>';
		}
	echo '</div>
	<div style="overflow:hidden;margin-top:0.5cm;font-size:14">
		<div style="float:left;text-align:center;margin-left:2cm">'.$levibox.'</div>
		<div style="float:right;text-align:center;margin-right:2cm">'.$desnibox.'</div>
	</div>
</div>
<div id="pagewrap" style="height:26.5cm">
	<div id="desniwrap" style="overflow:hidden">
		<div id="tabwrap" style="margin-top:260px;height:11cm">'.$sve.'</div>
		<div style="float:right; min-width:170px;margin-top:60px">
			<div style="float:left;text-align:right">Kontrolna količina:<br/>Iznos bez popusta:<br/>Popust:<br/>Osnovica:<br/>PDV:<br/><b>Za uplatu RSD:</b></div>
			<div style="float:right;margin-left:10px">
				<div style="text-align:center">'.$kontkol.'</div>
				<div style="text-align:right">'.$dolbox.'</div>
			</div>
		</div>
	</div>
	<div id="footer" style="position:absolute;bottom:30px;left:0;right:0">
	<div style="overflow:hidden">
		<div style="float:left;margin-left:1cm;text-align:center">Račun otpremnice izdao<br/><br/><br/>_____________________________________<br/>(potpis i pečat)</div>
	</div><br/>
	<div style="border-top:1px solid #000;padding-top:1px;width:100%;text-align:center">Tel: +381 60 8080 613; e-mail: office@bugarska-ruza.rs; web: www.bugarska-ruza.rs</div>
	</div>
</div>';
	}
	else {
		$stranicabr=$xx+1;
		if ($stranicabr==1) {
			echo '<div id="header">
	<div style="overflow:hidden">
		<img src="../images/logoLoR.jpg" style="float:left;width:12cm"/>
		<div style="float:right;text-align:right">LAND OF ROSES DOO Pančevo,<br/>26000, ul. Karađorđeva 30<br/>Matični broj: 20945508<br/>PIB: 108173517<br/>Tel: +381 60 8080 613<br/>br. računa: 220-130624-03<br/>web: www.bugarska-ruza.rs <br/>e-mai: office@bugarska-ruza.rs</div>
	</div>
	<div style="overflow:hidden;margin-top:0.5cm;font-size:14">
		<div style="float:left;text-align:center;margin-left:2cm">'.$levibox.'</div>
		<div style="float:right;text-align:center;margin-right:2cm">'.$desnibox.'</div>
	</div>
</div>
<div id="pagewrap" style="height:26.5cm">
				<div id="desniwrap">
					<div id="tabwrap" style="margin-top:260px">'.$sve[1].'</div>
				</div>
				<div id="footer">
				<div style="border-top:1px solid #000;padding-top:1px;width:100%;text-align:center">Tel: +381 60 8080 613; e-mail: office@bugarska-ruza.rs; web: www.bugarska-ruza.rs</div>
				</div>
			</div>';
		}
		elseif ($stranicabr==$brstranica) {
		echo '<div id="pagewrap" style="height:26.5cm">
			<div id="desniwrap" style="overflow:hidden">
				<div id="tabwrap">'.$sve[$stranicabr].'</div>
				<div style="float:right; min-width:170px;margin-top:60px">
					<div style="float:left;text-align:right">Kontrolna količina:<br/>Iznos bez popusta:<br/>Popust:<br/>Osnovica:<br/>PDV:<br/><b>Vrednost robe:</b></div>
					<div style="float:right;margin-left:10px;">
						<div style="text-align:center">'.$kontkol.'</div>
						<div style="text-align:right">'.$dolbox.'</div>
					</div>
				</div>
			</div>
			<div id="footer" style="position:absolute;bottom:0;left:0;right:0">
			<div style="overflow:hidden">
				<div style="float:left;margin-left:1cm;text-align:center">Račun otpremnice izdao<br/><br/><br/>_____________________________________<br/>(potpis i pečat)</div>
			</div><br/>
			<div style="border-top:1px solid #000;padding-top:1px;width:100%;text-align:center">Tel: +381 60 8080 613; e-mail: office@bugarska-ruza.rs; web: www.bugarska-ruza.rs</div>
			</div>
		</div>';
		}
		else {
			echo '<div id="pagewrap" style="height:26.5cm">
				<div id="desniwrap">
					<div id="tabwrap">'.$sve[$stranicabr].'</div>
				</div>
				<div id="footer">
				<div style="border-top:1px solid #000;padding-top:1px;width:100%;text-align:center">Tel: +381 60 8080 613; e-mail: office@bugarska-ruza.rs; web: www.bugarska-ruza.rs</div>
				</div>
			</div>';
		}
	}
}

?>
</body>
</html>