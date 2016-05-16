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

	if (isset($brnarudzbenice)) $brnarudzbenice=mysql_real_escape_string($brnarudzbenice);
	if (isset($kursbanka)) $kursbanka=mysql_real_escape_string($kursbanka);
	if (isset($kurssred)) $kurssred=mysql_real_escape_string($kurssred);
	if (isset($kurscarine)) $kurscarine=mysql_real_escape_string($kurscarine);
	if (isset($transport)) $transport=mysql_real_escape_string($transport);
	if (isset($neptroskoviuk)) $neptroskoviuk=mysql_real_escape_string($neptroskoviuk);
	if (isset($ulaznipdv)) $ulaznipdv=mysql_real_escape_string($ulaznipdv);
	if (isset($IDx)) $IDx=mysql_real_escape_string($IDx);
	
	$dattime=date('G:i:s j.n.Y.');
	$sql='SELECT ID FROM nabavka ORDER BY ID DESC LIMIT 1';
	$result=mysql_query($sql) or die;
	$row=mysql_fetch_assoc($result);
	$lastID=$row['ID'];
	$result = mysql_query("SHOW TABLE STATUS LIKE 'nabavka'");
	$data = mysql_fetch_assoc($result);
	$nextID = $data['Auto_increment'];
	
	if(isset($sorterklik)) $sorterklikx=explode(',',$sorterklik);
	
	if (isset($_POST['del'])) {
		$del=$_POST['del'];
		$sklad=$_POST['delsklad'];
		$proizvodi=array();
		$sql1='SELECT proizvod FROM nabavkaitems WHERE nabavka="'.$del.'"';
		$result=mysql_query($sql1) or die;
		while($row=mysql_fetch_assoc($result)) {
		$proizvodi[]=$row['proizvod'];
		}
		foreach($proizvodi as $oo) {
		$sql1a='SELECT kolicina FROM nabavkaitems WHERE nabavka="'.$del.'" AND proizvod="'.$oo.'"';
		$result=mysql_query($sql1a) or die;
		$row=mysql_fetch_assoc($result);
		$pstanjeit=$row['kolicina'];

		$sql1b='SELECT kolicina FROM zalihe WHERE skladiste="'.$sklad.'" AND proizvod="'.$oo.'"';
		$result=mysql_query($sql1b) or die;
		$row=mysql_fetch_assoc($result);
		$pstanjez=$row['kolicina'];
		
		$novakolicina=$pstanjez-$pstanjeit;
		
		$sql2a='UPDATE zalihe SET kolicina="'.$novakolicina.'" WHERE skladiste="'.$sklad.'" AND proizvod="'.$oo.'"';
		
		$sql2b='DELETE FROM nabavkaitems WHERE nabavka="'.$del.'" AND proizvod="'.$oo.'"';
		
		mysql_query($sql2a) or die;
		mysql_query($sql2b) or die;
		}
		
		$sql3='DELETE FROM nabavka WHERE ID="'.$del.'"';
		mysql_query($sql3);
	}
	elseif (isset($lastID)==false OR $IDx>$lastID) {
		$sql='INSERT INTO nabavka (datdostavnice, datprijemarobe, dobavljac, brnarudzbenice, skladiste, kursbanka, kurssred, kurscarine, transport, ulaznipdv, neptroskoviuk, placeno, ukfnc, ukpcb, ukrazlika, ukmarza, uneo) VALUES ("'.$datdostavnice.'", "'.$datprijemarobe.'", "'.$dobavljac.'", "'.$brnarudzbenice.'", "'.$skladiste.'", "'.$kursbanka.'", "'.$kurssred.'", "'.$kurscarine.'", "'.$transport.'", "'.$ulaznipdv.'", "'.$neptroskoviuk.'", "'.$placeno.'","'.$ukfnc.'","'.$ukpcb.'","'.$ukrazlika.'","'.$ukmarza.'","'.$user.' - '.$dattime.'")';
		mysql_query($sql) or die;
		
		foreach($sorterklikx as $zz) {
			$nabavka=$nextID;
			$idunabavci=${'id'.$zz};
			$proizvod=$zz;
			$kolicina=${'kolitem'.$zz};
			$cenaueur=${'ncena'.$zz};
			$transportpr=${'tezinap'.$zz};
			$transportiznos=${'transportiznos'.$zz};
			$cstopa=${'cstopa'.$zz};
			$neptroskovi=${'neptroskovi'.$zz};
			$nabcena=${'nabcena'.$zz};
			$razlika=${'razlika'.$zz};
			$mpbezpdv=${'mpbezpdv'.$zz};
			$marza=${'marza'.$zz};
			$pdv=${'pdv'.$zz};
			$mpsapdv=${'mpsapdv'.$zz};
			
			$sql2='INSERT INTO nabavkaitems (nabavka, idunabavci, proizvod, kolicina, cenaueur, transportpr, transportiznos, cstopa, neptroskovi, nabcena, razlika, mpbezpdv, marza, pdv, mpsapdv, uneo) VALUES ("'.$nabavka.'", "'.$idunabavci.'", "'.$proizvod.'", "'.$kolicina.'", "'.$cenaueur.'", "'.$transportpr.'", "'.$transportiznos.'", "'.$cstopa.'", "'.$neptroskovi.'", "'.$nabcena.'", "'.$razlika.'", "'.$mpbezpdv.'", "'.$marza.'", "'.$pdv.'", "'.$mpsapdv.'", "'.$user.' - '.$dattime.'")';
			mysql_query($sql2) or die;
			
			$sql3a='SELECT kolicina FROM zalihe WHERE skladiste="'.$skladiste.'" AND proizvod="'.$zz.'"';
			$result=mysql_query($sql3a) or die;
			$row=mysql_fetch_assoc($result);
			$pstanje=$row['kolicina'];
			
			if (isset($pstanje)==false) {
				$sql3b='INSERT INTO zalihe (skladiste, proizvod, kolicina, uneo) VALUES ("'.$skladiste.'", "'.$zz.'", "'.$kolicina.'", "'.$user.' - '.$dattime.'")';
			}
			else {
				$nstanje=$pstanje+$kolicina;
				$sql3b='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="'.$skladiste.'" AND proizvod="'.$proizvod.'"';
			}
			mysql_query($sql3b) or die;
			
		}

	}
	else {
	
		$sql='SELECT menjali FROM nabavka WHERE ID="'.$IDx.'"';
		$result=mysql_query($sql) or die;
		$row=mysql_fetch_assoc($result);
		$xmenjali=$row['menjali'];
		$cid=$IDx;
		
		$sql='UPDATE nabavka SET datdostavnice="'.$datdostavnice.'", datprijemarobe="'.$datprijemarobe.'", dobavljac="'.$dobavljac.'", brnarudzbenice="'.$brnarudzbenice.'", skladiste="'.$skladiste.'", kursbanka="'.$kursbanka.'", kurssred="'.$kurssred.'", kurscarine="'.$kurscarine.'", transport="'.$transport.'", ulaznipdv="'.$ulaznipdv.'", neptroskoviuk="'.$neptroskoviuk.'", placeno="'.$placeno.'", ukfnc="'.$ukfnc.'", ukpcb="'.$ukpcb.'", ukrazlika="'.$ukrazlika.'", ukmarza="'.$ukmarza.'", menjali="'.$xmenjali.'; '.$user.' - '.$dattime.'" WHERE ID="'.$IDx.'"';
		mysql_query($sql) or die;
		
		$sviitemi=array();
		$sql='SELECT proizvod FROM nabavkaitems WHERE nabavka="'.$IDx.'"';
		$result=mysql_query($sql) or die;
		while($row=mysql_fetch_assoc($result)) {
		$sviitemi[]=$row['proizvod'];
		}
		
		foreach ($sviitemi as $hh) {
			if (in_array($hh, $sorterklikx)==false) {
				$sql1='SELECT kolicina FROM nabavkaitems WHERE nabavka="'.$IDx.'" AND proizvod="'.$hh.'"';
				$result=mysql_query($sql1) or die;
				$row=mysql_fetch_assoc($result);
				$pstanjen=$row['kolicina'];

				$sql2='SELECT kolicina FROM zalihe WHERE skladiste="'.$skladiste.'" AND proizvod="'.$hh.'"';
				$result=mysql_query($sql2) or die;
				$row=mysql_fetch_assoc($result);
				$pstanjez=$row['kolicina'];
				
				$nstanje=$pstanjez-$pstanjen;
				
				$sql3='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="'.$skladiste.'" AND proizvod="'.$hh.'"';
				mysql_query($sql3) or die;
				$sql4='DELETE FROM nabavkaitems WHERE nabavka="'.$IDx.'" AND proizvod="'.$hh.'"';
				mysql_query($sql4) or die;
			}
		}

		foreach ($sorterklikx as $zz) {
		
			$nabavka=$IDx;
			$idunabavci=${'id'.$zz};
			$proizvod=$zz;
			$kolicina=${'kolitem'.$zz};
			$cenaueur=${'ncena'.$zz};
			$transportpr=${'tezinap'.$zz};
			$transportiznos=${'transportiznos'.$zz};
			$cstopa=${'cstopa'.$zz};
			$neptroskovi=${'neptroskovi'.$zz};
			$nabcena=${'nabcena'.$zz};
			$razlika=${'razlika'.$zz};
			$mpbezpdv=${'mpbezpdv'.$zz};
			$marza=${'marza'.$zz};
			$pdv=${'pdv'.$zz};
			$mpsapdv=${'mpsapdv'.$zz};

			$sql='SELECT kolicina, menjali FROM nabavkaitems WHERE nabavka="'.$IDx.'" AND proizvod="'.$zz.'"';
			$result=mysql_query($sql) or die;
			$row=mysql_fetch_assoc($result);
			$xxmenjali=$row['menjali'];
			$pstanjen=$row['kolicina'];
			
			if (isset($pstanjen)==false) {
			$sql2='INSERT INTO nabavkaitems (nabavka, idunabavci, proizvod, kolicina, cenaueur, transportpr, transportiznos, cstopa, neptroskovi, nabcena, razlika, mpbezpdv, marza, pdv, mpsapdv, uneo) VALUES ("'.$nabavka.'", "'.$idunabavci.'", "'.$proizvod.'", "'.$kolicina.'", "'.$cenaueur.'", "'.$transportpr.'", "'.$transportiznos.'", "'.$cstopa.'", "'.$neptroskovi.'", "'.$nabcena.'", "'.$razlika.'", "'.$mpbezpdv.'", "'.$marza.'", "'.$pdv.'", "'.$mpsapdv.'", "'.$user.' - '.$dattime.'")';
			}
			else {
			$sql2='UPDATE nabavkaitems SET idunabavci="'.$idunabavci.'", kolicina="'.$kolicina.'", cenaueur="'.$cenaueur.'", transportpr="'.$transportpr.'", transportiznos="'.$transportiznos.'", cstopa="'.$cstopa.'", neptroskovi="'.$neptroskovi.'", nabcena="'.$nabcena.'", razlika="'.$razlika.'", mpbezpdv="'.$mpbezpdv.'", marza="'.$marza.'", pdv="'.$pdv.'", mpsapdv="'.$mpsapdv.'", menjali="'.$xxmenjali.'; '.$user.' - '.$dattime.'" WHERE nabavka="'.$nabavka.'" AND proizvod="'.$proizvod.'"';
			}
			mysql_query($sql2) or die;
			
			$sql3a='SELECT kolicina FROM zalihe WHERE skladiste="'.$skladiste.'" AND proizvod="'.$zz.'"';
			$result=mysql_query($sql3a) or die;
			$row=mysql_fetch_assoc($result);
			$pstanjez=$row['kolicina'];
			
			if (isset($pstanjez)==false) {
				$sql3b='INSERT INTO zalihe (skladiste, proizvod, kolicina, uneo) VALUES ("'.$skladiste.'", "'.$zz.'", "'.$kolicina.'", "'.$user.' - '.$dattime.'")';
				mysql_query($sql3b) or die;
			}
			else {
				if (($pstanjen==$kolicina)==false) {
					$nstanje=$pstanjez-$pstanjen+$kolicina;
					$sql3b='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="'.$skladiste.'" AND proizvod="'.$proizvod.'"';
					mysql_query($sql3b) or die;
				}
			}

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
<title id="Timerhead">Nabavka - Land of Roses doo: baza podataka</title>
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
	font-size:12;
}
#desnakolona li,
#trecakolona li{
	margin: 2px;
	padding: 2px;
	font-size: 1em;
	height:18px;
	color:#000;
}
#trecakolona li{
	overflow-y:hidden;
	height:14px;
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
}
#desnakolona li input {
	padding:0;
	margin-right:2px;
	float:left;
	background:white;
	height:18px;
}
#desnakolona li .ssifralist {
	display:none;
}
#trecakolona li .nazivlist,
#trecakolona li .ssifralist {
	width:285px;
	display:inline;
}
#tabbar {
	height:50px;
	width:2190px;
}
#desnakolona {
	position:absolute;
	top:51px;
	min-height:500px;
	width:2190px;
}
#trecakolona {
	position:absolute;
	top:347px;
	left:205px;
	bottom:5px;
	width:320px;
	overflow:auto;
}
#tabbarlab {
	height:21px;
	width:2190px;
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
	width:2190px;
	font-size:12;
}
#tabbaruk div {
	float:left;
	padding:3px 2px 1px 2px;
	height:16px;
	border-right:2px solid #777;
	background:#fff;
	color:#000;
	font-weight:bold;
	text-align:center;
}
#desniwrap {
	position:absolute;
	top:32px;
	left:535px;
	bottom:0;
	right:0;
	overflow:scroll;
	border: 3px inset #aaa;
}
.idlist {width:20px;}
.nazivlist {
	width:200px;
	overflow:hidden;
}
.kolkutlist {
	height:20px;
	font-size:12;
}
.kollist {width:24px;}
.ncenalist {width:50px;}
.cenauklist {
	width:56px;
	font-weight:bold;
}
.netolist {width:24px;}
.netouklist {width:36px;}
.brutolist {width:24px;}
.brutouklist {width:36px;}
.tezinaplist {width:30px;}
.cenaplist {width:30px;}
.csifralist {width:79px;}
.cstopalist {width:30px;}
.pcarcenalist {width:50px;}
.ukcarcenalist {width:80px;}
.pdcenalist {width:50px;}
.ukdcenalist {width:80px;}
.pcarcenalist {width:50px;}
.ukcarcenalist {width:80px;}
.pdcenalist {width:50px;}
.ukdcenalist {width:80px;}
.ptrocarlist {width:50px;}
.uktrocarlist {width:80px;}
.ptrotralist {width:50px;}
.uktrotralist {width:80px;}
.potrolist {width:50px;}
.ukotrolist {width:80px;}
.fnclist {width:50px;}
.pcblist {width:50px;}
.pcslist {width:50px;}
.cdblist {width:50px;}
.cdslist {width:50px;}
.razlist {width:50px;}
.marzalist {width:50px;}
.zar2list {width:50px;}
.zar3list {width:50px;}
.pdvlist {width:50px;}
.ccarinalist {width:50px;}
.ssifralist {width:50px;}
</style>
<meta name="robots" content="noindex">
</head>
<body onload="proizvodi('x')<?php
if (isset($del)) echo ',novo()';
elseif (isset($cid)) echo ',izmena('.$IDx.')';
?>">
<form id="forma" action="#" method="POST">
<input type="hidden" name="sorterklik" id="sorterklik" />
<?php include 'topbar.php'; ?>

