<?php
	session_start();
	$uri=$_SERVER['REQUEST_URI'];
	$pos = strrpos($uri, "/");
	$url = substr($uri, $pos+1);
	if ($_SESSION['loggedin'] != 1 OR $_SESSION['level'] < 3 ) {
		header("Location: login.php?url=$url");
		exit;
	}
	else {
	include 'config.php';
	$level=$_SESSION['level'];
	$user=$_SESSION['user'];
	}
	
if(isset($_POST) && !empty($_POST)) {
	foreach($_POST as $xx => $yy) {
		$$xx=$yy;
	}
	if (isset($IDx)) $IDx=mysql_real_escape_string($IDx);
	if (isset($datprometa)) $datprometa=mysql_real_escape_string($datprometa);
	if (isset($bifr)) $bifr=mysql_real_escape_string($bifr);
	if (isset($bezpopusta)) $bezpopusta=mysql_real_escape_string($bezpopusta);
	if (isset($popust)) $popust=mysql_real_escape_string($popust);
	if (isset($bezpdva)) $bezpdva=mysql_real_escape_string($bezpdva);
	if (isset($iznospdv)) $iznospdv=mysql_real_escape_string($iznospdv);
	if (isset($zauplatu)) $zauplatu=mysql_real_escape_string($zauplatu);
	
	
	$dattime=date('G:i:s j.n.Y.');
	$sql='SELECT ID FROM prodaja WHERE kupac="84" ORDER BY ID DESC LIMIT 1';
	$result=mysql_query($sql) or die($sql.': '.mysql_error());
	$row=mysql_fetch_assoc($result);
	$lastID=$row['ID'];
	$result = mysql_query("SHOW TABLE STATUS LIKE 'prodaja'");
	$data = mysql_fetch_assoc($result);
	$nextID = $data['Auto_increment'];
	
	if(isset($sorterklik)) $sorterklikx=explode(',',$sorterklik);
	
	$result = mysql_query("SHOW TABLE STATUS LIKE 'prodaja'");
	$data = mysql_fetch_assoc($result);
	$nextmsklad = $data['Auto_increment'];

	// ----------------- Brisanje ------------------
	
	if (isset($_POST['del'])) {
		$del=$_POST['del'];
		$proizvodi=array();
		
		$sql='SELECT konsignacija FROM prodaja WHERE ID="'.$del.'"';
		$result=mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_assoc($result);
		$konsignacija=$row['konsignacija'];
		
		$sql1='SELECT proizvod FROM prodajaitems WHERE prodaja="'.$del.'"';
		$result=mysql_query($sql1) or die;
		while($row=mysql_fetch_assoc($result)) {
		$proizvodi[]=$row['proizvod'];
		}
		foreach($proizvodi as $oo) {
			$sql1a='SELECT kolicina FROM prodajaitems WHERE prodaja="'.$del.'" AND proizvod="'.$oo.'"';
			$result=mysql_query($sql1a) or die;
			$row=mysql_fetch_assoc($result);
			$pstanjeit=$row['kolicina'];

			$sql1b='SELECT kolicina FROM zalihe WHERE skladiste="2" AND proizvod="'.$oo.'"';
			$result=mysql_query($sql1b) or die;
			$row=mysql_fetch_assoc($result);
			$pstanjez=$row['kolicina'];
			
			$novakolicina=$pstanjez+$pstanjeit;
			
			$sql2a='UPDATE zalihe SET kolicina="'.$novakolicina.'" WHERE skladiste="2" AND proizvod="'.$oo.'"';
			mysql_query($sql2a) or die;
			
			$sql2b='DELETE FROM prodajaitems WHERE prodaja="'.$del.'" AND proizvod="'.$oo.'"';
			mysql_query($sql2b) or die;
			
			$sql5='DELETE FROM msklad WHERE idmsklad="'.$konsignacija.'" AND proizvod="'.$oo.'"';
			mysql_query($sql5) or die (mysql_error());
		}
		
		$sql3='DELETE FROM prodaja WHERE ID="'.$del.'"';
		mysql_query($sql3) or die;
		
	}
	
	// ----------------- Unos ------------------
	
	elseif (isset($lastID)==false OR $IDx>$lastID) {
		$sql='INSERT INTO prodaja (kupac, datprometa, bifr, nacdost, skladiste, tisporuke, bezpopusta, popust, bezpdva, iznospdv, zauplatu, zarada, konsignacija, uneo) VALUES ("84", "'.$datprometa.'", "'.$bifr.'", "1", "22", "0", "'.$bezpopusta.'", "'.$popust.'", "'.$bezpdva.'", "'.$iznospdv.'", "'.$zauplatu.'", "'.$ukkzarada.'", "'.$nextmsklad.'","'.$user.' - '.$dattime.'")';
		mysql_query($sql) or die($sql.': '.mysql_error());
		
		$sql='SELECT ID FROM prodaja WHERE kupac="84" ORDER BY ID DESC LIMIT 1';
		$result=mysql_query($sql) or die($sql.': '.mysql_error());
		$row=mysql_fetch_assoc($result);
		$prodajano=$row['ID'];
		
		foreach($sorterklikx as $zz) {
			$prodaja=$prodajano;
			$iduprodaji=${'id'.$zz};
			$proizvod=$zz;
			$kolicina=${'kolicina'.$zz};
			$mpbezpdv=${'pcena'.$zz};
			$rabat=${'rabat'.$zz};
			$pdv=${'pdvlist'.$zz};
			$zaradauklist=${'zaradauklist'.$zz};
			
			$sqls='INSERT INTO msklad (idmsklad, datum, skladiz, skladu, proizvod, razlika, uneo) VALUES ("'.$nextmsklad.'","'.$datprometa.'","2","22","'.$proizvod.'","'.$kolicina.'","'.$user.' - '.$dattime.'")';
			mysql_query($sqls) or die (mysql_error());
		
			$sql2='INSERT INTO prodajaitems (prodaja, iduprodaji, proizvod, kolicina, mpbezpdv, rabat, pdv, zarada, uneo) VALUES ("'.$prodaja.'", "'.$iduprodaji.'", "'.$proizvod.'", "'.$kolicina.'", "'.$mpbezpdv.'", "'.$rabat.'", "'.$pdv.'", "'.$zaradauklist.'", "'.$user.' - '.$dattime.'")';
			mysql_query($sql2) or die;
			
			$sql3a='SELECT kolicina FROM zalihe WHERE skladiste="2" AND proizvod="'.$zz.'"';
			$result=mysql_query($sql3a) or die;
			$row=mysql_fetch_assoc($result);
			$pstanje=$row['kolicina'];
			
			$nstanje=$pstanje-$kolicina;
			$sql3b='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="2" AND proizvod="'.$proizvod.'"';

			mysql_query($sql3b) or die;
			
		}

	}
	else {
	 // ----------------- Menjanje ------------------
		$sql='SELECT menjali, konsignacija FROM prodaja WHERE ID="'.$IDx.'"';
		$result=mysql_query($sql) or die($sql.': '.mysql_error());
		$row=mysql_fetch_assoc($result);
		$xmenjali=$row['menjali'];
		$konsignacija=$row['konsignacija'];
		$cid=$IDx;
		
		$sql='UPDATE prodaja SET datprometa="'.$datprometa.'", bifr="'.$bifr.'", bezpopusta="'.$bezpopusta.'", popust="'.$popust.'", bezpdva="'.$bezpdva.'", iznospdv="'.$iznospdv.'", zauplatu="'.$zauplatu.'", zarada="'.$ukkzarada.'", menjali="'.$xmenjali.'; '.$user.' - '.$dattime.'" WHERE ID="'.$IDx.'"';
		mysql_query($sql) or die($sql.': '.mysql_error());
		
		$sviitemi=array();
		$sql='SELECT proizvod FROM prodajaitems WHERE prodaja="'.$IDx.'"';
		$result=mysql_query($sql) or die($sql.': '.mysql_error());
		while($row=mysql_fetch_assoc($result)) {
		$sviitemi[]=$row['proizvod'];
		}
		
		foreach ($sviitemi as $hh) {

		if (in_array($hh, $sorterklikx)==false) {
				
				// Ako se briše jedan proizvod sa liste
			
				$sql1='SELECT kolicina FROM prodajaitems WHERE prodaja="'.$IDx.'" AND proizvod="'.$hh.'"';
				$result=mysql_query($sql1) or die;
				$row=mysql_fetch_assoc($result);
				$pstanjen=$row['kolicina'];

				$sql2='SELECT kolicina FROM zalihe WHERE skladiste="2" AND proizvod="'.$hh.'"';
				$result=mysql_query($sql2) or die;
				$row=mysql_fetch_assoc($result);
				$pstanjez=$row['kolicina'];
				
				$nstanje=$pstanjez+$pstanjen;
				
				$sql3='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="2" AND proizvod="'.$hh.'"';
				mysql_query($sql3) or die;

				$sql4='DELETE FROM prodajaitems WHERE prodaja="'.$IDx.'" AND proizvod="'.$hh.'"';
				mysql_query($sql4) or die;
				
				$sql5='DELETE FROM msklad WHERE idmsklad="'.$konsignacija.'" AND proizvod="'.$hh.'"';
				mysql_query($sql5) or die (mysql_error());
			}
		}
		foreach ($sorterklikx as $zz) {
		
			$prodaja=$IDx;
			$iduprodaji=${'id'.$zz};
			$proizvod=$zz;
			$kolicina=${'kolicina'.$zz};
			$mpbezpdv=${'pcena'.$zz};
			$rabat=${'rabat'.$zz};
			$pdv=${'pdvlist'.$zz};
			$zaradauklist=${'zaradauklist'.$zz};

			$sql='SELECT kolicina, menjali FROM prodajaitems WHERE prodaja="'.$IDx.'" AND proizvod="'.$zz.'"';
			$result=mysql_query($sql) or die($sql.': '.mysql_error());
			$row=mysql_fetch_assoc($result);
			$xxmenjali=$row['menjali'];
			$pstanjen=$row['kolicina'];
			
			$sql='SELECT menjali FROM msklad WHERE idmsklad="'.$konsignacija.'" AND proizvod="'.$zz.'"';
			$result=mysql_query($sql) or die($sql.': '.mysql_error());
			$row=mysql_fetch_assoc($result);
			$yymenjali=$row['menjali'];
			
			if (isset($pstanjen)==false) {
			
			// Dodavanje novog predmeta na postojeću listu
			
				$sql2='INSERT INTO prodajaitems (prodaja, iduprodaji, proizvod, kolicina, mpbezpdv, rabat, pdv, zarada, uneo) VALUES ("'.$prodaja.'", "'.$iduprodaji.'", "'.$proizvod.'", "'.$kolicina.'", "'.$mpbezpdv.'", "'.$rabat.'", "'.$pdv.'", "'.$zaradauklist.'", "'.$user.' - '.$dattime.'")';

				$sql2s='INSERT INTO msklad (idmsklad, datum, skladiz, skladu, proizvod, razlika, uneo) VALUES ("'.$konsignacija.'","'.$datprometa.'","2","22","'.$proizvod.'","'.$kolicina.'","'.$user.' - '.$dattime.'")';

			}
			else {
			
			// Menjanje postojećeg predmeta sa liste
			
				if ($kolicina=="0") {
					$sql2='DELETE FROM prodajaitems WHERE prodaja="'.$prodaja.'" AND proizvod="'.$proizvod.'"';
					mysql_query($sql2) or die (mysql_error());

					$sql2s='DELETE FROM msklad WHERE idmsklad="'.$konsignacija.'" AND proizvod="'.$proizvod.'"';
					mysql_query($sql2s) or die (mysql_error());
				}
				else {
					$sql2='UPDATE prodajaitems SET prodaja="'.$prodaja.'", iduprodaji="'.$iduprodaji.'", proizvod="'.$proizvod.'", kolicina="'.$kolicina.'", mpbezpdv="'.$mpbezpdv.'", rabat="'.$rabat.'", pdv="'.$pdv.'", zarada="'.$zaradauklist.'", menjali="'.$xxmenjali.'; '.$user.' - '.$dattime.'" WHERE prodaja="'.$prodaja.'" AND proizvod="'.$proizvod.'"';

					$sql2s='UPDATE msklad SET datum="'.$datprometa.'", razlika="'.$kolicina.'", menjali="'.$yymenjali.'; '.$user.' - '.$dattime.'" WHERE idmsklad="'.$konsignacija.'" AND proizvod="'.$proizvod.'"';
				}
			}
			mysql_query($sql2) or die;
			mysql_query($sql2s) or die (mysql_error());
			
			$sql3a='SELECT kolicina FROM zalihe WHERE skladiste="2" AND proizvod="'.$zz.'"';
			$result=mysql_query($sql3a) or die;
			$row=mysql_fetch_assoc($result);
			$pstanjez=$row['kolicina'];
		
			$nstanje=$pstanjez+$pstanjen-$kolicina;
			$sql3b='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="2" AND proizvod="'.$proizvod.'"';
			mysql_query($sql3b) or die;

		}
	}
		
}

