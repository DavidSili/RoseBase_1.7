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

	if (isset($_POST['del'])) {
		$del=$_POST['del'];
		$sql='DELETE FROM proizvodi WHERE ID="'.$del.'"';
		mysql_query($sql);
	}
elseif(isset($_POST) && !empty($_POST)) {
	foreach($_POST as $xx => $yy) {
		$$xx=$yy;
	}
	$svi2=explode(',',$svi);

	$grupa="";
	foreach($svi2 as $xx) {
		if (isset(${'sub'.$xx})) $grupa.=$xx.',';
	}
	$grupa=substr($grupa, 0, -1);
	$grupa2=explode(',',$grupa);
	$svip="";
	$sql='SELECT ID,nadgrupa FROM gproizvoda ORDER BY nadgrupa, ID';
	$result=mysql_query($sql) or die($sql.': '.mysql_error());
	while($row=mysql_fetch_assoc($result)) {
		$ID=$row['ID'];
		$nadgrupa=$row['nadgrupa'];
		if (in_array($ID,$grupa2) AND empty($svip) == false) {
			if (strpos($svip,$nadgrupa)=== false) {
				$svip.=$nadgrupa.',';
			}
		}
		elseif (in_array($ID,$grupa2)) {
		$svip.=$nadgrupa.',';
		}
	}

	$nadgrupa=substr($svip, 0, -1);
		

	if (isset($naziv)) $naziv=mysql_real_escape_string($naziv);
	if (isset($link)) $link=mysql_real_escape_string($link);
	$sql='SELECT ID FROM proizvodi ORDER BY ID DESC LIMIT 1';
	$result=mysql_query($sql) or die($sql.': '.mysql_error());
	$row=mysql_fetch_assoc($result);
	$lastID=$row['ID'];
	$result = mysql_query("SHOW TABLE STATUS LIKE 'proizvodi'");
	$data = mysql_fetch_assoc($result);
	$nextID = $data['Auto_increment'];
	
	$dattime=date('G:i:s j.n.Y.');

	if (isset($lastID)==false OR $IDx>$lastID) {
		$sql='INSERT INTO proizvodi (sifra, sifrakasa, barcode, naziv, link, namgrupa, nadgrupa, grupa, brend, dobavljac, zapremina, tezinaneto, tezinabruto, kolpak, minzal, cartar, pdv, ncena, pcena, uneo) VALUES ("'.$sifra.'","'.$sifkas.'","'.$barcode.'","'.$naziv.'","'.$link.'","'.$namgrupa.'","'.$nadgrupa.'","'.$grupa.'","'.$brend.'","'.$dobavljac.'","'.$zapremina.'","'.$tezinaneto.'","'.$tezinabruto.'","'.$kolpak.'","'.$minzal.'","'.$cartar.'","'.$pdv.'","'.$ncena.'","'.$pcenas.'","'.$user.' - '.$dattime.'")';
		mysql_query($sql) or die($sql.': '.mysql_error());
	}
	else {
		$sql='SELECT menjali FROM proizvodi WHERE ID="'.$IDx.'"';
		$result=mysql_query($sql);
		$row=mysql_fetch_assoc($result);
		$xmenjali=$row['menjali'];
		$cid=$IDx;
		
		$sql='UPDATE proizvodi SET sifra="'.$sifra.'", sifrakasa="'.$sifkas.'", barcode="'.$barcode.'", naziv="'.$naziv.'", link="'.$link.'", namgrupa="'.$namgrupa.'", nadgrupa="'.$nadgrupa.'", grupa="'.$grupa.'", brend="'.$brend.'", dobavljac="'.$dobavljac.'", zapremina="'.$zapremina.'", tezinaneto="'.$tezinaneto.'", tezinabruto="'.$tezinabruto.'", kolpak="'.$kolpak.'", minzal="'.$minzal.'", cartar="'.$cartar.'", pdv="'.$pdv.'", ncena="'.$ncena.'", pcena="'.$pcenas.'", menjali="'.$xmenjali.'; '.$user.' - '.$dattime.'" WHERE ID="'.$IDx.'"';
		mysql_query($sql) or die($sql.': '.mysql_error());
	}
		
}

?>
<html>
<head profile="http://www.w3.org/2005/20/profile">
<link rel="icon"
	  type="image/png"
	  href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title id="Timerhead">Proizvodi - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<style type="text/css">
