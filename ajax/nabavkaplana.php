<?php
include '../config.php';
$izbor = isset($_GET["izbor"]) ? $_GET["izbor"] : 0;
$period = isset($_GET["period"]) ? $_GET["period"] : 0;

$passhtml=array();

$sifre=array();
$tabela=array();
$sql='SELECT sifra, sifrakasa, naziv FROM `proizvodi` ORDER BY sifrakasa ASC';
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
while ($row=$result->fetch_assoc()) {
	$tabela[$row['sifra']]['sifrakasa']=$row['sifrakasa'];
	$tabela[$row['sifra']]['naziv']=$row['naziv'];
	array_push($sifre,$row['sifra']);
}

$sql='SELECT proizvod, kolicina FROM `zalihe` WHERE skladiste="2"';
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
while ($row=$result->fetch_assoc()) {
	$tabela[$row['proizvod']]['nastanju']=$row['kolicina'];
}

/* last month */
$sql='SELECT pi.proizvod sifra,
		  SUM(pi.kolicina) ukupno
		FROM `prodajaitems` pi
		LEFT JOIN prodaja pr
			ON pi.prodaja = pr.ID
		WHERE pr.datprometa > DATE_SUB(NOW(), INTERVAL 1 MONTH)
		GROUP BY pi.proizvod
		ORDER BY pi.proizvod';
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
while ($row=$result->fetch_assoc()) {
	if (isset($row['ukupno']))  $tabela[$row['sifra']]['mesec1']=$row['ukupno'];
	else $tabela[$row['sifra']]['mesec1']=0;
}

/* last two months - last month */
$sql='SELECT pi.proizvod sifra,
		  SUM(pi.kolicina) ukupno
		FROM `prodajaitems` pi
		LEFT JOIN prodaja pr
			ON pi.prodaja = pr.ID
		WHERE pr.datprometa > DATE_SUB(NOW(), INTERVAL 2 MONTH)
			AND pr.datprometa <= DATE_SUB(NOW(), INTERVAL 1 MONTH)
		GROUP BY pi.proizvod
		ORDER BY pi.proizvod';
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
while ($row=$result->fetch_assoc()) {
	$tabela[$row['sifra']]['mesec2']=$row['ukupno'];
}

/* last three months - last two months */
$sql='SELECT pi.proizvod sifra,
		  SUM(pi.kolicina) ukupno
		FROM `prodajaitems` pi
		LEFT JOIN prodaja pr
			ON pi.prodaja = pr.ID
		WHERE pr.datprometa > DATE_SUB(NOW(), INTERVAL 3 MONTH)
			AND pr.datprometa <= DATE_SUB(NOW(), INTERVAL 2 MONTH)
		GROUP BY pi.proizvod
		ORDER BY pi.proizvod';
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
while ($row=$result->fetch_assoc()) {
	if (isset($row['ukupno']))  $tabela[$row['sifra']]['mesec3']=$row['ukupno'];
	else $tabela[$row['sifra']]['mesec3']=0;
}

/* last six months */
$sql='SELECT pi.proizvod sifra,
		  SUM(pi.kolicina) ukupno
		FROM `prodajaitems` pi
		LEFT JOIN prodaja pr
			ON pi.prodaja = pr.ID
		WHERE pr.datprometa > DATE_SUB(NOW(), INTERVAL 6 MONTH)
		GROUP BY pi.proizvod
		ORDER BY pi.proizvod';
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
while ($row=$result->fetch_assoc()) {
	if (isset($row['ukupno']))  $tabela[$row['sifra']]['meseci6']=$row['ukupno'];
	else $tabela[$row['sifra']]['meseci6']=0;
}

/* last 12 months */
$sql='SELECT pi.proizvod sifra,
		  SUM(pi.kolicina) ukupno
		FROM `prodajaitems` pi
		LEFT JOIN prodaja pr
			ON pi.prodaja = pr.ID
		WHERE pr.datprometa > DATE_SUB(NOW(), INTERVAL 12 MONTH)
		GROUP BY pi.proizvod
		ORDER BY pi.proizvod';
$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
while ($row=$result->fetch_assoc()) {
	if (isset($row['ukupno']))  $tabela[$row['sifra']]['meseci12']=$row['ukupno'];
	else $tabela[$row['sifra']]['meseci12']=0;
}

/* var_dump($tabela); */