?>
<html>
<head profile="http://www.w3.org/2005/20/profile">
<link rel="icon"
	  type="image/png"
	  href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title id="Timerhead">Unos pazara - Biofresh doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<link type='text/css' rel="stylesheet" href="js/jquery-ui.css" />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<style type="text/css">
#desnakolona,
#trecakolona {
	list-style-type: none;
	margin: 0;
	float: left;
	overflow:auto;
	font-size:12;
}
#desnakolona li,
#trecakolona li{
	margin: 2px;
	padding: 2px;
	font-size: 1em;
	color:#000;
}
#trecakolona li {
	height:13px;
}
#desnakolona li {
	height:18px;
}
#trecakolona li div {
	display:none;
}
#trecakolona li input {
	display:none;
}
#desnakolona li {
	background:#777;
	margin:0;
	border-color:#777;
	padding:1px;
}
#desnakolona li div {
	padding:3px 5px 0 2px;
	margin-right:2px;
	float:left;
	background:white;
	height:15px;
	text-align:right;
}
#desnakolona li input {
	padding:0;
	margin-right:2px;
	float:left;
	background:white;
	height:18px;
}
#trecakolona li .nazivlist {
	width:230px;
	display:inline;
	float:right;
	height:15px;
}
#trecakolona li .sifkaslist {
	width:53px;
	display:inline;
	float:left;
}
#tabbar {
	height:50px;
	width:1175px;
}
#desnakolona {
	position:absolute;
	top:51px;
	width:1175px;
	min-height:700px;
}
#trecakolona {
	position:absolute;
	top:215px;
	left:205px;
	bottom:5px;
	width:320px;
	overflow:auto;
}
#tabbarlab {
	height:21px;
	width:1175px;
	font-size:12;
	text-align:center;
	border-bottom:5px solid #777;
	font-weight:bold;
}
#tabbarlab div {
	padding:0 2px;
	float:left;
	height:21px;
	border-right:2px solid #777;
}
#tabbaruk {
	background:#777;
	padding-top:3px;
	height:22px;
	width:1175px;
	font-size:12;
}
#tabbaruk div {
	text-align:right;
	float:left;
	padding:3px 2px 1px 2px;
	height:16px;
	border-right:2px solid #777;
	background:#fff;
	color:#000;
	font-weight:bold;
}
#desniwrap {
	position:absolute;
	top:32px;
	left:535px;
	bottom:0;
	width:800px;
	overflow:scroll;
	border: 3px inset #aaa;
}
.idlist {width:20px;}
.kolicinalist {
	height:20px;
	font-size:12;
}
.nazivlist {
	width:180px;
	overflow:hidden;
}
.maxlist {width:24px;}
.fnclist {width:48px;}
.pcenalist {width:48px;}
.zaradalist {width:48px;}
.zaradaplist {width:48px;}
.ptisporukelist {width:48px;}
.pcenasplist {width:48px;}
.rabatlist {
	width:79px;
	font-size:12;
	text-align:right;
}
.pdvlist {
	width:30px;
	text-align:right;
}
.pdvvredlist {width:56px;}
.ukbezpopsapdvlist {width:56px;}
.popustlist {width:56px;}
.uksapopbezpdvlist {width:56px;}
.cenauklist {
	width:56px;
	font-weight:bold;
}
.zaradauklist {
	width:56px;
	font-weight:bold;
}
.zaradapuklist {
	width:56px;
	font-weight:bold;
}
.sifkaslist {width:53px;}
</style>
<meta name="robots" content="noindex">
</head>
<body onload="proizvodi()<?php
if (isset($del)) echo ',novo()';
elseif (isset($cid)) echo ',izmena('.$IDx.')';
?>">
<form id="forma" action="#" method="POST">
<?php include 'topbar.php'; ?>

