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
	if (isset($bezpopusta)) $bezpopusta=mysqli_real_escape_string($mysqli,$bezpopusta);
	if (isset($popust)) $popust=mysqli_real_escape_string($mysqli,$popust);
	if (isset($bezpdva)) $bezpdva=mysqli_real_escape_string($mysqli,$bezpdva);
	if (isset($iznospdv)) $iznospdv=mysqli_real_escape_string($mysqli,$iznospdv);
	if (isset($zauplatu)) $zauplatu=mysqli_real_escape_string($mysqli,$zauplatu);
	if (isset($ukkzarada)) $ukkzarada=mysqli_real_escape_string($mysqli,$ukkzarada);
	
	$dattime=date('G:i:s j.n.Y.');
	$sql='SELECT ID FROM prodaja ORDER BY ID DESC LIMIT 1';
	$result=mysqli_query($mysqli,$sql);
	$row=$result->fetch_assoc();
	$lastID=$row['ID'];
	$result = mysqli_query($mysqli,"SHOW TABLE STATUS LIKE 'prodaja'");
	$data = $result->fetch_assoc();
	$nextID = $data['Auto_increment'];
	
	if(isset($sorterklik)) $sorterklikx=explode(',',$sorterklik);
	
	$result = mysqli_query($mysqli,"SHOW TABLE STATUS LIKE 'prodaja'");
	$data = $result->fetch_assoc();
	$nextmsklad = $data['Auto_increment'];

	// ----------------- Brisanje ------------------
	
	if (isset($_POST['del'])) {
		$del=$_POST['del'];
		$proizvodi=array();

		$sql='SELECT konsignacija FROM prodaja WHERE ID="'.$del.'"';
		$result=mysqli_query($mysqli,$sql) or die;
		$row=$result->fetch_assoc();
		$konsignacija=$row['konsignacija'];
		
		$sql1='SELECT proizvod FROM prodajaitems WHERE prodaja="'.$del.'"';
		$result=mysqli_query($mysqli,$sql1) or die;
		while($row=$result->fetch_assoc()) {
		$proizvodi[]=$row['proizvod'];
		}
		foreach($proizvodi as $oo) {
			$sql1a='SELECT kolicina FROM prodajaitems WHERE prodaja="'.$del.'" AND proizvod="'.$oo.'"';
			$result=mysqli_query($mysqli,$sql1a) or die;
			$row=$result->fetch_assoc();
			$pstanjeit=$row['kolicina'];

			$sql1b='SELECT kolicina FROM zalihe WHERE skladiste="2" AND proizvod="'.$oo.'"';
			$result=mysqli_query($mysqli,$sql1b) or die;
			$row=$result->fetch_assoc();
			$pstanjez=$row['kolicina'];
			
			$novakolicina=$pstanjez+$pstanjeit;
			
			$sql2a='UPDATE zalihe SET kolicina="'.$novakolicina.'" WHERE skladiste="2" AND proizvod="'.$oo.'"';
			mysqli_query($mysqli,$sql2a) or die;
			
			$sql2b='DELETE FROM prodajaitems WHERE prodaja="'.$del.'" AND proizvod="'.$oo.'"';
			mysqli_query($mysqli,$sql2b) or die;

			$sql5='DELETE FROM msklad WHERE idmsklad="'.$konsignacija.'" AND proizvod="'.$oo.'"';
			mysqli_query($mysqli,$sql5) or die;
		}
		
		$sql3='DELETE FROM prodaja WHERE ID="'.$del.'"';
		mysqli_query($mysqli,$sql3) or die;
		
	}
	
	// ----------------- Unos ------------------
	
	elseif (isset($lastID)==false OR $IDx>$lastID) {
		$sql='INSERT INTO prodaja (kupac, brpracuna, brracuna, brizvoda, rok, datprometa, nacdost, brracunau, pozivnb, skladiste, tisporuke, bezpopusta, popust, bezpdva, iznospdv, zauplatu, zarada, konsignacija, uneo) VALUES ("'.$kupac.'", "'.$brpracuna.'", "'.$brracuna.'", "'.$brizvoda.'", "'.$rok.'", "'.$datprometa.'", "'.$nacdost.'", "'.$brracunau.'", "'.$pozivnb.'", "22", "'.$tisporuke.'", "'.$bezpopusta.'", "'.$popust.'", "'.$bezpdva.'", "'.$iznospdv.'", "'.$zauplatu.'", "'.$ukkzarada.'", "'.$nextmsklad.'","'.$user.' - '.$dattime.'")';
		mysqli_query($mysqli,$sql) or die;
		
		foreach($sorterklikx as $zz) {
			$prodaja=$nextID;
			$iduprodaji=${'id'.$zz};
			$proizvod=$zz;
			$kolicina=${'kolicina'.$zz};
			$mpbezpdv=${'pcena'.$zz};
			$rabat=${'rabat'.$zz};
			$pdv=${'pdvlist'.$zz};
			$zaradauklist=${'zaradauklist'.$zz};
			
			$sqls='INSERT INTO msklad (idmsklad, datum, skladiz, skladu, proizvod, razlika, uneo) VALUES ("'.$nextmsklad.'","'.$datprometa.'","2","22","'.$proizvod.'","'.$kolicina.'","'.$user.' - '.$dattime.'")';
			mysqli_query($mysqli,$sqls) or die;
		
			$sql2='INSERT INTO prodajaitems (prodaja, iduprodaji, proizvod, kolicina, mpbezpdv, rabat, pdv, zarada, uneo) VALUES ("'.$prodaja.'", "'.$iduprodaji.'", "'.$proizvod.'", "'.$kolicina.'", "'.$mpbezpdv.'", "'.$rabat.'", "'.$pdv.'", "'.$zaradauklist.'", "'.$user.' - '.$dattime.'")';
			mysqli_query($mysqli,$sql2) or die;
			
			$sql3a='SELECT kolicina FROM zalihe WHERE skladiste="2" AND proizvod="'.$proizvod.'"';
			$result=mysqli_query($mysqli,$sql3a) or die;
			$row=$result->fetch_assoc();
			$pstanje=$row['kolicina'];
			
			$nstanje=$pstanje-$kolicina;
			$sql3b='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="2" AND proizvod="'.$proizvod.'"';

			mysqli_query($mysqli,$sql3b) or die;
			
		}

	}
	else {
	 // ----------------- Menjanje ------------------
		$sql='SELECT menjali, konsignacija FROM prodaja WHERE ID="'.$IDx.'"';
		$result=mysqli_query($mysqli,$sql) or die;
		$row=$result->fetch_assoc();
		$xmenjali=$row['menjali'];
		$konsignacija=$row['konsignacija'];
		$cid=$IDx;
		
		$sql='UPDATE prodaja SET kupac="'.$kupac.'", brpracuna="'.$brpracuna.'", brracuna="'.$brracuna.'", rok="'.$rok.'", datprometa="'.$datprometa.'", nacdost="'.$nacdost.'", brracunau="'.$brracunau.'", pozivnb="'.$pozivnb.'", skladiste="22", tisporuke="'.$tisporuke.'", bezpopusta="'.$bezpopusta.'", popust="'.$popust.'", bezpdva="'.$bezpdva.'", iznospdv="'.$iznospdv.'", zauplatu="'.$zauplatu.'", zarada="'.$ukkzarada.'", menjali="'.$xmenjali.'; '.$user.' - '.$dattime.'" WHERE ID="'.$IDx.'"';
		mysqli_query($mysqli,$sql) or die;
		
		$sviitemi=array();
		$sql='SELECT proizvod FROM prodajaitems WHERE prodaja="'.$IDx.'"';
		$result=mysqli_query($mysqli,$sql) or die;
		while($row=$result->fetch_assoc()) {
		$sviitemi[]=$row['proizvod'];
		}
		
		foreach ($sviitemi as $hh) {
		
			if (in_array($hh, $sorterklikx)==false) {
				
				// Ako se briše jedan proizvod sa liste
			
				$sql1='SELECT kolicina FROM prodajaitems WHERE prodaja="'.$IDx.'" AND proizvod="'.$hh.'"';
				$result=mysqli_query($mysqli,$sql1) or die;
				$row=$result->fetch_assoc();
				$pstanjen=$row['kolicina'];

				$sql2='SELECT kolicina FROM zalihe WHERE skladiste="2" AND proizvod="'.$hh.'"';
				$result=mysqli_query($mysqli,$sql2) or die;
				$row=$result->fetch_assoc();
				$pstanjez=$row['kolicina'];
				
				$nstanje=$pstanjez+$pstanjen;
				
				$sql3='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="2" AND proizvod="'.$hh.'"';
				mysqli_query($mysqli,$sql3) or die;

				$sql4='DELETE FROM prodajaitems WHERE prodaja="'.$IDx.'" AND proizvod="'.$hh.'"';
				mysqli_query($mysqli,$sql4) or die;

				$sql5='DELETE FROM msklad WHERE idmsklad="'.$konsignacija.'" AND proizvod="'.$hh.'"';
				mysqli_query($mysqli,$sql5) or die;
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
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$xxmenjali=$row['menjali'];
			$pstanjen=$row['kolicina'];
			
			$sql='SELECT menjali FROM msklad WHERE idmsklad="'.$konsignacija.'" AND proizvod="'.$zz.'"';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
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
					mysqli_query($mysqli,$sql2) or die (mysql_error());

					$sql2s='DELETE FROM msklad WHERE idmsklad="'.$konsignacija.'" AND proizvod="'.$proizvod.'"';
					mysqli_query($mysqli,$sql2s) or die (mysql_error());
				}
				else {
					$sql2='UPDATE prodajaitems SET prodaja="'.$prodaja.'", iduprodaji="'.$iduprodaji.'", proizvod="'.$proizvod.'", kolicina="'.$kolicina.'", mpbezpdv="'.$mpbezpdv.'", rabat="'.$rabat.'", pdv="'.$pdv.'", zarada="'.$zaradauklist.'", menjali="'.$xxmenjali.'; '.$user.' - '.$dattime.'" WHERE prodaja="'.$prodaja.'" AND proizvod="'.$proizvod.'"';

					$sql2s='UPDATE msklad SET datum="'.$datprometa.'", razlika="'.$kolicina.'", menjali="'.$yymenjali.'; '.$user.' - '.$dattime.'" WHERE idmsklad="'.$konsignacija.'" AND proizvod="'.$proizvod.'"';
				}
			}
			mysqli_query($mysqli,$sql2) or die;
			mysqli_query($mysqli,$sql2s) or die;
			
			$sql3a='SELECT kolicina FROM zalihe WHERE skladiste="2" AND proizvod="'.$proizvod.'"';
			$result=mysqli_query($mysqli,$sql3a) or die;
			$row=$result->fetch_assoc();
			$pstanjez=$row['kolicina'];
		
			$nstanje=$pstanjez+$pstanjen-$kolicina;
			$sql3b='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="2" AND proizvod="'.$proizvod.'"';
			mysqli_query($mysqli,$sql3b) or die;

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
<title id="Timerhead">Biofresh posredna prodaja - Land of Roses doo: baza podataka</title>
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
	font-size:12pt;
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
	top:387px;
	left:205px;
	bottom:5px;
	width:320px;
	overflow:auto;
}
#tabbarlab {
	height:21px;
	width:1175px;
	font-size:12pt;
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
	font-size:12pt;
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
	font-size:12pt;
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
	font-size:12pt;
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
<body onload="proizvodi('x')<?php
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
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px"></div>
		<div id="blacklink" style="font-size:12pt;overflow:auto">