if ($izbor==1) {
	$passhtml['sve'] = '<table style="font-size:12pt" border="1"><tr><th>Šifra</th><th>Šifra u kasi</th><th>Vrsta robe</th><th style="min-width:40px">< 12 m.</th><th>12 m. nab.</th><th>< 6 m.</th><th>6 m. nab.</th><th>< 3 m.</th><th>3 m. nab.</th><th>1 m.</th><th>1 m. nab.</th><th>Na stanju</th></tr>';

	foreach ($sifre as $x) {
		if (isset($tabela[$x]['mesec1'])==false) $tabela[$x]['mesec1']=0;
		if (isset($tabela[$x]['mesec2'])==false) $tabela[$x]['mesec2']=0;
		if (isset($tabela[$x]['mesec3'])==false) $tabela[$x]['mesec3']=0;
		if (isset($tabela[$x]['meseci6'])==false) $tabela[$x]['meseci6']=0;
		if (isset($tabela[$x]['meseci12'])==false) $tabela[$x]['meseci12']=0;
		if (isset($tabela[$x]['nastanju'])==false) $tabela[$x]['nastanju']=0;

		$p12m=round($tabela[$x]['meseci12']/12);
		$p12mm=$p12m*$period;
		$p12mn=$p12mm-$tabela[$x]['nastanju'];
		$p6m=round($tabela[$x]['meseci6']/6);
		$p6mm=$p6m*$period;
		$p6mn=$p6mm-$tabela[$x]['nastanju'];
		$p3m=round(($tabela[$x]['mesec3']+$tabela[$x]['mesec2']+$tabela[$x]['mesec1'])/3);
		$p3mm=$p3m*$period;
		$p3mn=$p3mm-$tabela[$x]['nastanju'];
		$p1m=$tabela[$x]['mesec1'];
		$p1mm=$p1m*$period;
		$p1mn=$p1mm-$tabela[$x]['nastanju'];

		$passhtml['sve'] .= '<tr><td>' . $x . '</td><td>' . $tabela[$x]['sifrakasa'] . '</td><td class="levo">' . $tabela[$x]['naziv'] . '</td><td>' . $p12m . '</td><td';
		if ($p12mn>0) $passhtml['sve'] .= ' class="debelo"';
		$passhtml['sve'] .= '>' . $p12mn . '</td><td>' . $p6m . '</td><td';
		if ($p6mn>0) $passhtml['sve'] .= ' class="debelo"';
		$passhtml['sve'] .= '>' . $p6mn . '</td><td>' . $p3m . '</td><td';
		if ($p3mn>0) $passhtml['sve'] .= ' class="debelo"';
		$passhtml['sve'] .= '>' . $p3mn . '</td><td>' . $p1m . '</td><td';
		if ($p1mn>0) $passhtml['sve'] .= ' class="debelo"';
		$passhtml['sve'] .= '>' . $p1mn . '</td><td>' . $tabela[$x]['nastanju'] . '</td></tr>';
	}

	$passhtml['sve'] .= '</table>';
}
else {
	$passhtml['sve'] = '<table style="font-size:12pt" border="1"><tr><th>Šifra</th><th>Šifra u kasi</th><th>Vrsta robe</th><th>< 3 m.</th><th>< 3 m. nab.</th><th>< 2 m.</th><th>2 m. nab.</th><th>Na stanju</th></tr>';

	foreach ($sifre as $x) {
		if (isset($tabela[$x]['mesec1'])==false) $tabela[$x]['mesec1']=0;
		if (isset($tabela[$x]['mesec2'])==false) $tabela[$x]['mesec2']=0;
		if (isset($tabela[$x]['mesec3'])==false) $tabela[$x]['mesec3']=0;
		if (isset($tabela[$x]['nastanju'])==false) $tabela[$x]['nastanju']=0;

		$p3m=round((($tabela[$x]['mesec3'])*0.2)+(($tabela[$x]['mesec2'])*0.3)+(($tabela[$x]['mesec1'])*0.5));
		$p3mm=$p3m*$period;
		$p3mn=$p3mm-$tabela[$x]['nastanju'];
		$p2m=round((($tabela[$x]['mesec2'])*0.3)+(($tabela[$x]['mesec1'])*0.7));
		$p2mm=$p2m*$period;
		$p2mn=$p2mm-$tabela[$x]['nastanju'];

		$passhtml['sve'] .= '<tr><td>' . $x . '</td><td>' . $tabela[$x]['sifrakasa'] . '</td><td class="levo">' . $tabela[$x]['naziv'] . '</td><td>' . $p3m . '</td><td';
		if ($p3mn>0) $passhtml['sve'] .= ' class="debelo"';
		$passhtml['sve'] .= '>' . $p3mn . '</td><td>' . $p2m . '</td><td';
		if ($p2mn>0) $passhtml['sve'] .= ' class="debelo"';
		$passhtml['sve'] .= '>' . $p2mn . '</td><td>' . $tabela[$x]['nastanju'] . '</td></tr>';
	}

	$passhtml['sve'] .= '</table>';
}
echo json_encode($passhtml);
?>