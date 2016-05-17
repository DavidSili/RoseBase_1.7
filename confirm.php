<?php
include 'config.php';

$passkey=$_GET['passkey'];
$passkey=stripslashes($passkey);
$passkey=mysqli_real_escape_string($mysqli,$passkey);

$sql="SELECT * FROM users WHERE confcode ='$passkey'";
$result=mysqli_query($mysqli,$sql) or die;
if($result){
	$count=mysql_num_rows($result);
	if($count==1){
		$row=$result->fetch_assoc();
		$ID=$row['ID'];
		$name=$row['name'];
		$username=$row['username'];
		$email=$row['email'];
		$phone=$row['phone'];
		$country=$row['country'];
		$confcode2=$row['confcode2'];
		$sql="UPDATE users SET `level`='1' WHERE `ID`='$ID'";
		$result=mysqli_query($mysqli,$sql) or die;
		
		echo "Vasa e-mail adresa je proverena. Molimo vas za strpljenje - administrator ce vas uskoro kontaktirati i aktivirati vas nalog.";

		$to=$email;
		$subject="Provera e-mail-a";
		$headers = "MIME-Version: 1.0\n";  
		$headers .= "Content-type: text/plain; charset=utf-8\n";  
		$headers .= "To: ".$username." <".$to.">\n";  
		$headers .= "From: rose-base <no-reply@vodicbg.com>\n";  
		$headers .= "Reply-To: rose-base <no-reply@vodicbg.com>\n";  
		$headers .= "Return-Path: rose-base <no-reply@vodicbg.com>\n";
		$message="Vaša e-mail adresa je proverena. Molimo vas za strpljenje - administrator će vas uskoro kontaktirati i aktivirati vaš nalog.";
		$fifth = "-f no-reply@vodicbg.com";
		$sentmail = mail($to,$subject,$message,$headers,$fifth);

		$to="lingo@vodicbg.com";
		$subject="Aktivacija za rose-base";
		$headers = "MIME-Version: 1.0\n";  
		$headers .= "Content-type: text/plain; charset=utf-8\n";  
		$headers .= "To: admin <".$to.">\n";  
		$headers .= "From: $name <no-reply@vodicbg.com>\n";  
		$headers .= "Reply-To: rose-base <no-reply@vodicbg.com>\n";  
		$headers .= "Return-Path: rose-base <no-reply@vodicbg.com>\n";
		$message="username: $username\n";
		$message.="email: $email\n\n";
		$message.="phone: $phone\n";
		$message.="country: $country\n";
		$message.="link za aktivaciju: http://rose-base.vodicbg.com/adminconfirm.php?passkey=$confcode2";
		$fifth = "-f no-reply@vodicbg.com";
		$sentmail = mail($to,$subject,$message,$headers,$fifth);
		
		}

		else {
		echo "Pogresan kod za potvrdu";
		}

}
?>