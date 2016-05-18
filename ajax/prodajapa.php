<?php
include '../config.php';
$godina = isset($_GET["godina"]) ? $_GET["godina"] : 0;
$mesec = isset($_GET["mesec"]) ? $_GET["mesec"] : 0;
$izborx = isset($_GET["izborx"]) ? $_GET["izborx"] : 0;
$sortx = isset($_GET["sortx"]) ? $_GET["sortx"] : 0;
$smerx = isset($_GET["smerx"]) ? $_GET["smerx"] : 0;
$periodx = isset($_GET["periodx"]) ? $_GET["periodx"] : 0;
$datod = isset($_GET["datod"]) ? $_GET["datod"] : 0;
$datdo = isset($_GET["datdo"]) ? $_GET["datdo"] : 0;

$passhtml=array();

switch ($periodx) {
	case 1:
		$sql='SELECT COUNT(ID) pazar FROM prodaja WHERE brracuna IS NULL';
		$result=mysqli_query($mysqli,$sql) or die;
		$row=$result->fetch_assoc();
		$pazara=$row['pazar'];

		$sql='SELECT COUNT(ID) fakt FROM prodaja WHERE brracuna IS NOT NULL AND brracuna <>""';
		$result=mysqli_query($mysqli,$sql) or die;
		$row=$result->fetch_assoc();
		$fakt=$row['fakt'];

		$sql='SELECT COUNT(ID) prof FROM prodaja WHERE brpracuna IS NOT NULL AND brracuna =""';
		$result=mysqli_query($mysqli,$sql) or die;
		$row=$result->fetch_assoc();
		$prof=$row['prof'];
		break;
	case 2:
	switch ($izborx) {
		case 1:
			$sql='SELECT COUNT(ID) pazar FROM prodaja WHERE YEAR(datprometa) = '.$godina.' AND MONTH(datprometa) = '.$mesec.' AND brracuna IS NULL';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$pazara=$row['pazar'];
			$fakt=0;
			$prof=0;
			break;
		case 2:
			$sql='SELECT COUNT(ID) fakt FROM prodaja WHERE YEAR(datprometa) = '.$godina.' AND MONTH(datprometa) = '.$mesec.' AND brracuna IS NOT NULL AND brracuna <>""';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$fakt=$row['fakt'];
			$pazara=0;
			$prof=0;
			break;
		case 3:
			$sql='SELECT COUNT(ID) prof FROM prodaja WHERE YEAR(datprometa) = '.$godina.' AND MONTH(datprometa) = '.$mesec.' AND brpracuna IS NOT NULL AND brracuna =""';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$prof=$row['prof'];
			$pazara=0;
			$fakt=0;
			break;
		case 4:
			$sql='SELECT COUNT(ID) pazar FROM prodaja WHERE YEAR(datprometa) = '.$godina.' AND MONTH(datprometa) = '.$mesec.' AND brracuna IS NULL';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$pazara=$row['pazar'];
			$sql='SELECT COUNT(ID) fakt FROM prodaja WHERE YEAR(datprometa) = '.$godina.' AND MONTH(datprometa) = '.$mesec.' AND brracuna IS NOT NULL AND brracuna <>""';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$fakt=$row['fakt'];
			$prof=0;
			break;
		case 5:
			$sql='SELECT COUNT(ID) pazar FROM prodaja WHERE YEAR(datprometa) = '.$godina.' AND MONTH(datprometa) = '.$mesec.' AND brracuna IS NULL';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$pazara=$row['pazar'];
			$sql='SELECT COUNT(ID) fakt FROM prodaja WHERE YEAR(datprometa) = '.$godina.' AND MONTH(datprometa) = '.$mesec.' AND brracuna IS NOT NULL AND brracuna <>""';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$fakt=$row['fakt'];
			$sql='SELECT COUNT(ID) prof FROM prodaja WHERE YEAR(datprometa) = '.$godina.' AND MONTH(datprometa) = '.$mesec.' AND brpracuna IS NOT NULL AND brracuna =""';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$prof=$row['prof'];
			break;
		}
		break;
	case 3:
		switch ($izborx) {
		case 1:
			$sql='SELECT COUNT(ID) pazar FROM prodaja WHERE datprometa BETWEEN "'.$datod.'" AND "'.$datdo.'" AND brracuna IS NULL';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$pazara=$row['pazar'];
			$fakt=0;
			$prof=0;
			break;
		case 2:
			$sql='SELECT COUNT(ID) fakt FROM prodaja WHERE datprometa BETWEEN "'.$datod.'" AND "'.$datdo.'" AND brracuna IS NOT NULL AND brracuna <>""';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$fakt=$row['fakt'];
			$pazara=0;
			$prof=0;
			break;
		case 3:
			$sql='SELECT COUNT(ID) prof FROM prodaja WHERE datprometa BETWEEN "'.$datod.'" AND "'.$datdo.'" AND brpracuna IS NOT NULL AND brracuna =""';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$prof=$row['prof'];
			$pazara=0;
			$fakt=0;
			break;
		case 4:
			$sql='SELECT COUNT(ID) pazar FROM prodaja WHERE datprometa BETWEEN "'.$datod.'" AND "'.$datdo.'" AND brracuna IS NULL';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$pazara=$row['pazar'];
			$sql='SELECT COUNT(ID) fakt FROM prodaja WHERE datprometa BETWEEN "'.$datod.'" AND "'.$datdo.'" AND brracuna IS NOT NULL AND brracuna <>""';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$fakt=$row['fakt'];
			$prof=0;
			break;
		case 5:
			$sql='SELECT COUNT(ID) pazar FROM prodaja WHERE datprometa BETWEEN "'.$datod.'" AND "'.$datdo.'" AND brracuna IS NULL';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$pazara=$row['pazar'];
			$sql='SELECT COUNT(ID) fakt FROM prodaja WHERE datprometa BETWEEN "'.$datod.'" AND "'.$datdo.'" AND brracuna IS NOT NULL AND brracuna <>""';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$fakt=$row['fakt'];
			$sql='SELECT COUNT(ID) prof FROM prodaja WHERE datprometa BETWEEN "'.$datod.'" AND "'.$datdo.'" AND brpracuna IS NOT NULL AND brracuna =""';
			$result=mysqli_query($mysqli,$sql) or die;
			$row=$result->fetch_assoc();
			$prof=$row['prof'];
			break;
		}
		break;
}
switch ($periodx) {
	case 1:
		$sql='SELECT SUM(popust) popust, SUM(zarada) zarada, SUM(bezpopusta) bezpopusta, SUM(bezpdva) bezpdva, SUM(iznospdv) iznospdv, SUM(zauplatu) zauplatu FROM prodaja';
		switch ($izborx) {
			case 1:
				$sql.=' WHERE brracuna IS NULL';
				break;
			case 2:
				$sql.=' WHERE brracuna IS NOT NULL AND brracuna <>""';
				break;
			case 3:
				$sql.=' WHERE brpracuna IS NOT NULL AND brracuna =""';
				break;
			case 4:
				$sql.=' AND (brracuna <>"" OR brracuna IS NULL)';
				break;
		}
		break;
	case 2:
		$sql='SELECT SUM(popust) popust, SUM(zarada) zarada, SUM(bezpopusta) bezpopusta, SUM(bezpdva) bezpdva, SUM(iznospdv) iznospdv, SUM(zauplatu) zauplatu FROM prodaja WHERE YEAR(datprometa) = '.$godina.' AND MONTH(datprometa) = '.$mesec;
		switch ($izborx) {
			case 1:
				$sql.=' AND brracuna IS NULL';
				break;
			case 2:
				$sql.=' AND brracuna IS NOT NULL AND brracuna <>""';
				break;
			case 3:
				$sql.=' AND brpracuna IS NOT NULL AND brracuna =""';
				break;
			case 4:
				$sql.=' AND (brracuna <>"" OR brracuna IS NULL)';
				break;
		}
		break;
	case 3:
		$sql='SELECT SUM(popust) popust, SUM(zarada) zarada, SUM(bezpopusta) bezpopusta, SUM(bezpdva) bezpdva, SUM(iznospdv) iznospdv, SUM(zauplatu) zauplatu FROM prodaja WHERE datprometa BETWEEN "'.$datod.'" AND "'.$datdo.'"';
		switch ($izborx) {
			case 1:
				$sql.=' AND brracuna IS NULL';
				break;
			case 2:
				$sql.=' AND brracuna IS NOT NULL AND brracuna <>""';
				break;
			case 3:
				$sql.=' AND brpracuna IS NOT NULL AND brracuna =""';
				break;
			case 4:
				$sql.=' AND (brracuna <>"" OR brracuna IS NULL)';
				break;
		}
		break;
}
$result=mysqli_query($mysqli,$sql) or die;
while ($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
}
$procenat=$zarada/$zauplatu*100;
$bezpdva=number_format($bezpdva, 2, '.', ',');
$bezpopusta=number_format($bezpopusta, 2, '.', ',');
$iznospdv=number_format($iznospdv, 2, '.', ',');
$zauplatu=number_format($zauplatu, 2, '.', ',');
$popust=number_format($popust, 2, '.', ',');
$zarada=number_format($zarada, 2, '.', ',');
$procenat=number_format($procenat, 2, '.', ',');
$passhtml['info']='<div style="width:115px;float:left;font-weight:bold">Br. faktura:<br/>Br. profaktura<br/>Br. dnevnih pazara:<br/>Bez PDVa:<br/>Bez popusta:</div><div style="width:80px;float:left;text-align:right;margin-right:10px;padding-right:10px;border-right: solid 1px #000">'.$fakt.'<br/>'.$prof.'<br/>'.$pazara.'<br/>'.$bezpdva.'<br/>'.$bezpopusta.'</div><div style="width:80px;float:left;font-weight:bold">Iznos PDVa:<br/>Uplaćeno:<br/>Popust:<br/>Zarada:<br/>Procenat:</div><div style="width:80px;float:left;text-align:right">'.$iznospdv.'<br/>'.$zauplatu.'<br/>'.$popust.'<br/>'.$zarada.'<br/>'.$procenat.' %</div>';

