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

?>
<html>
<head profile="http://www.w3.org/2005/20/profile">
<link rel="icon"
	  type="image/png"
	  href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title id="Timerhead">Pregled prodaja - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<link type='text/css' rel="stylesheet" href="js/jquery-ui.css" />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<style type="text/css">
#gornjiwrap {
	position:absolute;
	top:32px;
	left:205px;
	height:75px;
	right:0;
	overflow:auto;
	font-size:12;
	padding:3px;
}
#desniwrap {
	position:absolute;
	top:118px;
	left:205px;
	bottom:0;
	right:0;
	overflow:auto;
	font-size:12;
	padding:3px;
}
td {
	text-align:right;
}
</style>
<meta name="robots" content="noindex">
</head>
<body>
<?php include 'topbar.php'; ?>

<div style="width:200px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6">
</div>
	<div style="position:absolute;top:32px;left:5px;width:190px">
		<div style="width:100%;border-bottom:1px solid #000;text-align:center;font-weight:bold">Postojeće prodaje</div>
		<div id="blacklink" style="font-size:12;overflow:auto">
		<div>
			<input type="radio" name="izbor" value="1">Pazar<br>
			<input type="radio" name="izbor" value="2">Fakture<br>
			<input type="radio" name="izbor" value="3">Profakture<br>
			<input type="radio" name="izbor" value="4">Pazar + fakture<br>
			<input type="radio" name="izbor" value="5" checked="checked">Pazar + fakture + profakture
		</div>
		<div style="border-top:1px solid #000;padding-top:3px;margin-top:3px">
			Sortiraj po: <select name="sort" id="sorter">
				<option value="1">Šifri</option>
				<option value="2" selected="selected">Šifri u kasi</option>
				<option value="3">Nazivu</option>
				<option value="4">Količini</option>
			</select>
		</div>
		<div>
			<input type="radio" name="smer" value="1" checked="checked">Rastuće
			<input type="radio" name="smer" value="2" style="margin-left:30px">Opadajuće
		</div>
		<div style="border-top:1px solid #000;padding:3px 0;margin:3px 0;border-bottom:1px solid #000">
			<div style="height:16px"><div style="float:left">Period:</div><div style="float:right"><input type="button" id="pokazi" value="pokaži" onclick="prikaz('x','x')"/></div></div>
			<input type="radio" name="period" value="1" checked="checked" onclick="selector(1)">Sve<br>
			<input type="radio" name="period" value="2" onclick="selector(2)">Po mesecima<br>
			<input type="radio" name="period" value="3" onclick="selector(3)">Određeno<br>
<?php
$danas=date('Y-m-d');
$pre=date('Y-m-d',strtotime("-1 month"));
?>			
			<div id="datumspec">Od: <input type="date" name="datod" id="datod" disabled="disabled" value="<?php echo $pre;?>"><br>Do: <input type="date" name="datdo" id="datdo" disabled="disabled" value="<?php echo $danas; ?>"></div>
		</div>
		<div style="display:none" id="meseciselect">
<?php
$godinax="";
$kalendar=array(1=>'Januar',2=>'Februar',3=>'Mart',4=>'April',5=>'Maj',6=>'Jun',7=>'Jul',8=>'Avgust',9=>'Septembar',10=>'Oktobar',11=>'Novembar',12=>'Decembar');
$sql="SELECT month(datprometa) mesec, year(datprometa) godina FROM prodaja GROUP BY month(datprometa), year(datprometa) ORDER BY datprometa DESC";
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	if ($godina==0) echo '<div style="font-weight:bold;font-style:italic;margin:5px 0 5px 20px">bez datuma</div>';
	elseif ($godina!=$godinax) echo '<div style="font-weight:bold;margin:5px 0 5px 20px">'.$godina.'</div>';
	if ($godina==0) echo '<a href="#" onclick="prikaz('.$godina.','.$mesec.')">bez datuma</a><br/>';
	else echo '<a href="#" onclick="prikaz('.$godina.','.$mesec.')">'.$kalendar[$mesec].'</a><br/>';
	$godinax=$godina;
}
?>
		</div>
	</div>
</div>
<div style="position:absolute;top:28px;left:205px;right:0;bottom:0;background:#fff;opacity:0.8">
</div>
<div id="gornjiwrap">
</div>
<div id="desniwrap">
</div>

<script type="text/javascript">
function selector(op)
	{
		switch(op) {
			case 1:
				 $('#datumspec').find(':input').prop('disabled', true);
				 $('#meseciselect').hide();
				 $('#pokazi').show();
				 break;
			case 2:
				 $('#datumspec').find(':input').prop('disabled', true);
				 $('#meseciselect').show();
				 $('#pokazi').hide();
				break;
			case 3:
				 $('#datumspec').find(':input').prop('disabled', false);
				 $('#meseciselect').hide();
				 $('#pokazi').show();
				break;
		}
	}
function prikaz(godina,mesec)
	{
		var izbor = document.getElementsByName('izbor');
		var izborx;
		for (var i = 0, length = izbor.length; i < length; i++) {
			if (izbor[i].checked) {
				izborx=izbor[i].value;
				break;
			}
		}
		var sortx = document.getElementById("sorter").value;
		var smer = document.getElementsByName('smer');
		var smerx;
		for (var i = 0, length = smer.length; i < length; i++) {
			if (smer[i].checked) {
				smerx=smer[i].value;
				break;
			}
		}
		var period = document.getElementsByName('period');
		var periodx;
		for (var i = 0, length = period.length; i < length; i++) {
			if (period[i].checked) {
				periodx=period[i].value;
				break;
			}
		}
		if (periodx == 3) {
		var datod = document.getElementById("datod").value;
		var datdo = document.getElementById("datdo").value;
		}
		else {
		datod = 0;
		datdo = 0;
		}
		$.getJSON('ajax/prodajapa.php', {godina: godina, mesec: mesec, izborx:izborx, sortx:sortx, smerx:smerx, periodx:periodx, datod:datod, datdo:datdo}, function(data) {
			$('#gornjiwrap').html(data.info);
			$('#desniwrap').html(data.sve);
		});
	}
</script>
</body>
</html>