#dkolona {
	text-align:left;
}
#dkolona input {
	float:left;
	width:12px;
	height:12px;
}
#dkolona div {
	min-height:20px;
}
.ckb {
	margin-left:15px
}
#blacklink {
	position:absolute;
	left:0;
	bottom:0;
	top:80px;
	width:190px;
	overflow-y:auto;
	overflow-x:hidden;
	font-size:12;
	padding-right:5px;
}
</style>
<meta name="robots" content="noindex">
</head>
<body<?php
if (isset($del)) echo ' onload="novo()"';
elseif (isset($cid)) echo ' onload="izmena('.$IDx.')"';
?>>
<form id="forma" action="#" method="POST">
<?php include 'topbar.php'; ?>

<div style="width:200px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6">
</div>
	<div style="position:absolute;top:32px;left:5px;bottom:5px;width:190px">
		<input id="unosbtn" type="submit" value="Unesi" style="width:100%;height:20px" />
		<input type="button" value="Novi proizvod" style="width:100%;margin-top:5px" onclick="novo()"/>
		<input type="button" value="Obriši" style="width:100%" onclick="delform()"/>
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px"></div>
		<div id="blacklink" >
<?php
$sql="SELECT `ID`,`naziv` FROM brendovi ORDER BY `ID`";
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$brendovi[$ID]=$naziv;
}
$brendxx="";
$sql="SELECT `ID`,`naziv`,`brend` FROM proizvodi ORDER BY `brend`,`ID` ASC";
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while($row=mysql_fetch_assoc($result)) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	
	if ($brendxx!=$brend) echo '<div><center><b>'.$brendovi[$brend].'</b></center></div>';

	echo '<a href="#" onclick="izmena('.$ID.')">'.$naziv.'</a><br/>';

	$brendxx=$brend;
}
?>
		</div>
	</div>
<div style="width:180px;top:27px;left:210px;position:absolute;bottom:0;background:#fff;opacity:0.5">
</div>
<div style="width:180px;top:27px;left:560px;position:absolute;bottom:0;background:#fff;opacity:0.5">
</div>
<div class="wrap" style="position:absolute;top:32px;left:220px;width:800px">
	<div class="iur">
		<div class="iul">ID</div>
		<input id="yid" type="text" name="IDx" class="iud" readonly style="background:#ccc" value="<?php
$sql="SELECT `ID` FROM proizvodi ORDER BY `ID` DESC LIMIT 1";
$result=mysql_query($sql) or die($sql.': '.mysql_error());
$row=mysql_fetch_assoc($result);
if (isset($row['ID'])) {
$result = mysql_query("SHOW TABLE STATUS LIKE 'proizvodi'");
$data = mysql_fetch_assoc($result);
$nextID = $data['Auto_increment'];
echo $nextID;
}
else  {
$nextID='1';
echo $nextID;
}
		?>" />
				<input id="hidid" type="hidden" class="iud" readonly style="background:#ccc" value="<?php echo $nextID; ?>" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Šifra proizvoda</div>
		<input id="ysifra" type="text" name="sifra" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Šifra u kasi</div>
		<input id="ysifkas" type="text" name="sifkas" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Bar code</div>
		<input id="ybarcode" type="text" name="barcode" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Naziv proizvoda</div>
		<textarea id="ynaziv" style="width:153px" rows="3" name="naziv" class="iud"></textarea>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Link</div>
		<textarea id="ylink" style="width:153px" rows="3" name="link" class="iud"></textarea>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Namenska grupa</div>
		<select id="ynamgrupa" type="text" name="namgrupa" class="iud" style="width:153px" >
			<option value="1">Za žene</option>
			<option value="2">Za muškarce</option>
			<option value="3">Za decu</option>
		</select>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Brend</div>
		<select id="ybrend" type="text" name="brend" class="iud" style="width:153px" >
<?php
$sql='SELECT ID, naziv FROM brendovi ORDER BY naziv';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
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
		<div class="iul">Dobavljač</div>
		<select id="ydobavljac" type="text" name="dobavljac" class="iud" style="width:153px" >
