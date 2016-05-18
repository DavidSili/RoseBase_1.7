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
	
	$idpopisa=$IDx;

	$dattime=date('G:i:s j.n.Y.');
	$datbase=date('Y-m-d');
	$sorterklikx=explode(',',$sorterklik);
	
	$novo=array();
	foreach($sorterklikx as $zz) {
		$novo[$zz]=${'element'.$zz};
	}
	
	$postojeci=array();
	$sql='SELECT * FROM zalihe WHERE skladiste = "'.$skladiste.'"';
	$result=mysqli_query($mysqli,$sql) or die;
	while ($row=$result->fetch_assoc()) {
		foreach($row as $xx => $yy) {
			$$xx=$yy;
		}
		
		if ($kolicina>0) {
			if (isset($novo[$proizvod])) {
				$sql='UPDATE zalihe SET `kolicina`="'.$novo[$proizvod].'" WHERE `proizvod`="'.$proizvod.'" AND `skladiste`="'.$skladiste.'"';
				$sql2='INSERT INTO popis (idpopisa, datum, proizvod, skladiste, kolknjiz, kolpopis, menjali) VALUES ("'.$idpopisa.'","'.$datbase.'","'.$proizvod.'","'.$skladiste.'","'.$kolicina.'","'.$novo[$proizvod].'","'.$user.' - '.$dattime.'")';
				mysqli_query($mysqli,$sql) or die;
				mysqli_query($mysqli,$sql2) or die;
			}
			else {
				$sql='UPDATE zalihe SET `kolicina`="0" WHERE `proizvod`="'.$proizvod.'" AND `skladiste`="'.$skladiste.'"';
				$sql2='INSERT INTO popis (idpopisa, datum, proizvod, skladiste, kolknjiz, kolpopis, menjali) VALUES ("'.$idpopisa.'","'.$datbase.'","'.$proizvod.'","'.$skladiste.'","'.$kolicina.'","0","'.$user.' - '.$dattime.'")';
				mysqli_query($mysqli,$sql) or die;
				mysqli_query($mysqli,$sql2) or die;
			}
		}
		else {
			if (isset($novo[$proizvod]) AND $novo[$proizvod]>0) {
				$sql='UPDATE zalihe SET `kolicina`="'.$novo[$proizvod].'" WHERE `proizvod`="'.$proizvod.'" AND `skladiste`="'.$skladiste.'"';
				$sql2='INSERT INTO popis (idpopisa, datum, proizvod, skladiste, kolknjiz, kolpopis, menjali) VALUES ("'.$idpopisa.'","'.$datbase.'","'.$proizvod.'","'.$skladiste.'","'.$kolicina.'","'.$novo[$proizvod].'","'.$user.' - '.$dattime.'")';
				mysqli_query($mysqli,$sql) or die;
				mysqli_query($mysqli,$sql2) or die;
			}
		}
		$postojeci[]=$proizvod;
	}

	foreach($novo as $gg=>$hh) {
		if (in_array($gg, $postojeci)==false) {
			$sql='INSERT INTO zalihe (skladiste, proizvod, kolicina, uneo) VALUES ("'.$skladiste.'","'.$gg.'","'.$hh.'","'.$user.' - '.$dattime.'")';
			$sql2='INSERT INTO popis (idpopisa, datum, proizvod, skladiste, kolknjiz, kolpopis, uneo) VALUES ("'.$idpopisa.'","'.$datbase.'","'.$gg.'","'.$skladiste.'","0","'.$hh.'","'.$user.' - '.$dattime.'")';
			mysqli_query($mysqli,$sql) or die;
			mysqli_query($mysqli,$sql2) or die;
		}
	}
	$IDx="";
	
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
<title id="Timerhead">Popis robe - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<link type='text/css' rel="stylesheet" href="js/jquery-ui.css" />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<style type="text/css">
#container {
	position:absolute;
	top:30px;
	bottom:0px;
	left:536px;
	width:415px;
	padding:0 5px 5px 5px;
	font-size:12;
	overflow:auto;
}
.contel {
	margin-top:3px;
	float:left;
	width:270px;
	height:15px;
	overflow:hidden;
}
.contc {
	margin:3px 3px 0 0;
	float:right;
}
.contd {
	float:right;
	margin:0;
	height:18px;
	font-size:12;
}
.contkas {
	width:30px;
	text-align:right;
	float:left;
	margin:3px 3px 0 0;
}
#sortable1, #sortable2 {
	list-style-type: none;
	margin: 0;
	padding: 2px;
	float: left;
	margin-right: 10px;
	min-height:300px;
	overflow:auto;
	width:316px;
}
#sortable1 li, #sortable2 li{
	margin: 2px;
	padding: 2px;
	font-size: 1em;
	height:18px;
}
#sortable1 li input{
	display:none;
}
#sortable1 li .contc{
	display:none;
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
		<input id="resetbtn" type="reset" value="Nov popis" style="width:100%;height:20px;margin-bottom:2px;" />
		<div class="iur" style="margin-bottom:2px;">
			<div style="float:left;padding-top:4px;font-size:13">ID: </div>
			<input id="yid" type="text" name="IDx" readonly style="background:#ccc;width:170px;margin-left:3px" value="<?php