$passhtml['sve']='<table style="font-size:12" border="1"><tr><th>Šifra</th><th>Šifra u kasi</th><th>Vrsta robe</th><th>Količina</th></tr>';
switch ($periodx) {
	case 1:
		$sql='SELECT prodajaitems.proizvod sifra, proizvodi.naziv naziv, proizvodi.sifrakasa sifkas, SUM(prodajaitems.kolicina) kolicina FROM prodajaitems LEFT JOIN prodaja ON prodajaitems.prodaja = prodaja.ID LEFT JOIN proizvodi ON prodajaitems.proizvod = proizvodi.sifra';
		switch ($izborx) {
			case 1:
				$sql.=' WHERE brracuna IS NULL';
				break;
			case 2:
				$sql.=' WHERE brracuna IS NOT NULL AND brracuna <>""';
				break;
			case 3:
				$sql.=' WHERE brpracuna IS NOT NULL AND brracuna =""';
				break;
			case 4:
				$sql.=' AND (brracuna <>"" OR brracuna IS NULL)';
				break;
		}
		break;
	case 2:
		$sql='SELECT prodajaitems.proizvod sifra, proizvodi.naziv naziv, proizvodi.sifrakasa sifkas, SUM(prodajaitems.kolicina) kolicina FROM prodajaitems LEFT JOIN prodaja ON prodajaitems.prodaja = prodaja.ID LEFT JOIN proizvodi ON prodajaitems.proizvod = proizvodi.sifra WHERE YEAR(prodaja.datprometa) = '.$godina.' AND MONTH(prodaja.datprometa) = '.$mesec;
		switch ($izborx) {
			case 1:
				$sql.=' AND brracuna IS NULL';
				break;
			case 2:
				$sql.=' AND brracuna IS NOT NULL AND brracuna <>""';
				break;
			case 3:
				$sql.=' AND brpracuna IS NOT NULL AND brracuna =""';
				break;
			case 4:
				$sql.=' AND (brracuna <>"" OR brracuna IS NULL)';
				break;
		}
		break;
	case 3:
		$sql='SELECT prodajaitems.proizvod sifra, proizvodi.naziv naziv, proizvodi.sifrakasa sifkas, SUM(prodajaitems.kolicina) kolicina FROM prodajaitems LEFT JOIN prodaja ON prodajaitems.prodaja = prodaja.ID LEFT JOIN proizvodi ON prodajaitems.proizvod = proizvodi.sifra WHERE datprometa BETWEEN "'.$datod.'" AND "'.$datdo.'"';
		switch ($izborx) {
			case 1:
				$sql.=' AND brracuna IS NULL';
				break;
			case 2:
				$sql.=' AND brracuna IS NOT NULL AND brracuna <>""';
				break;
			case 3:
				$sql.=' AND brpracuna IS NOT NULL AND brracuna =""';
				break;
			case 4:
				$sql.=' AND (brracuna <>"" OR brracuna IS NULL)';
				break;
		}
		break;
}
$sql.= ' GROUP BY sifra ORDER BY ';
switch ($sortx) {
	case 1:
		$sql.='proizvodi.sifra ';
		break;
	case 2:
		$sql.='proizvodi.sifrakasa ';
		break;
	case 3:
		$sql.='proizvodi.naziv ';
		break;
	case 4:
		$sql.='SUM(prodajaitems.kolicina) ';
		break;
}
if($smerx==1) $sql.='ASC';
	else $sql.='DESC';

$result=mysqli_query($mysqli,$sql) or die;
while ($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$passhtml['sve'].='<tr><td>'.$sifra.'</td><td>'.$sifkas.'</td><td>'.$naziv.'</td><td>'.$kolicina.'</td></tr>';
}
$passhtml['sve'].='</table>';
echo json_encode($passhtml);
?>