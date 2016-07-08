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
	
	if (isset($brpracuna)) $brpracuna=mysqli_real_escape_string($mysqli,$brpracuna);
	if (isset($brracuna)) $brracuna=mysqli_real_escape_string($mysqli,$brracuna);
	if (isset($brracunau)) $brracunau=mysqli_real_escape_string($mysqli,$brracunau);
	if (isset($pozivnb)) $pozivnb=mysqli_real_escape_string($mysqli,$pozivnb);
	if (isset($IDx)) $IDx=mysqli_real_escape_string($mysqli,$IDx);
	if (isset($zauplatu)) $zauplatu=mysqli_real_escape_string($mysqli,$zauplatu);

	$dattime=date('G:i:s j.n.Y.');
	$svesifre=explode(',',$svesifre);
	$sviid=explode(',',$sviid);
	$svekon=explode(',',$svekon);
	
	// ----------------- Unos ------------------
	
	$sql='INSERT INTO prodaja (kupac, brpracuna, brracuna, brizvoda, rok, datprometa, nacdost, brracunau, pozivnb, skladiste, tisporuke, zauplatu, uneo) VALUES ("175", "'.$brpracuna.'", "'.$brracuna.'", "'.$brizvoda.'", "'.$rok.'", "'.$datprometa.'", "1", "'.$brracunau.'", "'.$pozivnb.'", "2", "0.00", "'.$zauplatu.'", "'.$user.' - '.$dattime.'")';
	mysqli_query($mysqli,$sql) or die ('1: '.$sql.' - '.mysqli_error($mysqli));
	
	$sql='SELECT ID FROM prodaja ORDER BY ID DESC LIMIT 1';
	$result=mysqli_query($mysqli,$sql) or die ('2: '.$sql.' - '.mysqli_error($mysqli));
	$row=$result->fetch_assoc();
	$prodaja=$row['ID'];
	
	foreach($svesifre as $zz) {
		$iduprodaji=${'id'.$zz};
		$proizvod=$zz;
		$kolicina=${'kolicina'.$zz};
		$mpbezpdv=${'pojcenabez'.$zz};
		$rabat=${'smarza'.$zz};
		$mpsapdv=${'pojcenasa'.$zz};
		$pdv=$mpsapdv-$mpbezpdv;
		
		$sql2='INSERT INTO prodajaitems (prodaja, iduprodaji, proizvod, kolicina, mpbezpdv, rabat, pdv, uneo) VALUES ("'.$prodaja.'", "'.$iduprodaji.'", "'.$proizvod.'", "'.$kolicina.'", "'.$mpbezpdv.'", "'.$rabat.'", "'.$pdv.'", "'.$user.' - '.$dattime.'")';
	mysqli_query($mysqli,$sql2) or die ('3: '.$sql2.' - '.mysqli_error($mysqli));
	}
		
	foreach($sviid as $pp) {
	$sql='UPDATE prodaja SET konsignacija=NULL WHERE ID="'.$pp.'"';
	mysqli_query($mysqli,$sql) or die ('4: '.$sql.' - '.mysqli_error($mysqli));
	}

	foreach($svekon as $oo) {
	$sql='DELETE FROM msklad WHERE idmsklad="'.$oo.'"';
	mysqli_query($mysqli,$sql) or die ('5: '.$sql.' - '.mysqli_error($mysqli));
	}
}

?>
<html>
<head profile="http://www.w3.org/2005/20/profile">
<link rel="icon"
	  type="image/png"
	  href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title id="Timerhead">Biofresh presek stanja - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<link type='text/css' rel="stylesheet" href="js/jquery-ui.css" />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<style type="text/css">
#desnakolona {
	position:absolute;
	width:861px;
	min-height:660px;
	margin: 0;
	float: left;
	overflow:auto;
	font-size:12;
}

#trecakolona {
	position:absolute;
	top:247px;
	left:0;
	bottom:0;
	width:320px;
	overflow:auto;
	padding: 5px;
}
#trecakolona table {
	font-size:12;
}
#trecakolona th,
#trecakolona td {
	padding:0 3px;
}

