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
<title id="Timerhead">Pregled prodaja po partnerima- Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<link type='text/css' rel="stylesheet" href="js/jquery-ui.css" />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<style type="text/css">
#ko {
	position:absolute;
	top:32px;
	left:205px;
	height:30px;
	text-align:center;
	font-weight:bold;
	width:570px;
	right:0;
	overflow:auto;
	font-size:12;
	padding:3px;
	color:#000;
}
#gornjiwrap {
	position:absolute;
	top:62px;
	left:205px;
	height:90px;
	right:0;
	overflow:auto;
	font-size:12;
	padding:3px;
	color:#000;
}
#desniwrap {
	position:absolute;
	top:158px;
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
#gornjiwrap a{
	color:#000;
	text-decoration:none;
}
#gornjiwrap a:hover{
	color:#000;
	text-decoration:underline;
}
#gornjiwrap a:visited{
	color:#000;
	text-decoration:none;
}
</style>
<meta name="robots" content="noindex">
</head>
<body>
<?php include 'topbar.php'; ?>

<div style="width:200px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.6">
</div>
	<div style="position:absolute;top:32px;left:5px;width:190px">
		<div style="width:100%;border-bottom:1px solid #000;text-align:center;font-weight:bold">Postojeći partneri</div>
		<div id="blacklink" style="font-size:12;overflow:auto">
		<div style="width:100%;border-bottom:1px solid #000;padding: 3px 0px;margin-bottom:3px"><input type="checkbox" name="prof" id="prof" checked="checked" style="margin: 3px 3px 0px 4px" />Profakture</div>
		<center><a href="#" onclick="prikaz('x')" style="font-weight:bold">Svi partneri</a></center>
<?php
$gpartneraxx="";
$sql="SELECT `ID`,`naziv` FROM gpartnera ORDER BY `ID`";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	$gpartnerax[$ID]=$naziv;
}
$sql="SELECT `ID`,`prezime`,`ime`,`gpartnera` FROM partneri WHERE gpartnera <> 8 ORDER BY `gpartnera` DESC,`prezime` ASC,`ime` ASC";
$result=mysqli_query($mysqli,$sql) or die;
while($row=$result->fetch_assoc()) {

	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
	if ((isset($gpartnera) AND $gpartneraxx==$gpartnera)== false) echo '<b>'.$gpartnerax[$gpartnera].'</b><br/>';

	echo '<a href="#" onclick="prikaz('.$ID.')">'.$prezime.' '.$ime.'</a><br/>';

	$gpartneraxx=$gpartnera;
}
?>
		</div>
	</div>
<div style="position:absolute;top:28px;left:205px;right:0;bottom:0;background:#fff;opacity:0.8">
</div>
<div id="ko" style="font-size:16">
</div>
<div id="gornjiwrap">
</div>
<div id="desniwrap">
</div>

<script type="text/javascript">
function prikaz(posebno)
	{
		var prof;
        if (document.getElementById('prof').checked) {
            prof=1;
        } else {
            prof=0;
        }
		$.getJSON('ajax/prodajap2a.php', {posebno: posebno, prof: prof}, function(data) {
			$('#ko').html(data.ko);
			$('#gornjiwrap').html(data.info);
			$('#desniwrap').html(data.sve);
		});
	}
</script>
</body>
</html>