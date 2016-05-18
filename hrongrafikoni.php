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
<title id="Timerhead">Hronološki grafikoni - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<link type='text/css' rel="stylesheet" href="js/jquery-ui.css" />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<style type="text/css">
#ostalo td, #ostalo th {
	background:#fff;
	padding:2px 3px;
}
.stepen {
	height:49px;
	border-top:1px solid #555;
	border-left:1px solid #555;
}
.element {
	height:620px;
	width:50px;
	margin:20px 10px 0 0;
	display: inline-block;
	font-size:10;
}
.skala {
	height:50px;
	width:45px;
	text-align:right;
}
.elprazno {
	background:none;
	width:50px;
}
.elmp {
	background:#38c;
	opacity:0.8;
	width:50px;
	text-align:center;
}
.elvp {
	background:#3a3;
	opacity:0.8;
	width:50px;
	text-align:center;
}
.elcom {
	background:none;
	padding-top:10px;
	width:50px;
	height:89px;
	color:#000;
}
</style>
<meta name="robots" content="noindex">
</head>
<body>
<?php include 'topbar.php'; ?>

<div style="width:200px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6"></div>
<div style="position:absolute;left:205px;top:32px;right:5px;bottom:5px;background:#fff;opacity:0.6"></div>
<div style="position:absolute;left:205px;top:32px;right:5px;height:500px;background:#fff;padding:25px 0 100px 50px"><div class="stepen"></div><div class="stepen"></div><div class="stepen"></div><div class="stepen"></div><div class="stepen"></div><div class="stepen"></div><div class="stepen"></div><div class="stepen"></div><div class="stepen"></div><div class="stepen"></div><div class="stepen" style="height:99px"></div></div>

<div id="skala" style="position:absolute;left:210px;top:32px;width:50px;height:642px"></div>
<div id="grafikon" style="position:absolute;left:265px;top:32px;right:5px;height:642px;overflow-x:scroll;overflow-y:hidden;white-space:nowrap"></div>

<div style="position:absolute;top:32px;bottom:5px;left:5px;width:190px;overflow-x:auto">
	<div style="width:100%;border-bottom:1px solid #000;text-align:center;font-weight:bold;margin-bottom:3px">Vremenski okvir</div>
	<div id="blacklink" style="font-size:12;overflow:auto;border-bottom:1px solid #000;padding-bottom:3px">
		<center><input type="button" id="pokazi" value="pokaži" onclick="prikaz()" style="padding:2px 15px"/></center>
	<div style="border-bottom:1px solid #000;margin-bottom:3px">
		<input type="radio" name="prihod" value="1" checked="checked">Prihod<br>
		<input type="radio" name="prihod" value="2">Zarada<br>
	</div>
		<input type="radio" name="period" value="1" onclick="selector(1)">Po mesecima<br>
		<input type="radio" name="period" value="2" checked="checked" onclick="selector(1)">Po sedmicama<br>
		<input type="radio" name="period" value="3" onclick="selector(1)">Po danima<br>
		<input type="radio" name="period" value="4" onclick="selector(2)">Specifično<br>
<?php
$danas=date('Y-m-d');
$pre=date('Y-m-d',strtotime("-1 month"));
?>			
		<div id="datumspec" style="margin-top:3px">Od: <input type="date" name="datod" id="datod" disabled="disabled" value="<?php echo $pre;?>"><br>Do: <input type="date" name="datdo" id="datdo" disabled="disabled" value="<?php echo $danas; ?>"></div>
	</div>
</div>
<script type="text/javascript">
function selector(op)
	{
		switch(op) {
			case 1:
				 $('#datumspec').find(':input').prop('disabled', true);
				 break;
			case 2:
				 $('#datumspec').find(':input').prop('disabled', false);
				break;
		}
	}
function prikaz()
	{
		var prihod = document.getElementsByName('prihod');
		var prihodx;
		for (var i = 0, length = prihod.length; i < length; i++) {
			if (prihod[i].checked) {
				prihodx=prihod[i].value;
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
		if (periodx == 4) {
		var datod = document.getElementById("datod").value;
		var datdo = document.getElementById("datdo").value;
		}
		else {
		datod = 0;
		datdo = 0;
		}
		$.getJSON('ajax/hrografikonia.php', {prihodx:prihodx, periodx:periodx, datod:datod, datdo:datdo}, function(data) {
			$('#skala').html(data.skala);
			$('#grafikon').html(data.grafikon);
		});
	}
</script>
</body>
</html>