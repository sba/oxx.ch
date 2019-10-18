<?php
/**
* @version $Id $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from the
* GNU General Public License or other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
 */
/**
 * plugin_googlemap2.php,v 2.10 2007/11/19 13:34:11
 * @copyright (C) Reumer.net
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 /* ----------------------------------------------------------------
 * 2007-04-14 version 2.11: Improved by Mike Reumer
 * - The following new possibilties are added
 *   - Adsmanager
 *   - Local Search
 *   - Googlebar
 *   - Traffic overlay
 * - Added panoramio pictures
 * - Set caption and zoom for lightbox and keep the center of the map the same
 * - Extra parameters centerlat and centerlon for different center then map
 * - If encoding can't be detected it will not be converted. In geocoding function
 *   Warning: mb_convert_encoding() [function.mb-convert-encoding]: Illegal character encoding specified  
 /* ----------------------------------------------------------------
 * 2007-09-24 version 2.10: Improved by Mike Reumer
 * - The kml is only positioned if no coordinates are entered.<br />
 *   - Removed condition text=''
 * - Made it possible to call the plugin as a module
 *   - The custumAddHeadtags doesn't work in modules!
 * - Removed notice when xml of gecode doesn't deliver coordinates
 *   - use of xpath and remove of xmlns attributes!
 * - Possible to use a effect on Map.
 *   - Get Mootools script
 *   - If effect then call mootool functions
 * - Possible to show the map in a lightbox/modalbox
 *   - Get Moodalbox hack script
 *   - Extra parameter to place button/link
 *   - Parameter for the text of button/link
 * - Clear debug text after replaceing mosmap tag
 * - Extra parameter to select a version of the plugin
 * - Possibility to use joomfish as language selector
 * - Extra parameter for url to a icon
 * - Solved problem that calling in hack other plugins that frontend 
 *   editor breaks
 * 	 - Add extra event onMap so only the Google Maps plugin is called
 * - Directory of plugin in J15 is different then J10x.
 * - Improved lightbox:
 *   - Beter close an reopen
 *   - Possible to make lightbox bigger
 * - The overview won't open correctly. New timer for opening overview
 * - htmlspecialchars_decode breaks other extensions 
 *   - Replace by correct implementatiom
 *   - Placed all functions and variables in a class to hide it from other programs
 * - A variable joomla_version wasn't defined correctly.
 * - Changed injectCustomHeadTags so Joomla 1.5 can add scripts in header
 *   - Added extra parameter for the bodytext.
 *   - Removed check for Joomla 1.5 so if header is already done the replace is possible
 * - Multiple domain based on PHP variable instead of Joomla because 1.5 doesn't have it.
 *   Now there is no configuration change necessary for Joomla.
 /* ----------------------------------------------------------------
 * 2007-09-22 version 2.9: Improved by Mike Reumer
 * - #6022: strip <br> etc out of address for geocoding.
 * - #6023: Center and zoom the map based on the kml-file
 *   - If coordinates are entered center and zoom the map based on these coordinates
 *   - If no coordinates are entered center and zoom the map based on the kml-file
 *   - moved KML-file actions from end to middle of code.
 * - #6024: Show direction form when no text
 *   - If dir=1 make always a infowindow with the directions form
 * - #6025: Polylines don't show in Opera.
 *   - Added lines: var _mSvgForced = true; var _mSvgEnabled = true; when browser is Opera!
 * - #6037: Labels of directions form couln't be changed.
 *   - Names of parameters didn't match.
 * - #6131: Solved problems with PHP5 and opening files and wrong coding of xml.
 * - #6470: Added debug mechanisme for debugging options.
 * - #6471: If server side geocoding fails do it at the client side.
 * - #6472: Only remove quotes that surround the content of a parameter and change double qoutes to single quotes in text
 *   So HTML is better resolved for generated bu EasyTube plugin and others.
 * - #6637: The direction form doesn't have a css-class.
 *   - Gave the direction form a class for css-styling.
 *   - Place the direction form within the first pair of div before the closing div
 * - #7055: The replacement of the mosmap code isn't done correctly step by step.
 *   - replace str_replace with preg_replace for 1 item.
 * - #7132: Placed kml-file also in overviewmap.
 *   - Created a second xml variable for the kml-file for the overview map.
 /* ----------------------------------------------------------------
 * 2007-03-24 version 2.8: Improved by Mike Reumer and Arjan Menger of WELLdotCOM (www.welldotcom.nl) with donation
 * - artf6173: wheel-mouse zooming
 *   - Problem with multiple maps solved by naming function unique.
 *   - Problem with scroll wheel moves page too by adding cancelevent
 * - artf7734: Load kml overlay out of file
 *   - New parameter for KML-overlay
 * - #4494: Add buttons for driving directions
 * - #5274 PHP problem URL file-access is disabled in the server configuration
 * ---------------------------------------------------------------- */
/* ----------------------------------------------------------------
 * 2007-02-10 version 2.7: Improved by Keith Slater and Mike Reumer
 * - artf7666: Check if javascript is enabled or browser is compatible.
 * - artf7564: Multiple urls
 *   - Added the option to get the single key or search in the multiple url's for a key.
 * - artf6182: Localization
 *   - Get language from the site itself
 *	 - Get language as parameter from the {mosmap}
 *   - Set language if available as parameter or setting
 * ---------------------------------------------------------------- */
/* ----------------------------------------------------------------
 * 2006-12-11 version 2.6: Improved by Eugene Trotsan and Mike Reumer
 * - artf7020: Extra parameter for address.
 *	 - Get the coordinates of the address at google when parameter lon/lat are empty.
 *   - Problem with SimpleXMLElement PHP >= 5
 * - artf6293: Tool tips
 *   - New parameter for tooltip 
 * - artf6995: Turn off overview
 *   - A new value for overview. 2 for overview window to be closed initially.
 * - artf6294 : Turn off infowindow of marker
 *   - New parameter to set infowindow initially closed (0) or open (1 default)
 * - artf6996: Alignment of the map
 *   - New parameter align for the map.
 * ---------------------------------------------------------------- */
/* ----------------------------------------------------------------
 * 2006-10-27 version 2.5: Improved by Mike Reumer
 * - artf6794: Multiple contentitems with maps won't work
 *   - Placed a random text in the name of the googlemap and it's functions.
 * - artf6758: Warning: Wrong value for parameter 4 in call to preg_match_all()
 *   - PREG_OFFSET_CAPTURE has to be combined with PREG_PATTERN_ORDER
 * - artf6755 Call-time pass-by-reference has been deprecated
 *   - Removed & in the call of functions
 * - artf6756 : Warning about variable not defined
 *   - Correctly defined a global parameter
 * ---------------------------------------------------------------- */
/* ----------------------------------------------------------------
 * 2006-10-13 version 2.4: Improved by Mike Reumer
 * - artf6402: Googlemap plugin with tabs not working
 *   - Added a function to look if the offsetposition is changed
 *   - Only make a map when its visible on the page
 *   - Changed event for displaying map in interval for checking if map is visible
 *   - Made important variable in scripts dedicated to the number of the map
 * - artf6456 : Placing defaults of parameters in backoffice
 *   - Created the possibility to set parameters for the plugin in the 
 *     administator of Joomla.
 * - artf6409: Joomla 1.5 support
 *   - Plugin made ready for Joomla 1.5 with configparameter legacy on!
 *   - Calls for Joomla 1.0.x and for Joomla 1.5 created with correct params
 *   - Use a plugin parameter for Joomla 1.5 if plugin is published or not
 * ---------------------------------------------------------------- */