<?php
$sql="SELECT `ID`,`naziv` FROM brendovi ORDER BY `ID`";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$brendovi[$ID]=$naziv;
}
$brendxx="";
$sql='SELECT ID, brpracuna, brracuna, skladiste FROM prodaja WHERE skladiste = "22" AND kupac <> "84" ORDER BY ID DESC';
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	if (empty($brracuna)) echo '<a href="#" onclick="izmena('.$ID.')"><i><b>'.$ID.' - '.$brpracuna.'</b></i></a><br/>';
	else echo '<a href="#" onclick="izmena('.$ID.')">'.$ID.' - '.$brracuna.'</a><br/>';
}
?>
		</div>
	</div>
<div style="width:165px;top:27px;left:205px;position:absolute;height:355px;background:#fff;opacity:0.5">
</div>
<div style="position:absolute;top:32px;left:535px;width:803px;height:50px;background:#fff">
</div>
<div style="position:absolute;top:83px;left:535px;width:803px;bottom:0;background:#fff;opacity:0.8">
</div>
<div style="width:325px;top:387px;left:205px;position:absolute;bottom:0;background:#fff;opacity:0.5">
</div>
<div class="wrap" style="position:absolute;top:32px;left:200px;width:330px;height:300px">
	<div class="iur">
		<div class="iul">ID</div>
		<input id="yid" type="text" name="IDx" class="iud" readonly style="background:#ccc" value="<?php
