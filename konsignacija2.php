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
<title id="Timerhead">Štampanje konsignacija за префърцунените - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<link type='text/css' rel="stylesheet" href="js/jquery-ui.css" />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<style type="text/css">
</style>
<meta name="robots" content="noindex">
</head>
<body>
<?php include 'topbar.php'; ?>

<div style="width:200px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6">
</div>
	<div style="position:absolute;top:32px;left:5px;width:190px">
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px;text-align:center;font-weight:bold">Postojeći unosi</div>
		<div id="blacklink" style="font-size:12;overflow:auto">
<?php
$sql="SELECT `ID`,`naziv` FROM skladista ORDER BY `ID` DESC";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	echo '<a href="ajax/konsignacijapa2.php?posebno='.$ID.'&specifikacija=00000&redova=0" target="_blank">'.$naziv.'</a><br/>';
}
?>
		</div>
	</div>
</body>
</html>