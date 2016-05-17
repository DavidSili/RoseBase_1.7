<?php
include '../config.php';
$IDx = isset($_GET["ID"]) ? $_GET["ID"] : 0;

$passhtml=array();

$sql="SELECT * FROM prodaja WHERE `ID`=$IDx";
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
foreach($row as $xx => $yy) {
	$$xx=$yy;
}

$sql='SELECT SUM(kolicina) kontkol FROM prodajaitems WHERE prodaja="'.$IDx.'"';
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
$kontkol=$row['kontkol'];

$datprometa=date('d.m.Y.',strtotime($datprometa));

$zauplatu=number_format($zauplatu, 2, ',', '.');

$passhtml['gore']='<div style="float:left;min-width:200px;max-width:300px;height:120px;padding:5px 10px 0 0"><b>Datum: </b>'.$datprometa.'<br><b>Ukupno predmeta: </b>'.$kontkol.'<br><b>Dnevni izveštaj: </b>'.$bifr.'<br><b>Ukupni pazar: </b>'.$zauplatu.'</div>';

$passhtml['ostalo']='<table style="font-size:12" border="1"><thead><th>Br.</th><th>Šifra kase</th><th>Šifra proizvoda</th><th>Proizvod</th><th>Kol.</th><th>MPC</th></thead>';

$sql='SELECT prodajaitems.iduprodaji iduprodaji, prodajaitems.proizvod proizvod, prodajaitems.kolicina kolicina, proizvodi.naziv naziv, proizvodi.pcena pcena, proizvodi.sifrakasa sifkas FROM prodajaitems LEFT JOIN proizvodi ON prodajaitems.proizvod = proizvodi.sifra WHERE prodajaitems.prodaja="'.$IDx.'" ORDER BY prodajaitems.iduprodaji ASC';
$result=mysqli_query($mysqli,$sql) or die;
while ($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$pcena=number_format($pcena, 2, ',', '.');
	$passhtml['ostalo'].='<tr><td style="text-align:center">'.$iduprodaji.'</td><td style="text-align:right">'.$sifkas.'</td><td style="text-align:center">'.$proizvod.'</td><td style="text-align:left">'.$naziv.'</td><td style="text-align:center">'.$kolicina.'</td><td style="text-align:right">'.$pcena.' RSD</td></tr>';
}

$passhtml['ostalo'].='</table>';
echo json_encode($passhtml);
?>