<div style="width:200px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6">
</div>
	<div style="position:absolute;top:32px;left:5px;width:190px">
		<input id="unosbtn" type="submit" value="Unesi" style="width:100%;height:20px" />
		<input type="button" value="Nova prodaja" style="width:100%;margin-top:5px" onclick="novo()"/>
		<input type="button" value="Obriši" style="width:100%" onclick="delform()"/>
		<input type="hidden" name="sorterklik" id="sorterklik" />
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px"></div>
		<div id="blacklink" style="font-size:12;overflow:auto">
<?php
$sql="SELECT `ID`,`naziv` FROM brendovi ORDER BY `ID`";
$result=mysql_query($sql)or die;
while($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$brendovi[$ID]=$naziv;
}
$brendxx="";
$sql="SELECT `ID`,`datprometa` FROM prodaja WHERE kupac='84' ORDER BY `datprometa` DESC";
$result=mysql_query($sql)or die;
while($row=mysql_fetch_assoc($result)) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	if (empty($datprometa) OR ($datprometa=="0000-00-00")) $datum='';
	else $datum=date('d.m.Y.',strtotime($datprometa));
	echo '<a href="#" onclick="izmena('.$ID.',2)">'.$datum.'</a><br/>';
}
?>
		</div>
	</div>
<div style="width:165px;top:27px;left:205px;position:absolute;height:183px;background:#fff;opacity:0.5">
</div>
<div style="position:absolute;top:32px;left:535px;width:803px;height:50px;background:#fff">
</div>
<div style="position:absolute;top:83px;left:535px;width:803px;bottom:0;background:#fff;opacity:0.8">
</div>
<div style="width:325px;top:215px;left:205px;position:absolute;bottom:0;background:#fff;opacity:0.5">
</div>
<div class="wrap" style="position:absolute;top:32px;left:200px;width:330px;height:163px">
	<input type="hidden" id="IDx" name="IDx" class="iud" value="<?php
