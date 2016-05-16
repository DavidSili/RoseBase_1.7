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
<title id="Timerhead">Izveštaj kase - Land of Roses doo: baza podataka</title>
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
</style>
<meta name="robots" content="noindex">
</head>
<body>
<?php include 'topbar.php'; ?>

<div style="width:120px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6"></div>
<div style="position:absolute;left:125px;top:32px;right:5px;height:80px;background:#fff;opacity:0.6"></div>
<div style="position:absolute;left:125px;top:117px;right:5px;bottom:5px;background:#fff;opacity:0.6"></div>
<div id="gore" style="position:absolute;left:125px;top:32px;right:5px;height:80px;padding:0 5px"></div>
<div id="ostalo" style="position:absolute;left:130px;top:122px;right:5px;bottom:10px;overflow-y:auto"></div>

	<div style="position:absolute;top:32px;bottom:5px;left:5px;width:110px;overflow-y:auto">
		<div style="width:100%;border-bottom:1px solid #000;margin-bottom:5px;text-align:center;font-weight:bold; font-size:14">Postojeći unosi</div>
		<div id="blacklink" style="font-size:12;line-height:16px">
<?php
$kalendar=array(1=>'Januar',2=>'Februar',3=>'Mart',4=>'April',5=>'Maj',6=>'Jun',7=>'Jul',8=>'Avgust',9=>'Septembar',10=>'Oktobar',11=>'Novembar',12=>'Decembar');
$sgodina=0;
$smesec=0;
$sql="SELECT `ID`,`datprometa` FROM prodaja WHERE `brpracuna` = '' ORDER BY `datprometa` DESC";
$result=mysql_query($sql) or die;
while($row=mysql_fetch_assoc($result)) {
$ID=$row['ID'];
$datprometa=$row['datprometa'];
$dansedmice=date('N',strtotime($datprometa));
$godina=date('Y',strtotime($datprometa));
$mesec=date('n',strtotime($datprometa));
if ($godina!=$sgodina) {
	echo '<center style="margin-top:5px"><b>'.$godina.'</b></center>';
	echo '<div style="margin:0 0 2px 10px"><b>'.$kalendar[$mesec].'</b></div>';
	$sgodina=$godina;
	$smesec=$mesec;
}
elseif ($mesec != $smesec) {
	echo '<div style="margin:3px 0 2px 10px"><b>'.$kalendar[$mesec].'</b></div>';
	$smesec=$mesec;
}
	$fdatum=date('d.m.Y.',strtotime($datprometa));
	echo '<a href="#" style="background:#';

switch ($dansedmice) {
	case 1:
	echo 'fcb';
		break;
	case 2:
	echo 'ffc';
		break;
	case 3:
	echo 'cfc';
		break;
	case 4:
	echo 'cff';
		break;
	case 5:
	echo 'ccf';
		break;
	case 6:
	echo 'fcf';
		break;
	case 7:
	echo 'fbb';
		break;
}
	echo ';padding:2px;margin-bottom:1px" onclick="prikaz(\''.$ID.'\')">'.$fdatum.'</a>&nbsp&nbsp<a href="ajax/izkasea2.php?posebno='.$ID.'" target="_blank">>></a><br/>';

}
?>
		</div>
	</div>
<script type="text/javascript">
function prikaz(ID)
	{
		$.getJSON('ajax/izkasea.php', {ID:ID}, function(data) {
		$('#gore').html(data.gore);
		$('#ostalo').html(data.ostalo);
		});
	}
</script>
</body>
</html>