<div style="width:200px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6">
</div>
	<div style="position:absolute;top:32px;left:5px;width:190px">
		<input id="unosbtn" type="submit" value="Unesi" style="width:100%;height:20px" />
		<input type="button" value="Nova nabavka" style="width:100%;margin-top:5px" onclick="novo()"/>
		<input type="button" value="Obriši" style="width:100%" onclick="delform()"/>
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px"></div>
		<div id="blacklink" style="font-size:12;overflow:auto">
<?php
$sql="SELECT `ID`,`naziv` FROM brendovi ORDER BY `ID`";
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$brendovi[$ID]=$naziv;
}
$brendxx="";
$sql="SELECT `ID`,`datprijemarobe` FROM nabavka ORDER BY `ID` ASC";
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$datprijemarobe=date('d.m.Y.',strtotime($datprijemarobe));
	echo '<a href="#" onclick="izmena('.$ID.')">'.$ID.' - '.$datprijemarobe.'</a><br/>';
}
?>
		</div>
	</div>
<div style="width:165px;top:27px;left:205px;position:absolute;height:315px;background:#fff;opacity:0.5">
</div>
<div style="position:absolute;top:32px;left:535px;right:0;height:50px;background:#fff">
</div>
<div style="position:absolute;top:83px;left:535px;right:0;bottom:0;background:#fff;opacity:0.8">
</div>
<div style="width:325px;top:347px;left:205px;position:absolute;bottom:0;background:#fff;opacity:0.5">
</div>
<div class="wrap" style="position:absolute;top:32px;left:200px;width:330px;height:300px">
	<div class="iur">
		<div class="iul">ID</div>
		<input id="yid" type="text" name="IDx" class="iud" readonly style="background:#ccc" value="<?php
