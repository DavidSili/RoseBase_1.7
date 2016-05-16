<?php
	session_start();
	$uri=$_SERVER['REQUEST_URI'];
	$pos = strrpos($uri, "/");
	$url = substr($uri, $pos+1);
	if ($_SESSION['loggedin'] != 1 OR $_SESSION['level'] == 0 ) {
		header("Location: login.php?url=$url");
		exit;
	}
	else {
	include 'config.php';
	$level=$_SESSION['level'];
	$user=$_SESSION['user'];
	}
if(isset($_POST) && !empty($_POST)) {

	$sifra=$_POST['psifra'];
	$hash = hash('sha256', $sifra);
	$salt = md5(uniqid(rand(), true));
	$salt = substr($salt, 0, 11);
	$hash = hash('sha256', $salt.$hash);

	$query='UPDATE users SET password="'.$hash.'", salt="'.$salt.'" WHERE username="'.$user.'"';
	mysql_query($query);

}
?>
<html>
<head profile="http://www.w3.org/2005/20/profile">
<link rel="icon"
	  type="image/png"
	  href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title id="Timerhead">Profil - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<script src="js/jquery.min.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<style type="text/css">
</style>
<meta name="robots" content="noindex">
</head>
<body onload="setdis()">
<?php include 'topbar.php'; ?>

<div id="introbox" style="width:200px;top:27px;position:absolute;left:0;bottom:0;background:#fff;opacity:0.4">
</div>
<div class="wrap" style="position:absolute;top:32px;left:30px;width:480px">
	
<?php
		echo '<div class="iur" style="margin-bottom:5px"><div class="iul" style="text-align:left"><b>Korisnik '.$user.':</b></div><div style="clear:both;"></div></div>';
$sql="SELECT * FROM users";
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
	foreach($row as $xx => $yy) {
		$$xx=$yy;
	}
switch ($funkcija) {
	case "1" :
	$funkcija="vođa";
		break;
	case "2" :
	$funkcija="zamenik";
		break;
	case "3" :
	$funkcija="blagajnik";
		break;
	case "4" :
	$funkcija="sekretar";
		break;
	case "5" :
	$funkcija="ekonom";
		break;
}
$sql='SELECT ime FROM odredi WHERE ID="'.$zaodred.'"';
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
echo '<div class="iur"><b>ime:</b> '.$name.'<br/><b>email:</b> '.$email.'<br/><b>funkcija u HISu:</b> '.$funkcija.'<br/><b>deo odreda:</b> '.$row['ime'].'</div>';
?>
<br/><br/>
	<div class="iur" style="margin-bottom:5px">
		<div class="iul" style="text-align:left">
			<b>Promena šifre:</b></div><div style="clear:both;">
		</div>
	</div>
	<form action="#" method="POST">
	<div class="iur">
		<div class="iul" style="text-align:left"><b>nova šifra</b><br/><input id="sifra1" id="ydatpoc" type="password" name="nsifra" class="iud" title="kombinacija brojeva, malih i velikih slova - dužine 6-64; bez nizova" onchange="check(),icheck2()" autocomplete="off" /></div>		<div style="clear:both;"></div>
		<div class="iul" style="text-align:left"><b>ponovi šifru</b><br/><input id="sifra2" type="password" name="psifra" class="iud" title="ponovite šifru" onchange="check(),icheck3()"  autocomplete="off"/></div><div style="clear:both;"></div>
		<input type="submit" id="submit1" value="promeni šifru" class="iud" style="height:20px" />
		<div style="clear:both;"></div>
	</div>
	</form>
</div>
<script type="text/javascript">
function validatePassword (pw, options) {
	// default options (allows any password)
	var o = {
		lower:    0,
		upper:    0,
		alpha:    0, /* lower + upper */
		numeric:  0,
		special:  0,
		length:   [1, Infinity],
		custom:   [ /* regexes and/or functions */ ],
		badWords: [" "],
		badSequenceLength: 0,
		noQwertySequences: false,
		noSequential:      false
	};

	for (var property in options)
		o[property] = options[property];

	var	re = {
			lower:   /[a-z]/g,
			upper:   /[A-Z]/g,
			alpha:   /[A-Z]/gi,
			numeric: /[0-9]/g,
			special: /[\W_]/g
		},
		rule, i;

	// enforce min/max length
	if (pw.length < o.length[0] || pw.length > o.length[1])
		return false;

	// enforce lower/upper/alpha/numeric/special rules
	for (rule in re) {
		if ((pw.match(re[rule]) || []).length < o[rule])
			return false;
	}

	// enforce word ban (case insensitive)
	for (i = 0; i < o.badWords.length; i++) {
		if (pw.toLowerCase().indexOf(o.badWords[i].toLowerCase()) > -1)
			return false;
	}

	// enforce the no sequential, identical characters rule
	if (o.noSequential && /([\S\s])\1/.test(pw))
		return false;

	// enforce alphanumeric/qwerty sequence ban rules
	if (o.badSequenceLength) {
		var	lower   = "abcdefghijklmnopqrstuvwxyz",
			upper   = lower.toUpperCase(),
			numbers = "0123456789",
			qwerty  = "qwertyuiopasdfghjklzxcvbnm",
			start   = o.badSequenceLength - 1,
			seq     = "_" + pw.slice(0, start);
		for (i = start; i < pw.length; i++) {
			seq = seq.slice(1) + pw.charAt(i);
			if (
				lower.indexOf(seq)   > -1 ||
				upper.indexOf(seq)   > -1 ||
				numbers.indexOf(seq) > -1 ||
				(o.noQwertySequences && qwerty.indexOf(seq) > -1)
			) {
				return false;
			}
		}
	}

	// enforce custom regex/function rules
	for (i = 0; i < o.custom.length; i++) {
		rule = o.custom[i];
		if (rule instanceof RegExp) {
			if (!rule.test(pw))
				return false;
		} else if (rule instanceof Function) {
			if (!rule(pw))
				return false;
		}
	}

	// great success!
	return true;
}
function icheck2() {
var sifra1=document.getElementById("sifra1");
if (validatePassword(sifra1.value,{length:[6,64],lower:1,upper:1,numeric:1})==true)
{
sifra1.style.border="2px inset #0a0";
return true;
}
else
{
sifra1.style.border="2px inset #c00";
return false;
}
}
function icheck3() {
var sifra1=document.getElementById("sifra1");
var sifra2=document.getElementById("sifra2");
if (sifra1.value==sifra2.value && icheck2()==true)
{
sifra2.style.border="2px inset #0a0";
}
else
{
sifra2.style.border="2px inset #c00";
}
}
function check() {
var submit1=document.getElementById("submit1");
var sifra1=document.getElementById("sifra1").value;
var sifra2=document.getElementById("sifra2").value;

	if (validatePassword(sifra1,{length:[6,64],lower:1,upper:1,numeric:1})==true && (sifra1==sifra2)==true)
	{
		submit1.disabled=false;
	}
	else {
		submit1.disabled=true;
	}

}
function setdis() {
var submit1=document.getElementById("submit1");
submit1.disabled=true;
}
</script>
</body>
</html>