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
<title id="Timerhead">Izveštaji međuskladišnica - Land of Roses doo: baza podataka</title>
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
include 'config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;
$redova = isset($_GET["redova"]) ? $_GET["redova"] : 0;
$redovi=explode(',',$redova);
$brstranica=count($redovi);

$sql="SELECT * FROM msklad WHERE `idmsklad`=$posebno LIMIT 1";
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
foreach($row as $xx => $yy) {
	$$xx=$yy;
}

$sql="SELECT SUM(razlika) cnt FROM msklad WHERE idmsklad=$posebno AND skladiz > skladu";
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
$cnt1=$row['cnt'];

$sql="SELECT SUM(razlika) cnt FROM msklad WHERE idmsklad=$posebno AND skladiz < skladu";
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
$cnt2=$row['cnt'];

$ok="0";
if ($cnt1 == 0 OR $cnt2 == 0) $ok="1";

else {
	if ($cnt1>$cnt2) $sql="SELECT * FROM msklad WHERE `idmsklad`=$posebno AND skladiz > skladu LIMIT 1";
	else $sql="SELECT * FROM msklad WHERE `idmsklad`=$posebno AND skladiz < skladu LIMIT 1";
	$result=mysql_query($sql) or die;
	$row=mysql_fetch_assoc($result);
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
}

$sql='SELECT naziv nskladiz FROM skladista WHERE ID="'.$skladiz.'"';
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
$nskladiz=$row['nskladiz'];

$sql='SELECT naziv nskladu FROM skladista WHERE ID="'.$skladu.'"';
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
$nskladu=$row['nskladu'];
$levibox='Međuskladišnica od '.$nskladiz.' prema '.$nskladu;
$sql='SELECT SUM(razlika) kontkol FROM msklad WHERE idmsklad="'.$posebno.'"';
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
$kontkol=$row['kontkol'];

$datum=date('d.m.Y.',strtotime($datum));

$desnibox='<b>Međuskladišnica broj: '.$idmsklad.'</b><br/>Datum: '.$datum.'<br/>Kontrolna količina: '.$kontkol;

if ($brstranica==1) {
$sve='<table style="font-size:12" border="1"><tr style="background:#ddd"><th>Br.</th><th>Šifra proizvoda</th><th>Vrsta robe - usluga</th><th>Količina</th>';
if ($ok=="0") $sve.='<th>Transakcija</th>';
$sve.'</tr>';
$rb="1";
$sql='SELECT msklad.skladiz skladizx, msklad.proizvod proizvod, proizvodi.naziv naziv, msklad.razlika kolicina FROM msklad LEFT JOIN proizvodi ON msklad.proizvod = proizvodi.sifra WHERE idmsklad="'.$posebno.'" ORDER BY msklad.ID ASC';
$result=mysql_query($sql) or die;
while ($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$sve.='<tr><td style="text-align:center">'.$rb.'</td><td style="text-align:center">'.$proizvod.'</td><td style="text-align:left;max-width:300px">'.$naziv.'</td><td style="text-align:center">'.$kolicina.'</td>';
	if ($ok=="0") {
		if ($skladizx==$skladiz) $sve.='<td style="text-align:center">Predaja</td>';
			else $sve.='<td style="text-align:center">Uzimanje</td>';
	}
	$sve.='</tr>';
	$rb++;
}
$sve.='</table>';
}
else {
	$staro=0;
	foreach($redovi as $xx => $yy) {
		$stranicabr=$xx+1;
		$sve[$stranicabr]='<table style="font-size:12" border="1"><tr style="background:#ddd"><th>Br.</th><th>Šifra proizvoda</th><th>Vrsta robe - usluga</th><th>Količina</th>';
		if ($ok=="0") $sve[$stranicabr].'<th>Transakcija</th>';
		$sve[$stranicabr].'</tr>';

		if ($stranicabr==1) {
			$sql='SELECT msklad.skladiz skladizx, msklad.proizvod proizvod, proizvodi.naziv naziv, msklad.razlika kolicina FROM msklad LEFT JOIN proizvodi ON msklad.proizvod = proizvodi.sifra WHERE idmsklad="'.$posebno.'" ORDER BY msklad.ID ASC LIMIT '.$yy;
		$prosli=$xx;
		}
		else {
		$prosli=$xx;
		$sql='SELECT msklad.proizvod proizvod, proizvodi.naziv naziv, msklad.razlika kolicina FROM msklad LEFT JOIN proizvodi ON msklad.proizvod = proizvodi.sifra WHERE idmsklad="'.$posebno.'" ORDER BY msklad.ID ASC LIMIT '.$staro.','.$yy;
		}
		$result=mysql_query($sql) or die;
		while ($row=mysql_fetch_assoc($result)) {
			foreach($row as $xx => $yy) {
				$$xx=$yy;
			}
			$sve[$stranicabr].='<tr><td style="text-align:center">'.$rb.'</td><td style="text-align:center">'.$proizvod.'</td><td style="text-align:left;max-width:300px">'.$naziv.'</td><td style="text-align:center">'.$kolicina.'</td>';
			if ($ok=="0") {
				if ($skladizx==$skladiz) $sve[$stranicabr].='<td style="text-align:center">Predaja</td>';
					else $sve[$stranicabr].='<td style="text-align:center">Uzimanje</td>';
			}
			$sve[$stranicabr].='</tr>';
		}
		
		$sve[$stranicabr].='</table>';
		$staro=$staro+$redovi[$prosli];
		
	}
	$desnibox='<b>Međuskladišnica broj: '.$idmsklad.'</b><br/>Datum: '.$datum.'<br/>Kontrolna količina: '.$konkol;

}
foreach($redovi as $xx => $yy) {

	if ($brstranica==1) {
		echo '<div id="header">
	<div style="overflow:hidden;margin-top:0.5cm;font-size:14">
		<div style="float:left;text-align:center;margin-left:2cm">'.$levibox.'</div>
		<div style="float:right;text-align:center;margin-right:2cm">'.$desnibox.'</div>
	</div>
</div>
<div id="pagewrap" style="height:26.5cm">
	<div id="desniwrap" style="overflow:hidden">
		<div id="tabwrap" style="margin-top:260px;height:11cm">'.$sve.'</div>
	</div>
	<div id="footer" style="position:absolute;bottom:30px;left:0;right:0">
	<div style="border-top:1px solid #000;padding-top:1px;width:100%;text-align:center">Tel: +381 60 8080 613; e-mail: office@bugarska-ruza.rs; web: www.bugarska-ruza.rs</div>
	</div>
</div>';
	}
	else {
		$stranicabr=$xx+1;
		if ($stranicabr==1) {
			echo '<div id="header">
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
			</div>
			<div id="footer" style="position:absolute;bottom:0;left:0;right:0">
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