$sql="SELECT `ID` FROM nabavka ORDER BY `ID` DESC LIMIT 1";
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
if (isset($row['ID'])) {
$result = mysql_query("SHOW TABLE STATUS LIKE 'nabavka'");
$data = mysql_fetch_assoc($result);
$nextID = $data['Auto_increment'];
echo $nextID;
}
else echo '1';

		?>"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Način carinjenja</div>
		<select id="yncarine" type="text" name="ncarine" class="iud" style="width:153px" >
			<option value="1">Dimitrovgrad</option>
			<option value="2">Pančevo</option>
		</select>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Datum dostavnice</div>
		<input id="ydatdostavnice" type="date" name="datdostavnice" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Datum prijema robe</div>
		<input id="ydatprijemarobe" type="date" name="datprijemarobe" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Dobavljač</div>
		<select id="ydobavljac" type="text" name="dobavljac" class="iud" style="width:153px" >
<?php
$sql='SELECT ID, prezime, ime FROM partneri WHERE gpartnera="8" ORDER BY prezime, ime';
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {
	$ID=$row['ID'];
	$prezime=$row['prezime'];
	$ime=$row['ime'];
	echo '<option value="'.$ID.'">'.$prezime.' '.$ime.'</option>';
}
?>
		</select>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Broj narudžbenice</div>
		<input id="ybrnarudzbenice" type="text" name="brnarudzbenice" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Skladište</div>
		<select id="yskladiste" type="text" name="skladiste" class="iud" style="width:153px" >
