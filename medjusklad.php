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

if(isset($_POST) && !empty($_POST) AND $_POST['sorterklik1']==$_POST['sorterklik2']) {
	foreach($_POST as $xx => $yy) {
		$$xx=$yy;
	}
	
	$idmsklad=$IDx;

	$dattime=date('G:i:s j.n.Y.');
	$datbase=date('Y-m-d');
	
	$sorterklikx=explode(',',$sorterklik1);
	$novo1=array();
	$novo2=array();
	foreach($sorterklikx as $zz) {
		$novo1[$zz]=${'elementa'.$zz};
		$novo2[$zz]=${'elementb'.$zz};
	}

	$postojeci1=array();
	$postojeci2=array();
	
	$sql='SELECT proizvod, kolicina FROM zalihe WHERE skladiste = "'.$skladiste1.'"';
	$result=mysqli_query($mysqli,$sql) or die;
	while ($row=$result->fetch_assoc()) {
		foreach($row as $xx => $yy) {
			$$xx=$yy;
		}
		$postojeci1[$proizvod]=$kolicina;
	}
	
	$sql='SELECT proizvod, kolicina FROM zalihe WHERE skladiste = "'.$skladiste2.'"';
	$result=mysqli_query($mysqli,$sql) or die;
	while ($row=$result->fetch_assoc()) {
		foreach($row as $xx => $yy) {
			$$xx=$yy;
		}
		$postojeci2[$proizvod]=$kolicina;
	}
	
	foreach($novo1 as $sifra => $vrednost) {
		if (isset($postojeci1[$sifra])) {
			$sql1='UPDATE zalihe SET `kolicina`="'.$novo1[$sifra].'" WHERE `skladiste`="'.$skladiste1.'" AND `proizvod`="'.$sifra.'"';
			if ($vrednost < $postojeci1[$sifra]) {
				$razlika=$postojeci1[$sifra]-$vrednost;
				$sql2='INSERT INTO msklad (idmsklad, datum, skladiz, skladu, proizvod, razlika, uneo) VALUES ("'.$IDx.'","'.$datbase.'","'.$skladiste1.'","'.$skladiste2.'","'.$sifra.'","'.$razlika.'","'.$user.' - '.$dattime.'")';
				mysqli_query($mysqli,$sql2) or die;
			}
			elseif($vrednost > $postojeci1[$sifra]) {
				$razlika=$vrednost-$postojeci1[$sifra];
				$sql2='INSERT INTO msklad (idmsklad, datum, skladiz, skladu, proizvod, razlika, uneo) VALUES ("'.$IDx.'","'.$datbase.'","'.$skladiste2.'","'.$skladiste1.'","'.$sifra.'","'.$razlika.'","'.$user.' - '.$dattime.'")';
				mysqli_query($mysqli,$sql2) or die;
			}
			
		}
		else {
			$sql1='INSERT INTO zalihe (skladiste, proizvod, kolicina, uneo) VALUES ("'.$skladiste1.'","'.$sifra.'","'.$novo1[$sifra].'","'.$user.' - '.$dattime.'")';
		}
		mysqli_query($mysqli,$sql1) or die;
	}
	foreach($novo2 as $sifra => $vrednost) {
		if (isset($postojeci2[$sifra])) {
			$sql='UPDATE zalihe SET `kolicina`="'.$novo2[$sifra].'" WHERE `skladiste`="'.$skladiste2.'" AND `proizvod`="'.$sifra.'"';
		}
		else {
			$sql='INSERT INTO zalihe (skladiste, proizvod, kolicina, uneo) VALUES ("'.$skladiste2.'","'.$sifra.'","'.$novo2[$sifra].'","'.$user.' - '.$dattime.'")';
		}
		mysqli_query($mysqli,$sql) or die;
	}
	
}

	$svaskladista=array();
	$sql='SELECT ID, naziv FROM skladista';
	$result=mysqli_query($mysqli,$sql) or die;
	while ($row=$result->fetch_assoc()) {
		foreach($row as $xx => $yy) {
			$$xx=$yy;
		}
		$svaskladista[$ID]=$naziv;
	}

