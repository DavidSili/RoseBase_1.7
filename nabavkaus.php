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

	if (isset($brnarudzbenice)) $brnarudzbenice=mysqli_real_escape_string($mysqli,$brnarudzbenice);
	if (isset($kursbanka)) $kursbanka=mysqli_real_escape_string($mysqli,$kursbanka);
	if (isset($kurssred)) $kurssred=mysqli_real_escape_string($mysqli,$kurssred);
	if (isset($kurscarine)) $kurscarine=mysqli_real_escape_string($mysqli,$kurscarine);
	if (isset($transport)) $transport=mysqli_real_escape_string($mysqli,$transport);
	if (isset($neptroskoviuk)) $neptroskoviuk=mysqli_real_escape_string($mysqli,$neptroskoviuk);
	if (isset($IDx)) $IDx=mysqli_real_escape_string($mysqli,$IDx);

	if(isset($sorterklik)) {
	$sviid=explode(',',$sorterklik);
	foreach($sviid as $uu) {
	$ver='OR `ID`="'.$uu.'" ';
	}
	$sql='SELECT proizvodi.sifra sifra,
		proizvodi.tezinaneto neto,
		proizvodi.tezinabruto bruto,
		ctarife.stopa cstopa,
		proizvodi.pcena pcena,
		proizvodi.pdv pdv
	FROM proizvodi
	LEFT JOIN ctarife
		ON proizvodi.cartar = ctarife.ID
	ORDER BY proizvodi.sifra ASC';
	$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
	while ($row=$result->fetch_assoc()) {
		foreach($row as $xx => $yy) {
			$$xx=$yy;
		}
		${'neto'.$sifra}=$neto;
		${'bruto'.$sifra}=$bruto;
		${'cstopa'.$sifra}=$cstopa;
		${'pcena'.$sifra}=$pcena;
		${'pdv'.$sifra}=$pdv;
	}

	$ukkbruto=0;
	$ukkcena=0;
	$ukkcarcena=0;
	$ukkdcena=0;
	$ukkcarina=0;
	$ukkccarina=0;
	$ukkpcb=0;
	foreach($sviid as $ff) {
		${'cenauk'.$ff}=${'ncena'.$ff}*${'kolitem'.$ff};
		if (${'bruto'.$ff}!=0) ${'brutouk'.$ff}=${'bruto'.$ff}*${'kolitem'.$ff};
		else ${'brutouk'.$ff}=${'neto'.$ff}*${'kolitem'.$ff};
		$ukkcena=$ukkcena+${'cenauk'.$ff};
		$ukkbruto=$ukkbruto+${'brutouk'.$ff};
		${'pcarcena'.$ff}=${'ncena'.$ff}*$kurscarine;
		${'ukcarcena'.$ff}=${'cenauk'.$ff}*$kurscarine;
		${'pdcena'.$ff}=${'ncena'.$ff}*$kursbanka;
		${'ukdcena'.$ff}=${'cenauk'.$ff}*$kursbanka;
		${'ptrocar'.$ff}=${'cstopa'.$ff}*${'pcarcena'.$ff}/100;
		${'uktrocar'.$ff}=${'cstopa'.$ff}*${'ukcarcena'.$ff}/100;
		${'dimcar'.$ff}=${'ukcarcena'.$ff}-(${'cenauk'.$ff}*$kursbanka);
		if ($ncarine=='2') {
			${'cosnovica'.$ff}=${'ukcarcena'.$ff}*((${'cstopa'.$ff}+100)/100);
		}
		else {
			${'cosnovica'.$ff}=${'ukcarcena'.$ff}*((${'cstopa'.$ff}+100)/100)+${'dimcar'.$ff};
		}
		${'ccarina'.$ff}=${'cosnovica'.$ff}*${'pdv'.$ff}/100;
		$ukkdcena=$ukkdcena+${'ukdcena'.$ff};
		$ukkcarina=$ukkcarina+${'uktrocar'.$ff};
		$ukkccarina=$ukkccarina+${'ccarina'.$ff};
		${'pcb'.$ff}=${'pcena'.$ff}/((100+${'pdv'.$ff})/100);
		$ukkpcb=$ukkpcb+(${'kolitem'.$ff}*${'pcb'.$ff});
	}
	$ukkfnc=$ukkdcena+$ukkcarina+$transport+$neptroskoviuk;
	$ukkrazlika=$ukkpcb-$ukkfnc;
	$ukkmarza=$ukkrazlika/$ukkfnc;
	foreach($sviid as $ff) {
		${'uctez'.$ff}=${'brutouk'.$ff}/$ukkbruto;
		${'uktrotra'.$ff}=$transport*${'uctez'.$ff};
		${'ptrotra'.$ff}=${'uktrotra'.$ff}/${'kolitem'.$ff};
		${'uccena'.$ff}=${'cenauk'.$ff}/$ukkcena;
		${'ukotro'.$ff}=$neptroskoviuk*${'uccena'.$ff};
		${'potro'.$ff}=${'ukotro'.$ff}/${'kolitem'.$ff};
		${'fnc'.$ff}=${'pdcena'.$ff}+${'ptrocar'.$ff}+${'ptrotra'.$ff}+${'potro'.$ff};
		${'razlika'.$ff}=${'pcb'.$ff}-${'fnc'.$ff};
		${'marza'.$ff}=${'razlika'.$ff}/${'fnc'.$ff};
		${'uctez'.$ff}=ceil(${'uctez'.$ff}*10000)/100;
		${'ptrotra'.$ff}=ceil(${'ptrotra'.$ff}*100)/100;
		${'potro'.$ff}=ceil(${'potro'.$ff}*100)/100;
		${'fnc'.$ff}=ceil(${'fnc'.$ff}*100)/100;
		${'razlika'.$ff}=ceil(${'razlika'.$ff}*100)/100;
		${'marza'.$ff}=ceil(${'marza'.$ff}*10000)/100;
		${'pcb'.$ff}=ceil(${'pcb'.$ff}*100)/100;
	}
	$ukkccarina=ceil($ukkccarina*100)/100;
	$ukkfnc=ceil($ukkfnc*100)/100;
	$ukkrazlika=ceil($ukkrazlika*100)/100;
	$ukkmarza=ceil($ukkmarza*10000)/100;
	}

	$dattime=date('G:i:s j.n.Y.');
	$sql='SELECT ID FROM nabavka ORDER BY ID DESC LIMIT 1';
	$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
	$row=$result->fetch_assoc();
	$lastID=$row['ID'];
	$result = mysqli_query($mysqli,"SHOW TABLE STATUS LIKE 'nabavka'");
	$data = $result->fetch_assoc();
	$nextID = $data['Auto_increment'];
	
	if(isset($sorterklik)) $sorterklikx=explode(',',$sorterklik);
	
	// Brisanje
	
	if (isset($_POST['del'])) {
		$del=$_POST['del'];
		$sklad=$_POST['delsklad'];
		$proizvodi=array();
		$sql1='SELECT proizvod FROM nabavkaitems WHERE nabavka="'.$del.'"';
		$result=mysqli_query($mysqli,$sql1) or die (mysqli_error($mysqli));
		while($row=$result->fetch_assoc()) {
		$proizvodi[]=$row['proizvod'];
		}
		foreach($proizvodi as $oo) {
		$sql1a='SELECT kolicina FROM nabavkaitems WHERE nabavka="'.$del.'" AND proizvod="'.$oo.'"';
		$result=mysqli_query($mysqli,$sql1a) or die (mysqli_error($mysqli));
		$row=$result->fetch_assoc();
		$pstanjeit=$row['kolicina'];

		$sql1b='SELECT kolicina FROM zalihe WHERE skladiste="'.$sklad.'" AND proizvod="'.$oo.'"';
		$result=mysqli_query($mysqli,$sql1b) or die (mysqli_error($mysqli));
		$row=$result->fetch_assoc();
		$pstanjez=$row['kolicina'];
		
		$novakolicina=$pstanjez-$pstanjeit;
		
		$sql2a='UPDATE zalihe SET kolicina="'.$novakolicina.'" WHERE skladiste="'.$sklad.'" AND proizvod="'.$oo.'"';
		
		$sql2b='DELETE FROM nabavkaitems WHERE nabavka="'.$del.'" AND proizvod="'.$oo.'"';
		mysqli_query($mysqli,$sql2a) or die (mysqli_error($mysqli));
		mysqli_query($mysqli,$sql2b) or die (mysqli_error($mysqli));
		}
		
		$sql3='DELETE FROM nabavka WHERE ID="'.$del.'"';
		mysqli_query($mysqli,$sql3);
	}                                                        // Unos nove nabavke
	elseif (isset($lastID)==false OR $IDx>$lastID) {
		$sql='INSERT INTO nabavka (ncarine, datdostavnice, datprijemarobe, dobavljac, brnarudzbenice, skladiste, kursbanka, kurssred, kurscarine, transport, ulaznipdv, neptroskoviuk, placeno, ukfnc, ukpcb, ukrazlika, ukmarza, uneo) VALUES ("'.$ncarine.'", "'.$datdostavnice.'", "'.$datprijemarobe.'", "'.$dobavljac.'", "'.$brnarudzbenice.'", "'.$skladiste.'", "'.$kursbanka.'", "'.$kurssred.'", "'.$kurscarine.'", "'.$transport.'", "'.$ukkccarina.'", "'.$neptroskoviuk.'", "'.$placeno.'","'.$ukkfnc.'","'.$ukkpcb.'","'.$ukkrazlika.'","'.$ukkmarza.'","'.$user.' - '.$dattime.'")';
		mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
		
		foreach($sorterklikx as $zz) {
			$nabavka=$nextID;
			$idunabavci=${'id'.$zz};
			$proizvod=$zz;
			$kolicina=${'kolitem'.$zz};
			$cenaueur=${'ncena'.$zz};
			$transportpr=${'uctez'.$zz};
			$transportiznos=${'ptrotra'.$zz};
			$cstopa=${'cstopa'.$zz};
			$neptroskovi=${'potro'.$zz};
			$nabcena=${'fnc'.$zz};
			$razlika=${'razlika'.$zz};
			$mpbezpdv=${'pcb'.$zz};
			$marza=${'marza'.$zz};
			$pdv=${'pdv'.$zz};
			$mpsapdv=${'pcena'.$zz};
			
			$sql2='INSERT INTO nabavkaitems (nabavka, idunabavci, proizvod, kolicina, cenaueur, transportpr, transportiznos, cstopa, neptroskovi, nabcena, razlika, mpbezpdv, marza, pdv, mpsapdv, uneo) VALUES ("'.$nabavka.'", "'.$idunabavci.'", "'.$proizvod.'", "'.$kolicina.'", "'.$cenaueur.'", "'.$transportpr.'", "'.$transportiznos.'", "'.$cstopa.'", "'.$neptroskovi.'", "'.$nabcena.'", "'.$razlika.'", "'.$mpbezpdv.'", "'.$marza.'", "'.$pdv.'", "'.$mpsapdv.'", "'.$user.' - '.$dattime.'")';
			// $debug2.=$sql2.'; YY ;';
			mysqli_query($mysqli,$sql2) or die (mysqli_error($mysqli));
			
			$sql3a='SELECT kolicina FROM zalihe WHERE skladiste="'.$skladiste.'" AND proizvod="'.$zz.'"';
			$result=mysqli_query($mysqli,$sql3a) or die (mysqli_error($mysqli));
			$row=$result->fetch_assoc();
			$pstanje=$row['kolicina'];
			
			if (isset($pstanje)==false) {
				$sql3b='INSERT INTO zalihe (skladiste, proizvod, kolicina, uneo) VALUES ("'.$skladiste.'", "'.$zz.'", "'.$kolicina.'", "'.$user.' - '.$dattime.'")';
			}
			else {
				$nstanje=$pstanje+$kolicina;
				$sql3b='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="'.$skladiste.'" AND proizvod="'.$proizvod.'"';
			}
			// $debug2.=$sql3b;
			mysqli_query($mysqli,$sql3b) or die (mysqli_error($mysqli));
			
		}

	}                                                  // Izmena postojeće nabavke
	else {
	
		$sql='SELECT menjali FROM nabavka WHERE ID="'.$IDx.'"';
		$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
		$row=$result->fetch_assoc();
		$xmenjali=$row['menjali'];
		$cid=$IDx;
		
		$sql='UPDATE nabavka SET ncarine="'.$ncarine.'", datdostavnice="'.$datdostavnice.'", datprijemarobe="'.$datprijemarobe.'", dobavljac="'.$dobavljac.'", brnarudzbenice="'.$brnarudzbenice.'", skladiste="'.$skladiste.'", kursbanka="'.$kursbanka.'", kurssred="'.$kurssred.'", kurscarine="'.$kurscarine.'", transport="'.$transport.'", ulaznipdv="'.$ukkccarina.'", neptroskoviuk="'.$neptroskoviuk.'", placeno="'.$placeno.'", ukfnc="'.$ukkfnc.'", ukpcb="'.$ukkpcb.'", ukrazlika="'.$ukkrazlika.'", ukmarza="'.$ukkmarza.'", menjali="'.$xmenjali.'; '.$user.' - '.$dattime.'" WHERE ID="'.$IDx.'"';
		mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
		
		$sviitemi=array();
		$sql='SELECT proizvod FROM nabavkaitems WHERE nabavka="'.$IDx.'"';
		$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
		while($row=$result->fetch_assoc()) {
		$sviitemi[]=$row['proizvod'];
		}
		
		foreach ($sviitemi as $hh) {
			if (in_array($hh, $sorterklikx)==false) {
				$sql1='SELECT kolicina FROM nabavkaitems WHERE nabavka="'.$IDx.'" AND proizvod="'.$hh.'"';
				$result=mysqli_query($mysqli,$sql1) or die (mysqli_error($mysqli));
				$row=$result->fetch_assoc();
				$pstanjen=$row['kolicina'];

				$sql2='SELECT kolicina FROM zalihe WHERE skladiste="'.$skladiste.'" AND proizvod="'.$hh.'"';
				$result=mysqli_query($mysqli,$sql2) or die (mysqli_error($mysqli));
				$row=$result->fetch_assoc();
				$pstanjez=$row['kolicina'];
				
				$nstanje=$pstanjez-$pstanjen;
				
				$sql3='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="'.$skladiste.'" AND proizvod="'.$hh.'"';
				mysqli_query($mysqli,$sql3) or die (mysqli_error($mysqli));
				$sql4='DELETE FROM nabavkaitems WHERE nabavka="'.$IDx.'" AND proizvod="'.$hh.'"';
				mysqli_query($mysqli,$sql4) or die (mysqli_error($mysqli));
			}
		}

		foreach ($sorterklikx as $zz) {
		
			$nabavka=$IDx;
			$idunabavci=${'id'.$zz};
			$proizvod=$zz;
			$kolicina=${'kolitem'.$zz};
			$cenaueur=${'ncena'.$zz};
			$transportpr=${'uctez'.$zz};
			$transportiznos=${'ptrotra'.$zz};
			$cstopa=${'cstopa'.$zz};
			$neptroskovi=${'potro'.$zz};
			$nabcena=${'fnc'.$zz};
			$razlika=${'razlika'.$zz};
			$mpbezpdv=${'pcb'.$zz};
			$marza=${'marza'.$zz};
			$pdv=${'pdv'.$zz};
			$mpsapdv=${'pcena'.$zz};

			$sql='SELECT kolicina, menjali FROM nabavkaitems WHERE nabavka="'.$IDx.'" AND proizvod="'.$zz.'"';
			$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
			$row=$result->fetch_assoc();
			$xxmenjali=$row['menjali'];
			$pstanjen=$row['kolicina'];
			
			if (isset($pstanjen)==false) {
			$sql2='INSERT INTO nabavkaitems (nabavka, idunabavci, proizvod, kolicina, cenaueur, transportpr, transportiznos, cstopa, neptroskovi, nabcena, razlika, mpbezpdv, marza, pdv, mpsapdv, uneo) VALUES ("'.$nabavka.'", "'.$idunabavci.'", "'.$proizvod.'", "'.$kolicina.'", "'.$cenaueur.'", "'.$transportpr.'", "'.$transportiznos.'", "'.$cstopa.'", "'.$neptroskovi.'", "'.$nabcena.'", "'.$razlika.'", "'.$mpbezpdv.'", "'.$marza.'", "'.$pdv.'", "'.$mpsapdv.'", "'.$user.' - '.$dattime.'")';
			}
			else {
			$sql2='UPDATE nabavkaitems SET idunabavci="'.$idunabavci.'", kolicina="'.$kolicina.'", cenaueur="'.$cenaueur.'", transportpr="'.$transportpr.'", transportiznos="'.$transportiznos.'", cstopa="'.$cstopa.'", neptroskovi="'.$neptroskovi.'", nabcena="'.$nabcena.'", razlika="'.$razlika.'", mpbezpdv="'.$mpbezpdv.'", marza="'.$marza.'", pdv="'.$pdv.'", mpsapdv="'.$mpsapdv.'", menjali="'.$xxmenjali.'; '.$user.' - '.$dattime.'" WHERE nabavka="'.$nabavka.'" AND proizvod="'.$proizvod.'"';
			}
			mysqli_query($mysqli,$sql2) or die (mysqli_error($mysqli));
			
			$sql3a='SELECT kolicina FROM zalihe WHERE skladiste="'.$skladiste.'" AND proizvod="'.$zz.'"';
			$result=mysqli_query($mysqli,$sql3a) or die (mysqli_error($mysqli));
			$row=$result->fetch_assoc();
			$pstanjez=$row['kolicina'];
			
			if (isset($pstanjez)==false) {
				$sql3b='INSERT INTO zalihe (skladiste, proizvod, kolicina, uneo) VALUES ("'.$skladiste.'", "'.$zz.'", "'.$kolicina.'", "'.$user.' - '.$dattime.'")';
				mysqli_query($mysqli,$sql3b) or die (mysqli_error($mysqli));
			}
			else {
				if (($pstanjen==$kolicina)==false) {
					$nstanje=$pstanjez-$pstanjen+$kolicina;
					$sql3b='UPDATE zalihe SET kolicina="'.$nstanje.'" WHERE skladiste="'.$skladiste.'" AND proizvod="'.$proizvod.'"';
					mysqli_query($mysqli,$sql3b) or die (mysqli_error($mysqli));
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
<title id="Timerhead">Jednostavna nabavka - Land of Roses doo: baza podataka</title>
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
	text-align:right;
}
#desnakolona li input {
	padding:0;
	margin-right:2px;
	float:left;
	background:white;
	height:18px;
	text-align:right;
}
#trecakolona li .nazivlist,
#trecakolona li .ssifralist {
	width:285px;
	display:inline;
}
#tabbar {
	height:50px;
	width:612px;
}
#desnakolona {
	position:absolute;
	top:51px;
	min-height:500px;
	width:612px;
}
#trecakolona {
	position:absolute;
	top:302px;
	left:205px;
	bottom:5px;
	width:320px;
	overflow:auto;
}
#tabbarlab {
	height:21px;
	width:612px;
	font-size:12;
	text-align:center;
	border-bottom:5px solid #777;
	font-weight:bold;
}
#tabbarlab div {
	padding:3px 2px 0 2px;
	float:left;
	height:21px;
	border-right:2px solid #777;
}
#tabbaruk {
	background:#777;
	padding-top:3px;
	height:22px;
	width:612px;
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
	text-align:right;
}
#desniwrap {
	position:absolute;
	top:32px;
	left:535px;
	bottom:0;
	width:629px;
	overflow-y:scroll;
	border: 3px inset #aaa;
}
.idlist {width:20px;}
.nazivlist {
	width:300px;
	overflow:hidden;
}
.kolkutlist {
	height:20px;
	font-size:12;
}
.kollist {width:34px;}
.ncenalist {width:50px;}
.cenauklist {
	width:56px;
	font-weight:bold;
}
.ssifralist {width:53px;}
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
	<div style="position:absolute;top:32px;left:5px;bottom:5px;width:190px">
		<input id="unosbtn" type="submit" value="Unesi" style="width:100%;height:20px" />
		<input type="button" value="Nova nabavka" style="width:100%;margin-top:5px" onclick="novo()"/>
		<input type="button" value="Obriši" style="width:100%" onclick="delform()"/>
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px"></div>
		<div style="position:absolute;top:70px;left:0px;width:190px;bottom:0px;overflow-y:auto;">
			<div id="blacklink" style="font-size:12;overflow:auto">