<?php
$sql='SELECT ID, naziv FROM skladista WHERE status="da" ORDER BY ID ASC';
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {
	$ID=$row['ID'];
	$naziv=$row['naziv'];
	echo '<option value="'.$ID.'">'.$naziv.'</option>';
}
?>
		</select>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Kurs banke (EUR/RSD)</div>
		<input id="ykursbanka" type="text" name="kursbanka" class="iud" value="<?php
$sql='SELECT kcar, kbank, ksred FROM kurs ORDER BY datum DESC, ID DESC LIMIT 1';
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	echo $kbank;
		?>"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Srednji kurs (EUR/RSD)</div>
		<input id="ykurssred" type="text" name="kurssred" class="iud" value="<?php echo $ksred; ?>" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Carinski kurs (EUR/RSD)</div>
		<input id="ykurscarine" type="text" name="kurscarine" class="iud" value="<?php echo $kcar; ?>" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Troškovi transporta (RSD)</div>
		<input id="ytransport" type="text" name="transport" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Ukupni neposredni troškovi (RSD)</div>
		<input id="yneptroskoviuk" type="text" name="neptroskoviuk" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Ulazni PDV</div>
		<input id="yulaznipdv" type="text" name="ulaznipdv" class="iud" readonly style="background:#ccc" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Plaćeno</div>
		<div class="iud">
			<input type="radio" name="placeno" id="yplacenoda" value="da" style="width:20px;margin-left:30px"><span style="text-shadow: 0px 0px 4px #fff"> da / ne</span>
			<input type="radio" name="placeno" id="yplacenone" value="ne" style="width:20px" checked="checked">
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
<div id="desniwrap">
	<div id="tabbar">
		<div id="tabbaruk">
			<div style="color:#fff;background:#777">Ukupno stavki: </div>
			<div style="width:24px" id="ukstavki"></div>
			<div style="color:#fff;background:#777;margin-left:69px">Ukupno: </div>
			<div style="width:37px" id="ukkutija"></div>
			<div style="width:36px;text-align:left" id="ukpredmeta"></div>
			<div style="margin-left:41px;width:68px" id="ukkcena"></div>
			<div style="width:56px;margin-left:16px" id="ukkneto" title="(kg)"></div>
			<div style="width:56px;margin-left:16px" id="ukkbruto" title="(kg) - gde nema bruto dodaje se neto"></div>
			<div style="width:107px;margin-left:240px" id="ukkcarcena"></div>
			<div style="width:107px;margin-left:35px" id="ukkdcena"></div>
			<div style="width:107px;margin-left:35px" id="ukkcarina"></div>
			<div style="color:#fff;background:#777;margin-left:27px">Uk. nabavna cena: </div>
			<div style="width:107px" id="ukkfnc"></div>
			<input type="hidden" name="ukfnc" id="hukkfnc"/>
			<div style="color:#fff;background:#777;margin-left:9px">Uk. prodajna cena (bez PDVa): </div>
			<div style="width:107px" id="ukkpcb"></div>
			<input type="hidden" name="ukpcb" id="hukkpcb"/>
			<div style="color:#fff;background:#777;margin-left:9px">Uk. razlika: </div>
			<div style="width:107px" id="ukkrazlika"></div>
			<input type="hidden" name="ukrazlika" id="hukkrazlika"/>
			<div style="color:#fff;background:#777;margin-left:9px">Uk. marža (%): </div>
			<div style="width:107px" id="ukkmarza"></div>
			<input type="hidden" name="ukmarza" id="hukkmarza"/>
		</div>
		<div id="tabbarlab">
			<div style="width:25px">ID</div>
			<div style="width:203px">Naziv</div>
			<div style="width:37px">Kutija</div>
			<div style="width:27px;font-size:9;word-break:break-all;line-height:9px">pred-meta</div>
			<div style="width:53px">Cena</div>
			<div style="width:59px">Uk. cena</div>
			<div style="width:27px;line-height:9px;font-size:9">Neto (g)</div>
			<div style="width:39px;line-height:9px;font-size:9">Uk.neto (kg)</div>
			<div style="width:27px;line-height:9px;font-size:9">Bruto (g)</div>
			<div style="width:39px;line-height:9px;font-size:9">Uk.bruto (kg)</div>
			<div style="width:33px;line-height:9px;font-size:8;font-weight:normal">Učešće težine %</div>
			<div style="width:33px;line-height:9px;font-size:8;font-weight:normal">Učešće cene %</div>
			<div style="width:82px">Carinska šifra</div>
			<div style="width:33px;line-height:9px;font-size:8;font-weight:normal">C. tarifa (%)</div>
			<div style="width:53px;line-height:9px;font-size:9">Pojedin. car. cena</div>
			<div style="width:83px;line-height:9px;font-size:9">Ukupna car. cena</div>
			<div style="width:53px;line-height:9px;font-size:9">Pojedin. cena</div>
			<div style="width:83px;line-height:9px;font-size:9">Ukupna cena</div>
			<div style="width:53px;line-height:9px;font-size:9">Pojedin. trošk. car.</div>
			<div style="width:83px;line-height:9px;font-size:9">Ukupni trošk. car.</div>
			<div style="width:53px;line-height:9px;font-size:8">Pojedin. trošk. trans.</div>
			<div style="width:83px;line-height:9px;font-size:9">Ukupni trošk. trans.</div>
			<div style="width:53px;line-height:9px;font-size:8">Pojedin. ostali trošk.</div>
			<div style="width:83px;line-height:9px;font-size:9">Ukupni ostali trošk.</div>
			<div style="width:53px;line-height:9px;font-size:9">Finalna nab. cena</div>
			<div style="width:53px;line-height:9px;font-size:9">Prodajna bez PDVa</div>
			<div style="width:53px;line-height:9px;font-size:9">Prodajna sa PDVom</div>
			<div style="width:53px;line-height:9px;font-size:8">Cena ka dist. bez PDV</div>
			<div style="width:53px;line-height:9px;font-size:8">Cena ka dist. sa PDV</div>
			<div style="width:53px;line-height:9px;font-size:9">Razlika u ceni</div>
			<div style="width:53px">Marža %</div>
			<div style="width:53px;line-height:9px;font-size:9">Zarada sa 20%</div>
			<div style="width:53px;line-height:9px;font-size:9">Zarada sa 30%</div>
			<div style="width:53px">PDV (%)</div>
			<div style="width:53px;line-height:9px;font-size:9">Ulazna carina</div>
		</div>
	</div>
	<div class="connectedSortable" id="desnakolona" onmouseup="sorter()" onmouseout="sorter()">
	</div>
