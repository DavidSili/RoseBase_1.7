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
	if (isset($opis)) $opis=mysqli_real_escape_string($mysqli,$opis);
	
	$dattime=date('G:i:s j.n.Y.');
	$sql='SELECT ID FROM brendovi ORDER BY ID DESC LIMIT 1';
	$result=mysqli_query($mysqli,$sql) or die;
	$row=$result->fetch_assoc();
	$lastID=$row['ID'];
	$nextID=$lastID+1;
	
	if (isset($_POST['del'])) {
		$del=$_POST['del'];
		$sql='DELETE FROM brendovi WHERE ID="'.$del.'"';
		mysqli_query($mysqli,$sql);
	}
	elseif (isset($lastID)==false OR $IDx>$lastID) {
		$IDx="";
		$sql='INSERT INTO brendovi (naziv, opis, status, uneo) VALUES ("'.$naziv.'","'.$opis.'","'.$status.'","'.$user.' - '.$dattime.'")';
		mysqli_query($mysqli,$sql) or die;
	}
	else {
		$sql='SELECT menjali FROM brendovi WHERE ID="'.$IDx.'"';
		$result=mysqli_query($mysqli,$sql);
		$row=$result->fetch_assoc();
		$xmenjali=$row['menjali'];
		
		$sql='UPDATE brendovi SET naziv="'.$naziv.'", opis="'.$opis.'", status="'.$status.'", menjali="'.$xmenjali.'; '.$user.' - '.$dattime.'" WHERE ID="'.$IDx.'"';
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
<title id="Timerhead">Brendovi - Land of Roses doo: baza podataka</title>
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
	<div style="position:absolute;top:32px;left:5px;width:190px">
		<input id="unosbtn" type="submit" value="Unesi" style="width:100%;height:20px" />
		<input type="button" value="Novi brend" style="width:100%;margin-top:5px" onclick="novo()"/>
		<input type="button" value="Obriši" style="width:100%" onclick="delform()"/>
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px"></div>
		<div id="blacklink" style="font-size:12;overflow:auto">
<?php
$sql="SELECT `ID`,`naziv`,`status`, IF (`status`='da',1,2) AS statusx FROM brendovi ORDER BY `statusx`,`naziv` ASC";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {

foreach($row as $xx => $yy) {
	$$xx=$yy;
}
echo '<a href="#" onclick="izmena('.$ID.')"';
if ($status=="ne") echo ' style="color:#777"';
echo '>'.$naziv.'</a><br/>';
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
$sql="SELECT `ID` FROM brendovi ORDER BY `ID` DESC LIMIT 1";
$result=mysqli_query($mysqli,$sql) or die;
$row=$result->fetch_assoc();
if (isset($row['ID'])) {
$ID=$row['ID']+1;
echo $ID;
}
else echo '1';

		?>" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Naziv</div>
		<input id="ynaziv" type="text" name="naziv" class="iud" autofocus/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Opis</div>
		<textarea id="yopis" style="width:153px" rows="10" name="opis" class="iud"></textarea>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">Status</div>
		<div class="iud">
			<input type="radio" name="status" id="ystatusda" value="da" style="width:20px;margin-left:30px" checked="checked"><span style="text-shadow: 0px 0px 4px #fff"> da / ne</span>
			<input type="radio" name="status" id="ystatusne" value="ne" style="width:20px">
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
<script type="text/javascript">
function delform()
{
var r=confirm("Da li sigurno želite da obrišete brend iz baze?");
if (r==true)
  {
	document.getElementById("delform").submit();
  }
}
function novo()
	{
		document.getElementById("forma").reset();
		document.getElementById("yid").innerHTML="";
		$("#unosbtn").prop('value', 'Unesi');
	}
function izmena(posebno)
	{
		d = new Date();
		$("#unosbtn").prop('value', 'Promeni');
		document.getElementById("forma").reset();
		document.getElementById("del").value=posebno;
		$.getJSON('ajax/brendovii.php', {posebno: posebno}, function(data) {
			$('#yid').val(data.yid);
			$('#ynaziv').val(data.ynaziv);
			$('#yopis').val(data.yopis);
			var ystatus=(data.ystatus);
			$(':radio[name="status"][value='+ystatus+']').prop('checked', true);
		});
	}
</script>
</form>
<form id="delform" action="#" method="post">
<input type="hidden" id="del" name="del" />
</form>
</body>
</html>