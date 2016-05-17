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
<title id="Timerhead">Izveštaj kase za štampu - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<style type="text/css">
table {
	page-break-inside:auto
}
tr {
	page-break-inside:avoid;
	page-break-after:auto
}
thead {
	display:table-header-group
}
tfoot {
	display:table-footer-group
}
</style>
<meta name="robots" content="noindex">
</head>
<body>
<?php
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$sql="SELECT * FROM prodaja WHERE `ID`=$posebno";
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
foreach($row as $xx => $yy) {
	$$xx=$yy;
}

$sql='SELECT SUM(kolicina) kontkol FROM prodajaitems WHERE prodaja="'.$posebno.'"';
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
$kontkol=$row['kontkol'];

$datprometa=date('d.m.Y.',strtotime($datprometa));

$zauplatu=number_format($zauplatu, 2, ',', '.');

$poslednjikontkol=substr($kontkol, -1);
$poslednjibifr=substr($bifr, -1);

if ($kontkol==1 OR ($kontkol>20 AND $poslednjikontkol==1)) $gramkontkol="predmet";
else $gramkontkol="predmeta";

echo '<table style="font-size:12;border-top-width: .9090;margin-top: 20px;font-size:12pt" border="1"><caption style="font-size:16;font-weight:bold;padding-bottom:5px">Izveštaj kase za: '.$datprometa.' firme Biofresh doo, Pančevo</caption><thead><th>Br.</th><th>Šifra kase</th><th>Šifra proizvoda</th><th>Proizvod</th><th>Kol.</th><th style="width:80px">MPC</th><th>Rabat</th><th style="width:80px">Za uplatu</th></thead><tfoot><td colspan="8" style="text-align:right">Ukupno <b>'.$kontkol.'</b> '.$gramkontkol.', <b>'.$bifr.'.</b> dnevni izveštaj., Vrednost: <b>'.$zauplatu.'</b></td></tfoot><tbody>';

$sql='SELECT prodajaitems.iduprodaji iduprodaji, prodajaitems.proizvod proizvod, prodajaitems.kolicina kolicina, prodajaitems.rabat rabat, proizvodi.naziv naziv, proizvodi.pcena pcena, proizvodi.sifrakasa sifkas FROM prodajaitems LEFT JOIN proizvodi ON prodajaitems.proizvod = proizvodi.sifra WHERE prodajaitems.prodaja="'.$posebno.'" ORDER BY prodajaitems.iduprodaji ASC';
$result=mysqli_query($mysqli,$sql) or die;
while ($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$zauplatu=$pcena*$kolicina*(100-$rabat)/100;
	$pcena=number_format($pcena, 2, ',', '.');
	$zauplatu=number_format($zauplatu, 2, ',', '.');
	echo '<tr><td style="text-align:center">'.$iduprodaji.'</td><td style="text-align:right">'.$sifkas.'</td><td style="text-align:center">'.$proizvod.'</td><td style="text-align:left">'.$naziv.'</td><td style="text-align:center">'.$kolicina.'</td><td style="text-align:right">'.$pcena.' RSD</td><td style="text-align:right">'.$rabat.' %</td><td style="text-align:right">'.$zauplatu.' RSD</td></tr>';
}
echo '</tbody></table>';
?>
</body>
</html>