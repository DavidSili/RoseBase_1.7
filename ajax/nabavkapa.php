<?php
include '../config.php';
$posebno = isset($_GET["posebno"]) ? $_GET["posebno"] : 0;

$passhtml=array();

if ($posebno!=0) $sql="SELECT * FROM nabavka WHERE `ID`=$posebno";
else $sql="SELECT * FROM nabavka ORDER BY `ID` DESC LIMIT 1";
$result=mysqli_query($mysqli,$sql) or die;
while ($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
}
$sql='SELECT ime, prezime FROM partneri WHERE ID="'.$dobavljac.'"';
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
$dobavljac=$row['prezime'].' '.$row['ime'];

$sql='SELECT naziv FROM skladista WHERE ID="'.$dobavljac.'"';
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
$skladiste=$row['naziv'];

$datdostavnice=date('d.m.Y.',strtotime($datdostavnice));
$datprijemarobe=date('d.m.Y.',strtotime($datprijemarobe));

$passhtml['info']='<b>ID: </b>'.$ID.'<br/><b>Način carine: </b>';

if ($ncarine==1) $passhtml['info'].='Pančevo';
else $passhtml['info'].='Dimitrovgrad';

$passhtml['info'].='<br/><b>Datum dostavnice: </b>'.$datdostavnice.'<br/><b>Datum prijema robe: </b>'.$datprijemarobe.'<br/><b>Dobavljač: </b>'.$dobavljac.'<br/><b>Broj narudžbenice: </b>'.$brnarudzbenice.'<br/><b>Skladište: </b>'.$skladiste.'<br/><b>Kurs banke: </b>'.$kursbanka.'<br/><b>Srednji kurs: </b>'.$kurssred.'<br/><b>Kurs carine: </b>'.$kurscarine.'<br/><b>Troškovi transporta: </b>'.$transport.'<br/><b>Neptredviđeni troškovi: </b>'.$neptroskoviuk.'<br/><b>Ulazni PDV: </b>'.$ulaznipdv.'<br/><b>Ukupna nabavna vrednost: </b>'.$ukfnc.'<br/><b>Ukupna prodajna vrednost (bez PDVa): </b>'.$ukpcb.'<br/><b>Ukupna razlika: </b>'.$ukrazlika.'<br/><b>Ukupna marža: </b>'.$ukmarza.'%<br/><b>Da li je plaćeno: </b>'.$placeno;

$passhtml['sve']='<table style="font-size:12" border="1"><tr><th>ID u nabavci</th><th>Šifra</th><th>Količina</th><th>Nabavna cena u EUR</th><th>% trošk. trans.</th><th>Trošk. trans. po kom.</th><th>Carinska stopa</th><th>Nepr. trošk. po kom.</th><th>Finalna nabavna cena</th><th>Razlika</th><th>MP. cena bez PDVa</th><th>Marža</th><th>PDV</th><th>MP cena sa PDVom</th></tr>';

$sql='SELECT * FROM nabavkaitems WHERE nabavka="'.$ID.'" ORDER BY idunabavci ASC';
$result=mysqli_query($mysqli,$sql) or die;
while ($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$passhtml['sve'].='<tr><td>'.$idunabavci.'</td><td>'.$proizvod.'</td><td>'.$kolicina.'</td><td>'.$cenaueur.'</td><td>'.$transportpr.'%</td><td>'.$transportiznos.'</td><td>'.$cstopa.'%</td><td>'.$neptroskovi.'</td><td>'.$nabcena.'</td><td>'.$razlika.'</td><td>'.$mpbezpdv.'</td><td>'.$marza.'</td><td>'.$pdv.'</td><td>'.$mpsapdv.'</td></tr>';
}
$passhtml['sve'].='</table>';
echo json_encode($passhtml);
?>