<?php
$sql="SELECT `ID`,`naziv` FROM brendovi ORDER BY `ID`";
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
while($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$brendovi[$ID]=$naziv;
}
$brendxx="";
$sql="SELECT `ID`,`datprijemarobe` FROM nabavka ORDER BY `ID` DESC";
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
while($row=$result->fetch_assoc()) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$datprijemarobe=date('d.m.Y.',strtotime($datprijemarobe));
	echo '<a href="#" onclick="izmena('.$ID.')">'.$ID.' - '.$datprijemarobe.'</a><br/>';
}
?>
			</div>
		</div>
	</div>
<div style="width:165px;top:27px;left:205px;position:absolute;height:270px;background:#fff;opacity:0.5">
</div>
<div style="position:absolute;top:32px;left:535px;width:629px;height:50px;background:#fff">
</div>
<div style="position:absolute;top:83px;left:535px;width:629px;bottom:0;background:#fff;opacity:0.8">
</div>
<div style="width:325px;top:302px;left:205px;position:absolute;bottom:0;background:#fff;opacity:0.5">
</div>
<div class="wrap" style="position:absolute;top:32px;left:200px;width:330px;height:267px">
	<div class="iur">
		<div class="iul">ID</div>
		<input id="yid" type="text" name="IDx" class="iud" readonly style="background:#ccc" value="<?php
