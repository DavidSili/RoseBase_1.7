<?php
include 'config.php';

$passkey=$_GET['passkey'];
$passkey=stripslashes($passkey);
$passkey=mysqli_real_escape_string($mysqli,$passkey);

$sql="SELECT * FROM users WHERE confcode2 ='$passkey'";
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
		$sql="UPDATE users SET `level`='2' WHERE `ID`='$ID'";
		$result=mysqli_query($mysqli,$sql) or die;
		
		}

		else {
		echo "Pogresan kod";
		}

		if($result){

		echo "Nalog sa korisnickim imenom: $username je aktiviran";

		$to=$email;
		$subject="Vas račun je aktiviran";
		$headers = "MIME-Version: 1.0\r\n";  
		$headers .= "Content-type: text/plain; charset=utf-8\r\n";  
		$headers .= "To: ".$username." <".$to.">\r\n";  
		$headers .= "From: Lingo 2.0 <no-reply@vodicbg.com>\r\n";  
		$headers .= "Reply-To: Lingo 2.0 <no-reply@vodicbg.com>\r\n";  
		$headers .= "Return-Path: Lingo 2.0 <no-reply@vodicbg.com>\r\n";
		$message="Vaš nalog je aktiviran. Dobrodošli ste da koristite naše usluge. http://rose-base.vodicbg.com/login.php";
		$fifth = "-f no-reply@vodicbg.com";
		$sentmail = mail($to,$subject,$message,$headers,$fifth);

	}

}
?>