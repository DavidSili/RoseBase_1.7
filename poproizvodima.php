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
<title id="Timerhead">Izveštaj po proizvodima - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<link type='text/css' rel="stylesheet" href="js/jquery-ui.css" />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<style type="text/css">
th, td {
	padding:2px 10px;
}
td a{
	color:#555;
	text-decoration:none;
}
td a:hover{
	color:#555;
	text-decoration:underline;
}
td a:visited{
	color:#555;
	text-decoration:none;
}</style>
<meta name="robots" content="noindex">
</head>
<body>
<?php include 'topbar.php'; ?>

<div style="width:350px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6">
</div>
	<div style="position:absolute;top:32px;bottom:5px;left:5px;width:345px;overflow-y:auto">
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px;text-align:center;font-weight:bold">Proizvodi</div>
		<div id="blacklink" style="font-size:12;overflow:auto">
<?php
$sql="SELECT `sifra`,`naziv` FROM proizvodi ORDER BY `ID` ASC";
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	echo '<a href="#" onclick="prikaz(\''.$sifra.'\')" >'.$naziv.'</a><br/>';
}
?>
		</div>
	</div>
<div style="position:absolute;left:355px;top:32px;right:5px;height:125px;background:#fff;opacity:0.6"></div>
<div style="position:absolute;left:355px;top:162px;right:5px;bottom:5px;background:#fff;opacity:0.6"></div>
	
<div id="gore" style="position:absolute;left:355px;top:32px;right:5px;height:125px;padding:0 5px;font-size:12"></div>
<div id="ostalo" style="position:absolute;left:360px;top:167px;right:5px;bottom:10px;overflow-y:auto"></div>
<script type="text/javascript">
function prikaz(posebno)
	{
		$.getJSON('ajax/poproa.php', {posebno: posebno}, function(data) {
			$('#gore').html(data.gore);
			$('#ostalo').html(data.ostalo);
		});
	}
</script>
</body>
</html>