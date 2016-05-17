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
<title id="Timerhead">Pregled nabavki - Land of Roses doo: baza podataka</title>
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
	height:100px;
	right:0;
	overflow:auto;
	font-size:12;
	padding:3px;
}
#desniwrap {
	position:absolute;
	top:143px;
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
<body onload="proizvodi('x')<?php
if (isset($del)) echo ',novo()';
elseif (isset($cid)) echo ',izmena('.$IDx.')';
?>">
<?php include 'topbar.php'; ?>

<div style="width:200px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6">
</div>
	<div style="position:absolute;top:32px;left:5px;width:190px">
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px;text-align:center;font-weight:bold">Postojeći unosi</div>
		<div id="blacklink" style="font-size:12;overflow:auto">
<?php
$sql="SELECT `ID`,`datprijemarobe` FROM nabavka ORDER BY `ID` ASC";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$datprijemarobe=date('d.m.Y.',strtotime($datprijemarobe));
	echo '<a href="#" onclick="izmena('.$ID.')">'.$ID.' - '.$datprijemarobe.'</a><br/>';
}
?>
		</div>
	</div>
<div style="position:absolute;top:28px;left:205px;right:0;bottom:0;background:#fff;opacity:0.8">
</div>
<div id="gornjiwrap">
</div>
<div id="desniwrap">
</div>

<script type="text/javascript">
function izmena(posebno)
	{
		$.getJSON('ajax/nabavkapa.php', {posebno: posebno}, function(data) {
			$('#gornjiwrap').html(data.info);
			$('#desniwrap').html(data.sve);
		});
	}
</script>
</body>
</html>