</div>

<div class="connectedSortable" id="trecakolona" onmouseup="sorter()" onmouseout="sorter()">
</div>
<script type="text/javascript">
$(function()
	{
		$( "#desnakolona, #trecakolona" ).sortable({
			connectWith: ".connectedSortable"
		});
	});
function delform()
{
var r=confirm("Da li sigurno želite da obrišete ovu nabavku iz baze?");
if (r==true)
  {
	var skladiste = document.getElementById("yskladiste").value;
	document.getElementById("delsklad").value=skladiste;
	document.getElementById("delform").submit();
  }
}
function novo()
	{
		document.getElementById("forma").reset();
		document.getElementById("yid").value="";
		$("#unosbtn").prop('value', 'Unesi');
		proizvodi("x");
	}
function izmena(posebno)
	{
		var sortedIDs;
		d = new Date();
		$("#unosbtn").prop('value', 'Promeni');
		document.getElementById("forma").reset();
		document.getElementById("del").value=posebno;
		$.getJSON('ajax/nabavkaui.php', {posebno: posebno}, function(data) {
			$('#yid').val(data.yid);
			$('#yncarina').val(data.yncarina);
			$('#ydatdostavnice').val(data.ydatdostavnice);
			$('#ydatprijemarobe').val(data.ydatprijemarobe);
			$('#ydobavljac').val(data.ydobavljac);
			$('#ybrnarudzbenice').val(data.ybrnarudzbenice);
			$('#yskladiste').val(data.yskladiste);
			$('#ykursbanka').val(data.ykursbanka);
			$('#ykurssred').val(data.ykurssred);
			$('#ykurscarine').val(data.ykurscarine);
			$('#ytransport').val(data.ytransport);
			$('#yneptroskoviuk').val(data.yneptroskoviuk);
			var yplaceno=(data.yplaceno);
			$(':radio[name="placeno"][value='+yplaceno+']').prop('checked', true);
		});
		$.getJSON('ajax/nabavkaup.php', {posebno: posebno}, function(data) {
			$('#trecakolona').html(data.ysortostali);
			$('#desnakolona').html(data.ysorttu);
			sorter();
			var sortedIDs = $( "#desnakolona" ).sortable( "toArray" );
			$.each(sortedIDs, function(index, value) {
				var posebnox = "0000000"+value;
				posebnox = posebnox.substr(posebnox.length-8);
				kolitem(posebnox);
			});
		});
	}