$sql="SELECT `ID` FROM prodaja ORDER BY `ID` DESC LIMIT 1";
$result=mysqli_query($mysqli,$sql) or die;
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
	<input type="hidden" name="nid" id="nid" value="<?php echo $ID; ?>" />
	<div class="iur">
		<div class="iul">Kupac</div>
		<select id="ykupac" type="text" name="kupac" class="iud" style="width:153px" >
<?php
$sql='SELECT partneri.ID ID, partneri.ime ime, partneri.prezime prezime, partneri.firma firma, gpartnera.ID posao FROM partneri LEFT JOIN gpartnera ON partneri.gpartnera = gpartnera.ID WHERE partneri.ID <> "84" ORDER BY partneri.prezime ASC, partneri.ime ASC';
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {
	$ID=$row['ID'];
	$ime=$row['ime'];
	$prezime=$row['prezime'];
	$posao=$row['posao'];
	$firma=$row['firma'];
	if ($posao>4 AND $posao<9) echo '<option value="'.$ID.'" style="background:#cdf">'.$firma.'</option>';
	else echo '<option value="'.$ID.'" style="background:#dfd">'.$prezime.' '.$ime.'</option>';
}
?>
		</select>
		<div style="clear:both;"></div>
	</div>
<?php
$godina=date('Y');
$sql='SELECT brpracuna FROM prodaja WHERE skladiste = "22" AND kupac <> "84" ORDER BY brpracuna DESC LIMIT 1';
$result=mysqli_query($mysqli,$sql) or die;
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

