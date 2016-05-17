<?php 
include 'config.php';
if (isset($_GET['register'])) {

	$usersent=($_POST['username']);
	$passsent1=$_POST['password1'];
	$passsent2=$_POST['password2'];
	$name=$_POST['name'];
	$email=$_POST['email'];
	$phone=$_POST['phone'];
	$country=$_POST['country'];
	$usersent=stripslashes($usersent);
	$passsent1=stripslashes($passsent1);
	$passsent2=stripslashes($passsent2);
	$name=stripslashes($name);
	$email=stripslashes($email);
	$phone=stripslashes($phone);
	$country=stripslashes($country);
	$usersent=mysqli_real_escape_string($mysqli,$usersent);
	$passsent1=mysqli_real_escape_string($mysqli,$passsent1);
	$passsent2=mysqli_real_escape_string($mysqli,$passsent2);
	$name=mysqli_real_escape_string($mysqli,$name);
	$email=mysqli_real_escape_string($mysqli,$email);
	$phone=mysqli_real_escape_string($mysqli,$phone);
	$country=mysqli_real_escape_string($mysqli,$country);
	$confcode=md5(uniqid(rand(), true));
	$confcode2=md5(uniqid(rand(), true));
	if (($passsent1==$passsent2) AND ($usersent!="") AND ($passsent1!="") AND ($passsent2!="") AND ($name!="") AND ($email!="") AND ($phone!="") AND ($country!="")) {
	$hash = hash('sha256', $passsent1);

	function createSalt()
	{
		$string = md5(uniqid(rand(), true));
		return substr($string, 0, 11);
	}
	$salt = createSalt();
	$hash = hash('sha256', $salt . $hash);
$break=0;
$sql = "SELECT * FROM users WHERE `username` = '$usersent'";
$result = mysqli_query($mysqli,$sql) or die;
if (mysqli_num_rows($result)>0) {
	$break=1;
	echo '<div style="background:#fff;-moz-border-radius: 7px;border-radius: 7px;border: 2px #333 solid;padding:5px;text-align:center;font-weight:bold;color:#d55">Već postoji ovo korisničko ime. Molimo vas izaberite neko drugo korisničko ime</div>';
}

$sql = "SELECT * FROM users WHERE `email` = '$email'";
$result = mysqli_query($mysqli,$sql) or die;
if (mysqli_num_rows($result)>0) {
	$break=1;
	echo '<div style="background:#fff;-moz-border-radius: 7px;border-radius: 7px;border: 2px #333 solid;padding:5px;text-align:center;font-weight:bold;color:#d55">Već postoji korisnik sa ovim e-mail-om. Molimo vas izaberite drugi e-mail, ili izaberite izaberite opciju da ste zaboravili šifru</div>';
}

	
$query = "INSERT INTO users ( username, password, salt, confcode, confcode2, level, name, email, phone, country )
        VALUES ( '$usersent' , '$hash' , '$salt' , '$confcode' , '$confcode2' , '0' , '$name' , '$email' , '$phone' , '$country' );";
		
		if ($break==0) {
		$result=mysqli_query($mysqli,$query) or die;

		if($result){

		$to=$email;
		$subject="Molimo vas potvrdite vasu e-mail adresu";
		$headers = "MIME-Version: 1.0\n";  
		$headers .= "Content-type: text/plain; charset=utf-8\n";  
		$headers .= "To: ".$usersent." <".$to.">\n";  
		$headers .= "From: Rose-base <no-reply@vodicbg.com>\n";  
		$headers .= "Reply-To: Rose-base <no-reply@vodicbg.com>\n";  
		$headers .= "Return-Path: Rose-base <no-reply@vodicbg.com>\n";
		$message="Vaš link za potvrdu\n";
		$message.="Kliknite na ovaj link da bi ste potvrdili vašu e-mail adresu\n";
		$message.="http://rose-base.vodicbg.com/confirm.php?passkey=$confcode\n\n";
		$message.="Ako imate neka pitanja za administratora, pitanja možete poslati na e-mail adresu: lingo@vodicbg.com";
		$fifth = "-f no-reply@vodicbg.com";
		$sentmail = mail($to,$subject,$message,$headers,$fifth);
		}
		else {
		header("Refresh: 10; url=login.php");
		echo '<div style="background:#fff;-moz-border-radius: 7px;border-radius: 7px;border: 2px #333 solid;padding:5px;text-align:center">Vaša e-mail adresa nije pronađena u našoj bazi.</div>';
		}

		if($sentmail){
		header("Refresh: 10; url=login.php");
		echo '<div style="background:#fff;-moz-border-radius: 7px;border-radius: 7px;border: 2px #333 solid;padding:5px;text-align:center">Link za potvrdu je poslat na vašu e-mail adresu.</div>';
		}
		else {
		header("Refresh: 10; url=login.php");
		echo '<div style="background:#fff;-moz-border-radius: 7px;border-radius: 7px;border: 2px #333 solid;padding:5px;text-align:center">Nije moguće poslati link za potvrdu na vašu e-mail adresu.</div>';
		}

		mysqli_close();
		}

	}

}

