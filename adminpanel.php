<?php
	session_start();
	$uri=$_SERVER['REQUEST_URI'];
	$pos = strrpos($uri, "/");
	$url = substr($uri, $pos+1);
	if ($_SESSION['loggedin'] != 1 OR $_SESSION['level'] < 4 ) {
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
	
	$xime=$_POST['ime'];
	$xusername=$_POST['username'];
	$xpass=$_POST['pass'];
	$xemail=$_POST['email'];
	$hash = hash('sha256', $xpass);
	$salt = md5(uniqid(rand(), true));
	$salt = substr($salt, 0, 11);
	$hash = hash('sha256', $salt.$hash);
	$sql = 'SELECT * FROM users WHERE `username` = "'.$xusername.'"';
	$result = mysql_query($sql) or die;
	$usernejmovi=mysql_num_rows($result);
	$sql = 'SELECT * FROM users WHERE `email` = "'.$xemail.'"';
	$result = mysql_query($sql) or die;
	$emailovi=mysql_num_rows($result);
	if ($usernejmovi ==0 AND $emailovi ==0) {
	
		$query='INSERT INTO users (username, name, password, salt, level, email) VALUES ("'.$xusername.'","'.$xime.'","'.$hash.'","'.$salt.'","2","'.$xemail.'")';
		mysql_query($query);
	}
	
}

?>
<html>
<head profile="http://www.w3.org/2005/20/profile">
<link rel="icon"
	  type="image/png"
	  href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title id="Timerhead">Admin panel - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<style type="text/css">
.iur select {
	width:150px;
}
#form2 .iud {
	width:200px;
}
</style>
<meta name="robots" content="noindex">
</head>
<body>
<?php include 'topbar.php'; ?>

<div id="introbox2" style="width:180px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.4">
</div>
<div class="wrap" style="position:absolute;top:32px;left:10px;width:480px">
<form name="form2" id="form2" action="#" method="POST">
<div class="wrap" style="position:absolute;top:0;left:0px;width:400px">
	<div class="iur" style="margin-bottom:5px">
		<div class="iul" style="text-align:center"><b>Unos novog korisnika</b></div>
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">ime</div>
		<input id="yime" type="text" name="ime" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">email</div>
		<input id="yemail" type="text" name="email" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">korisničko ime</div>
		<input id="yusername" type="text" name="username" class="iud" />
		<div style="clear:both;"></div>
	</div>
	<div class="iur">
		<div class="iul">šifra</div>
		<input id="ypass" type="text" name="pass" class="iud" autocomplete="off"/>
		<div style="clear:both;"></div>
	</div>
	<div class="iur" style="margin-bottom:5px">
		<div class="iul" style="text-align:center"><input type="submit" value="unesi" style="height:20px"/></div>
		<div style="clear:both;"></div>
	</div>
</div>
<input type="hidden" name="do" value="2" />
</form>
<script type="text/javascript">
</script>
</body>
</html>