#tabbarlab {
	height:21px;
	width:877px;
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
#tabbar {
	position:absolute;
	top:32px;
	left:335px;
	height:50px;
	width:877px;
	border: 3px inset #aaa;
}
#tabbaruk {
	background:#777;
	padding-top:3px;
	height:22px;
	width:877px;
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
	top:86px;
	left:335px;
	bottom:0;
	width:878px;
	overflow-y:scroll;
	border: 3px inset #aaa;
	border-top:none;
}

.idlist {
	width:27px;
	text-align:right;
}
.sifprolist {
	width:53px;
}
.nazivlist {
	width:330px;
	overflow:hidden;
}
.kolicinalist {
	height:20px;
	font-size:12;
	text-align:right;
}
.maxlist {
	width:24px;
}
.ncenalist {
	width:48px;
}
.korekcijalist {
	width:55px;
	text-align:right;
}
.smarzalist {
	width:55px;
	text-align:right;
}
.pojcenabezlist {
	width:55px;
	text-align:right;
}
.pojcenasalist {
	width:55px;
	text-align:right;
}
.ukcenalist {
	width:63px;
	text-align:right;
}

.redlist {
	background:#777;
	padding:1px;
	font-size:1em;
	height:18px;
	overflow:hidden;
	border: 1px solid #777;
}
.redlist input {
	padding:3px 5px 0 2px;
	margin-right:2px;
	float:left;
	background:white;
	height:18px;
	border:1px;
}
.redlist div {
	padding:3px 5px 0 2px;
	margin-right:2px;
	float:left;
	background:white;
	height:15px;
	text-align:right;
	border:1px;
}
</style>
<meta name="robots" content="noindex">
</head>
<body onload="proizvodi('x')<?php
if (isset($del)) echo ',novo()';
elseif (isset($cid)) echo ',izmena('.$IDx.')';
?>">
<form id="forma" action="#" method="POST">
<?php include 'topbar.php'; ?>

<div style="width:170px;top:27px;left:0;position:absolute;height:215px;background:#fff;opacity:0.5">
</div>
<div style="position:absolute;top:32px;left:335px;width:883px;height:50px;background:#fff">
</div>
<div style="position:absolute;top:83px;left:335px;width:864px;bottom:0;background:#fff;opacity:0.8">
</div>
<div style="width:330px;top:247px;left:0;position:absolute;bottom:0;background:#fff;opacity:0.8">
</div>
<div class="wrap" style="position:absolute;top:32px;left:0;width:330px;height:210px">
	<div class="iur">
		<input id="unosbtn" type="submit" value="Unesi" style="width:120px;height:20px;float:left;margin-left:10px" />
		<div class="iul" style="width:35px">ID</div>
		<input id="yid" type="text" name="IDx" class="iud" readonly style="background:#ccc" value="<?php
$sql="SELECT `ID` FROM prodaja ORDER BY `ID` DESC LIMIT 1";
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
$row=$result->fetch_assoc();
if (isset($row['ID'])) {
$ID=$row['ID']+1;
echo $ID;
}
else {
$ID =1;
echo $ID;
}
		?>"/>
		<div style="clear:both;"></div>
	</div>
	<input type="hidden" name="svesifre" id="svesifre" />
	<input type="hidden" name="sviid" id="sviid" />
	<input type="hidden" name="svekon" id="svekon" />
	<input type="hidden" name="nid" id="nid" value="<?php echo $ID; ?>" />
<?php
$godina=date('Y');
$sql='SELECT brpracuna FROM prodaja WHERE skladiste = "2" AND kupac <> "84" ORDER BY brpracuna DESC LIMIT 1';
$result=mysqli_query($mysqli,$sql)or die (mysqli_error($mysqli));
if (mysqli_num_rows($result)>0) {
while($row=$result->fetch_assoc()) {
	$brpracuna=$row['brpracuna'];
	$sgodina=substr($brpracuna, 0, -6);
	if ($sgodina!=$godina) {
	$npracun=$godina.'-00001';
	}
	else {
	$brpracuna=substr($brpracuna, 5);
	$nbroj=str_pad(($brpracuna+1), 5, "0", STR_PAD_LEFT);
	$npracun=$godina.'-'.$nbroj;
	}
}}
else {
$npracun=$godina.'-00001';
}

