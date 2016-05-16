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

	if (isset($naziv)) $naziv=mysql_real_escape_string($naziv);
	
	$dattime=date('G:i:s j.n.Y.');
	$sql='SELECT ID FROM partneri ORDER BY ID DESC LIMIT 1';
	$result=mysql_query($sql) or die;
	$row=mysql_fetch_assoc($result);
	$lastID=$row['ID'];
	$nextID=$lastID+1;

if (isset($firma)==false) $firma=NULL;
if (isset($pib)==false) $pib=NULL;
if (isset($maticni)==false) $maticni=NULL;
	
	if (isset($_POST['del'])) {
		$del=$_POST['del'];
		$sql='DELETE FROM partneri WHERE ID="'.$del.'"';
		mysql_query($sql);
	}
	elseif (isset($lastID)==false OR $IDx>$lastID) {
		$sql='INSERT INTO partneri (gpartnera, ime, prezime, pol, ulicaibr, mesto, drzava, firma, pib, maticni, telefon, mobilni, email, uneo) VALUES ("'.$gpartnera.'","'.$ime.'","'.$prezime.'","'.$pol.'","'.$ulicaibr.'","'.$mesto.'","'.$drzava.'","'.$firma.'","'.$pib.'","'.$maticni.'","'.$telefon.'","'.$mobilni.'","'.$email.'","'.$user.' - '.$dattime.'")';
		mysql_query($sql) or die;
	}
	else {
		$sql='SELECT menjali FROM partneri WHERE ID="'.$IDx.'"';
		$result=mysql_query($sql);
		$row=mysql_fetch_assoc($result);
		$xmenjali=$row['menjali'];
		$cid=$IDx;
		
		$sql='UPDATE partneri SET gpartnera="'.$gpartnera.'", ime="'.$ime.'", prezime="'.$prezime.'", pol="'.$pol.'", ulicaibr="'.$ulicaibr.'", mesto="'.$mesto.'", drzava="'.$drzava.'", firma="'.$firma.'", pib="'.$pib.'", maticni="'.$maticni.'", telefon="'.$telefon.'", mobilni="'.$mobilni.'", email="'.$email.'", menjali="'.$xmenjali.'; '.$user.' - '.$dattime.'" WHERE ID="'.$IDx.'"';
		mysql_query($sql) or die;
	}
		
}

?>
<html>
<head profile="http://www.w3.org/2005/20/profile">
<link rel="icon"
	  type="image/png"
	  href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title id="Timerhead">Partneri - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<style type="text/css">
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
	<div style="position:absolute;top:32px;bottom:5px;left:5px;width:195px;overflow-y:auto">
		<input id="unosbtn" type="submit" value="Unesi" style="width:100%;height:20px" />
		<input type="button" value="Novi partner" style="width:100%;margin-top:5px" onclick="novo()"/>
		<input type="button" value="Obriši" style="width:100%" onclick="delform()"/>
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px"></div>
		<div id="blacklink" style="font-size:12;overflow:auto">
<?php
$gpartneraxx="";
$sql="SELECT `ID`,`naziv` FROM gpartnera ORDER BY `ID`";
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$gpartnerax[$ID]=$naziv;
}
$sql="SELECT `ID`,`prezime`,`ime`,`gpartnera` FROM partneri ORDER BY `gpartnera`,`prezime`,`ime` ASC";
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	if ((isset($gpartnera) AND $gpartneraxx==$gpartnera)== false) echo '<b>'.$gpartnerax[$gpartnera].'</b><br/>';

	echo '<a href="#" onclick="izmena('.$ID.')">'.$prezime.' '.$ime.'</a><br/>';

	$gpartneraxx=$gpartnera;
}
?>
		</div>
	</div>
<div style="width:180px;top:27px;left:210px;position:absolute;bottom:0;background:#fff;opacity:0.5">
</div>
<div class="wrap" style="position:absolute;top:32px;left:220px;width:100%">
	<div class="iur">
		<div class="iul">ID</div>
		<input id="yid" type="text" name="IDx" class="iud" readonly style="background:#ccc" value="<?php
$sql="SELECT `ID` FROM partneri ORDER BY `ID` DESC LIMIT 1";
$result=mysql_query($sql) or die;
$row=mysql_fetch_assoc($result);
if (isset($row['ID'])) {
$ID=$row['ID']+1;
echo $ID;
}
else echo '1';

		?>"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Grupa partnera</div>
		<select id="ygpartnera" type="text" name="gpartnera" class="iud" autofocus onchange="pravno()">
