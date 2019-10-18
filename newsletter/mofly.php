<?php
/*error_reporting(0);*/
/*ini_set('display_errors', '1');*/

require_once('cls.newsletter.php');

$db = new PDO('mysql:host=localhost;dbname=db_oxx', 'mysql_oxx', 'oxxPass7');

setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');

if(isset($_GET['m']) && isset($_GET['y'])){
	$dateMonth = $_GET['y'].'-'.$_GET['m'].'-01';
} else {
	//assume that we want to display next month events
	$dateMonth = date('Y-m-d', mktime(0,0,0,date('m')+1,1,date('Y')));
}

$filename='mofly'.date('Y-m',strtotime($dateMonth)).'.html';

$months_german=array(1=>'Januar',2=>'Februar',3=>'März',4=>'April',5=>'Mai',6=>'Juni',7=>'Juli',8=>'August',9=>'September',10=>'Oktober',11=>'November',12=>'Dezember');

$strMonth=$months_german[date('n',strtotime($dateMonth))].' '.date('Y',strtotime($dateMonth));
$doc = $cls_newsletter->getHeader($strMonth);
$doc .= $cls_newsletter->getMonth($dateMonth);
$doc .= $cls_newsletter->getFooter();

$cls_newsletter->saveHtml($doc,$filename);

header("Content-type: text/html; charset=utf-8");
if(file_exists($filename)){
    $output = file_get_contents($filename);    
    echo  $output;
}
?>