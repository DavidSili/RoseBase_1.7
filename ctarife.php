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

	if (isset($naziv)) $naziv=mysqli_real_escape_string($mysqli,$naziv);
	if (isset($sifra)) $sifra=mysqli_real_escape_string($mysqli,$sifra);
	
	$dattime=date('G:i:s j.n.Y.');
	$sql='SELECT ID FROM ctarife ORDER BY ID DESC LIMIT 1';
	$result=mysqli_query($mysqli,$sql) or die;
	$row=$result->fetch_assoc();
	$lastID=$row['ID'];
	$nextID=$lastID+1;
	
	if (isset($_POST['del'])) {
		$del=$_POST['del'];
		$sql='DELETE FROM ctarife WHERE ID="'.$del.'"';
		mysqli_query($mysqli,$sql);
	}
	elseif (isset($lastID)==false OR $IDx>$lastID) {
		$sql='INSERT INTO ctarife (naziv, sifra, stopa, uneo) VALUES ("'.$naziv.'","'.$sifra.'","'.$stopa.'","'.$user.' - '.$dattime.'")';
		mysqli_query($mysqli,$sql) or die;
	}
	else {
		$sql='SELECT menjali FROM ctarife WHERE sifra="'.$sifra.'"';
		$result=mysqli_query($mysqli,$sql);
		$row=$result->fetch_assoc();
		$xmenjali=$row['menjali'];
		$cid=$IDx;
		
		$sql='UPDATE ctarife SET naziv="'.$naziv.'", sifra="'.$sifra.'", stopa="'.$stopa.'", menjali="'.$xmenjali.'; '.$user.' - '.$dattime.'" WHERE ID="'.$IDx.'"';
		mysqli_query($mysqli,$sql) or die;
	}
		
}

?>
<html>
<head profile="http://www.w3.org/2005/20/profile">
<link rel="icon"
	  type="image/png"
	  href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title id="Timerhead">Carinske tarife - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<style type="text/css">
#blacklink {
	position:absolute;
	left:0;
	bottom:0;
	top:70px;
	width:185px;
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
		<input type="button" value="Nova carinska tarifa" style="width:100%;margin-top:5px" onclick="novo()"/>
		<input type="button" value="Obriši" style="width:100%" onclick="delform()"/>
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px"></div>
		<div id="blacklink" style="font-size:12;overflow:auto">
<?php
$sql="SELECT `ID`,`naziv`,`sifra` FROM ctarife ORDER BY `naziv` ASC";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {

foreach($row as $xx => $yy) {
	$$xx=$yy;
}
echo '<a href="#" onclick="izmena('.$ID.')" title="'.$sifra.'">'.$naziv.'</a><br/>';
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
$sql="SELECT `ID` FROM ctarife ORDER BY `ID` DESC LIMIT 1";
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
if (isset($row['ID'])) {
$ID=$row['ID']+1;
echo $ID;
}
else echo '1';

		?>"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Naziv</div>
		<input id="ynaziv" type="text" name="naziv" class="iud" autofocus/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Šifra carinske tarife</div>
		<input id="ysifra" type="text" name="sifra" class="iud"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Stopa (%)</div>
		<input id="ystopa" type="text" name="stopa" class="iud" title="Format stope: ###.## (#=cifra)"/>
		<div style="clear:both;"></div>
	</div>
</div>
<script type="text/javascript">
function delform()
{
var r=confirm("Da li sigurno želite da obrišete ovu carinsku stopu iz baze?");
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
		$.getJSON('ajax/ctarifei.php', {posebno: posebno}, function(data) {
			$('#ynaziv').val(data.ynaziv);
			$('#ysifra').val(data.ysifra);
			$('#ystopa').val(data.ystopa);
			$('#yid').val(data.yid);
		});
	}
</script>
</form>
<form id="delform" action="#" method="post">
<input type="hidden" id="del" name="del" />
</form>
</body>
</html>