$sql='SELECT brracuna FROM prodaja WHERE skladiste ="22" AND kupac <> "84" ORDER BY brracuna DESC LIMIT 1';
$result=mysqli_query($mysqli,$sql) or die;
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
		<div class="iul">Predračun br. <input type="button" value=">>" id="predbutton" onclick="predprodaj()" style="width:27px;float:right" /></div>
		<input type="hidden" id="pracun" value="<?php echo $npracun; ?>" />
		<input type="hidden" id="racun" value="<?php echo $nracun; ?>" />
		<input id="ybrpracuna" type="text" name="brpracuna" class="iud"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Račun - opremnica br. <input type="button" value=">>" id="pbutton" onclick="prodaj()" style="width:27px;float:right" /></div>
		<input id="ybrracuna" type="text" name="brracuna" class="iud" />
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
		<input id="yrok" type="date" name="rok" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Način dostave</div>
		<select id="ynacdost" type="text" name="nacdost" class="iud" style="width:153px" >
			<option value="1">Lično preuzimanje</option>
			<option value="2">Kurirska dostava</option>
			<option value="3">Lična isporuka</option>
		</select>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Broj računa za uplatu</div>
		<input id="ybrracunau" type="text" name="brracunau" class="iud" value="180-1281210042203-84" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Poziv na broj</div>
		<input id="ypozivnb" type="text" name="pozivnb" class="iud"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Troškovi isporuke</div>
		<input id="ytisporuke" type="text" name="tisporuke" class="iud" style="text-align:right" value="0"/>
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
	<input type="hidden" name="sorterklik" id="sorterklik" />