$sql="SELECT `ID` FROM prodaja ORDER BY `ID` DESC LIMIT 1";
$result=mysql_query($sql) or die($sql.': '.mysql_error());
$row=mysql_fetch_assoc($result);
if (isset($row['ID'])) {
$ID=$row['ID']+1;
echo $ID;
}
else {
$ID =1;
echo $ID;
}
		?>"/>
	<input type="hidden" name="nid" id="nid" value="<?php echo $ID; ?>" />
	<input type="hidden" name="kupac" value="84" />
	<div class="iur">
		<div class="iul">Datum prometa</div>
		<input id="ydatprometa" type="date" name="datprometa" class="iud" value="<?php echo date('Y-m-d'); ?>" />
		<div style="clear:both;"></div>
	</div>
	<input type="hidden" name="nacdost" value="1" />
	<input type="hidden" name="skladiste" value="2" />
	<div class="iur">
		<div class="iul">Broj dnevnog izveštaja</div>
		<input id="ybifr" type="number" name="bifr" class="iud" style="text-align:right" value="1" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Iznos bez popusta</div>
		<input id="ybezpopusta" type="text" name="bezpopusta" class="iud" readonly style="background:#ccc;text-align:right" value="0" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Popust</div>
		<input id="ypopust" type="text" name="popust" class="iud" readonly style="background:#ccc;text-align:right" value="0" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Ukupna vrednost bez PDVa</div>
		<input id="ybezpdva" type="text" name="bezpdva" class="iud" readonly style="background:#ccc;text-align:right" value="0" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Iznos PDVa</div>
		<input id="yiznospdv" type="text" name="iznospdv" class="iud" readonly style="background:#ccc;text-align:right" value="0" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Za uplatu RSD</div>
		<input id="yzauplatu" type="text" name="zauplatu" class="iud" readonly style="background:#ccc;text-align:right" value="0" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Ukupna zarada</div>
		<input id="yukkzarada" type="text" name="ukkzarada" class="iud" readonly style="background:#ccc;text-align:right" value="0" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Procenat zarade</div>
		<input id="yukkpzarada" type="text" name="ukkpzarada" class="iud" readonly style="background:#ccc;text-align:right" value="0" />
		<div style="clear:both;"></div>
	</div>
