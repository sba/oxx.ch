<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

@set_time_limit(9999999);
@ini_set("max_execution_time","false");
@ini_set("memory_limit","128M");


// ensure user has access to this function
/*if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
		| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_jcrawler' ))) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}*/

// require the html view class
jimport( 'joomla.application.helper' );
jimport('joomla.filesystem.file');

require_once( JApplicationHelper::getPath( 'admin_html', 'com_jcrawler' ) ); 

$task = JRequest::getVar( 'task', '' );

switch ($task) {
	case 'submit':
		submit($option);
		break;
	case 'notify':
		notify($option);
		break;
	case 'updatecheck':
		updatecheck($option);
		break;
	default:
		HTML_jcrawler::showForm($option);
		break;
}

$stack = array();
$disallow_file = array();


function submit($option) {
	global $stack;
	
	// get values from gui of script
	$website = JRequest::getVar( 'http_host', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	if(substr($website,-1)=="/") $website=substr($website,0,-1);
	$page_root = JRequest::getVar( 'document_root', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$sitemap_file = $page_root . JRequest::getVar( 'sitemap_url', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$sitemap_url =  $website . JRequest::getVar( 'sitemap_url', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$priority = JRequest::getVar( 'priority', '1.0', 'POST', 'DOUBLE', JREQUEST_ALLOWHTML );
	$forbidden_types = toArray(JRequest::getVar( 'forbidden_types', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML ));
	$exclude_names = toArray(JRequest::getVar( 'exclude_names', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML ));
	$freq = JRequest::getVar( 'freq', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$modifyrobots = JRequest::getVar( 'robots', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$method = JRequest::getVar( 'method', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$level = JRequest::getVar( 'levels', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$maxcon = JRequest::getVar( 'maxcon', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	
	($priority >= 1)?$priority="1.0":null;
	
	if (substr($page_root,-1)!="/") $page_root=$page_root."/";
	$robots=@JFile::read( $page_root.'robots.txt' );
	//$pos=stripos("Sitemap:",$robots);
	preg_match_all("/Disallow:(.*?)\n/", $robots, $pos);
	
	if ($exclude_names[0]=="")
		unset($exclude_names[0]);
	
	foreach($pos[1] as $disallow){
		$disallow=trim($disallow);
		if (strpos($disallow,$website)===false)
			$disallow=$website.$disallow;
		$exclude_names[]=$disallow;
	}
	
	array_push($forbidden_types,".javascript",".close()");
	
	$forbidden_strings=array("print=1","format=pdf","option=com_mailto","component/mailto","#","javascript:","/mailto/","mailto:","login","register","reset","remind","javascript:");
	foreach ($exclude_names as $name) {
		($name!="") ? ($forbidden_strings[]=$name):null;
	}
	
	
	$stack = array();
	$file = genSitemap($priority, getlinks($website,$forbidden_types,$level,$forbidden_strings,$method,$maxcon),$freq,$website);
	writeXML($file,$sitemap_file,$option, $sitemap_url);
	if ($modifyrobots==1) modifyrobots($sitemap_url,$page_root);
}

function notify($option) {
	global $mainframe;
	$url = JRequest::getVar( 'url', 'none', 'POST', 'ARRAY', JREQUEST_ALLOWHTML );
	
	if ($url[0]!="none"){
		foreach ($url as $key) {
			if (JFile::read(urldecode($key))!=false){
				$mainframe->enqueueMessage( "Submission to ".parse_url($key, PHP_URL_HOST)." succeed " );
			} else {
				$errors[] = JError::getErrors();
				foreach ($errors as $error) {
					$mainframe->enqueueMessage($error->message,error);
				}
			
			}
		}
	}
	
	$mainframe->redirect('index2.php?option='.$option);
	
}

function updatecheck($option){

	define("jcrawler_version","1.4 Beta");
	
	 // erzeuge einen neuen cURL-Handle
	 $ch = curl_init();
	 
	 // setze die URL und andere Optionen
	 curl_setopt($ch, CURLOPT_URL, "http://www.pixelschieber.ch/version.php");
	 curl_setopt($ch, CURLOPT_HEADER, 0);
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 
	 // führe die Aktion aus und gebe die Daten an den Browser weiter
	 $content=curl_exec($ch);
	 
	 // schließe den cURL-Handle und gebe die Systemresourcen frei
	 curl_close($ch);
	 
	 if ($content==jcrawler_version){
	 	echo "<h2>Thank you for checking, you have the latest version (".jcrawler_version.")</h2>";
	 } else {
	 	echo "<h2>There is a new version (".$content.") available.</h2>Download version ".$content." now: <a href=\"http://joomlacode.org/gf/project/jcrawler/frs/\" target=\"_blank\">JCrawler ".$content."</a>";
	 }
	
	

}

	function modifyrobots ($sitemap_url,$page_root){
		global $mainframe;
		
		if (substr($page_root,-1)!="/") $page_root=$page_root."/";
		$robots=JFile::read( $page_root.'robots.txt' );
		//$pos=stripos("Sitemap:",$robots);
		$pos=preg_match("/Sitemap:/", $robots);
		
		if ($pos==0){	
			if (JFile::write( $page_root.'robots.txt',$robots."\n# BEGIN JCAWLER-XML-SITEMAP-COMPONENT\nSitemap: ".$sitemap_url."\n# END JCAWLER-XML-SITEMAP-COMPONENT" )!=false) {
				$mainframe->enqueueMessage( "robots.txt modified" );
			} else {
				$errors[] = JError::getErrors();
				foreach ($errors as $error) {
					$mainframe->enqueueMessage($error->message,error);
				}
				$mainframe->enqueueMessage("Your robots.txt is not writable!",error);
			}
		} else {
			$mainframe->enqueueMessage( "robots.txt contains already sitemap location" );
		}
	}


	function writeXML ($file, $location, $option, $sitemap_url) {
		global $mainframe;
			// Write $somecontent to our opened file.
		$buffer = pack("CCC",0xef,0xbb,0xbf);
		$buffer .= utf8_encode($file);
		if (JFile::write( $location, $buffer )){
		
			//print "Success, wrote the XML to file $location";
			$mainframe->enqueueMessage( "Success, wrote the XML to file $location" );
			HTML_jcrawler::showNotifyForm($option, $sitemap_url);
			
		} else {
			$errors[] = JError::getErrors();
			foreach ($errors as $error) {
				//echo $error->message;
				$mainframe->enqueueMessage($error->message,error);
			}
			$mainframe->enqueueMessage("your sitemap file is not writable, create an empty sitemap.xml and make a chmod 666 to the file",error);
			
		}
		//$mainframe->redirect('index2.php?option='.$option.'&task=notify', "Success, wrote the XML to file $location" );
		return;
	}
	

function getlinks($url,$forbidden_types,$level,$exclude_names,$method,$maxcon) {
	global $stack;
	is_array($url)?$arrurl=$url:$arrurl[]=$url;
	//(count($arrurl)<41)?$z=1:$z=count($arrurl);
	(count($arrurl)>$maxcon)?$z=$maxcon:$z=count($arrurl);
	

	$tmparr_last=array_merge($stack,getUrl(connect($arrurl,$z,$method),$forbidden_types,$exclude_names));
		
	if ($level==0)
		$stack=array_merge($stack,$tmparr_last);
		
	for ($u=0;$u<$level;$u++){
		(count($tmparr_last)>$maxcon)?$z=$maxcon:$z=count($tmparr_last);
		$tmparr=getUrl(connect($tmparr_last,$z,$method),$forbidden_types,$exclude_names);
		$tmparr_last=array_diff($tmparr,$tmparr_last);
		$stack=array_merge($stack,array_diff($tmparr,$stack));
	}
	
	$stack=array_unique($stack);
	return $stack;
}

function printprogress($url, $count){
	print "<script language='JavaScript' type='text/javascript'>
				<!--
  					var d = document.getElementById('statusinfo');
  					if (d) d.innerHTML = '<b>&nbsp;Lese: </b>".$url." &nbsp; <b>".$count."</b> Links gefunden. Noch zu durchsuchende Seiten <b>".$count."</b>';
				//-->
			</script>";

}


function genSitemap($priority, $urls, $freq, $document_root){
	
	$xml_string = '<?xml version=\'1.0\' encoding=\'UTF-8\'?><?xml-stylesheet type="text/xsl" href="'.$document_root.'/administrator/components/com_jcrawler/sitemap.xsl"?>
	<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	
	foreach ($urls as $loc){
		/* urf-8 encoding */
		//$loc=htmlentities($loc,ENT_QUOTES);
		$loc=htmlspecialchars($loc,ENT_QUOTES,'UTF-8');
		
		$modified_at = date('Y-m-d\Th:i:s\Z');
		$xml_string .= "
		<url>
		   <loc>$loc</loc>
		   <lastmod>$modified_at</lastmod>
		   <priority>$priority</priority>
		   <changefreq>$freq</changefreq>
		</url>";
	}
	
	$xml_string .= "
	</urlset>";
	
	return $xml_string;
}

// misc functions

function toArray($str, $delim = "\n") {
	$res = array();	
	$res = explode($delim, $str);
	
	for($i = 0; $i < count($res); $i++) {
		$res[$i] = trim($res[$i]);
	}
	
	return $res;
}
/* returns a string of all entries of array with delim */
function arrToString($array, $delim = "\n") {
  $res = "";
  if (is_array($array)) {
	for ($i = 0; $i < count($array); $i++) {
	  $res .= $array[$i];
	  if ($i < (count($array)-1)) $res .= $delim;
	}
   }
   return $res;
}

/* simple compare function: equals */
function ar_contains($key, $array) {
  if (is_array($array) && count($array) > 0) {
    foreach ($array as $val) {
	  	if ($key == $val) {
			return true;
		}
    }
  }
  return false;
}

/* better compare function: contains */
function fl_contains($key, $array) {
  if (is_array($array) && count($array) > 0) {
	foreach ($array as $val) {
	  $pos = strpos($key, $val);
	  if ($pos === FALSE) continue;
	  return true;
    }
  }

  return false;
}

/* this function changes a substring($old_offset) of each array element to $offset */
function changeOffset($array, $old_offset, $offset) {
  $res = array();
  if (is_array($array) && count($array) > 0) {
    foreach ($array as $val) {
      $res[] = str_replace($old_offset, $offset, $val);
    }
  }
  return $res;
}

	function parsebuffer($buffer){
	
		$suchmuster='<a href=("|\')(.*?)("|\')>';
		preg_match_all($suchmuster, $buffer, $treffer);
		preg_match('/Location:(.*?)\n/', $buffer, $matches);
	
		foreach($matches as $match){
			if(strpos($match,"Location:")===false) $treffer[2][]=trim($match);
		}

		$treffer[2]=array_unique($treffer[2]);
	
		return $treffer[2];
	
	}


	function checkcurl() {
		$modullist=parsePHPinfo();
		if ($modullist['curl']['cURL support'] == "enabled" or $modullist['curl']['CURL support'] == "enabled"){ 
			return true;
		} else {
			return false;
		}
	}
	
	function checkfopen() {
		$modullist=parsePHPinfo();
		if ($modullist['PHP Core']['allow_url_fopen'][0]=="On"){
			return true;
		} else {
			return false;
		}
	
	} 
	
	
	function parsePHPinfo() {
	 ob_start();
	phpinfo();
	$phpinfo = array('phpinfo' => array());
	if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
		foreach($matches as $match)
			if(strlen($match[1]))
				$phpinfo[$match[1]] = array();
			elseif(isset($match[3]))
				$phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
			else
				$phpinfo[end(array_keys($phpinfo))][] = $match[2];
				
		return $phpinfo;
	}    
	
	

function connect ($url,$z,$method){
	global $mainframe,$_POST;
	
	$ref = JRequest::getVar( 'http_host', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$buffer= array();
	$str  = array(
		"Accept-Language: en-us,en;q=0.5",
		"Accept-Charset: utf-8;q=0.7,*;q=0.7",
		"Keep-Alive: 300",
		"Connection: keep-alive");
	
		if (function_exists('curl_init') and function_exists('curl_multi_init') and $method=="curl") {
	
		if(count($url)!=0){
			$k=ceil(count($url)/$z);
			$urls=array_chunk($url, ceil(count($url) / $k),true);
		}else {
			$k=0;
		}
		for ($i=0;$i<$k;$i++){	
			//(($i+$z)>count($url))?$z=($i+$z)-count($url):null;
	
			foreach ($urls[$i] as $key => $urlentry){
				$ch[$key] = curl_init($urlentry);
				curl_setopt($ch[$key], CURLOPT_HEADER, true);
				curl_setopt($ch[$key], CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch[$key], CURLOPT_REFERER, $ref);
				curl_setopt($ch[$key], CURLOPT_HTTPHEADER, $str);
			}
			$mh = curl_multi_init();
			
			foreach ($urls[$i] as $key => $urlentry){
				curl_multi_add_handle($mh,$ch[$key]);
			}
			

			$running=null;
			//execute the handles
			do {
				curl_multi_exec($mh,$running);
			} while ($running > 0);
			
			
			foreach ($urls[$i] as $key => $urlentry){ 
				$http_code=curl_getinfo($ch[$key], CURLINFO_HTTP_CODE);
				if ($http_code>=400) {
					 $mainframe->enqueueMessage("httpcode: ".$http_code." on url ".$urlentry." make sure this url is availabe to the crawler" ,error);
					 
				 } else {
					$buffer = array_unique(array_merge($buffer,parsebuffer(curl_multi_getcontent ($ch[$key]))));
				 }
			}
			
			foreach ($urls[$i] as $key => $urlentry){ 
				curl_multi_remove_handle($mh,$ch[$key]);
			}
			curl_multi_close($mh);
			
		}
		
	} elseif (function_exists('fopen')  and $method=="fopen"){
	
		foreach ($url as $key) {
			
			$handle = @fopen ($key, "r");
			
			$return_code = @explode(' ', $http_response_header[0]);
    		$return_code = (int)$return_code[1];
			
			if ($return_code>=400) {
				 $mainframe->enqueueMessage($http_response_header[0]." on url ".$key." make sure this url is availabe to the crawler" ,error);
			} else {
				while (!feof($handle)) {
					$buffer = array_unique(array_merge($buffer,parsebuffer(fgets($handle))));
				}
			}
			@fclose($handle);
		}
		
	} elseif (function_exists(file_get_contents)) {
		foreach ($url as $key) {
			$buffer = array_unique(array_merge($buffer,parsebuffer(file_get_contents($key))));
		}
	} else {
		$mainframe->enqueueMessage("You need curl or fopen, neither of them were available",error);
	}
	
	return array_unique($buffer);

}


/* this walks recursivly through all directories starting at page_root and
   adds all files that fits the filter criterias */
// taken from Lasse Dalegaard, http://php.net/opendir
function getUrl($buffer,$forbidden_types,$forbidden_strings) {
	global $_POST, $stack;
	$website = JRequest::getVar( 'http_host', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$web=parse_url($website);
	(strtolower(substr($web['host'],0,4))=="www.")?$web['host']=substr($web['host'],4):null;
       // Create an array for all files found
       $tmp = Array();
	
	if(substr($website,-1)=="/") $website=substr($website,0,-1);
	if(substr($web['path'],-1)=="/") $web['path']=substr($web['path'],0,-1);
	//$epos=0; $i=0;
	//$pattern="<a href";
	$tmparray=array();
	
		foreach ($buffer as $key) {
			if(substr($key,0,4)!="http" && substr($key,0,5)!="https"){
				if(substr($key,0,1)!="/"){
					$key="/".$key;
				}
				($web['path']!="" and $web['path']!="/")?$key=substr($website,0,strpos($website,$web['path'])).$key:$key=$website.$key;
			}
		
			if(!in_array($key,$tmparray) && !in_array($key,$stack) && strpos($key,$web['host'])!==false && fl_contains($key, $forbidden_strings)===false && in_array(substr(strtolower($key),strrpos($key,".")),$forbidden_types)===false){
				$key=trim(str_replace("&amp;","&",$key));
				$tmparray[]=$key;
				//$stack[]=$key;
			}
		} //endforeach
	
	return $tmparray;
}

?>