<?php
$sql='SELECT ID, prezime, ime FROM partneri WHERE gpartnera="8" ORDER BY prezime, ime';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
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
		<div class="iul">Zapremina (ml)</div>
		<input id="yzapremina" type="text" name="zapremina" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Neto težina (g)</div>
		<input id="ytezinaneto" type="text" name="tezinaneto" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Bruto težina (g)</div>
		<input id="ytezinabruto" type="text" name="tezinabruto" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Količina u pakovanju</div>
		<input id="ykolpak" type="text" name="kolpak" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Minimalna zaliha</div>
		<input id="yminzal" type="text" name="minzal" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Carinska tarifa i stopa</div>
		<select id="ycartar" type="text" name="cartar" class="iud" style="min-width:153px" >
<?php
$sql='SELECT ID, naziv, sifra FROM ctarife ORDER BY naziv';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while($row=mysql_fetch_assoc($result)) {
	$ID=$row['ID'];
	$naziv=$row['naziv'];
	$sifra=$row['sifra'];
	echo '<option value="'.$ID.'" title="'.$naziv.'">'.$sifra.'</option>';
}
?>
		</select>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Stopa PDVa (%)</div>
		<input id="ypdv" type="text" name="pdv" value="20.00" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<input type="hidden" id="ykurs" value="<?php
$sql='SELECT ksred FROM kurs ORDER BY ID DESC LIMIT 1';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while($row=mysql_fetch_assoc($result)) {
	$ksred=$row['ksred'];
	echo $ksred;
}
	?>"/>
	<input type="hidden" id="ygrupa"/>
	<div class="iur">
		<div class="iul">Nabavna cena (EUR)</div>
		<input id="yncenae" type="text" name="ncena" class="iud" onkeyup="ncenad()"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Nabavna cena (RSD)</div>
		<input id="yncena" type="text" class="iud" onkeyup="ncenag()" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Prodajna cena bez PDVa</div>
		<input id="ypcena" type="text" class="iud" onkeyup="pdvd()" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Prodajna cena sa PDVom</div>
		<input id="ypcenas" type="text" name="pcenas" class="iud" onkeyup="pdvg()"/>
		<div style="clear:both;"></div>
	</div>
</div>
<div class="wrap" id="desnakolona" style="position:absolute;top:32px;left:565px;width:400px">
	<div class="iur">
		<div class="iul" id="dkolona"><center><b>Grupa proizvoda</b></center><br/>
			<div><b>Nega kose</b></div>
<?php
$sql='SELECT ID, naziv FROM gproizvoda WHERE nadgrupa="1" ORDER BY naziv';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while($row=mysql_fetch_assoc($result)) {
	$ID=$row['ID'];
	$naziv=$row['naziv'];
	echo '<div class="ckb"><input type="checkbox" name="sub'.$ID.'" id="sub'.$ID.'" value="'.$ID.'" /> '.$naziv.'</div>';
}
?>
			<div><b>Nega lica</b></div>
<?php
$sql='SELECT ID, naziv FROM gproizvoda WHERE nadgrupa="2" ORDER BY naziv';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while($row=mysql_fetch_assoc($result)) {
	$ID=$row['ID'];
	$naziv=$row['naziv'];
	echo '<div class="ckb"><input type="checkbox" name="sub'.$ID.'" id="sub'.$ID.'" value="'.$ID.'" /> '.$naziv.'</div>';
}
?>
			<div><b>Nega tela</b>
<?php
$sql='SELECT ID, naziv FROM gproizvoda WHERE nadgrupa="3" ORDER BY naziv';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while($row=mysql_fetch_assoc($result)) {
	$ID=$row['ID'];
	$naziv=$row['naziv'];
	echo '<div class="ckb"><input type="checkbox" name="sub'.$ID.'" id="sub'.$ID.'" value="'.$ID.'" /> '.$naziv.'</div>';
}
?>
			<div><b>Parfemi</b></div>
<?php
$sql='SELECT ID, naziv FROM gproizvoda WHERE nadgrupa="4" ORDER BY naziv';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while($row=mysql_fetch_assoc($result)) {
	$ID=$row['ID'];
	$naziv=$row['naziv'];
	echo '<div class="ckb"><input type="checkbox" name="sub'.$ID.'" id="sub'.$ID.'" value="'.$ID.'" /> '.$naziv.'</div>';
}
?>
			<div><b>Setovi</b></div>
<?php
$sql='SELECT ID, naziv FROM gproizvoda WHERE nadgrupa="5" ORDER BY naziv';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while($row=mysql_fetch_assoc($result)) {
	$ID=$row['ID'];
	$naziv=$row['naziv'];
	echo '<div class="ckb"><input type="checkbox" name="sub'.$ID.'" id="sub'.$ID.'" value="'.$ID.'" /> '.$naziv.'</div>';
}
?>
			<div><b>Ostalo</b></div>