$sql='SELECT brracuna FROM prodaja WHERE skladiste ="2" AND kupac <> "84" ORDER BY brracuna DESC LIMIT 1';
$result=mysqli_query($mysqli,$sql)or die (mysqli_error($mysqli));
if (mysqli_num_rows($result)>0) {
while($row=$result->fetch_assoc()) {
	$brracuna=$row['brracuna'];
	$sgodina=substr($brracuna, 0, -6);
	if ($sgodina!=$godina) {
	$nracun=$godina.'-00001';
	}
	else {
	$brracuna=substr($brracuna, 5);
	$nbroj=str_pad(($brracuna+1), 5, "0", STR_PAD_LEFT);
	$nracun=$godina.'-'.$nbroj;
	}
}}
else {
$nracun=$godina.'-00001';
}
$defdat=date('m/d/Y');
$datum=date('Y-m-d');

?>
	<div class="iur">
		<div class="iul">Predračun br.</div>
		<input id="ybrpracuna" type="text" name="brpracuna" class="iud" value="<?php echo $npracun; ?>"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Račun - opremnica br.</div>
		<input id="ybrracuna" type="text" name="brracuna" class="iud" value="<?php echo $nracun; ?>"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Broj izvoda iz Banke</div>
		<input id="ybrizvoda" type="text" name="brizvoda" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Datum prometa</div>
		<input id="ydatprometa" type="date" name="datprometa" class="iud" value="<?php echo $datum; ?>"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Rok plaćanja</div>
		<input id="yrok" type="date" name="rok" class="iud" value="<?php echo $datum; ?>" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Broj računa za uplatu</div>
		<input id="ybrracunau" type="text" name="brracunau" class="iud" value="180-1281210041143-63" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Poziv na broj</div>
		<input id="ypozivnb" type="text" name="pozivnb" class="iud" value="<?php echo $npracun; ?>"/>
		<div style="clear:both;"></div>
	</div>
<?php
$sql='SELECT kbank FROM kurs ORDER BY datum DESC LIMIT 1';
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
$row=$result->fetch_assoc();
$kurs=$row['kbank'];
?>
	<div class="iur">
		<div class="iul">Kurs €</div>
		<input id="kurs" type="text" class="iud" value="<?php echo $kurs; ?>" onchange="globalkolitem()"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Globalna Marža (%)</div>
		<input id="gmarza" type="number" class="iud" value="0" onchange="globalkolitem()" style="text-align:right"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Za uplatu RSD</div>
		<input id="yzauplatu" type="text" name="zauplatu" class="iud" readonly style="background:#ccc;text-align:right" value="0" />
		<div style="clear:both;"></div>
	</div>
</div>
	<div id="tabbar">
		<div id="tabbaruk">
			<div style="color:#fff;background:#777;margin-left:320px">Ukupno predmeta: </div>
			<div style="width:37px" id="ukpredmeta">0</div>
			<div style="color:#fff;background:#777;margin-left:210px">Ukupno za uplatu: </div>
			<div style="width:59px" id="ukkcena">0.00</div>
		</div>
		<div id="tabbarlab">
			<div style="width:23px;padding-top:3px;border-left:2px solid #777">ID</div>
			<div style="width:56px;padding-top:3px;font-size:9;line-height:8px">Šifra proizvoda</div>
			<div style="width:333px;padding-top:3px">Naziv</div>
			<div style="width:37px;padding-top:6px;font-size:9;height:15px">Komada</div>
			<div style="width:27px;padding-top:1px;font-size:8">Na<br/>lageru</div>
			<div style="width:51px;padding-top:1px;font-size:9">Nabavna cena</div>
			<div style="width:51px;padding-top:2px;font-size:9;line-height:9px">Korekcija marže</div>
			<div style="width:51px;padding-top:4px">Marža</div>
			<div style="width:51px;padding-top:2px;font-size:9;line-height:9px">Cena bez PDVa</div>
			<div style="width:51px;font-size:9;line-height:9px;padding-top:2px">Cena<br/>sa PDVom</div>
			<div style="width:59px;font-size:10;padding-top:1px;line-height:9px">Ukupna cena</div>
			<div style="width:19px;background:#777;padding:0;border:0"></div>
		</div>
	</div>
	<div id="desniwrap">
		<div id="desnakolona" style="overflow:hidden">
		</div>
	</div>

