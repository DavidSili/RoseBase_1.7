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
<title id="Timerhead">Planiranje nabavke - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<link type='text/css' rel="stylesheet" href="js/jquery-ui.css" />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<style type="text/css">
#desniwrap {
	position:absolute;
	top:30px;
	left:205px;
	bottom:0;
	right:0;
	overflow:auto;
	font-size:12pt;
	padding:3px;
}
td {
	text-align:right;
}
.debelo {
	font-weight:bold;
	background: rgba(107, 110, 255, 0.57);
}
.levo {
	text-align:left;
	font-size:10pt;
}
</style>
<meta name="robots" content="noindex">
</head>
<body>
<?php include 'topbar.php'; ?>

<div style="width:200px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6">
</div>
<div style="position:absolute;top:32px;left:5px;bottom:5px;width:190px">
	<div style="width:100%;border-bottom:1px solid #000;text-align:center;font-weight:bold;margin-bottom:5px;">Planiranje nabavke:</div>
	<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px;">
		<input type="radio" name="izbor" value="1" checked=""checked>Jednostavno<br>
		<input type="radio" name="izbor" value="2">Težinsko<br>
	</div>
	<div id="blacklink" style="font-size:12pt;overflow:auto">
		Za koliko narednih meseci: <select id="period">
			<option>1</option>
			<option selected="selected">2</option>
			<option>3</option>
			<option>4</option>
			<option>6</option>
			<option>12</option>
		</select><br>
		<input type="button" id="prikazi" value="prikaži" onclick="prikaz()" style="width:100%;margin-top:5px;"/>
	</div>
</div>
<div style="position:absolute;top:28px;left:205px;right:0;bottom:0;background:#fff;opacity:0.8">
</div>
<div id="desniwrap">
</div>

<script type="text/javascript">
function prikaz() {
	var izbor = document.getElementsByName('izbor');
	var izborx;
	for (var i = 0, length = izbor.length; i < length; i++) {
		if (izbor[i].checked) {
			izborx=izbor[i].value;
			break;
		}
	}
	var period = document.getElementById('period').value;
	$.getJSON('ajax/nabavkaplana.php', {period: period, izbor: izborx}, function(data) {
		$('#desniwrap').html(data.sve);
	});
}
</script>
</body>
</html>