<div id="desniwrap">
	<div id="tabbar">
		<div id="tabbaruk">
			<div style="color:#fff;background:#777;margin-left:1px">Ukupno stavki: </div>
			<div style="width:24px" id="ukstavki">0</div>
			<div style="width:37px;margin-left:159px" id="ukpredmeta">0</div>
			<div style="width:59px;margin-left:457px;" id="ukkpdv">0.00</div>
			<div style="width:59px;" id="ukkbezpopsapdv">0.00</div>
			<div style="width:59px;" id="ukkpopust">0.00</div>
			<div style="width:59px;" id="ukksapopbezpdv">0.00</div>
			<div style="width:59px;" id="ukkcena">0.00</div>
			<div style="width:59px;" id="ukkzarada">0.00</div>
		</div>
		<div id="tabbarlab">
			<div style="width:25px;padding-top:3px">ID</div>
			<div style="width:56px;padding-top:3px;font-size:10pt">Šifra u kasi</div>
			<div style="width:183px;padding-top:3px">Naziv</div>
			<div style="width:37px;padding-top:5px;font-size:9pt">Komada</div>
			<div style="width:27px;font-size:8pt;word-break:break-all;line-height:9px">Na<br/>lageru</div>
			<div style="width:51px">FNC</div>
			<div style="width:51px;font-size:9pt;word-break:break-all;line-height:9px">Cena<br/>bez PDV</div>
			<div style="width:51px">Zarada</div>
			<div style="width:51px;font-size:9pt;line-height:9px">Realna marža</div>
			<div style="width:51px;font-size:9pt;word-break:break-all;line-height:9px;padding-top:1px">Troškovi<br/>isporuke</div>
			<div style="width:51px;font-size:9pt;line-height:9px">Cena<br/>sa PDV</div>
			<div style="width:37px;padding-top:3px">Rabat</div>
			<div style="width:33px;padding-top:3px">PDV</div>
			<div style="width:59px;font-size:9pt;vertical-align:middle">vrednost PDVa</div>
			<div style="width:59px;font-size:9pt;vertical-align:middle">Bez pop. sa PDVom</div>
			<div style="width:59px;font-size:9pt;vertical-align:middle;padding-top:5px">Popust</div>
			<div style="width:59px;font-size:9pt;vertical-align:middle">Sa pop. bez PDVa</div>
			<div style="width:59px;font-size:9pt;vertical-align:middle;padding-top:5px">Ukupna cena</div>
			<div style="width:59px;font-size:9pt;vertical-align:middle">Ukupna Zarada</div>
			<div style="width:59px;font-size:9pt;vertical-align:middle">Ukupna Zarada</div>
			<div style="width:3px;background:#777;padding:0;border:0"></div>
		</div>
	</div>
	<div class="connectedSortable" id="desnakolona" onmouseup="sorter()" onmouseout="sorter()" style="overflow:hidden">
	</div>
</div>

<div class="connectedSortable" id="trecakolona" onmouseup="sorter()" onmouseout="sorter()">
</div>
<div id="debug"></div>
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
		var nid = document.getElementById("nid").value;
		document.getElementById("yid").value=nid;
		$("#unosbtn").prop('value', 'Unesi');
		document.getElementById("ukpredmeta").innerHTML=0;
		document.getElementById("ukstavki").innerHTML=0;
		proizvodi("x");
	}
function predprodaj()
	{
		var pracun = document.getElementById("pracun").value;
		document.getElementById("ybrpracuna").value=pracun;
		document.getElementById("ypozivnb").value=pracun;
	}
function prodaj()
	{
		var racun = document.getElementById("racun").value;
		document.getElementById("ybrracuna").value=racun;
	}
function izmena(posebno,skladiste)
	{
		var sortedIDs;
		d = new Date();
		$("#unosbtn").prop('value', 'Promeni');
		document.getElementById("forma").reset();
		document.getElementById("del").value=posebno;
		var osoba= document.getElementById("ykupac").value;
		$.getJSON('ajax/prodajaui.php', {posebno: posebno}, function(data) {
			$('#yid').val(data.yid);
			$('#ykupac').val(data.ykupac);
			$('#ybrpracuna').val(data.ybrpracuna);
			$('#ybrracuna').val(data.ybrracuna);
			$('#ybrizvoda').val(data.ybrizvoda);
			$('#yrok').val(data.yrok);
			$('#ydatprometa').val(data.ydatprometa);
			$('#ynacdost').val(data.ynacdost);
			$('#ybrracunau').val(data.ybrracunau);
			$('#ypozivnb').val(data.ypozivnb);
			$('#ytisporuke').val(data.ytisporuke);
			$('#ybezpopusta').val(data.ybezpopusta);
			$('#ypopust').val(data.ypopust);
			$('#ybezpdva').val(data.ybezpdva);
			$('#yiznospdv').val(data.yiznospdv);
			$('#yzauplatu').val(data.yzauplatu);
		});
		$.getJSON('ajax/bfprodajap.php', {posebno: posebno, osoba: osoba}, function(data) {
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
		var osoba= document.getElementById("ykupac").value;
		$.getJSON('ajax/bfprodajap.php', {posebno: xx, osoba: osoba}, function(data) {
			$('#trecakolona').html(data.ysortostali);
			$('#desnakolona').html(data.ysorttu);
			// $('#debug').html(data.ydebug);
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
	var ytisporuke = document.getElementById("ytisporuke").value;
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
<input type="hidden" id="delsklad" name="delsklad" />
</form>
</body>
</html>