<div id="trecakolona">
<table border="1">
 <caption><b>Dnevni pazar</b></caption>
 <thead>
  <tr>
     <th style="min-width:90px">datum</th>
     <th style="min-width:76px">konsignacija</th>
     <th style="min-width:76px">za uplatu</th>
  </tr>
 </thead>
 <tbody>
<?php
$ukpazar=0;
$sql='SELECT prodaja.datprometa datum, prodaja.konsignacija konsignacija, prodaja.zauplatu zauplatu FROM prodaja INNER JOIN msklad ON prodaja.konsignacija = msklad.idmsklad WHERE prodaja.kupac="84" GROUP BY prodaja.konsignacija ORDER BY prodaja.datprometa DESC';
$result=mysqli_query($mysqli,$sql)or die (mysqli_error($mysqli));
while($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$datum=date('d.m.Y.',strtotime($datum));
	echo '<tr><td style="text-align:center">'.$datum.'</td><td style="text-align:center">'.$konsignacija.'</td><td style="text-align:right">'.$zauplatu.'</td></tr>';
	$ukpazar=$ukpazar+$zauplatu;
}
$ukpazar=number_format($ukpazar);
echo ' <tfoot>
  <tr>
     <td colspan="3"><b>Ukupno: '.$ukpazar.' RSD</b></td>
  </tr>
 </tfoot>';
?>
 </tbody>
</table>
<br/>
<table border="1">
 <caption><b>Neplaćene profakture</b></caption>
 <thead>
  <tr>
     <th style="min-width:90px">br. predračuna</th>
     <th style="min-width:76px">konsignacija</th>
     <th style="min-width:76px">za uplatu</th>
  </tr>
 </thead>
 <tbody>
<?php
$ukprof=0;
$sql='SELECT prodaja.brpracuna brpracuna, prodaja.konsignacija konsignacija, prodaja.zauplatu zauplatu FROM prodaja INNER JOIN msklad ON prodaja.konsignacija = msklad.idmsklad WHERE prodaja.brracuna = "" GROUP BY prodaja.konsignacija';
$result=mysqli_query($mysqli,$sql)or die (mysqli_error($mysqli));
while($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	echo '<tr><td style="text-align:center">'.$brpracuna.'</td><td style="text-align:center">'.$konsignacija.'</td><td style="text-align:right">'.$zauplatu.'</td></tr>';
	$ukprof=$ukprof+$zauplatu;
}
$ukprof=number_format($ukprof);
echo ' <tfoot>
  <tr>
     <td colspan="3"><b>Ukupno: '.$ukprof.' RSD</b></td>
  </tr>
 </tfoot>';
?>
 </tbody>
</table>
<br/>
<table border="1">
 <caption><b>Fakture</b></caption>
 <thead>
  <tr>
     <th style="min-width:90px">br. računa</th>
     <th style="min-width:76px">konsignacija</th>
     <th style="min-width:76px">za uplatu</th>
  </tr>
 </thead>
 <tbody>
<?php
$ukfakt=0;
$sql='SELECT prodaja.brracuna brracuna, prodaja.konsignacija konsignacija, prodaja.zauplatu zauplatu FROM prodaja INNER JOIN msklad ON prodaja.konsignacija = msklad.idmsklad WHERE prodaja.brracuna != "" GROUP BY prodaja.konsignacija';
$result=mysqli_query($mysqli,$sql)or die (mysqli_error($mysqli));
while($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	echo '<tr><td style="text-align:center">'.$brracuna.'</td><td style="text-align:center">'.$konsignacija.'</td><td style="text-align:right">'.$zauplatu.'</td></tr>';
	$ukfakt=$ukfakt+$zauplatu;
}
$ukfakt=number_format($ukfakt);
echo ' <tfoot>
  <tr>
     <td colspan="3"><b>Ukupno: '.$ukfakt.' RSD</b></td>
  </tr>
 </tfoot>';
?>
 </tbody>