?>
<html>
<head profile="http://www.w3.org/2005/20/profile">
<link rel="icon"
	  type="image/png"
	  href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title id="Timerhead">Međuskladišnice - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<link type='text/css' rel="stylesheet" href="js/jquery-ui.css" />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<style type="text/css">
#container {
	height:600px;
	margin:27px 10px 10px 532px;
	padding:5px;
	column-width:300px;
	-moz-column-width:300px;
	-webkit-column-width:300px;
	min-height:300px;
	font-size:12;
}
.contel {
	margin-top:3px;
	float:left;
	width:220px;
	height:15px;
	overflow:hidden;
}
.contc {
	margin:0px 3px 0 0;
	float:right;
}
.contd {
	float:right;
	margin:0;
	height:18px;
	font-size:12;
}
#sortable1, #sortable2 {
	list-style-type: none;
	margin: 0;
	padding: 2px;
	float: left;
	margin-right: 10px;
	min-height:300px;
	overflow:auto;
	width:466px;
}
#sortable1 li, #sortable2 li{
	margin: 2px;
	padding: 2px;
	font-size: 1em;
	height:18px;
}
.razlika {
	width:24px;
	float:right;
	text-align:right;
	margin-right:2px;
	font-weight:bold
}
</style>
<meta name="robots" content="noindex">
</head>
<body onload="izmena()">
<form id="forma" action="#" method="POST">
<?php include 'topbar.php'; ?>

<div style="width:200px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6">
</div>
	<div style="position:absolute;top:32px;left:5px;bottom:5px;width:190px">
		<input id="resetbtn" type="reset" value="Novo međuskladištenje" style="width:100%;height:20px;margin-bottom:2px;" />
		<div class="iur" style="margin-bottom:2px;">
			<div style="float:left;padding-top:4px;font-size:13">ID: </div>
			<input id="yid" type="text" name="IDx" readonly style="background:#ccc;width:170px;margin-left:3px" value="<?php
$sql="SELECT `idmsklad` FROM msklad ORDER BY `idmsklad` DESC LIMIT 1";
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
if (isset($row['idmsklad'])) {
$idmsklad=$row['idmsklad']+1;
echo $idmsklad;
}
else echo '1';

		?>"/>
			<div style="clear:both;"></div>
		</div>
		<input id="unosbtn" type="submit" value="Zapamti novo stanje" style="width:100%;height:20px" />
		<div style="width:100%;border-bottom:1px solid #000;margin:2px 0"></div>
		<div style="position:absolute;top:70px;left:0px;width:190px;bottom:0px;overflow-y:auto;">
			<div style="font-size:12;overflow:auto">
<?php
$sql="SELECT idmsklad, datum FROM msklad GROUP BY `idmsklad` ORDER BY `idmsklad` DESC";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$datum=date('d.m.Y.',strtotime($datum));
	echo '<div>'.$idmsklad.' - '.$datum.'</div>';
	}
?>
			</div>
		</div>
	</div>
<div id="bgn1" style="width:480px;top:27px;left:210px;position:absolute;bottom:0;background:#fff;opacity:0.6">
</div>
<div id="bgn2" style="width:480px;top:27px;left:700px;position:absolute;bottom:0;background:#fff;opacity:0.6">
</div>
<div class="wrap" style="position:absolute;top:32px;left:215px;width:470px;overflow:auto" onmouseup="sorter()" onmouseout="sorter()">
<center><b>Skladište: </b>
		<select id="yskladiste1" name="skladiste1" style="margin-bottom:2px;" onchange="izmena()" >
<?php
$sql="SELECT `ID`,`naziv` FROM skladista ORDER BY `naziv` ASC";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	echo '<option value="'.$ID.'">'.$naziv.'</option>';

	}
?>
		</select>
</center>
	<ul id="sortable1" class="connectedSortable">
	</ul>
</div>
<div class="wrap" style="position:absolute;top:32px;left:700px;width:470px;overflow:auto" onmouseup="sorter()" onmouseout="sorter()">
<center><b>Skladište: </b>
		<select id="yskladiste2" name="skladiste2" style="margin-bottom:2px;" onchange="izmena()" >
<?php
$sql="SELECT `ID`,`naziv` FROM skladista ORDER BY `naziv` ASC";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	echo '<option value="'.$ID.'">'.$naziv.'</option>';

	}