function proizvodi(xx)
	{
		$.getJSON('ajax/nabavkaup.php', {posebno: xx}, function(data) {
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
	var posebnox = "0000000"+posebno;
	posebnox = posebnox.substr(posebnox.length-8);
	var posebno=posebnox;
	var pakovanje = document.getElementById("kolpak"+posebno).value;
	var kutija = document.getElementById("kolkut"+posebno).value;
	var ncena = document.getElementById("ncenalist"+posebno).innerHTML;
	var neto = document.getElementById("netolist"+posebno).innerHTML;
	var bruto = document.getElementById("brutolist"+posebno).innerHTML;
	var cstopa = document.getElementById("cstopalist"+posebno).innerHTML;
	var ncarine = document.getElementById("yncarine").value;
	var kbanke = document.getElementById("ykursbanka").value;
	var kcarine = document.getElementById("ykurscarine").value;
	var trostransport = document.getElementById("ytransport").value;
	var ostalitros = document.getElementById("yneptroskoviuk").value;
	var pdv = document.getElementById("pdvlist"+posebno).innerHTML;
	var kolitem = pakovanje*kutija;
	var ukcena = ncena*kolitem;
	var ukneto = neto*kolitem/1000;
	var ukbruto = bruto*kolitem/1000;
	var pcarcena = ncena*kcarine;
	var ukcarcena = ukcena*kcarine;
	var pdcena = ncena*kbanke;
	var ukdcena = ukcena*kbanke;
	var ptrocar = cstopa*pcarcena/100;
	var uktrocar = cstopa*ukcarcena/100;
	var bankvred = ukcena*kbanke;
	var dimcar = ukcarcena-bankvred;
	switch(ncarine)
		{
		case '2':
			var cosnovica = +ukcarcena + +(ukcarcena*cstopa/100);
		  break;
		default:
			var cosnovica = +ukcarcena + +(ukcarcena*cstopa/100) + +dimcar;
		}
	var ccarina = cosnovica * pdv / 100;
	ukcena=Math.ceil(ukcena * 100)/100;
	pcarcena=Math.ceil(pcarcena * 100)/100;
	ukcarcena=Math.ceil(ukcarcena * 100)/100;
	pdcena=Math.ceil(pdcena * 100)/100;
	ukdcena=Math.ceil(ukdcena * 100)/100;
	ptrocar=Math.ceil(ptrocar * 100)/100;
	uktrocar=Math.ceil(uktrocar * 100)/100;
	ccarina=Math.ceil(ccarina * 100)/100;
	document.getElementById("kolitem"+posebno).value=kolitem;
	document.getElementById("kollist"+posebno).innerHTML=kolitem;
	document.getElementById("cenauklist"+posebno).innerHTML=ukcena;
	document.getElementById("netouklist"+posebno).innerHTML=ukneto;
	document.getElementById("brutouklist"+posebno).innerHTML=ukbruto;
	document.getElementById("pcarcenalist"+posebno).innerHTML=pcarcena;
	document.getElementById("ukcarcenalist"+posebno).innerHTML=ukcarcena;
	document.getElementById("pdcenalist"+posebno).innerHTML=pdcena;
	document.getElementById("ukdcenalist"+posebno).innerHTML=ukdcena;
	document.getElementById("ptrocarlist"+posebno).innerHTML=ptrocar;
	document.getElementById("uktrocarlist"+posebno).innerHTML=uktrocar;
	document.getElementById("ccarinalist"+posebno).innerHTML=ccarina;
	var sortedIDs = $( "#desnakolona" ).sortable( "toArray" );
	var ukkutija=0;
	var ukpredmeta=0;
	var ukkcena=0;
	var ukkneto=0;
	var ukkbruto=0;
	var ukkcarcena=0;
	var ukkdcena=0;
	var ukkcarina=0;
	var ukkccarina=0;
	$.each(sortedIDs, function(index, value) {
		var	kutija = document.getElementById("kolkut"+value).value;
		ukkutija=+ukkutija + +kutija;
		var	predmeta = document.getElementById("kolitem"+value).value;
		ukpredmeta=+ukpredmeta + +predmeta;
		var	cenauk = document.getElementById("cenauklist"+value).innerHTML;
		ukkcena=+ukkcena + +cenauk;
		var	netouk = document.getElementById("netouklist"+value).innerHTML;
		ukkneto=+ukkneto + +netouk;
		var	brutouk = document.getElementById("brutouklist"+value).innerHTML;
		if (brutouk!=0)	ukkbruto=+ukkbruto + +brutouk;
		else ukkbruto=+ukkbruto + +netouk;
		var	carcenauk = document.getElementById("ukcarcenalist"+value).innerHTML;
		ukkcarcena=+ukkcarcena + +carcenauk;
		var	dcenauk = document.getElementById("ukdcenalist"+value).innerHTML;
		ukkdcena=+ukkdcena + +dcenauk;
		var	carinauk = document.getElementById("uktrocarlist"+value).innerHTML;
		ukkcarina=+ukkcarina + +carinauk;
		var	ccarinauk = document.getElementById("ccarinalist"+value).innerHTML;
		ukkccarina=+ukkccarina + +ccarinauk;
	});
	$.each(sortedIDs, function(index, value) {
		kolitem=document.getElementById("kolitem"+value).value;
		var uneto=document.getElementById("netouklist"+value).innerHTML;
		var ubruto=document.getElementById("brutouklist"+value).innerHTML;
		var ucena=document.getElementById("cenauklist"+value).innerHTML;
		var pdcena=document.getElementById("pdcenalist"+value).innerHTML;
		var ptrocar=document.getElementById("ptrocarlist"+value).innerHTML;
		var pcb=document.getElementById("pcblist"+value).innerHTML;
		if (ubruto!=0) {
			var uctez=ubruto/ukkbruto;
		}
		else {
			var uctez=uneto/ukkbruto;
		}
		var uktrotra = trostransport*uctez;
		var ptrotra = uktrotra/kolitem;
		var uccena = ucena/ukkcena;
		var ukotro = ostalitros*uccena;
		var potro = ukotro/kolitem;
		var fnc = +pdcena + +ptrocar + +ptrotra + +potro;
		var razlika = pcb - fnc;
		var marza = razlika/fnc;
		var zar2 = (pcb*0.8) - fnc;
		var zar3 = (pcb*0.7) - fnc;
		uktrotra=Math.ceil(uktrotra * 100)/100;
		ptrotra=Math.ceil(ptrotra * 100)/100;
		uctez=Math.ceil(uctez * 10000)/100;
		ukotro=Math.ceil(ukotro * 100)/100;
		potro=Math.ceil(potro * 100)/100;
		uccena=Math.ceil(uccena * 10000)/100;
		fnc=Math.ceil(fnc * 100)/100;
		razlika=Math.ceil(razlika * 100)/100;
		marza=Math.ceil(marza * 10000)/100;
		zar2=Math.ceil(zar2 * 100)/100;
		zar3=Math.ceil(zar3 * 100)/100;
		document.getElementById("tezinaplist"+value).innerHTML=uctez;
		document.getElementById("htezinap"+value).value=uctez;
		document.getElementById("cenaplist"+value).innerHTML=uccena;
		document.getElementById("ptrotralist"+value).innerHTML=ptrotra;
		document.getElementById("htransportiznos"+value).value=ptrotra;
		document.getElementById("uktrotralist"+value).innerHTML=uktrotra;
		document.getElementById("potrolist"+value).innerHTML=potro;
		document.getElementById("hneptroskovi"+value).value=potro;
		document.getElementById("ukotrolist"+value).innerHTML=ukotro;
		document.getElementById("fnclist"+value).innerHTML=fnc;
		document.getElementById("hnabcena"+value).value=fnc;
		document.getElementById("razlist"+value).innerHTML=razlika;
		document.getElementById("hrazlika"+value).value=razlika;
		document.getElementById("marzalist"+value).innerHTML=marza;
		document.getElementById("hmarza"+value).value=marza;
		document.getElementById("zar2list"+value).innerHTML=zar2;
		document.getElementById("zar3list"+value).innerHTML=zar3;
		
	});	
	var ukkfnac = +ukkdcena + +ukkcarina + +trostransport + +ostalitros;
	var ukkpcb=0;
	$.each(sortedIDs, function(index, value) {
		var	xkol = document.getElementById("kollist"+value).innerHTML;
		var	xpcb = document.getElementById("pcblist"+value).innerHTML;
		ukkpcb=ukkpcb + (xkol*xpcb);
	});	
	var ukkrazlika = ukkpcb-ukkfnac;
	var ukkmarza = ukkrazlika/ukkfnac;
	ukkcena=Math.ceil(ukkcena * 10000)/10000;
	ukkneto=Math.ceil(ukkneto * 1000)/1000;
	ukkbruto=Math.ceil(ukkbruto * 1000)/1000;
	ukkcarcena=Math.ceil(ukkcarcena * 100)/100;
	ukkdcena=Math.ceil(ukkdcena * 100)/100;
	ukkcarina=Math.ceil(ukkcarina * 100)/100;
	ukkccarina=Math.ceil(ukkccarina * 100)/100;
	ukkfnac=Math.ceil(ukkfnac * 100)/100;
	ukkpcb=Math.ceil(ukkpcb * 100)/100;
	ukkrazlika=Math.ceil(ukkrazlika * 100)/100;
	ukkmarza=Math.ceil(ukkmarza * 10000)/100;
	document.getElementById("ukkutija").innerHTML=ukkutija;
	document.getElementById("ukpredmeta").innerHTML=ukpredmeta;
	document.getElementById("ukkcena").innerHTML=ukkcena;
	document.getElementById("ukkneto").innerHTML=ukkneto;
	document.getElementById("ukkbruto").innerHTML=ukkbruto;
	document.getElementById("ukkcarcena").innerHTML=ukkcarcena;
	document.getElementById("ukkdcena").innerHTML=ukkdcena;
	document.getElementById("ukkcarina").innerHTML=ukkcarina;
	document.getElementById("yulaznipdv").value=ukkccarina;
	document.getElementById("ukkfnc").innerHTML=ukkfnac;
	document.getElementById("hukkfnc").value=ukkfnac;
	document.getElementById("ukkpcb").innerHTML=ukkpcb;
	document.getElementById("hukkpcb").value=ukkpcb;
	document.getElementById("ukkrazlika").innerHTML=ukkrazlika;
	document.getElementById("hukkrazlika").value=ukkrazlika;
	document.getElementById("ukkmarza").innerHTML=ukkmarza;
	document.getElementById("hukkmarza").value=ukkmarza;
}
</script>
</form>
<form id="delform" action="#" method="post">
<input type="hidden" id="del" name="del" />
<input type="hidden" id="delsklad" name="delsklad" />
</form>
</body>
</html>