</table>
</div>
<div id="debug"></div>
<script type="text/javascript">
function proizvodi(xx)
	{
		$.getJSON('ajax/bfpresekp.php', {posebno: xx}, function(data) {
			$('#desnakolona').html(data.ysorttu);
			$('#svesifre').val(data.ysvesifre);
			$('#sviid').val(data.ysviID);
			$('#svekon').val(data.ysvekon);
			$('#ukpredmeta').html(data.yukpredmeta);
			// $('#debug').html(data.ydebug);
			globalkolitem();
		});
	}
function kolitem(posebno)
{
	var kolicina = document.getElementById("kolicina"+posebno).value;
	var ncena = document.getElementById("ncena"+posebno).innerHTML;
	var korekcija = document.getElementById("korekcija"+posebno).value;
	var gmarza = document.getElementById("gmarza").value;
	var kurs = document.getElementById("kurs").value;
	
	var smarza = +gmarza + +korekcija;
	var pojcenabez = ncena*kurs*(100+smarza)/100;
	var pojcenasa = pojcenabez*1.2;
	var ukcena = pojcenasa*kolicina;
	
	smarza=parseFloat(Math.ceil(smarza* 100)/100).toFixed(2);
	pojcenabez=parseFloat(Math.ceil(pojcenabez* 100)/100).toFixed(2);
	pojcenasa=parseFloat(Math.ceil(pojcenasa* 100)/100).toFixed(2);
	ukcena=parseFloat(Math.ceil(ukcena* 100)/100).toFixed(2);

	document.getElementById("smarza"+posebno).value=smarza;
	document.getElementById("pojcenabez"+posebno).value=pojcenabez;
	document.getElementById("pojcenasa"+posebno).value=pojcenasa;
	document.getElementById("ukcena"+posebno).value=ukcena;
	
	var svesifre = document.getElementById("svesifre").value;
	var svesifre = svesifre.split(",")
	var ukkcena=0;

	$.each(svesifre, function(index, value) {
		var	ukcena = document.getElementById("ukcena"+value).value;
		ukkcena=+ukkcena + +ukcena;
	});
	ukkcena=parseFloat(Math.ceil(ukkcena * 100)/100).toFixed(2);
	document.getElementById("ukkcena").innerHTML=ukkcena;
	document.getElementById("yzauplatu").value=ukkcena;
	
}
function globalkolitem()
{
	var gmarza = document.getElementById("gmarza").value;
	var kurs = document.getElementById("kurs").value;
	var svesifre = document.getElementById("svesifre").value;
	var svesifre = svesifre.split(",")
	var ukkcena=0;
	$.each(svesifre, function(index, value) {

		var kolicina = document.getElementById("kolicina"+value).value;
		var ncena = document.getElementById("ncena"+value).innerHTML;
		var korekcija = document.getElementById("korekcija"+value).value;
		
		var smarza = +gmarza + +korekcija;
		var pojcenabez = ncena*kurs*(100+smarza)/100;
		var pojcenasa = pojcenabez*1.2;
		var ukcena = pojcenasa*kolicina;
		
		smarza=parseFloat(Math.ceil(smarza* 100)/100).toFixed(2);
		pojcenabez=parseFloat(Math.ceil(pojcenabez* 100)/100).toFixed(2);
		pojcenasa=parseFloat(Math.ceil(pojcenasa* 100)/100).toFixed(2);
		ukcena=parseFloat(Math.ceil(ukcena* 100)/100).toFixed(2);

		document.getElementById("smarza"+value).value=smarza;
		document.getElementById("pojcenabez"+value).value=pojcenabez;
		document.getElementById("pojcenasa"+value).value=pojcenasa;
		document.getElementById("ukcena"+value).value=ukcena;
		
		ukkcena=+ukkcena + +ukcena;
	});
	ukkcena=parseFloat(Math.ceil(ukkcena * 100)/100).toFixed(2);
	document.getElementById("ukkcena").innerHTML=ukkcena;
	document.getElementById("yzauplatu").value=ukkcena;
}
</script>
</form>
<form id="delform" action="#" method="post">
<input type="hidden" id="del" name="del" />
<input type="hidden" id="delsklad" name="delsklad" />
</form>
</body>
</html>