$sql="SELECT `idpopisa` FROM popis ORDER BY `idpopisa` DESC LIMIT 1";
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
if (isset($row['idpopisa'])) {
$idpopisa=$row['idpopisa']+1;
echo $idpopisa;
}
else echo '1';

		?>"/>
			<div style="clear:both;"></div>
		</div>
		<select id="yskladiste" name="skladiste" style="width:190px;margin-bottom:2px;" onchange="izmena()" >
<?php
$sql="SELECT `ID`,`naziv` FROM skladista ORDER BY `ID` ASC";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	echo '<option value="'.$ID.'">'.$naziv.'</option>';

	}
?>
		</select>
		<input id="unosbtn" type="submit" value="Zapamti novo stanje" style="width:100%;height:20px" />
		<div style="width:100%;border-bottom:1px solid #000;margin:2px 0"></div>
		<div style="position:absolute;top:90px;left:0px;width:190px;bottom:0px;overflow-y:auto;">
			<div style="font-size:12px;overflow:auto">
<?php
$sql="SELECT `idpopisa`,`datum`, `skladiste` FROM popis GROUP BY `idpopisa` ORDER BY `idpopisa` DESC";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$datum=date('d.m.Y.',strtotime($datum));
	echo '<div>'.$idpopisa.' - '.$datum.' '.$svaskladista[$skladiste].'</div>';
	}
?>
			</div>
		</div>
	</div>
<div style="width:330px;top:27px;left:210px;position:absolute;bottom:0;background:#fff;opacity:0.6">
</div>
<div style="width:400px;top:27px;left:545px;position:absolute;height:22px;background:#fff;opacity:0.6;z-index:-5">
</div>
<div class="wrap" style="position:absolute;top:30px;left:210px;width:325px;padding-right:5px;bottom:0;overflow-x:hidden;overflow-y:auto;" onmouseup="sorter()" onmouseout="sorter()">
	<div style="text-align:center;font-style:oblique;font-weight:bold">Postojeći proizvodi:</div>
	<ul id="sortable1" class="connectedSortable">
	</ul>
</div>
<div id="container" onmouseup="sorter()" onmouseout="sorter()">
	<div style="text-align:center;font-style:oblique;font-weight:bold">Na lageru:</div>
	<ul id="sortable2" class="connectedSortable" style="width:400px">
	</ul>
</div>
	<input type="hidden" name="sorterklik" id="sorterklik" />
<script type="text/javascript">
function izmena()
	{
		var posebno = document.getElementById("yskladiste").value;
		$.getJSON('ajax/popisi.php', {posebno: posebno}, function(data) {
			$('#sortable1').html(data.ysortostali);
			$('#sortable2').html(data.ysorttu);
		});
	}
$(function()
	{
		$( "#sortable1, #sortable2" ).sortable({
			connectWith: ".connectedSortable"
		}).disableSelection();
	});
function sorter()
	{
		var sortedIDs = $( "#sortable2" ).sortable( "toArray" );
		document.getElementById("sorterklik").value=sortedIDs;
	}

</script>
</form>
</body>
</html>