<?php
$sql='SELECT ID, naziv FROM gproizvoda WHERE nadgrupa="6" ORDER BY naziv';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while($row=mysql_fetch_assoc($result)) {
	$ID=$row['ID'];
	$naziv=$row['naziv'];
	echo '<div class="ckb"><input type="checkbox" name="sub'.$ID.'" id="sub'.$ID.'" value="'.$ID.'" /> '.$naziv.'</div>';
}

$svi="";
$sql='SELECT ID FROM gproizvoda';
$result=mysql_query($sql) or die($sql.': '.mysql_error());
while($row=mysql_fetch_assoc($result)) {
	$ID=$row['ID'];
	$svi.=$ID.',';
}
$svi=substr($svi, 0, -1);
echo '<input type="hidden" name="svi" value="'.$svi.'" />';
?>
		</div>
	</div>
</div>
<script type="text/javascript">
function pdvd()
	{
		var cena=document.getElementById("ypcena").value;
		var pdv=document.getElementById("ypdv").value;
		var cenas=document.getElementById("ypcenas");
		if (cena=="") cenas.value=""
			else {
			cenap=parseInt(cena)+(cena*(pdv/100));
			cenas.value=Math.round(cenap * 100) / 100;
		}
	}
function pdvg()
	{
		var cenas=document.getElementById("ypcenas").value;
		var pdv=document.getElementById("ypdv").value;
		var cena=document.getElementById("ypcena");
		if (cenas=="") cena.value=""
			else {
			cenap=cenas/(1+(pdv/100));
			cena.value=Math.round(cenap*100)/100;
		}
	}
function ncenad()
	{
		var cenae=document.getElementById("yncenae").value;
		var kurs=document.getElementById("ykurs").value;
		var cena=document.getElementById("yncena");
		if (cenae=="") cena.value=""
			else {
			cenap=cenae*kurs;
			cena.value=Math.round(cenap * 100) / 100;
		}
	}
function ncenag()
	{
		var cena=document.getElementById("yncena").value;
		var kurs=document.getElementById("ykurs").value;
		var cenae=document.getElementById("yncenae");
		if (cena=="") cenae.value=""
			else {
			cenap=cena/kurs;
			cenae.value=Math.round(cenap*10000)/10000;
		}
	}
function delform()
{
var r=confirm("Da li sigurno želite da obrišete ovaj proizvod iz baze?");
if (r==true)
  {
	document.getElementById("delform").submit();
  }
}
function novo()
	{
		var hidid=document.getElementById("hidid").value;
		document.getElementById("forma").reset();
		document.getElementById("yid").value=hidid;
		$("#unosbtn").prop('value', 'Unesi');
	}
function izmena(posebno)
	{
		d = new Date();
		$("#unosbtn").prop('value', 'Promeni');
		document.getElementById("forma").reset();
		document.getElementById("del").value=posebno;
		$.getJSON('ajax/proizvodii.php', {posebno: posebno}, function(data) {
			$('#ysifra').val(data.ysifra);
			$('#ysifkas').val(data.ysifkas);
			$('#ybarcode').val(data.ybarcode);
			$('#ynaziv').val(data.ynaziv);
			$('#ylink').val(data.ylink);
			$('#ynamgrupa').val(data.ynamgrupa);
			$('#ybrend').val(data.ybrend);
			$('#ydobavljac').val(data.ydobavljac);
			$('#yzapremina').val(data.yzapremina);
			$('#ytezinaneto').val(data.ytezinaneto);
			$('#ytezinabruto').val(data.ytezinabruto);
			$('#ykolpak').val(data.ykolpak);
			$('#yminzal').val(data.yminzal);
			$('#ycartar').val(data.ycartar);
			$('#ypdv').val(data.ypdv);
			$('#yncenae').val(data.yncenae);
			$('#ypcenas').val(data.ypcena);
			$('#yid').val(data.yid);
			ncenad();
			pdvg();
			jQuery.each( data.ygrupa, function() {
			  $( "#sub" + this ).prop('checked', true);
			});
		});
	}
</script>
</form>
<form id="delform" action="#" method="post">
<input type="hidden" id="del" name="del" />
</form>
</body>
</html>