</div>
<div id="desniwrap">
	<div id="tabbar">
		<div id="tabbaruk">
			<div style="color:#fff;background:#777">Ukupno stavki: </div>
			<div style="width:24px" id="ukstavki">0</div>
			<div style="width:37px;margin-left:98px" id="ukpredmeta">0</div>
			<div style="width:59px;margin-left:522px;" id="ukkpdv">0.00</div>
			<div style="width:59px;" id="ukkbezpopsapdv">0.00</div>
			<div style="width:59px;" id="ukkpopust">0.00</div>
			<div style="width:59px;" id="ukksapopbezpdv">0.00</div>
			<div style="width:59px;" id="ukkcena">0.00</div>
			<div style="width:59px;" id="ukkzarada">0.00</div>
		</div>
		<div id="tabbarlab">
			<div style="width:25px;padding-top:3px">ID</div>
			<div style="width:56px;padding-top:3px">Šifra</div>
			<div style="width:183px;padding-top:3px">Naziv</div>
			<div style="width:37px;padding-top:5px;font-size:9">Komada</div>
			<div style="width:27px;font-size:8;word-break:break-all;line-height:9px">Na<br/>lageru</div>
			<div style="width:51px">FNC</div>
			<div style="width:51px;font-size:9;word-break:break-all;line-height:9px">Cena<br/>bez PDV</div>
			<div style="width:51px">Zarada</div>
			<div style="width:51px;font-size:9;line-height:9px">Realna marža</div>
			<div style="width:51px;font-size:9;word-break:break-all;line-height:9px;padding-top:1px">Troškovi<br/>isporuke</div>
			<div style="width:51px;font-size:9;line-height:9px">Cena<br/>sa PDV</div>
			<div style="width:37px;padding-top:3px">Rabat</div>
			<div style="width:33px;padding-top:3px">PDV</div>
			<div style="width:59px;font-size:9;vertical-align:center">vrednost PDVa</div>
			<div style="width:59px;font-size:9;vertical-align:center">Bez pop. sa PDVom</div>
			<div style="width:59px;font-size:9;vertical-align:center;padding-top:5px">Popust</div>
			<div style="width:59px;font-size:9;vertical-align:center">Sa pop. bez PDVa</div>
			<div style="width:59px;font-size:9;vertical-align:center;padding-top:5px">Ukupna cena</div>
			<div style="width:59px;font-size:9;vertical-align:center">Ukupna Zarada</div>
			<div style="width:3px;background:#777;padding:0;border:0"></div>
		</div>
	</div>
	<div class="connectedSortable" id="desnakolona" onmouseup="sorter()" onmouseout="sorter()" style="overflow:hidden">
	</div>