?>
<html>
<head profile="http://www.w3.org/2005/20/profile">
<link rel="icon"
	  type="image/png"
	  href="images/favicon.gif">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title id="Timerhead">Registracija - Land of Roses doo: baza podataka</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<script src="js/jquery.min.js"></script>
<meta name="robots" content="noindex">
</head>
<body onload="setdis()">

<div id="logform">
<div style="text-align:center;margin:10px 0 20px;font-size:16px"><b>Registrovanje</b></div>
	<form name="form1" method="post" action="?register=1">
		<div><div class="loglcol">korisničko ime: </div><input name="username" id="korime" type="text" title="dužine 6-14" onchange="check(),icheck1()" />*</div>
		<div><div class="loglcol">šifra: </div><input name="password1" id="sifra1" type="password" title="kombinacija brojeva, malih i velikih slova - dužine 6-64; bez nizova" onchange="check(),icheck2()" />*</div>
		<div><div class="loglcol">ponoviti šifru: </div><input name="password2" id="sifra2" type="password" title="ponovite šifru" onchange="check(),icheck3()"/>*</div>
		<div style="margin-top:10px"><div class="loglcol">Ime: </div><input name="name" id="ime" type="text" title="vaše ime" onchange="check(),icheck4()"/>*</div>
		<div><div class="loglcol">e-mail: </div><input name="email" id="email" type="text" title="tačna e-mail adresa. Potrebno je da bi mogli da aktivirate nalog" onchange="check(),icheck5()" />*</div>
		<div><div class="loglcol">telefon: </div><input name="phone" id="telefon" type="text" title="vaš broj telefona koji koristite (ukoliko je moguće - mobilni). Potrebno je da bi mogli da aktivirate nalog" onchange="check(),icheck6()" />*</div>
		<div><div class="loglcol">država: </div><input name="country" id="drzava" type="text" title="potrebno je radi statistike i radi uspostavljanja bolje komunikacije" onchange="check(),icheck7()"/>*</div>
		<div style="margin-top:10px;text-align:center"><input type="submit" id="submit1" name="Submit" value="Register" /></div><br/>
		<div style="font-size:10;color:#666">Zadržite kursor iznad polja za unos i pogledajte kako treba da se popune pojedinačna polja</div><br/>
		<div id="blacklink" style="line-height:20px"><a href="login.php">Prijava</a><br/><a href="forgot.php">Zaboravljeno korisničko ime/šifra</a></div>
	</form>
</div>
<script type="text/javascript">

function setdis() {
var submit1=document.getElementById("submit1");
submit1.disabled=true;
}

/*
	Password Validator 0.1
	(c) 2007 Steven Levithan <stevenlevithan.com>
	MIT License
*/

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
function icheck1() {
var korime=document.getElementById("korime");
if (validatePassword(korime.value,{length:[6,14]})==true)
{
korime.style.border="2px inset #0a0";
}
else
{
korime.style.border="2px inset #c00";
}
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
function icheck4() {
var ime=document.getElementById("ime");
if (validatePassword(ime.value,{alpha:1})==true)
{
ime.style.border="2px inset #0a0";
}
else
{
ime.style.border="2px inset #c00";
}
}
function icheck5() {
var email=document.getElementById("email");
if (validatePassword(email.value,{length:[6, Infinity]})==true && ValidateEmail(email)==true)
{
email.style.border="2px inset #0a0";
}
else
{
email.style.border="2px inset #c00";
}
}
function icheck6() {
var telefon=document.getElementById("telefon");
if (validatePassword(telefon.value,{numeric:8})==true)
{
telefon.style.border="2px inset #0a0";
}
else
{
telefon.style.border="2px inset #c00";
}
}
function icheck7() {
var drzava=document.getElementById("drzava");
if (validatePassword(drzava.value,{alpha:2})==true)
{
drzava.style.border="2px inset #0a0";
}
else
{
drzava.style.border="2px inset #c00";
}
}

function check() {
var submit1=document.getElementById("submit1");
var korime=document.getElementById("korime").value;
var sifra1=document.getElementById("sifra1").value;
var sifra2=document.getElementById("sifra2").value;
var ime=document.getElementById("ime").value;
var email=document.getElementById("email").value;
var telefon=document.getElementById("telefon").value;
var drzava=document.getElementById("drzava").value;

	if (validatePassword(korime,{length:[6,14]})==true && validatePassword(sifra1,{length:[6,64],lower:1,upper:1,numeric:1})==true && (sifra1==sifra2)==true && validatePassword(ime,{alpha:1})==true && validatePassword(email,{length:[6, Infinity]})==true && validatePassword(telefon,{numeric:8})==true && validatePassword(drzava,{alpha:2})==true )
	{
		submit1.disabled=false;
	}
	else {
		submit1.disabled=true;
	}

}
function ValidateEmail(inputText)  
{  
var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
if(inputText.value.match(mailformat))  
{  
return true;  
}  
else  
{  
return false;  
}  
}  
</script>
</body>
</html>