/* ----------------------------------------------------------------
 * 2006-10-02 version 2.3: Improved by Mike Reumer
 * - artf6183: Links not working in Marker
 *   - changed chopping of key and value and translate special htmlcodes
 * - artf6249: Overview initial not the same maptype as map
 *   - changed order of creating controls and setting maptype
 * - artf6280: In IE a big wrong shadow for Marker
 *   - API initialization with wrong version. Removed ".x"
 * ---------------------------------------------------------------- */
/* ----------------------------------------------------------------
 * 2006-09-27 version 2.2: Improved by Mike Reumer
 * - artf6122 Parameters width and height flexible
 *   - Removed px behind width and height
 *   - Changed defaults for width and height parameters
 *   - Check for backward compatibility if units are given
 * - artf6148 Option to turn off the map type selector visibility
 *   - If zoomType is None then no Zoomcontrols (default empty => Small zoomcontrols).
 *   - If showMaptype is 0 then no Maptype controls
 * - artf6176 : Remove mosmap tag if unpublished
 *   - Moved Published within the plugin to remove all the tags
 * - artf6174 : Multiple maps on article w/ {mospagebreak}'s
 *   - Replaced Google maps initialization to the header
 * - Moved default so they are set each {mosmap} 
 * - Settimeout higher for activating to show googlemap (for IE compatibility)
 * - New parameter zoom_new for continues zoom and Doubleclick center and zoom (default 0 => off)
 * - New parameter overview for a overview window at bottom right (default 0 => off)
 * - Scripts made XHTML compliant
 * - artf6150 Documentation with installation
 * ---------------------------------------------------------------- */
/* ----------------------------------------------------------------
 * 2006-09-21: Improved by PILLWAX Industrial Solutions Consulting
 *	- Fixed Script invocation from <body onLoad> to correct JavaScript call
 *   - Add Defaults for parameters
 * ---------------------------------------------------------------- */

/** ensure this file is being included by a parent file */

global $mainframe;

if(method_exists($mainframe,'registerEvent')){
	defined( '_JEXEC' ) or die( 'Restricted access' );
	$mainframe->registerEvent( 'onPrepareContent', 'Pre15x_PluginGoogleMap2' );
	$mainframe->registerEvent( 'onMap', 'Pre15x_PluginGoogleMap2' );
}else{
	defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
	$_MAMBOTS->registerFunction( 'onPrepareContent', 'Pre10x_PluginGoogleMap2' );
	$_MAMBOTS->registerFunction( 'onMap', 'Pre10xonMap_PluginGoogleMap2' );
}

/* Switch call to function of 1.5 to the real module 
 */
function Pre15x_PluginGoogleMap2( &$row, &$params, $page=0 ) {
	$database = &JFactory::getDBO();

	// Get Plugin info
	$plugin =& JPluginHelper::getPlugin('content', 'plugin_googlemap2'); 

	$plugin_params = new JParameter( $plugin->params );
	$joomla_version = 1.5;

	//$published = $plugin->published;
	// Solve bug in Joomal 1.5 that when plugin is unpublished that the tag is not removed
	// So use a parameter of plugin to set published for Joomla 1.5
	$published = $plugin_params->get( 'publ', '0' );
	$id = intval( JRequest::getVar('id', null) );	
	$id = explode(":", $id);
	$id = $id[0];
	$pluginmap = new PluginGoogleMap2();

	if( !$pluginmap->core($published, $row, $params, $page, $plugin_params, $id, $joomla_version) ){
		echo "problem";
	}
	return true;
}

/* Switch call to function of 1.0.x to the real module
 */
function Pre10x_PluginGoogleMap2( $published, &$row, $mask=0, $page=0 ) {
	global $database;

	// load plugin parameters
	$query = "SELECT id"
		. "\n FROM #__mambots"
		. "\n WHERE element = 'plugin_googlemap2'"
		. "\n AND folder = 'content'"
		;
	$database->setQuery( $query );
	$id = $database->loadResult();
	$plugin = new mosMambot( $database );
	$plugin->load( $id );
	$plugin_params =& new mosParameters( $plugin->params );
	$joomla_version = 1.0;

	$id = intval( mosGetParam( $_REQUEST, 'id', null ) );

	$pluginmap = new PluginGoogleMap2();
	
	if( !$pluginmap->core($published, $row, $mask, $page, $plugin_params, $id, $joomla_version) ){
		echo "problem";
	}
	return true;
}

function Pre10xonMap_PluginGoogleMap2( $published, &$row, $mask=0, $page=0 ) {
	global $database;

	// load plugin parameters
	$query = "SELECT id"
		. "\n FROM #__mambots"
		. "\n WHERE element = 'plugin_googlemap2'"
		. "\n AND folder = 'content'"
		;
	$database->setQuery( $query );
	$id = $database->loadResult();
	$plugin = new mosMambot( $database );
	$plugin->load( $id );
	$plugin_params =& new mosParameters( $plugin->params );
	$joomla_version = 1.0;

	$id = intval( mosGetParam( $_REQUEST, 'id', null ) );

	$pluginmap = new PluginGoogleMap2();
	$pluginmap->event = '10xonMap';

	if( !$pluginmap->core($published, $row, $mask, $page, $plugin_params, $id, $joomla_version) ){
		echo "problem";
	}
	return true;
}

class PluginGoogleMap2 {
	var $debug_plugin = '0';
	var $debug_text = '';
	var $event = '';

	/* If PHP < 5 then htmlspecialchars_decode doesn't exists
	 */
	
	function _htsdecode($string, $options=0) {
		if (function_exists('htmlspecialchars_decode')) {
			return htmlspecialchars_decode($string, $options);
		} else {
			return strtr($string,array_flip(get_html_translation_table(HTML_SPECIALCHARS, $options)));
		}
	}
	
	function debug_log($text)
	{
		if ($this->debug_plugin =='1')
			$this->debug_text .= "\n// ".$text;
	
		return;
	}
	
	function injectCustomHeadTags($html, $check, &$row) {
		global $mainframe;

		// Get buffer
		// Is there a difference between J15/J10
		$buf = &$row;
		if (!function_exists('jimport')) {
			// version 1.0.x
			$screen = ob_get_contents();
			$header = $mainframe->getHead();
		} else {
			$screen = '';
			$header = '';
			$header = $mainframe->getHead();
		}
			
		// Check if code already is inserted?
		$check = str_replace("/", "\/",$check);
		$check = str_replace(".", "\.",$check);
		$check = str_replace("?", "\?",$check);
		$check = "/".$check."/is";
		$chk = preg_match($check, $buf) + preg_match($check, $screen) + preg_match($check, $header);
		if ($chk==0) {
			// Check for head
			$head = preg_match("/<head>/is", $buf);
			$hd = preg_match("/<head>/is", $screen);
			// if no head do mainframe replace
			if ($head==0) {
				// With Joomla 10x onMap add header doesn't work
				if ($hd==0) {
					$this->debug_log("Mainframe header replace");
					$mainframe->addCustomHeadTag($html);
				}
				else {
					$this->debug_log("With Joomla 10x onMap add header doesn't work and header not available so place it in body");
					echo $html;
				}
			} else {
				// if head then place in head the scripts
				if (version_compare(phpversion(), '5.0') <0) {
					$this->debug_log("php4 header replace");
					$buf = preg_replace("/<head(| .*?)>(.*?)<\/head>/is", "<head$1>$2".$html."</head>", $buf);						
				} else {
					$this->debug_log("php5 header replace");
					$buf = preg_replace("/<head(| .*?)>(.*?)<\/head>/is", "<head$1>$2".$html."</head>", $buf, 1, $count);
				}
			}
		} else
			$this->debug_log("No replace script already available");
	}
	