</div>

<div class="connectedSortable" id="trecakolona" onmouseup="sorter()" onmouseout="sorter()">
</div>
<script type="text/javascript">
var viewportheight = document.documentElement.clientHeight;
document.getElementById("blacklink").style.height=(viewportheight-118)+'px';

$(function()
	{
		$( "#desnakolona, #trecakolona" ).sortable({
			connectWith: ".connectedSortable"
		});
	});
function delform()
{
var r=confirm("Da li sigurno želite da obrišete ovu prodaju iz baze?");
if (r==true)
  {
	document.getElementById("delsklad").value="2";
	document.getElementById("delform").submit();
  }
}
function novo()
	{
		document.getElementById("forma").reset();
		$("#unosbtn").prop('value', 'Unesi');
		document.getElementById("ukpredmeta").innerHTML=0;
		document.getElementById("ukstavki").innerHTML=0;
		var nid=document.getElementById("nid").value;
		document.getElementById("del").value=nid;
		document.getElementById("IDx").value=nid;
		proizvodi("x");
	}
function prodaj()
	{
		var nracun = document.getElementById("nracun").value;
		document.getElementById("ybrracuna").value=nracun;
	}
function izmena(posebno,skladiste)
	{
		var sortedIDs;
		d = new Date();
		$("#unosbtn").prop('value', 'Promeni');
		document.getElementById("forma").reset();
		document.getElementById("del").value=posebno;
		document.getElementById("IDx").value=posebno;
		var osoba=84;
		$.getJSON('ajax/prodajaui.php', {posebno: posebno, skladiste: skladiste, osoba: osoba}, function(data) {
			$('#yid').val(data.yid);
			$('#ybifr').val(data.ybifr);
			$('#ydatprometa').val(data.ydatprometa);
			$('#ybezpopusta').val(data.ybezpopusta);
			$('#ypopust').val(data.ypopust);
			$('#ybezpdva').val(data.ybezpdva);
			$('#yiznospdv').val(data.yiznospdv);
			$('#yzauplatu').val(data.yzauplatu);
		});
		$.getJSON('ajax/prodajaump.php', {posebno: posebno}, function(data) {
			$('#trecakolona').html(data.ysortostali);
			$('#desnakolona').html(data.ysorttu);
			sorter();
			var sortedIDs = $( "#desnakolona" ).sortable( "toArray" );
			$.each(sortedIDs, function(index, value) {
				kolitem(value);
			});
		});
	}