<?php
$sql='SELECT ID, naziv FROM gpartnera ORDER BY naziv';
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {
	$ID=$row['ID'];
	$naziv=$row['naziv'];
	if (strpos($naziv,'Pravno') !== false) $bgn='daeafa';
		else $bgn='e0fae0';
	echo '<option value="'.$ID.'" style="background:#'.$bgn.'">'.$naziv.'</option>';
}
?>
		</select>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Ime</div>
		<input id="yime" type="text" name="ime" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Prezime</div>
		<input id="yprezime" type="text" name="prezime" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Pol</div>
		<div class="iud">
			<input type="radio" name="pol" id="ypol" value="Muškarac" style="width:20px;margin-left:5px"><span style="text-shadow: 0px 0px 4px #fff"> Muškarac / Žena</span>
			<input type="radio" name="pol" id="ypol" value="Žena" style="width:20px" checked="checked">
		</div>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Ulica i broj</div>
		<input id="yulicaibr" type="text" name="ulicaibr" class="iud" />
		<div style="clear:both;"></div>
	</div>
<?php
$sql='SELECT broj, mesto FROM pobroj ORDER BY mesto';
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {
	$broj=$row['broj'];
	$mesto=$row['mesto'];
	$pobrojevi[$broj]=$mesto;
}
?>
	<div class="iur">
		<div class="iul">Mesto</div>
		<select id="ymesto" type="text" name="mesto" class="iud">
<?php
			foreach($pobrojevi as $aaa => $bbb) {
				echo '<option value="'.$bbb.'">'.$bbb.' ('.$aaa.')</option>';
			}
?>
			<option value="Sofia (BG)">Sofia (BG)</option>
			<option value="Plovdiv (BG)">Plovdiv (BG)</option>
		</select>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Država</div>
		<input id="ydrzava" type="text" name="drzava" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div id="yfirmax" class="iur" style="display:none">
		<div class="iul">Naziv firme</div>
		<input id="yfirma" type="text" name="firma" class="iud" disabled="disabled"/>
		<div style="clear:both;"></div>
	</div>
	<div id="ypibx" class="iur" style="display:none">
		<div class="iul">PIB</div>
		<input id="ypib" type="text" name="pib" class="iud" disabled="disabled"/>
		<div style="clear:both;"></div>
	</div>
	<div id="ymaticnix" class="iur" style="display:none">
		<div class="iul">Matični broj</div>
		<input id="ymaticni" type="text" name="maticni" class="iud" disabled="disabled" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Telefon</div>
		<input id="ytelefon" type="text" name="telefon" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Mobilni</div>
		<input id="ymobilni" type="text" name="mobilni" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">E-mail</div>
		<input id="yemail" type="text" name="email" class="iud" />
		<div style="clear:both;"></div>
	</div>
</div>
<script type="text/javascript">
function delform()
{
var r=confirm("Da li sigurno želite da obrišete ovog partnera iz baze?");
if (r==true)
  {
	document.getElementById("delform").submit();
  }
}
function novo()
	{
		document.getElementById("forma").reset();
		document.getElementById("yid").value="";
		$("#unosbtn").prop('value', 'Unesi');
	}
function izmena(posebno)
	{
		d = new Date();
		$("#unosbtn").prop('value', 'Promeni');
		document.getElementById("forma").reset();
		document.getElementById("del").value=posebno;
		$.getJSON('ajax/partnerii.php', {posebno: posebno}, function(data) {
			$('#ygpartnera').val(data.ygpartnera);
			$('#yime').val(data.yime);
			$('#yprezime').val(data.yprezime);
			var ypol=(data.ypol);
			$(':radio[name="pol"][value='+ypol+']').prop('checked', true);
			$('#yulicaibr').val(data.yulicaibr);
			$('#ymesto').val(data.ymesto);
			$('#ydrzava').val(data.ydrzava);
			$('#yfirma').val(data.yfirma);
			$('#ypib').val(data.ypib);
			$('#ymaticni').val(data.ymaticni);
			$('#ytelefon').val(data.ytelefon);
			$('#ymobilni').val(data.ymobilni);
			$('#yemail').val(data.yemail);
			$('#yid').val(data.yid);
			pravno();
		});
	}
function pravno()
	{
		var index = document.getElementById("ygpartnera").selectedIndex;
		var s = document.getElementById("ygpartnera").options[index].text;
		if (s.indexOf("Pravno") != -1) {
			document.getElementById("yfirmax").style.display="inline";
			document.getElementById("ypibx").style.display="inline";
			document.getElementById("ymaticnix").style.display="inline";
			document.getElementById("yfirma").disabled=false;
			document.getElementById("ypib").disabled=false;
			document.getElementById("ymaticni").disabled=false;
		}
		else {
			document.getElementById("yfirmax").style.display="none";
			document.getElementById("ypibx").style.display="none";
			document.getElementById("ymaticnix").style.display="none";
			document.getElementById("yfirma").disabled=true;
			document.getElementById("ypib").disabled=true;
			document.getElementById("ymaticni").disabled=true;
		}
	}
</script>
</form>
<form id="delform" action="#" method="post">
<input type="hidden" id="del" name="del" />
</form>
</body>
</html>