	/* If PHP < 5 then SimpleXMLElement doesn't exists
	 */
	function get_geo($address, $key)
	{
		$this->debug_log("get_geo(".$address.")");
	
		$coords = '';
		$getpage='';
		$replace = array("\n", "\r", "&lt;br/&gt;", "&lt;br /&gt;", "&lt;br&gt;", "<br>", "<br />", "<br/>");
		$address = str_replace($replace, '', $address);
	
		$this->debug_log("Address: ".$address);
		
		$uri = "http://maps.google.com/maps/geo?q=".urlencode($address)."&output=xml&key=".$key;
		$this->debug_log("get_geo(".$uri.")");
		
		if ( !class_exists('SimpleXMLElement') )
		{
			// PHP4
			$ok = false;
			$this->debug_log("SimpleXMLElement doesn't exists so probably PHP 4.x");
			if (ini_get('allow_url_fopen'))
				if (($getpage = file_get_contents($uri)))
					$ok = true;

			if (!$ok) {
				$this->debug_log("URI couldn't be opened probably ALLOW_URL_FOPEN off");
				if (function_exists('curl_init')) {
					$this->debug_log("curl_init does exists");
					$ch = curl_init();
					$timeout = 5; // set to zero for no timeout
					curl_setopt ($ch, CURLOPT_URL, $uri);
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
					$getpage = curl_exec($ch);
					curl_close($ch);
				} else
					$this->debug_log("curl_init doesn't exists");
			}
	
			$this->debug_log("Returned page: ".$getpage);
	
			if (function_exists('mb_detect_encoding')) {
				$enc = mb_detect_encoding($getpage);
				if (!empty($enc))
					$getpage = mb_convert_encoding($getpage, 'UTF-8', $enc);
			}
				
			if (function_exists('domxml_open_mem')&&($getpage<>'')) {
				$responsedoc = domxml_open_mem($getpage);
				$response = $responsedoc->get_elements_by_tagname("Response");
				if ($response!=null) {
					$placemark = $response[0]->get_elements_by_tagname("Placemark");
					if ($placemark!=null) {
						$point = $placemark[0]->get_elements_by_tagname("Point");
						if ($point!=null) {
							$coords = $point[0]->get_content();
							$this->debug_log("Coordinates: ".join(", ", explode(",", $coords)));
							return $coords;
						}
					}
				}
			}
			$this->debug_log("Coordinates: null");
			return null;
		}
		else
		{
			// PHP5
			$this->debug_log("SimpleXMLElement does exists so probably PHP 5.x");
			$ok = false;
			if (ini_get('allow_url_fopen')) { 
				if (file_exists($uri)) {
					$getpage = file_get_contents($uri);
					$ok = true;
				}
			} 
			
			if (!$ok) { 
				$this->debug_log("URI couldn't be opened probably ALLOW_URL_FOPEN off");
				if (function_exists('curl_init')) {
					$this->debug_log("curl_init does exists");
					$ch = curl_init();
					$timeout = 5; // set to zero for no timeout
					curl_setopt ($ch, CURLOPT_URL, $uri);
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
					$getpage = curl_exec($ch);
					curl_close($ch);
				} else
					$this->debug_log("curl_init doesn't exists");
			}
	
			$this->debug_log("Returned page: ".$getpage);
			if (function_exists('mb_detect_encoding')) {
				$enc = mb_detect_encoding($getpage);
				if (!empty($enc))
					$getpage = mb_convert_encoding($getpage, 'UTF-8', $enc);
			}
	
			if ($getpage <>'') {
				$expr = '/xmlns/';
				$getpage = preg_replace($expr, 'id', $getpage);
				$xml = new SimpleXMLElement($getpage);
				foreach($xml->xpath('//coordinates') as $coordinates) {
					$coords = $coordinates;
					break;
				}
				if ($coords=='') {
					$this->debug_log("Coordinates: null");
					return null;
				}
				$this->debug_log("Coordinates: ".join(", ", explode(",", $coords)));
				return $coords;
			}
		}
		$this->debug_log("get_geo totally wrong end!");
	}
	
	function randomkeys($length)
	{
		$key = "";
		$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
		for($i=0;$i<$length;$i++)
		{
			$key .= $pattern{rand(0,35)};
		}
		return $key;
	}
	