function proizvodi(xx)
	{
		$.getJSON('ajax/prodajaump.php', {posebno: xx}, function(data) {
			$('#trecakolona').html(data.ysortostali);
			$('#desnakolona').html(data.ysorttu);
		});
	}
function sorter()
	{
		var sortedIDs = $( "#desnakolona" ).sortable( "toArray" );
		document.getElementById("sorterklik").value=sortedIDs;
		$.each(sortedIDs, function(index, value) {
			var rednibr=index+1;
			document.getElementById("id"+value).innerHTML=rednibr;
			document.getElementById("hid"+value).value=rednibr;
		});
		var ukstavki=sortedIDs.length;
		document.getElementById("ukstavki").innerHTML=ukstavki;
	}
function kolitem(posebno)
{
	var kolicina = document.getElementById("kolicina"+posebno).value;
	var pcena = document.getElementById("pcenalist"+posebno).innerHTML;
	var pcenasp = document.getElementById("pcenasplist"+posebno).innerHTML;
	var rabat = document.getElementById("rabatlist"+posebno).value;
	var pdv = document.getElementById("pdvlist"+posebno).innerHTML;
	var fnc = document.getElementById("fnclist"+posebno).innerHTML;
	var tezina = document.getElementById("tezina"+posebno).value;
	var ytisporuke = 0;
	var tezinauk = tezina * kolicina;
	var ukbezpopsapdv = 1.00*pcenasp*kolicina;
	var uksapopbezpdv = pcena*kolicina*(100- +rabat)/100;
	var popust = pcenasp*kolicina*rabat/100;
	var pdvvred = pcena*kolicina*pdv*(100- +rabat)/10000;
	var ukcena = ukbezpopsapdv- +popust;
	ukbezpopsapdv=parseFloat(Math.ceil(ukbezpopsapdv* 100)/100).toFixed(2);
	uksapopbezpdv=parseFloat(Math.ceil(uksapopbezpdv* 100)/100).toFixed(2);
	popust=parseFloat(Math.ceil(popust* 100)/100).toFixed(2);
	pdvvred=parseFloat(Math.ceil(pdvvred* 100)/100).toFixed(2);
	ukcena=parseFloat(Math.ceil(ukcena* 100)/100).toFixed(2);
	document.getElementById("pdvvredlist"+posebno).innerHTML=pdvvred;
	document.getElementById("tezinauk"+posebno).value=tezinauk;
	document.getElementById("ukbezpopsapdvlist"+posebno).innerHTML=ukbezpopsapdv;
	document.getElementById("popustlist"+posebno).innerHTML=popust;
	document.getElementById("uksapopbezpdvlist"+posebno).innerHTML=uksapopbezpdv;
	document.getElementById("cenauklist"+posebno).innerHTML=ukcena;
	var sortedIDs = $( "#desnakolona" ).sortable( "toArray" );
	var ukkpdv=0;
	var ukkbezpopsapdv=0;
	var ukkpopust=0;
	var ukksapopbezpdv=0;
	var ukkcena=0;
	var ukktezina=0;
	var ukkzarada=0;
	var ukpredmeta=0;
	$.each(sortedIDs, function(index, value) {
		var	kolicinauk = document.getElementById("kolicina"+value).value;
		ukpredmeta=+ukpredmeta + +kolicinauk;
		var	tezinauk = document.getElementById("tezinauk"+value).value;
		ukktezina=+ukktezina + +tezinauk;
		var	pdvuk = document.getElementById("pdvvredlist"+value).innerHTML;
		ukkpdv=+ukkpdv + +pdvuk;
		var	bezpopsapdvuk = document.getElementById("ukbezpopsapdvlist"+value).innerHTML;
		ukkbezpopsapdv=+ukkbezpopsapdv + +bezpopsapdvuk;
		var	popustuk = document.getElementById("popustlist"+value).innerHTML;
		ukkpopust=+ukkpopust + +popustuk;
		var	sapopbezpdvuk = document.getElementById("uksapopbezpdvlist"+value).innerHTML;
		ukksapopbezpdv=+ukksapopbezpdv + +sapopbezpdvuk;
		var	ukcena = document.getElementById("cenauklist"+value).innerHTML;
		ukkcena=+ukkcena + +ukcena;
	});
	$.each(sortedIDs, function(index, value) {
		var	tezinauk = document.getElementById("tezinauk"+value).value;
		kolicina = document.getElementById("kolicina"+value).value;
		pcenasp = document.getElementById("pcenasplist"+value).innerHTML;
		rabat = document.getElementById("rabatlist"+value).value;
		pdv = document.getElementById("pdvlist"+value).innerHTML;
		fnc = document.getElementById("fnclist"+value).innerHTML;
		var uctez = tezinauk/ukktezina;
		var ptisporuke = ytisporuke*uctez/kolicina;
		var zarada = pcenasp*((100-rabat)/100)/((100 + +pdv)/100) - ptisporuke - fnc;
		var pzarada = zarada/fnc;
		var zaradauk = zarada*kolicina;
		ukkzarada = +ukkzarada + +zaradauk;
		document.getElementById("uctez"+value).value=uctez;
		ptisporuke=parseFloat(Math.ceil(ptisporuke * 100)/100).toFixed(2);
		zarada=parseFloat(Math.ceil(zarada * 100)/100).toFixed(2);
		zaradauk=parseFloat(Math.ceil(zaradauk * 100)/100).toFixed(2);
		pzarada=parseFloat(Math.ceil(pzarada * 10000)/100).toFixed(2);
		document.getElementById("ptisporukelist"+value).innerHTML=ptisporuke;
		document.getElementById("zaradalist"+value).innerHTML=zarada;
		document.getElementById("zaradauklist"+value).innerHTML=zaradauk;
		document.getElementById("zaradauklistx"+value).innerHTML=zaradauk;
		document.getElementById("zaradaplist"+value).innerHTML=pzarada+'%';
		});
	ukkpzarada=ukkzarada/ukkcena*100;
	ukkpzarada=parseFloat(Math.ceil(ukkpzarada*100)/100).toFixed(2);
	ukkzarada=parseFloat(Math.ceil(ukkzarada * 100)/100).toFixed(2);
	ukkpdv=parseFloat(Math.ceil(ukkpdv * 100)/100).toFixed(2);
	ukkbezpopsapdv=parseFloat(Math.ceil(ukkbezpopsapdv * 100)/100).toFixed(2);
	ukkpopust=parseFloat(Math.ceil(ukkpopust * 100)/100).toFixed(2);
	ukksapopbezpdv=parseFloat(Math.ceil(ukksapopbezpdv * 100)/100).toFixed(2);
	ukkcena=parseFloat(Math.ceil(ukkcena * 100)/100).toFixed(2);
	document.getElementById("ukpredmeta").innerHTML=ukpredmeta;
	document.getElementById("ukkpdv").innerHTML=ukkpdv;
	document.getElementById("yiznospdv").value=ukkpdv;
	document.getElementById("ukkbezpopsapdv").innerHTML=ukkbezpopsapdv;
	document.getElementById("ybezpopusta").value=ukkbezpopsapdv;
	document.getElementById("ukkpopust").innerHTML=ukkpopust;
	document.getElementById("ypopust").value=ukkpopust;
	document.getElementById("ukksapopbezpdv").innerHTML=ukksapopbezpdv;
	document.getElementById("ybezpdva").value=ukksapopbezpdv;
	document.getElementById("ukkcena").innerHTML=ukkcena;
	document.getElementById("ukkzarada").innerHTML=ukkzarada;
	document.getElementById("yukkzarada").value=ukkzarada;
	document.getElementById("yukkpzarada").value=ukkpzarada+' %';
	document.getElementById("yzauplatu").value=ukkcena;
}
</script>
</form>
<form id="delform" action="#" method="post">
<input type="hidden" id="del" name="del" />
<input type="hidden" id="delsklad" name="skladiste" value="2" />
</form>
</body>
</html>