$sql="SELECT `ID` FROM nabavka ORDER BY `ID` DESC LIMIT 1";
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
$row=$result->fetch_assoc();
if (isset($row['ID'])) {
$result = mysqli_query($mysqli,"SHOW TABLE STATUS LIKE 'nabavka'");
$data = $result->fetch_assoc();
$nextID = $data['Auto_increment'];
echo $nextID;
}
else {
$ID =1;
echo $ID;
}
		?>"/>
		<div style="clear:both;"></div>
	</div>
	<input type="hidden" id="nid" value="<?php echo $ID; ?>" />
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
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
while($row=$result->fetch_assoc()) {
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
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
while($row=$result->fetch_assoc()) {
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
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
$row=$result->fetch_assoc();
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
			<div style="width:24px" id="ukstavki">0</div>
			<div style="color:#fff;background:#777;margin-left:226px">Ukupno: </div>
			<div style="width:37px" id="ukkutija">0</div>
			<div style="width:37px" id="ukpredmeta">0</div>
			<div style="margin-left:50px;width:68px" id="ukkcena">0</div>
		</div>
		<div id="tabbarlab">
			<div style="width:25px">ID</div>
			<div style="width:56px">Šifra</div>
			<div style="width:303px">Naziv</div>
			<div style="width:37px">Kutija</div>
			<div style="width:37px;font-size:7;word-break:break-all;line-height:9px;padding-top:6px;height:15px">predmeta</div>
			<div style="width:53px">Cena</div>
			<div style="width:59px">Uk. cena</div>

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
		var nid = document.getElementById("nid").value;
		document.getElementById("yid").value=nid;
		document.getElementById("ukstavki").innerHTML="0";
		document.getElementById("ukkutija").innerHTML="0";
		document.getElementById("ukpredmeta").innerHTML="0";
		document.getElementById("ukkcena").innerHTML="0";
		$("#unosbtn").prop('value', 'Unesi');
		proizvodi("x");
	}
function izmena(posebno)
	{
		var sortedIDs;
		$("#unosbtn").prop('value', 'Promeni');
		document.getElementById("forma").reset();
		document.getElementById("del").value=posebno;
		$.getJSON('ajax/nabavkausi.php', {posebno: posebno}, function(data) {
			$('#yid').val(data.yid);
			$('#yncarine').val(data.yncarine);
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
		$.getJSON('ajax/nabavkausp.php', {posebno: posebno}, function(data) {
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
		$.getJSON('ajax/nabavkausp.php', {posebno: xx}, function(data) {
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
	var kolitem = pakovanje*kutija;
	var ukcena = ncena*kolitem;
	ukcena=parseFloat(Math.ceil(ukcena* 100)/100).toFixed(2);
	document.getElementById("kolitem"+posebno).value=kolitem;
	document.getElementById("kollist"+posebno).innerHTML=kolitem;
	document.getElementById("cenauklist"+posebno).innerHTML=ukcena;
	var sortedIDs = $( "#desnakolona" ).sortable( "toArray" );
	var ukkutija=0;
	var ukpredmeta=0;
	var ukkcena=0;
	$.each(sortedIDs, function(index, value) {
		var	kutija = document.getElementById("kolkut"+value).value;
		ukkutija=+ukkutija + +kutija;
		var	predmeta = document.getElementById("kolitem"+value).value;
		ukpredmeta=+ukpredmeta + +predmeta;
		var	cenauk = document.getElementById("cenauklist"+value).innerHTML;
		ukkcena=+ukkcena + +cenauk;
	});
	ukkcena=parseFloat(Math.ceil(ukkcena* 100)/100).toFixed(2);
	document.getElementById("ukkutija").innerHTML=ukkutija;
	document.getElementById("ukpredmeta").innerHTML=ukpredmeta;
	document.getElementById("ukkcena").innerHTML=ukkcena;
}
</script>
</form>
<form id="delform" action="#" method="post">
<input type="hidden" id="del" name="del" />
<input type="hidden" id="delsklad" name="delsklad" />
</form>
</body>
</html>