	function translate($orgtext, $lang) {
		$langtexts = preg_split("/[\n\r]+/", $orgtext);
		$text = "";
		$replace = array("\n", "\r", "<br/>", "<br />", "<br>");
	
		foreach($langtexts as $langtext)
		{
			$values = explode(";",$langtext, 2);
			$values[0] = trim(str_replace($replace, '', $values[0]));
	
			if (trim($lang)==$values[0])
			{
				$text = $values[1];
				break;
			}
		}
	
		if ($text=="")
			$text = $orgtext;
	
		$text = $this->_htsdecode($text, ENT_NOQUOTES);
	
		return $text;
	}
	/** Real module
	 */
	function core( $published, &$row, $mask=0, $page=0, &$params, $id, $joomla_version=1.0 ) {
		global $mainframe, $mosConfig_locale;
		global $iso_client_lang; // This is a global of Joomfish!
		
		if ($joomla_version< 1.5) {
			global $mosConfig_live_site, $mosConfig_locale, $iso_client_lang;
			$plugin_path = "mambots";
		} else {
			$mosConfig_live_site = JURI::base();
			$lang =& JFactory::getLanguage();
			$mosConfig_locale = $lang->getTag();
			$plugin_path = "plugins";
		}

		// get the parameter on what code should plugin trigger!
		$plugincode = $params->get( 'plugincode', 'mosmap' );
	
		$singleregex='/({'.$plugincode.'\s*)(.*?)(})/si';
		$regex='/{'.$plugincode.'\s*.*?}/si';
	
		$cnt=preg_match_all($regex,$row->text,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);
		$first=true;
		$first_mootools=true;
		$first_modalbox=true;
		$first_localsearch=true;
		$first_panoramio=true;
	
		for($counter = 0; $counter < $cnt; $counter ++)
		{
			// Parameters can get the default from the plugin if not empty or from the administrator part of the plugin
			$this->debug_plugin = $params->get( 'debug', '0' );
			$google_API_version = $params->get( 'Google_API_version', '2.x' );
			$width = $params->get( 'width', '100%' );
			$height = $params->get( 'height', '400px' );
			$latitude = $params->get( 'lat', '52.075581' );
			$deflatitude = $latitude;
			$longitude = $params->get( 'lon', '4.541513' );
			$deflongitude = $longitude;
			$centerlat = $params->get( 'centerlat', '' );
			$centerlon = $params->get( 'centerlon', '' );
			$address = $params->get( 'address', '' );
			$zoom = $params->get( 'zoom', '10' );
			$showmaptype = $params->get( 'showMaptype', '1' );
			$zoom_new = $params->get( 'zoomNew', '0' );
			$zoom_wheel = $params->get( 'zoomWheel', '0' );
			$overview = $params->get( 'overview', '0' );
			$dragging = $params->get( 'dragging', '1' );
			$marker = $params->get( 'marker', '1' );
			$icon = $params->get( 'icon', '' );
			$iconwidth = $params->get( 'iconwidth', '' );
			$iconheight = $params->get( 'iconheight', '' );
			$iconshadow = $params->get( 'iconshadow', '' );
			$iconshadowwidth = $params->get( 'iconshadowwidth', '' );
			$iconshadowheight = $params->get( 'iconshadowheight', '' );
			$iconshadowanchorx = $params->get( 'iconshadowanchorx', '' );
			$iconshadowanchory = $params->get( 'iconshadowanchory', '' );
			$iconanchorx = $params->get( 'iconanchorx', '' );
			$iconanchory = $params->get( 'iconanchory', '' );
			$iconinfoanchorx = $params->get( 'iconinfoanchorx', '' );
			$iconinfoanchory = $params->get( 'iconinfoanchory', '' );
			$icontransparent = $params->get( 'icontransparent', '' );
			$iconimagemap = $params->get( 'iconimagemap', '' );
			$gotoaddr = $params->get( 'gotoaddr', '0' );
			$erraddr = $params->get( 'erraddr', 'Address ## not found!' );
			$txtaddr = $params->get( 'txtaddr', 'Address: <br />##' );
			$align = $params->get( 'align', 'center' );
			$langtype = $params->get( 'langtype', '' );
			$dir = $params->get( 'dir', '0' );
			$traffic = $params->get( 'traffic', '0' );
			$panoramio = $params->get( 'panoramio', '0' );
			$adsmanager = $params->get( 'adsmanager', '0' );
			$maxads = $params->get( 'maxads', '3' );
			$localsearch = $params->get( 'localsearch', '0' );
			$adsense = $params->get( 'adsense', '' );
			$channel = $params->get( 'channel', '' );
			$googlebar = $params->get( 'googlebar', '0' );
			$searchlist = $params->get( 'searchlist', '0' );
			$searchtarget = $params->get( 'searchtarget', '0' );
			$searchzoompan = $params->get( 'searchzoompan', '1' );
			$txt_get_dir = $params->get( 'txtgetdir', 'Get Directions' );
			$txt_from = $params->get( 'txtfrom', 'From here' );
			$txt_to = $params->get( 'txtto', 'To here' );
			$txt_diraddr = $params->get( 'txtdiraddr', 'Address: ' );
			$txt_dir = $params->get( 'txtdir', 'Directions: ' );
			$lightbox = $params->get( 'lightbox', '0' );
			$lbxwidth = $params->get( 'lbxwidth', '100%' );
			$lbxheight = $params->get( 'lbxheight', '700px' );
			$txtlightbox = $params->get( 'txtlightbox', '0' );
			$lbxcaption =  $params->get( 'lbxcaption', '' );
			$lbxzoom =  $params->get( 'lbxzoom', '' );
			$effect = $params->get( 'effect', 'none' );
	
			// Key should be filled in the administrtor part or as parameter with the plugin out of content item
			$key = trim($params->get( 'Google_API_key', '' ));
			$this->debug_log("key: ".$key);
			$this->debug_log("mosConfig_live_site: ".$mosConfig_live_site);
			if ($key=='')
			{
				$multikey = trim($params->get( 'Google_Multi_API_key', '' ));
				if ($multikey!='') {
					$this->debug_log("multikey: ".$multikey);
					$this->debug_log("HTTP_HOST: ".$_SERVER['HTTP_HOST']);
					$replace = array("\n", "\r", "<br/>", "<br />", "<br>");
					$sites = preg_split("/[\n\r]+/", $multikey);
					foreach($sites as $site)
					{
						$values = explode(";",$site, 2);
						$values[0] = trim(str_replace($replace, '', $values[0]));
						$values[1] = str_replace($replace, '', $values[1]);
						$this->debug_log("values[0]: ".$values[0]);
						$this->debug_log("values[1]: ".$values[1]);
						if (trim($_SERVER['HTTP_HOST'])==$values[0] ||$_SERVER['HTTP_HOST']=="http://".$values[0]) 
						{
							$key = trim($values[1]);
							break;
						}
					}
				}
				$this->debug_log("key: ".$key);
			}
	
			// get default lang from $mosConfig_locale
			$this->debug_log("langtype: ".$langtype);
			$this->debug_log("mosConfig_locale: ".$mosConfig_locale);
			$this->debug_log("iso_client_lang: ".$iso_client_lang);
		
			if ($langtype == 'site') 
			{
				if ($joomla_version< 1.5) 
					$locale_parts = explode('_', $mosConfig_locale);
				else
					$locale_parts = explode('-', $mosConfig_locale);
				$lang = $locale_parts[0];
				
			} else if ($langtype == 'config') 
			{
				$lang = $params->get( 'lang', '' );
			} else if ($langtype == 'joomfish')
			{
				$lang = $iso_client_lang;
			} else {
				$lang = '';
			} 
	
			$this->debug_log("lang : ".$lang);
			
			//Translate parameters
			$erraddr = $this->translate($erraddr, $lang);
			$txtaddr = $this->translate($txtaddr, $lang);
			$txtaddr = str_replace(array("\r\n", "\r", "\n"), '', $txtaddr );
			$txt_get_dir = $this->translate($txt_get_dir, $lang);
			$txt_from = $this->translate($txt_from, $lang);
			$txt_to = $this->translate($txt_to, $lang);
			$txt_diraddr = $this->translate($txt_diraddr, $lang);
			$txt_dir = $this->translate($txt_dir, $lang);
			$txtlightbox = $this->translate($txtlightbox, $lang);
	
			// Next parameters can be set as default out of the administrtor module or stay empty and the plugin-code decides the default. 
			$zoomType = $params->get( 'zoomType', '' );
			$mapType = $params->get( 'mapType', '' );
	
			// default empty and should be filled as a parameter with the plugin out of the content item
			$code='';
			
			$mapname='';
			$mapclass='';
			$tolat='';
			$tolon='';
			$toaddress='';
			$text='';
			$tooltip='';
			$kml='';
			$client_geo = 0;
			$show = 1;
			$imageurl='';
			$imagex='';
			$imagey='';
			$imagexyunits='';
			$imagewidth='';
			$imageheight='';
			$imageanchorx='';
			$imageanchory='';
			$imageanchorunits='';
			$searchtext='';

			// Give the map a random name so it won't interfere with another map
			$mapnm = $id."_".$this->randomkeys(5)."_".$counter;
	
			$mosmap=$matches[0][$counter][0];
	
			if (!$published )
			{
				$row->text = str_replace($mosmap, $code, $row->text);
			} else
			{
				//track if coordinates different from config
				$inline_coords = 0;
				$inline_tocoords = 0;
	
				// Match the field details to build the html
				preg_match($singleregex,$mosmap,$mosmapparsed);
	
				$fields = explode("|", $mosmapparsed[2]);

				foreach($fields as $value)
				{
					$value=trim($value);
					$values = explode("=",$value, 2);
					$values=preg_replace("/^'/", '', $values);
					$values=preg_replace("/'$/", '', $values);
					$values=preg_replace("/^&#39;/",'',$values);
					$values=preg_replace("/&#39;$/",'',$values);
	
					if($values[0]=='debug'){
						$this->debug_plugin=$values[1];
					}else if($values[0]=='mapname'){
						$mapname=$values[1];
					}else if($values[0]=='mapclass'){
						$mapclass=$values[1];
					}else if($values[0]=='width'){
						$width=$values[1];
					}else if($values[0]=='height'){
						$height=$values[1];
					}else if($values[0]=='lat'){
						$latitude=$values[1];
						$inline_coords = 1;
					}else if($values[0]=='lon'){
						$longitude=$values[1];
						$inline_coords = 1;
					}else if($values[0]=='centerlat'){
						$centerlat=$values[1];
						$inline_coords = 1;
					}else if($values[0]=='centerlon'){
						$centerlon=$values[1];
						$inline_coords = 1;
					}else if($values[0]=='tolat'){
						$tolat=$values[1];
						$inline_tocoords = 1;
					}else if($values[0]=='tolon'){
						$tolon=$values[1];
						$inline_tocoords = 1;
					}else if($values[0]=='zoom'){
						$zoom=$values[1];
					}else if($values[0]=='key'){
						$key=$values[1];
					}else if($values[0]=='zoomType'){
						$zoomType=$values[1];
					}else if($values[0]=='text'){
						$text=trim($values[1]);
						$text=str_replace("\"",'\'', $text);
					}else if($values[0]=='tooltip'){
						$tooltip=trim($values[1]);
					}else if($values[0]=='mapType'){
						$mapType=$values[1];
					}else if($values[0]=='showMaptype'){
						$showmaptype=$values[1];
					}else if($values[0]=='zoomNew'){
						$zoom_new=$values[1];
					}else if($values[0]=='zoomWheel'){
						$zoom_wheel=$values[1];
					}else if($values[0]=='overview'){
						$overview=$values[1];
					}else if($values[0]=='dragging'){
						$dragging=$values[1];
					}else if($values[0]=='marker'){
						$marker=$values[1];
					}else if($values[0]=='icon'){
						$icon=$values[1];
					}else if($values[0]=='iconwidth'){
						$iconwidth=$values[1];
					}else if($values[0]=='iconheight'){
						$iconheight=$values[1];
					}else if($values[0]=='iconshadow'){
						$iconshadow=$values[1];
					}else if($values[0]=='iconshadowwidth'){
						$iconshadowwidth=$values[1];
					}else if($values[0]=='iconshadowheight'){
						$iconshadowheight=$values[1];
					}else if($values[0]=='iconshadowanchorx'){
						$iconshadowanchorx=$values[1];
					}else if($values[0]=='iconshadowanchory'){
						$iconshadowanchory=$values[1];
					}else if($values[0]=='iconanchorx'){
						$iconanchorx=$values[1];
					}else if($values[0]=='iconanchory'){
						$iconanchory=$values[1];
					}else if($values[0]=='iconinfoanchorx'){
						$iconinfoanchorx=$values[1];
					}else if($values[0]=='iconinfoanchory'){
						$iconinfoanchory=$values[1];
					}else if($values[0]=='icontransparent'){
						$icontransparent=$values[1];
					}else if($values[0]=='iconimagemap'){
						$iconimagemap=$values[1];
					}else if($values[0]=='address'){
						$address=trim($values[1]);
					}else if($values[0]=='toaddress'){
						$toaddress=trim($values[1]);
					}else if($values[0]=='gotoaddr'){
						$gotoaddr=$values[1];
					}else if($values[0]=='align'){
						$align=$values[1];
					}else if($values[0]=='lang'){
						$lang=$values[1];
					}else if($values[0]=='kml'){
						$kml=$values[1];
					}else if($values[0]=='traffic'){
						$traffic=$values[1];
					}else if($values[0]=='panoramio'){
						$panoramio=$values[1];
					}else if($values[0]=='adsmanager'){
						$adsmanager=$values[1];
					}else if($values[0]=='maxads'){
						$maxads=$values[1];
					}else if($values[0]=='localsearch'){
						$localsearch=$values[1];
					}else if($values[0]=='adsense'){
						$adsense=$values[1];
					}else if($values[0]=='channel'){
						$channel=$values[1];
					}else if($values[0]=='googlebar'){
						$googlebar=$values[1];
					}else if($values[0]=='searchtext'){
						$searchtext=$values[1];
					}else if($values[0]=='searchlist'){
						$searchlist=$values[1];
					}else if($values[0]=='searchtarget'){
						$searchtarget=$values[1];
					}else if($values[0]=='searchzoompan'){
						$searchzoompan=$values[1];
					}else if($values[0]=='dir'){
						$dir=$values[1];
					}else if($values[0]=='lightbox'){
						$lightbox=$values[1];
					}else if($values[0]=='lbxwidth'){
						$lbxwidth=$values[1];
					}else if($values[0]=='lbxheight'){
						$lbxheight=$values[1];
					}else if($values[0]=='lbxcaption'){
						$lbxcaption=$values[1];
					}else if($values[0]=='lbxzoom'){
						$lbxzoom=$values[1];
					}else if($values[0]=='show'){
						$show=$values[1];
					}else if($values[0]=='imageurl'){
						$imageurl=$values[1];
					}else if($values[0]=='imagex'){
						$imagex=$values[1];
					}else if($values[0]=='imagey'){
						$imagey=$values[1];
					}else if($values[0]=='imagexyunits'){
						$imagexyunits=$values[1];
					}else if($values[0]=='imagewidth'){
						$imagewidth=$values[1];
					}else if($values[0]=='imageheight'){
						$imageheight=$values[1];
					}else if($values[0]=='imageanchorx'){
						$imageanchorx=$values[1];
					}else if($values[0]=='imageanchory'){
						$imageanchory=$values[1];
					}else if($values[0]=='imageanchorunits'){
						$imageanchorunits=$values[1];
					}
				}

				$this->debug_log("Plugin Google Maps version 2.10h");
				$this->debug_log("Parameters: ");
				$this->debug_log("- debug: ".$this->debug_plugin);
				$this->debug_log("- dir: ".$dir);
				$this->debug_log("- text: ".$text);
				$this->debug_log("- icon: ".$icon);
				$this->debug_log("- iconwidth: ".$iconwidth);
				$this->debug_log("- iconheight: ".$iconheight);
				$this->debug_log("- iconinfoanchory: ".$iconinfoanchory);
				$this->debug_log("- searchlist: ".$searchlist);
				$this->debug_log("- searchzoompan: ".$searchzoompan);
				
				if($inline_coords == 0 && !empty($address))
				{
					$coord = $this->get_geo($address, $key);
					if ($coord=='') {
						$client_geo = 1;
					} else {
						list ($longitude, $latitude, $altitude) = explode(",", $coord);
						$inline_coords = 1;
					}
				}

				if($inline_tocoords == 0 && !empty($toaddress))
				{
					$tocoord = $this->get_geo($toaddress, $key);
					if ($tocoord=='') {
						$client_togeo = 1;
					} else {
						list ($tolon, $tolat, $altitude) = explode(",", $tocoord);
						$inline_tocoords = 1;
					}
				}
	
				if (is_numeric($lbxwidth))
				{
					$lbxwidth .= "px";
				}
				if (is_numeric($lbxheight))
				{
					$lbxheight .= "px";
				}
				if (is_numeric($width))
				{
					$width .= "px";
				}
				if (is_numeric($height))
				{
					$height .= "px";
				}
				
				if ($googlebar=='1'||$localsearch=='1') {
					$searchoption = array();
	
					switch ($searchlist) {
					case "suppress";
						$searchoption[] ="resultList : G_GOOGLEBAR_RESULT_LIST_SUPPRESS";
						break;
					
					case "inline";
						$searchoption[] ="resultList : G_GOOGLEBAR_RESULT_LIST_INLINE";
						break;

					case "div";
						$searchoption[] ="resultList : document.getElementById('searchresult".$mapnm."')";
						break;
	
					default;
						if(empty($searchlist))
							$searchoption[] ="resultList : G_GOOGLEBAR_RESULT_LIST_INLINE";
						else {
							$searchoption[] ="resultList : document.getElementById('".$searchlist."')";
							$extsearchresult= true;
						}
						break;
					}
					
					switch ($searchtarget) {
					case "_self";
						$searchoption[] ="linkTarget : G_GOOGLEBAR_LINK_TARGET_SELF";
						break;
					
					case "_blank";
						$searchoption[] ="linkTarget : G_GOOGLEBAR_LINK_TARGET_BLANK";
						break;
	
					case "_top";
						$searchoption[] ="linkTarget : G_GOOGLEBAR_LINK_TARGET_TOP";
						break;
	
					case "_parent";
						$searchoption[] ="linkTarget : G_GOOGLEBAR_LINK_TARGET_PARENT";
						break;
	
					default;
						$searchoption[] ="linkTarget : G_GOOGLEBAR_LINK_TARGET_BLANK";
						break;
					}
					
					if ($searchzoompan=="1")
						$searchoption[] ="suppressInitialResultSelection : false
										  , suppressZoomToBounds : false";
					else
						$searchoption[] ="suppressInitialResultSelection : true
										  , suppressZoomToBounds : true";
										  
					$searchoptions = implode(', ', $searchoption);
				} else 
					$searchoptions = "";

				if ($icon!='') {
					$code .= "\n<img src='".$icon."' style='display:none' alt='icon' />";
					if ($iconshadow!='')
						$code .= "\n<img src='".$iconshadow."' style='display:none' alt='icon shadow' />";
					if ($icontransparent!='')
						$code .= "\n<img src='".$icontransparent."' style='display:none' alt='icon transparent' />";
				} 

				// Generate the map position prior to any Google Scripts so that these can parse the code
				$code.= "<!-- fail nicely if the browser has no Javascript -->
						<noscript><b>JavaScript must be enabled in order for you to use Google Maps.</b> <br/>
							  However, it seems JavaScript is either disabled or not supported by your browser. <br/>
							  To view Google Maps, enable JavaScript by changing your browser options, and then try again. 
						</noscript>";
						
				$code.="<div align=\"".$align."\">";
						
				if ($gotoaddr=='1')
				{
					$code.="<form name=\"gotoaddress".$mapnm."\" class=\"gotoaddress\" action=\"javascript:gotoAddress".$mapnm."();\">";
					$code.="	<input id=\"txtAddress".$mapnm."\" name=\"txtAddress".$mapnm."\" type=\"text\" size=\"25\" value=\"\">";
					$code.="	<input name=\"goto\" type=\"button\" class=\"button\" onClick=\"gotoAddress".$mapnm."();return false;\" value=\"Goto\">";
					$code.="</form>";
				}

				if ($lightbox=='1') {
					$code.="<div class='lightboxlink'><a onclick='MOOdalBox.open(\"googlemap".$mapnm."\", \"".$lbxcaption."\", \"".$lbxwidth." ".$lbxheight."\", map".$mapnm.", ".((!empty($lbxzoom))?$lbxzoom:"null").");'>".$txtlightbox."</a></div>";
				}

				$code.="<div id=\"googlemap".$mapnm."\" ".((!empty($mapclass))?"class=\"".$mapclass."\"" :"")." style=\"width:".$width."; height:".$height.";".(($show==0)?"display:none":"")."\"></div>";

				if ($searchlist=='div') {
					$code.="<div id=\"searchresult".$mapnm."\"></div>";
				}

				$code.="</div>";
	
				// Only add the google javascript once
				if($first)
				{
					$head = "<script src=\"http://maps.google.com/maps?file=api&amp;v=".$google_API_version;
					if ($lang!='') 
						$head .= "&amp;hl=".$lang;
	
					$head .= "&amp;key=".$key."\" type=\"text/javascript\"></script>";
					$this->debug_log('Google API script');
					$this->injectCustomHeadTags($head, "maps.google.com/maps?file=api", $row->text);
					$first=false;
				}
	
				if (($lightbox=="1"||$effect!="none")&&$first_mootools&&($joomla_version< 1.5)) {
					$head ="<script src='".$mosConfig_live_site."/".$plugin_path."/content/mootools/mootools-release-1.11.js' type='text/javascript'></script>";
					$this->debug_log('mootools');
					$this->injectCustomHeadTags($head, "mootools", $row->text);
					$first_mootools = false;
				}
				if ($lightbox=="1"&&$first_modalbox)	{
					$head = "<link rel='stylesheet' href='".$mosConfig_live_site."/".$plugin_path."/content/moodalbox121/css/moodalbox.css' type='text/css' /><script src='".$mosConfig_live_site."/".$plugin_path."/content/moodalbox121/js/modalbox1.2hack.js' type='text/javascript'></script>";	
					$this->debug_log('modalbox');
					$this->injectCustomHeadTags($head, "modalbox1.2hack.js", $row->text);
					$first_modalbox = false;
				}
				if ($panoramio=="1"&&$first_panoramio)	{
					$head = "<script src='".$mosConfig_live_site."/".$plugin_path."/content/panoramio/pano_layer.js' type='text/javascript'></script>";	
					$this->debug_log('panoramio');
					$this->injectCustomHeadTags($head, "pano_layer.js", $row->text);
					$first_panoramio = false;
				}
				if ($localsearch=="1"&&$first_localsearch) {
					$head = "<script src='http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=".$key."' type='text/javascript'></script>
							<script src='http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js".((!empty($adsense))?"?adsense=".$adsense:"").((!empty($channel)&&!empty($adsense))?"&amp;channel=".$channel:"")."' type='text/javascript'></script>
							<style type='text/css'>
							  @import url('http://www.google.com/uds/css/gsearch.css');
							  @import url('http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css');
							</style>";
					$this->debug_log('localsearch');
					$this->injectCustomHeadTags($head, "gmlocalsearch.js", $row->text);
					$first_localsearch = false;
				}
	
				$code.="<script type='text/javascript'>//<![CDATA[\n";
	
				// Globale map variable linked to the div
				$code.="var tst".$mapnm."=document.getElementById('googlemap".$mapnm."');
				var tstint".$mapnm.";
				var map".$mapnm.";
				var mySlidemap".$mapnm.";
				var overviewmap".$mapnm.";
				var ovmap".$mapnm.";
				var xml2".$mapnm.";
				var imageovl".$mapnm.";
				";
				if ($panoramio=="1")
					$code.="var panoramio".$mapnm.";";
				if ($traffic=='1') 
					$code.="var trafficInfo".$mapnm.";";
				if ($localsearch=='1') 
					$code.="var localsearch".$mapnm.";";
				if ($adsmanager=='1') 
					$code.="var adsmanager".$mapnm.";";
				
				if ($icon!='') {
					$code.="\nmarkericon".$mapnm." = new GIcon(G_DEFAULT_ICON);";
					$code.="\nmarkericon".$mapnm.".image = '".$icon."';";
					if ($iconwidth!=''&&$iconheight!='')
						$code.="\nmarkericon".$mapnm.".iconSize = new GSize(".$iconwidth.", ".$iconheight.");";
					if ($iconshadow !='') {
						$code.="\nmarkericon".$mapnm.".shadow = '".$iconshadow."';";
		
						if ($iconshadowwidth!=''&&$iconshadowheight!='') 
							$code.="\nmarkericon".$mapnm.".shadowSize = new GSize(".$iconshadowwidth.", ".$iconshadowheight.");";
						if ($iconshadowanchorx!=''&&$iconshadowanchory!='')
							$code.="\nmarkericon".$mapnm.".infoShadowAnchor = new GPoint(".$iconshadowanchorx.", ".$iconshadowanchory.");";
					}
					if ($iconanchorx!=''&&$iconanchory!='')
						$code.="\nmarkericon".$mapnm.".iconAnchor = new GPoint(".$iconanchorx.", ".$iconanchory.");";
					if ($iconinfoanchorx!=''&&$iconinfoanchory!='')
						$code.="\nmarkericon".$mapnm.".infoWindowAnchor = new GPoint(".$iconinfoanchorx.", ".$iconinfoanchory.");";
					if ($icontransparent!='') 			
						$code.="\nmarkericon".$mapnm.".transparent = '".$icontransparent."';";
					if ($iconimagemap!='')
						$code.="\nmarkericon".$mapnm.".imageMap = [".$iconimagemap."];";
				}
	
				if ( strpos(" ".$_SERVER['HTTP_USER_AGENT'], 'Opera') )
				{
					$code.="var _mSvgForced = true;
							var _mSvgEnabled = true; ";
				}
	
				if($zoom_wheel=='1')
				{
					$code.="function CancelEvent".$mapnm."(event) { 
								var e = event; 
								if (typeof e.preventDefault == 'function') e.preventDefault(); 
									if (typeof e.stopPropagation == 'function') e.stopPropagation(); 
		
								if (window.event) { 
									window.event.cancelBubble = true; // for IE 
									window.event.returnValue = false; // for IE 
								} 
							}
						";
				}
	
				if ($gotoaddr=='1')
				{
					$code.="function gotoAddress".$mapnm."() {
								var address = document.getElementById('txtAddress".$mapnm."').value;
	
								if (address.length > 0) {
									var geocoder = new GClientGeocoder();
									geocoder.setViewport(map".$mapnm.".getBounds());
	
									geocoder.getLatLng(address,
									function(point) {
										if (!point) {
											var erraddr = '{$erraddr}';
											erraddr = erraddr.replace(/##/, address);
										  alert(erraddr);
										} else {
										  var txtaddr = '{$txtaddr}';
										  txtaddr = txtaddr.replace(/##/, address);
										  map".$mapnm.".setCenter(point);
										  map".$mapnm.".openInfoWindowHtml(point,txtaddr);
										  setTimeout('map".$mapnm.".closeInfoWindow();', 5000);
										}
									  });
								  }
							}";
				}
	
				if ($dir=='1') {
					$code.="\nDirectionMarkersubmit".$mapnm." = function( formObj ){
								if(formObj.dir[1].checked ){
									tmp = formObj.daddr.value;
									formObj.daddr.value = formObj.saddr.value;
									formObj.saddr.value = tmp;
								}
								formObj.submit();
								if(formObj.dir[1].checked ){
									tmp = formObj.daddr.value;
									formObj.daddr.value = formObj.saddr.value;
									formObj.saddr.value = tmp;
								}
							}";
				}
				
				// Function for overview
				if(!$overview==0&&$google_API_version >= '2.93')
				{
					$code.="\nfunction checkOverview".$mapnm."() {
						        var overmap = overviewmap".$mapnm.".getOverviewMap();
								if (overmap) {
								  // ======== get a reference to the GMap2 ===========
								  ovmap".$mapnm." = overviewmap".$mapnm.".getOverviewMap();
							";
								  
					if($overview==2)
					{
						$code.="\n		setTimeout('overviewmap".$mapnm.".hide(true);',1);";
					}

					switch ($mapType) {
					case "Satellite";
					
						$code.="\n		setTimeout('ovmap".$mapnm.".setMapType(G_SATELLITE_MAP);',1);";
						break;
					
					case "Hybrid";
						$code.="\n		setTimeout('ovmap".$mapnm.".setMapType(G_HYBRID_MAP);',1);";
						break;

					case "Terrain";
						$code.="\n		setTimeout('ovmap".$mapnm.".setMapType(G_PHYSICAL_MAP);',1);";
						break;
					
					default;
						$code.="\n		setTimeout('ovmap".$mapnm.".setMapType(G_NORMAL_MAP);',1);";
						break;
					}

//					Zoomlevel is n't correct 
//					$code.="\n		setTimeout('ovmap".$mapnm.".setZoom(map".$mapnm.".getZoom()-3);',400);";
//					Load of kml works but zoomlevel isn't oke
//					if ($kml!='') {
//						$code .= "\n		xml2".$mapnm." = new GGeoXml(\"".$kml."\");";
//						$code .= "\n		setTimeout('ovmap".$mapnm.".addOverlay(xml2".$mapnm.");',1);";
//					}
						  
					$code.= "\n	} else {
								  setTimeout('checkOverview".$mapnm."()',100);
								}
							  }";
				}

				// Functions to wacth if the map has changed
				$code.="\nfunction checkMap".$mapnm."()
				{
					if (tst".$mapnm.")
						if (tst".$mapnm.".offsetWidth != tst".$mapnm.".getAttribute(\"oldValue\"))
						{
							tst".$mapnm.".setAttribute(\"oldValue\",tst".$mapnm.".offsetWidth);
	
							if (tst".$mapnm.".getAttribute(\"refreshMap\")==0)
								if (tst".$mapnm.".offsetWidth > 0) {
									clearInterval(tstint".$mapnm.");";
				if ($effect !='none') 
					$code .="\n					mySlidemap".$mapnm." = new Fx.Slide('googlemap".$mapnm."',{wait:true, duration: 1500, transition:Fx.Transitions.Bounce.easeOut, mode: '".$effect."'})
									mySlidemap".$mapnm.".hide();
									mySlidemap".$mapnm.".slideIn();
									mySlidemap".$mapnm.".slideOut().chain(function(){
											mySlidemap".$mapnm.".slideIn();
										});";
		
				$code .="\n					getMap".$mapnm."();
									tst".$mapnm.".setAttribute(\"refreshMap\", 1);
								} 
						}
				}
				";
	
				// Function for displaying the map and marker
				$code.="	function getMap".$mapnm."(){
					if (tst".$mapnm.".offsetWidth > 0) {
						map".$mapnm." = new GMap2(document.getElementById('googlemap".$mapnm."')".(($googlebar=='1'&&!empty($searchoptions))?", { googleBarOptions: {".$searchoptions." } }":"").");
						map".$mapnm.".getContainer().style.overflow='hidden';
						";
				
				if($dragging=="0")
					$code.="map".$mapnm.".disableDragging();";

	
						if($zoomType!='None')
						{
							if($zoomType=='Large')
							{
								$code.="map".$mapnm.".addControl(new GLargeMapControl());";
							} else
							{
								$code.="map".$mapnm.".addControl(new GSmallMapControl());";
							}
						} 
	
						if(!$overview==0&&$google_API_version < '2.93')
						{
							$code.="overviewmap".$mapnm." = new GOverviewMapControl();";
							$code.="map".$mapnm.".addControl(overviewmap".$mapnm.", new GControlPosition(G_ANCHOR_BOTTOM_RIGHT));";
							
							if($overview==2)
							{
								$code.="overviewmap".$mapnm.".hide(true);";
							}
						}
						if(!$overview==0&&$google_API_version >= '2.93')
						{
							$code.="overviewmap".$mapnm." = new GOverviewMapControl();";
							$code.="map".$mapnm.".addControl(overviewmap".$mapnm.", new GControlPosition(G_ANCHOR_BOTTOM_RIGHT));";
							$code.="setTimeout('checkOverview".$mapnm."()',100);";
						}
	
						if($showmaptype!='0')
						{
							$code.="map".$mapnm.".addControl(new GMapTypeControl());";
							if ($google_API_version == '2.x'||$google_API_version > '2.93')
								$code.="map".$mapnm.".addMapType(G_PHYSICAL_MAP);";
						} 
	
						if($client_geo == 1)
						{
							$code.="var geocoder = new GClientGeocoder();";
							$replace = array("\n", "\r", "&lt;br/&gt;", "&lt;br /&gt;", "&lt;br&gt;");
							$addr = str_replace($replace, '', $address);
	
							$code.="var address = '".$addr."';";
							$code.="geocoder.getLatLng(address, function(point) {
										if (!point)
											point = new GLatLng( $latitude, $longitude);";
						} else { 
							$code.="var point = new GLatLng( $latitude, $longitude);";
						}
						if (!empty($centerlat)&&!empty($centerlon))
							$code.="var centerpoint = new GLatLng( $centerlat, $centerlon);";
						else
							$code.="var centerpoint = point;";
	
						if ($inline_coords == 0 && !empty($kml))
							$code.="map".$mapnm.".setCenter(new GLatLng(0, 0), 0);
							";					
						else
							$code.="map".$mapnm.".setCenter(centerpoint, ".$zoom.");
							";					
							
						if ($kml!='') {
							$code .= "var xml = new GGeoXml(\"".$kml."\");";
							$code .= "map".$mapnm.".addOverlay(xml);";
							if (!$overview==0&&$google_API_version < '2.93') {
								$code .= "var xml2 = new GGeoXml(\"".$kml."\");";
								$code .= "overview = overviewmap".$mapnm.".getOverviewMap();";
								$code .= "overview.addOverlay(xml2);";
							}
							if ($inline_coords==0)
								$code .= "xml.gotoDefaultViewport(map".$mapnm.");";
						}
						
						if ($traffic=='1') {
							$code .= "trafficInfo".$mapnm." = new GTrafficOverlay();";
							$code .= "map".$mapnm.".addOverlay(trafficInfo".$mapnm.");";
						}

						if ($panoramio=="1") {
							$code .= "panoramio".$mapnm." = new PanoramioLayer(map".$mapnm.");";
							$code .= "panoramio".$mapnm.".enable(map".$mapnm.");";
						}
	
						if ($localsearch=='1') {
							$code .= "localsearch".$mapnm." = new google.maps.LocalSearch(".((!empty($searchoptions))?"{ ".$searchoptions." }":"").");";
							$code .= "map".$mapnm.".addControl(localsearch".$mapnm.", new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,20)));";
							if (!empty($searchtext))
								$code .= "localsearch".$mapnm.".execute('".$searchtext."');";
						}
						
						if ($googlebar=='1') {
							$code .= "map".$mapnm.".enableGoogleBar();";
						}

						if ($adsmanager=='1') {
							$code .= "adsmanager".$mapnm." = new GAdsManager(map".$mapnm.", ".((!empty($adsense))?"'".$adsense."'":"''").", { maxAdsOnMap: ".$maxads.((!empty($searchtext))?", keywords: '".$searchtext."'":"").((!empty($channel)&&!empty($adsense))?", channel: '".$channel."'":"")."}); ";
							$code .= "adsmanager".$mapnm.".enable();";
						}

						if ((!empty($tolat)&&!empty($tolon))||!empty($address)) {
							// Route
						}
						
						switch ($mapType) {
						case "Satellite";
							$code.="map".$mapnm.".setMapType(G_SATELLITE_MAP);";
							break;
						
						case "Hybrid";
							$code.="map".$mapnm.".setMapType(G_HYBRID_MAP);";
							break;
	
						case "Terrain";
							if ($google_API_version == '2.x'||$google_API_version > '2.93')
								$code.="map".$mapnm.".setMapType(G_PHYSICAL_MAP);";
							else 
								$code.="map".$mapnm.".setMapType(G_NORMAL_MAP);";
							break;
						
						default;
							$code.="map".$mapnm.".setMapType(G_NORMAL_MAP);";
							break;
						}
	
						if($zoom_new=='1')
						{
							$code.="
							map".$mapnm.".enableContinuousZoom();
							map".$mapnm.".enableDoubleClickZoom();
							";
						} else {
							$code.="
							map".$mapnm.".disableContinuousZoom();
							map".$mapnm.".disableDoubleClickZoom();
							";
						}
	
						if($zoom_wheel=='1')
						{
							$code.="map".$mapnm.".enableScrollWheelZoom();
							";
						} 
	
	
						if (($inline_coords == 1&&!(!empty($kml)&&$text==''&&$dir==0))||($inline_coords == 0 && empty($kml))) {
	
	//					if (($inline_coords == 1&&($text!=''||$address!=''))||($inline_coords == 0 && empty($kml))) {
	
							$options = '';
							
							if ($tooltip!='') 
								$options .= (($options!='')?', ':'')."title:\"".$tooltip."\"";
							if ($icon!='')
								$options .= (($options!='')?', ':'')."icon:markericon".$mapnm;
							
							$code.="var marker".$mapnm." = new GMarker(point".(($options!='')?', {'.$options.'}':'').");";
							
							$code.="map".$mapnm.".addOverlay(marker".$mapnm.");
							";
	
							if ($text!=''||$dir==1) {
	
								if ($dir=='1') {
									$dirform="<form action='http://maps.google.com/maps' method='get' target='_blank' onsubmit='DirectionMarkersubmit".$mapnm."(this);return false;' class='mapdirform'>";
										
									$dirform.="<br />".$txt_dir."<input type='radio' checked name='dir' value='to'> <b>".$txt_to."</b> <input type='radio' name='dir' value='from'><b>".$txt_from."</b>";
									$dirform.="<br />".$txt_diraddr."<input type='text' class='inputbox' size='20' name='saddr' id='saddr' value='' /><br />";
									$dirform.="<input value='".$txt_get_dir."' class='button' type='submit' style='margin-top: 2px;'>";
									
									if (!empty($address))
										$dirform.="<input type='hidden' name='daddr' value='".$address." (".$latitude.", ".$longitude.")'/></form>";
									else
										$dirform.="<input type='hidden' name='daddr' value='".$latitude.", ".$longitude."'/></form>";
									// Add form before div or at the end of the html.
									$pat="/&lt;\/div&gt;$/";
									if (preg_match($pat, $text))
										$text = preg_replace($pat, $dirform."</div>", $text);
									else
										$text.=$dirform;
								}
								
								$text = $this->_htsdecode($text, ENT_NOQUOTES);
	
								// If marker 
								if ($marker==1)
									$code.="marker".$mapnm.".openInfoWindowHtml(\"".$text."\");";
								
								$code.="GEvent.addListener(marker".$mapnm.", 'click', function() {
										marker".$mapnm.".openInfoWindowHtml(\"".$text."\");
										});
								";
							}
						}
						
						if ($imageurl!='') {
							$code .= "imageovl".$mapnm." = new GScreenOverlay('$imageurl',
													new GScreenPoint($imagex, $imagey, '$imagexyunits', '$imagexyunits'),  // screenXY
													new GScreenPoint($imageanchorx, $imageanchory, '$imageanchorunits', '$imageanchorunits'),  // overlayXY
													new GScreenSize($imagewidth, $imageheight)  // size on screen
												);
										map".$mapnm.".addOverlay(imageovl".$mapnm.");
		  						";
						}
						
						if($zoom_wheel=='1')
						{
							$code.="GEvent.addDomListener(tst".$mapnm.", 'DOMMouseScroll', CancelEvent".$mapnm.");
									GEvent.addDomListener(tst".$mapnm.", 'mousewheel', CancelEvent".$mapnm.");
								";
						}

						/* remove copyright, terms and mapdata. Do not use 					
						$code.= "test_div = document.getElementById('googlemap".$mapnm."');";
						$code.= "test_obj = test_div.childNodes[1].style.display='none';";
						$code.= "test_obj = test_div.childNodes[2].style.display='none';";
						*/

						if($client_geo == 1)
						{
							$code.="		       
										  });";
						}

						// End of script voor showing the map 
						$code.="}
			}
			//]]></script>
			";
	
			// Call the Maps through timeout to render in IE also
			// Set an event for watching the changing of the map so it can refresh itself
			$code.= "<script type=\"text/javascript\">//<![CDATA[
					if (GBrowserIsCompatible()) {
						tst".$mapnm.".setAttribute(\"oldValue\",0);
						tst".$mapnm.".setAttribute(\"refreshMap\",0);
						tstint".$mapnm."=setInterval(\"checkMap".$mapnm."()\",500);
					}
			//]]></script>
			";

			if ($this->debug_text!='')
				$code = "\n<!-- ".$this->debug_text."\n-->\n".$code;
				
			$this->debug_text = '';
			
			$row->text = preg_replace($regex, $code, $row->text, 1);
			} 
	
		}
	
		return true;
	}
}
?>