?>
		</select>
</center>
	<ul id="sortable2" class="connectedSortable" >
	</ul>
</div>
	<input type="hidden" name="sorterklik1" id="sorterklik1" />
	<input type="hidden" name="sorterklik2" id="sorterklik2" />
<script type="text/javascript">
var viewportheight;
 if (typeof window.innerHeight != 'undefined')
 {
      viewportheight = window.innerHeight
 }
 else if (typeof document.documentElement != 'undefined'
     && typeof document.documentElement.clientHeight !=
     'undefined' && document.documentElement.clientHeight != 0)
 {
       viewportheight = document.documentElement.clientHeight
 }
 else
 {
       viewportheight = document.getElementsByTagName('body')[0].clientHeight
 }
function izmena()
	{
		var posebno1 = document.getElementById("yskladiste1").value;
		var posebno2 = document.getElementById("yskladiste2").value;
		document.getElementById("sortable1").innerHTML="";
		document.getElementById("sortable2").innerHTML="";
		$.getJSON('ajax/mskladi.php', {posebno1: posebno1, posebno2: posebno2}, function(data) {
			$('#sortable1').html(data.ysort1);
			$('#sortable2').html(data.ysort2);
		});
	}
$(function()
	{
		$( "#sortable1, #sortable2" ).sortable({
			connectWith: ".connectedSortable"
		});
	});
function sorter()
	{
		var sortedIDs1 = $( "#sortable1" ).sortable( "toArray" );
		document.getElementById("sorterklik1").value=sortedIDs1;
		var sortedIDs2 = $( "#sortable2" ).sortable( "toArray" );
		document.getElementById("sorterklik2").value=sortedIDs2;
	}
function slajder(sifra,smer,ukupno,staro) {
	var la = document.getElementById("rangea"+sifra);
	var lb = document.getElementById("elementa"+sifra);
	var lr = document.getElementById("razlikaa"+sifra);
	var da = document.getElementById("rangeb"+sifra);
	var db = document.getElementById("elementb"+sifra);
	var dr = document.getElementById("razlikab"+sifra);
	var razlikaa;
	var razlikab
	switch(smer)
	{
	case 1:
		lb.value=la.value;
		da.value=ukupno-la.value;
		db.value=da.value;
		if (lb.value!=staro) {
			razlikaa=lb.value-staro;
			razlikab=razlikaa*(-1);
			if (razlikaa>0) razlikaa="+"+razlikaa;
			else razlikab="+"+razlikab;
			lr.innerHTML=razlikaa;
			dr.innerHTML=razlikab;
		}
		else {
			lr.innerHTML="";
			dr.innerHTML="";
		}
		break;
	case 2:
		la.value=lb.value;
		da.value=ukupno-lb.value;
		db.value=da.value;
		if (lb.value!=staro) {
			razlikaa=lb.value-staro;
			razlikab=razlikaa*(-1);
			if (razlikaa>0) razlikaa="+"+razlikaa;
			else razlikab="+"+razlikab;
			lr.innerHTML=razlikaa;
			dr.innerHTML=razlikab;
		}
		else {
			lr.innerHTML="";
			dr.innerHTML="";
		}
		break;
	case 3:
		db.value=da.value;
		la.value=ukupno-da.value;
		lb.value=la.value;
		if (db.value!=staro) {
			razlikab=db.value-staro;
			razlikaa=razlikab*(-1);
			if (razlikab>0) razlikab="+"+razlikab;
			else razlikaa="+"+razlikaa;
			dr.innerHTML=razlikab;
			lr.innerHTML=razlikaa;
		}
		else {
			dr.innerHTML="";
			lr.innerHTML="";
		}
		break;
	case 4:
		da.value=db.value;
		la.value=ukupno-db.value;
		lb.value=la.value;
		if (db.value!=staro) {
			razlikab=db.value-staro;
			razlikaa=razlikab*(-1);
			if (razlikab>0) razlikab="+"+razlikab;
			else razlikaa="+"+razlikaa;
			dr.innerHTML=razlikab;
			lr.innerHTML=razlikaa;
		}
		else {
			dr.innerHTML="";
			lr.innerHTML="";
		}
		break;
	}
}

</script>
</form>
</body>
</html>