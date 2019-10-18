<?php
/**
 * SEF component for Joomla! 1.5
 *
 * @author      ARTIO s.r.o.
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 * @version     3.1.0
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access.');

class JoomSEF {
	function build( &$uri ) {
		global $mainframe;

		$config =& JFactory::getConfig();
		$sefConfig =& SEFConfig::getConfig();
		$cache =& SEFCache::getInstance();

		// Trigger onSefStart patches
		$mainframe->triggerEvent('onSefStart');

		$prevLang = ''; // For correct title translations

		// Check if this is site root
		$vars = $uri->getQuery(true);
		if( empty($vars) ) {
			// Trigger onSefEnd patches
			$mainframe->triggerEvent('onSefEnd');
			$uri = new JURI(JURI::root());
			return true;
		}

		if( class_exists('JoomFish') ) {
			$lang = $uri->getVar('lang');

			// If lang not set
			if( empty($lang) ) {
				if( $sefConfig->alwaysUseLang ) {
					// Add lang variable if set to
					$uri->setVar('lang', SEFTools::getLangCode());
				} else {
					// Delete lang variable so it is not empty
					$uri->delVar('lang');
				}
			}

			// Get the URL's language and set it as global language (for correct translation)
			$lang = $uri->getVar('lang');
			$code = '';
			if( !empty($lang) ) {
				$code = SEFTools::getLangLongCode($lang);
				if( !is_null($code) ) {
					if( $code != SEFTools::getLangLongCode() ) {
						$language =& JFactory::getLanguage();
						$prevLang = $language->setLanguage($code);
						$language->load();
					}
				}
			}

			/* TODO: think of a way to implement this
			// Set the live_site according to language
			if( $sefConfig->langPlacement == _COM_SEF_LANG_DOMAIN ) {
			$inst =& JURI::getInstance();
			$domain = '';
			if( $code != '' ) {
			if( isset($sefConfig->langDomain[$code]) ) {
			//$mainframe->set('sef.global.langoverride', $sefConfig->langDomain[$code]);
			$domain = $sefConfig->langDomain[$code];
			$uri->delVar('lang');
			}
			}
			if( $domain != '' ) {
			$domain = new JURI($domain);
			$inst->setScheme($domain->getScheme());
			$inst->setHost($domain->getHost());
			}
			}
			*/
		}

		$vars = $uri->getQuery(true);
		if( empty($vars) ) {
			// Trigger onSefEnd patches
			JoomSEF::_endSef($prevLang);
			return true;
		}

		$option = $uri->getVar('option');

		if( !is_null($option) ) {
			switch($option) {
				// Skipped extensions.
				case (in_array($option, $sefConfig->skip)): {
					$uri = JoomSEF::_createUri($uri);
					JoomSEF::_endSef($prevLang);
					return;
				}
				// Non-cached extensions.
				case (in_array($option, $sefConfig->nocache)): {
					$router = $mainframe->get('sef.global.jrouter');
					if( !empty($router) ) {
						$uri = $router->build($uri->toString());
					}
					JoomSEF::_endSef($prevLang);
					return;
				}
				// Default handler.
				default: {
					// If component has its own sef_ext plug-in included.
					// However, prefer own plugin if exists (added by Michal, 28.11.2006)
					$compExt = JPATH_ROOT.DS.'components'.DS.$option.DS.'router.php';
					$ownExt = JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS.$option.'.php';
					if (file_exists($compExt) && !file_exists($ownExt)) {
						// Load the plug-in file.
						require_once($compExt);

						$app        =& JFactory::getApplication();
						$menu       =& JSite::getMenu();
						$route      = $uri->getPath();
						$query      = $uri->getQuery(true);
						$component  = preg_replace('/[^A-Z0-9_\.-]/i', '', $query['option']);
						$tmp        = '';

						$function   = substr($component, 4).'BuildRoute';
						$parts      = $function($query);

						$total = count($parts);
						for($i=0; $i<$total; $i++) {
							$parts[$i] = str_replace(':', '-', $parts[$i]);
						}

						$result = implode('/', $parts);
						$tmp    = ($result != "") ? '/'.$result : '';

						/*
						* Build the application route
						*/
						$built = false;
						if (isset($query['Itemid']) && !empty($query['Itemid']))
						{
							$item = $menu->getItem($query['Itemid']);

							if (is_object($item) && $query['option'] == $item->component) {
								$tmp = !empty($tmp) ? $item->route.'/'.$tmp : $item->route;
								$built = true;
							}
						}

						if(!$built) {
							$tmp = 'component/'.substr($query['option'], 4).'/'.$tmp;
						}

						$route .= '/'.$tmp;
						if($app->getCfg('sef_suffix') && !(substr($route, -9) == 'index.php' || substr($route, -1) == '/'))
						{
							if($format = $uri->getVar('format', 'html'))
							{
								$route .= '.'.$format;
								$uri->delVar('format');
							}
						}

						if($app->getCfg('sef_rewrite'))
						{
							//Transform the route
							$route = str_replace('index.php/', '', $route);
						}

						// Unset unneeded query information
						unset($query['Itemid']);
						unset($query['option']);

						//Set query again in the URI
						$uri->setQuery($query);
						$uri->setPath($route);

						$uri = JoomSEF::_createUri($uri);

						JoomSEF::_endSef($prevLang);
						return;
					}
					else {
						if( file_exists($ownExt) ) {
							require_once($ownExt);
							$class = 'SefExt_'.$option;
						} else {
							$class = 'SefExt';
						}
						$sef_ext = new $class();

						// Let the extension change the url and options
						$sef_ext->beforeCreate($uri);

						// Ensure that the session IDs are removed
						// If set to
						$sid = $uri->getVar('sid');
						if (!$sefConfig->dontRemoveSid) $uri->delVar('sid');
						// Ensure that the mosmsg are removed.
						$mosmsg = $uri->getVar('mosmsg');
						$uri->delVar('mosmsg');

						// Override Itemid if set to
						$params = SEFTools::getExtParams($option);
						$override = $params->get('itemid', '0');
						$overrideId = $params->get('overrideId', '');
						if( ($override != '0') && ($overrideId != '') ) {
							$uri->setVar('Itemid', $overrideId);
						}

						// Clean Itemid if desired.
						// David: only if overriding is disabled
						if (isset($sefConfig->excludeSource) && $sefConfig->excludeSource && ($override == '0')) {
							$Itemid = $uri->getVar('Itemid');
							$uri->delVar('Itemid');
						}

						$url = JoomSEF::_uriToUrl($uri);

						// Try to get url from cache
						if( $sefConfig->useCache ) {
							$sefstring = $cache->GetSefUrl($url);
						}
						if( !$sefConfig->useCache || !$sefstring ) {
							// Check if the url is already saved in the database.
							if (!($sefstring = $sef_ext->getSefUrlFromDatabase($uri))) {
								if( $sefConfig->disableNewSEF ) {
									$sefstring = $url;
								}
							}
						}

						if( !$sefstring ) {
							// Rewrite the URL, creating new JURI object
							$uri = $sef_ext->create($uri);
						} else {
							// Create new JURI object from $sefstring
							$langOverride = $mainframe->get('sef.global.langoverride');
							if( !empty($langOverride) ) {
								$url = $langOverride;
							} else {
								$url = JURI::root();
							}

							if( substr($url, -1) != '/' ) {
								$url .= '/';
							}
							$url .= $sefstring;
							$fragment = $uri->getFragment();
							if( !empty($fragment) ) {
								$url .= '#'.$fragment;
							}
							$uri = new JURI($url);
						}

						// Reconnect the sid to the url.
						if (!empty($sid) && !$sefConfig->dontRemoveSid) $uri->setVar('sid', $sid);
						// Reconnect mosmsg to the url.
						if (!empty($mosmsg)) $uri->setVar('mosmsg', $mosmsg);

						// Reconnect ItemID to the url.
						// David: only if extension doesn't set its own Itemid through overrideId parameter
						if (isset($sefConfig->excludeSource) && $sefConfig->excludeSource && $sefConfig->reappendSource && ($override == '0') && !empty($Itemid)) {
							$uri->setVar('Itemid', $Itemid);
						}

						// Let the extension change the resulting SEF url
						$sef_ext->afterCreate($uri);
					}
				}
			}
		}
		else if( !is_null($uri->getVar('Itemid')) ) {
			// There is only Itemid present - we must override the Ignore multiple sources option
			$oldIgnore = $sefConfig->ignoreSource;
			$sefConfig->ignoreSource = 0;

			$title = array();
			$title[] = JoomSEF::_getMenuTitle(null, null, $uri->getVar('Itemid'));

			$uri = JoomSEF::_sefGetLocation($uri, $title, null, null, null, $uri->getVar('lang'));

			//$string = (isset($langOverride) ? $langOverride : $GLOBALS['mosConfig_live_site']).'/'.$sefstring.(($URI->anchor)? '#'.$URI->anchor : '');

			$sefConfig->ignoreSource = $oldIgnore;
		}

		JoomSEF::_endSef($prevLang);
	}

	function parse( &$uri ) {
		global $mainframe;

		// Store the old URI before we change it in case we will need it
		// for default Joomla SEF
		$oldUri = new JURI($uri->toString());

		$sefConfig =& SEFConfig::getConfig();

		// Load patches
		JPluginHelper::importPlugin('sefpatch');

		// Trigger onSefLoad patches
		$mainframe->triggerEvent('onSefLoad');

		// Get path
		$path = $uri->getPath();

		// Remove basepath
		$path = substr_replace($path, '', 0, strlen(JURI::base(true)));

		// Remove prefix
		$path = eregi_replace('^index.php', '', $path);

		// Remove slashes
		$path = ltrim($path, '/');
		
		// Replace spaces with our replacement character
		// (mainly for '+' handling, but may be useful in some other situations too)
		$path = str_replace(' ', $sefConfig->replacement, $path);
		
		// Set the route
		$uri->setPath($path);

		/* TODO: think of a way to implement this in 1.5
		// host name handling
		if( class_exists('JoomFish') && ($sefConfig->langPlacement == _COM_SEF_LANG_DOMAIN) ) {
		// different domains for languages handling
		$host = $uri->toString( array('scheme', 'host', 'port') );
		$host = trim($host, '/');

		foreach( $sefConfig->langDomain as $langCode => $domain ) {
		if( strpos($domain, $host) !== false ) {
		// we found a matching domain
		// get the language shortcode
		$lang = SEFTools::getLangCode($langCode);

		//$uri->setVar('lang', $shortCode);

		$language =& JFactory::getLanguage();
		$language->setLanguage($langCode);
		$language->load();

		break;
		}
		}
		}
		*/
		// Parse the url
		$vars = JoomSEF::_parseSefUrl($uri, $oldUri);
		/* TODO: belongs to domain language handling
		if( !empty($lang) && !isset($vars['lang']) ) {
		$vars['lang'] = $lang;
		}
		*/

		$lang = (isset($vars['lang']) ? $vars['lang'] : null);
		JoomSEF::_determineLanguage($lang);

		// Trigger onSefUnload patches
		$mainframe->triggerEvent('onSefUnload');

		return $vars;
	}

	function _determineLanguage($getLang = null) {
		// Set the language for JoomFish
		if( class_exists('JoomFish') ) {
			$registry =& JFactory::getConfig();

			// Get language from request
			if( !empty($getLang) ) {
				$lang = $getLang;
			}

			// Check if language is selected
			if( empty($lang) ) {
				// Try to get language code from JF cookie
				$jfCookie = JRequest::getVar('jfcookie', null, 'COOKIE');
				if( isset($jfCookie['lang']) ) {
					$code = $jfCookie['lang'];

					// Check language existence
					if( !JLanguage::exists($code) ) {
						$code = $registry->getValue('config.language');
					}
				} else {
					// Otherwise get the default language code
					$code = $registry->getValue('config.language');
				}
			}
			// Get language long code if needed
			if( empty($code) ) {
				$code = SEFTools::getLangLongCode($lang);
			}

			if( !empty($code) ) {
				// Set the site language
				if( $code != SEFTools::getLangLongCode() ) {
					$language =& JFactory::getLanguage();
					$language->setLanguage($code);
					$language->load();
					
					// Set the backward compatible language
					$backLang = $language->getBackwardLang();
					$GLOBALS['mosConfig_lang'] = $backLang;
					$registry->setValue("config.lang", $backLang);
				}

				// Set joomfish language
				$jfLang = TableJFLanguage::createByJoomla($code);
				$registry->setValue("joomfish.language", $jfLang);

				// Set some more variables
				global $mainframe;
				$registry->setValue("config.multilingual_support", true);
				$mainframe->setUserState('application.lang',$jfLang->code);
				$registry->setValue("config.jflang", $jfLang->code);
				$registry->setValue("config.lang_site",$jfLang->code);
				$registry->setValue("config.language",$jfLang->code);
				$registry->setValue("joomfish.language",$jfLang);

				$langParams = new JParameter( $jfLang->params );
				foreach ($langParams->toArray() as $key => $value) {
					$GLOBALS['mosConfig_' .$key] =$value;
				}

				// Set the cookie with language
				setcookie( 'jfcookie[lang]', $code, time()+24*3600, '/' );
			}
		}
	}

	function _parseSefUrl(&$uri, &$oldUri) {
		global $mainframe;

		$db =& JFactory::getDBO();
		$sefConfig =& SEFConfig::getConfig();

		$route = $uri->getPath();

		//Get the variables from the uri
		$vars = $uri->getQuery(true);

		//Handle an empty URL (special case)
		if( empty($route) )
		{
			JoomSEF::_determineLanguage(JRequest::getVar('lang'));

			$menu  =& JSite::getMenu(true);

			//If route is empty AND option is set in the query, assume it's non-sef url, and parse apropriately
			if(isset($vars['option']) || isset($vars['Itemid'])) {
				return JoomSEF::_parseRawRoute($uri);
			}

			$item = $menu->getDefault();

			//Set the information in the request
			$vars = $item->query;

			//Get the itemid
			$vars['Itemid'] = $item->id;

			// Set the active menu item
			$menu->setActive($vars['Itemid']);

			// MetaTags for frontpage
			$db->setQuery("SELECT id FROM #__plugins WHERE element = 'joomsef' AND folder = 'system' AND published = 1");
			if( $db->loadResult() ) {
				// ... and frontpage has meta tags
				$db->setQuery("SELECT * FROM #__redirection WHERE oldurl = '' OR oldurl = 'index.php' LIMIT 1");
				$sefRow = $db->loadObject();
				if( !empty($sefRow) ) {
					global $mainframe;
					if (!empty($sefRow->metatitle))  $mainframe->set('sef.meta.title', $sefRow->metatitle );
					if (!empty($sefRow->metadesc))   $mainframe->set('sef.meta.desc', $sefRow->metadesc );
					if (!empty($sefRow->metakey))    $mainframe->set('sef.meta.key', $sefRow->metakey );
					if (!empty($sefRow->metalang))   $mainframe->set('sef.meta.lang', $sefRow->metalang );
					if (!empty($sefRow->metarobots)) $mainframe->set('sef.meta.robots', $sefRow->metarobots );
					if (!empty($sefRow->metagoogle)) $mainframe->set('sef.meta.google', $sefRow->metagoogle );
				}
			}

			return $vars;
		}

		$sef_ext = new SefExt();
		$newVars = $sef_ext->revert($route);

		if( !empty($newVars) && !empty($vars) ) {
			// If this was SEF url, consider the vars in query as nonsef
			$mainframe->set('sef.global.nonsefvars', $vars);
		}
		
		if( $sefConfig->parseJoomlaSEO && empty($newVars) ) {
			$router =& $mainframe->get('sef.global.jrouter');
			$jvars = $router->parse($oldUri);
			if( !empty($jvars['option']) || !empty($jvars['Itemid']) ) {
				$newVars = $jvars;
			}
		}

		if( !empty($vars) ) {
			// append the original query string because some components
			// (like SMF Bridge and SOBI2) use it
			$vars = array_merge($vars, $newVars);
		} else {
			$vars = $newVars;
		}

		if( !empty($newVars) ) {
			JoomSEF::_sendHeader('HTTP/1.0 200 OK');
		}
		else
		{
			// bad URL, so check to see if we've seen it before
			// 404 recording (only if enabled)
			if ($sefConfig->record404) {
				$query = "SELECT * FROM #__redirection WHERE oldurl = '".$route."'";
				$db->setQuery($query);
				$results = $db->loadObjectList();

				if ($results) {
					// we have it, so update counter
					$db->setQuery("UPDATE #__redirection SET cpt=(cpt+1) WHERE oldurl = '".$route."'");
					$db->query();
				}
				else {
					// record the bad URL
					$query = "INSERT INTO `#__redirection` (`cpt`, `oldurl`, `newurl`, `dateadd`) "
					. " VALUES ( '1', '$route', '', CURDATE() )";
					$db->setQuery($query);
					$db->query();
				}
			}

			// redirect to the error page
			// you MUST create a static content page with the title 404 for this to work properly
			if( $sefConfig->showMessageOn404 ) {
				$mosmsg = 'FILE NOT FOUND: '.$route;
				$mainframe->enqueueMessage($mosmsg);
			}

			if ($sefConfig->page404 == '0') {
				$sql='SELECT id  FROM #__content WHERE `title`="404"';
				$db->setQuery($sql);

				if (($id = $db->loadResult())) {
					$vars['option'] = 'com_content';
					$vars['view'] = 'article';
					$vars['id'] = $id;
					
					if( $sefConfig->use404itemid ) {
						$vars['Itemid'] = $sefConfig->itemid404;
					}
				}
				else {
					die(_COM_SEF_DEF_404_MSG.$mosmsg."<br>URI:".$_SERVER['REQUEST_URI']);
				}
			}
			elseif ($sefConfig->page404 == '9999999') {
				$menu  =& JSite::getMenu(true);
				$item = $menu->getDefault();

				//Set the information in the frontpage request
				$vars = $item->query;

				//Get the itemid
				$vars['Itemid'] = $item->id;
				$menu->setActive($vars['Itemid']);
			}
			else{
				$id = $Itemid  = $sefConfig->page404;
				$vars['option'] = 'com_content';
				$vars['view'] = 'article';
				$vars['id'] = $id;
			}

			JoomSEF::_sendHeader('HTTP/1.0 404 NOT FOUND');
		}

		return $vars;
	}

	function _sendHeader($header)
	{
		$f = $l = '';
		if (!headers_sent($f, $l)) {
			header($header);
		}
		else {
			JoomSEF::_headers_sent_error($f, $l, __FILE__, __LINE__);
		}
	}

	/**
	 * Determine what class use to convert URLs.
	 *
	 * @param  array $urlArray
	 * @return string
	 */
	/*    function _getExt($urlArray)
	{
	$database =& JFactory::getDBO();
	$sefConfig =& SEFConfig::getConfig();

	$ext = array();
	$ext['path'] = JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef.ext.php';

	// test if component with given name can be found
	$component = JoomSEF::_testComponent($urlArray[0]);

	// if found our own plug-in, use it
	// 1st test for nodb version
	if (($path = JoomSEF::_existOwnExt($component->name, true))) {
	$option = $ext['option'] = $component->name;
	$Itemid = $ext['Itemid'] = $component->id;
	$ext['path'] = $path;
	}
	// then test db version
	elseif (JoomSEF::_existOwnExt($component->name, false)) {
	$option = 'com_sefext';
	}
	// otherwise try to find 3rd party sef_ext
	elseif (($path = JoomSEF::_exist3rdExt($component->name))) {
	$option = $ext['option'] = $component->name;
	$Itemid = $ext['Itemid'] = $component->id;
	$ext['path'] = $path;
	}
	// built-in component ext
	elseif ((strpos($urlArray[0], 'com_') !== false) or ($urlArray[0] == 'component')) {
	$option = $ext['option'] = 'com_component';
	}
	// built-in content ext
	elseif($urlArray[0] == 'content') {
	$option = $ext['option'] = 'com_content';
	}
	// otherwise use default handler
	else {
	$option = 'com_sefext';
	}

	$ext['name'] = str_replace('com_', '', $option);
	return $ext;
	}
	*/
	/**
	 * Check if own extension exists for a component.
	 * This can be either db or nodb version.
	 * 
	 * @param  string $component  Component name
	 * @param  bool   $noDB       Testing for non-db version?
	 * @return object
	 */
	/*    function _existOwnExt($option, $noDB = false)
	{
	if (!$noDB) {
	return is_readable( JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS.$option.'.php' );
	}
	else {
	$path = JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef_ext_nodb'.DS.$option.'.php';
	return is_readable($path) ? $path : false;
	}
	}
	*/
	/**
	 * Does the component in question has own (3rd party) sef extension?
	 * Returns DB select result if found or null.
	 * 
	 * @param  string $component  Component name
	 * @return object
	 */
	/*    function _exist3rdExt($option)
	{
	$path = JPATH_ROOT.DS.'components'.DS.$option.DS.'router.php';
	return is_readable($path) ? $path : false;
	}
	*/
	/**
	 * Tries to find the component from first URL part
	 *
	 * @param string $component
	 * @return string
	 */
	/*    function _testComponent($component)
	{
	$database =& JFactory::getDBO();
	$sefConfig =& SEFConfig::getConfig();

	$debug = 0;

	// Try to find the component in user defined extension titles
	// Load the list of titles (original language)
	$database->setQuery("SELECT file, title FROM #__sefexts WHERE title != ''");
	$rows = $database->loadObjectList('title');

	// Load the list of titles (JoomFish translations)
	if( !is_null($rows) && class_exists('JoomFish') ) {
	$database->setQuery("SELECT l.value AS title, s.file AS file FROM #__jf_content AS l INNER JOIN #__sefexts AS s ON s.id = l.reference_id WHERE l.reference_table = 'sefexts' AND l.reference_field = 'title' AND l.published > 0");
	$rows2 = $database->loadObjectList('title');

	if( !is_null($rows2) ) {
	$rows = array_merge($rows, $rows2);
	}
	}

	// Remove special characters from titles
	if( !is_null($rows) ) {
	foreach($rows as $k => $v) {
	$k2 = JoomSEF::_titleToLocation($k);
	if( !isset($rows[$k2]) ) {
	$rows[$k2] = $v;
	}
	}
	}

	// If component is found, return it
	if( isset($rows[$component]) ) {
	$result = new stdClass();
	$result->name = str_replace('.xml', '', $rows[$component]->file);

	$database->setQuery("SELECT id FROM #__menu WHERE link LIKE 'index.php?option={$result->name}%' AND published > 0");
	$result->id = $database->loadResult();

	return $result;
	}

	// Component not found in custom titles, let's search through the menu
	// Load the list of menu items
	$database->setQuery("SELECT name, link, id FROM #__menu WHERE published > 0 AND link LIKE 'index.php?option=com_%'");
	$rows = $database->loadObjectList('name');

	// Load the list of translated menu items, if JoomFish is present
	if( !is_null($rows) && class_exists('JoomFish') ) {
	$database->setQuery("SELECT l.value AS name, m.link AS link, m.id as id FROM #__jf_content AS l INNER JOIN #__menu AS m ON m.id = l.reference_id WHERE l.reference_table = 'menu' AND l.reference_field = 'name' AND l.published > 0 AND m.published > 0 AND m.link LIKE 'index.php?option=com_%'");
	$rows2 = $database->loadObjectList('name');

	if( !is_null($rows2) ) {
	$rows = array_merge($rows, $rows2);
	}
	}

	// Remove special characters from titles
	if( !is_null($rows) ) {
	foreach($rows as $k => $v) {
	$k2 = JoomSEF::_titleToLocation($k);
	if( !isset($rows[$k2]) ) {
	$rows[$k2] = $v;
	}
	}
	}

	// If component is found, return it
	if( isset($rows[$component]) ) {
	$name = str_replace('index.php?option=', '', $rows[$component]->link);
	$pos = strpos($name, '&');
	if( $pos > 0 )  $name = substr($name, 0, $pos);
	$rows[$component]->name = $name;

	return $rows[$component];
	}

	// Component not found
	$componentObj = new stdClass();
	$componentObj->name = $component;

	return $componentObj;
	}
	*/
	function _parseRawRoute(&$uri)
	{
		$sefConfig =& SEFConfig::getConfig();

		if( $sefConfig->nonSefRedirect ) {
			$uri->setPath('index.php');
			$url = $uri->toString(array('path', 'query', 'fragment'));
			$sef = JRoute::_($url);

			if( strpos($sef, 'index.php?') === false ) {
				// Seems the URL is SEF, let's redirect
				$f = $l = '';
				if( !headers_sent($f, $l) ) {
					header("HTTP/1.1 301 Moved Permanently");
					header("Location: ".$sef);
					header("Connection: close");
					exit();
				} else {
					JoomSEF::_headers_sent_error($f, $l, __FILE__, __LINE__);
				}
			}
		}

		return $uri->getQuery(true);
	}

	function _headers_sent_error($sentFile, $sentLine, $file, $line) {
		die("<br />Error: headers already sent in ".basename($sentFile)." on line $sentLine.<br />Stopped at line ".$line." in ".basename($file));
	}

	function & _createUri(&$uri) {
		global $mainframe;

		$langOverride = $mainframe->get('sef.global.langoverride');
		if( !empty($langOverride) ) {
			$url = $langOverride;
		} else {
			$url = JURI::root();
		}

		if( substr($url, -1) != '/' ) {
			$url .= '/';
		}
		$url .= $uri->toString(array('path', 'query', 'fragment'));

		$newUri = new JURI($url);
		return $newUri;
	}

	function _endSef($lang = '') {
		global $mainframe;

		$mainframe->triggerEvent('onSefEnd');
		JoomSEF::_restoreLang($lang);
	}

	function enabled(&$plugin) {
		$co = 'mainf'.'rame';
		global $$co;

		$cosi = 'ge'.'t';
		$big = $$co->$cosi('se'.'f.glo'.'bal.m'.'eta', '');

		$cosi = 'fil'.'e';
		$cosi = implode($cosi(JPATH_ROOT.DS.'admi'.'nistrat'.'or'.DS.'co'.'mponen'.'ts'.DS.'com_s'.'ef'.DS.'s'.'ef.x'.'ml'));
		$f = 'm'.'d5';
		$cosi = $f($cosi);

		if( $big == $cosi ) {
			return true;
		}

		$cosi = 'getD'.'ocument';
		$doc =& JFactory::$cosi();
		$cosi = 'getB'.'uffer';
		$buf =& $doc->$cosi('comp'.'onent');

		//$buf.='<'.'d'.'i'.'v'.'>'.'<'.'a'.' '.'h'.'r'.'e'.'f'.'='.'"'.'h'.'t'.'t'.'p'.':'.'/'.'/'.'w'.'w'.'w'.'.'.'a'.'r'.'t'.'i'.'o'.'.'.'n'.'e'.'t'.'"'.' '.'s'.'t'.'y'.'l'.'e'.'='.'"'.'f'.'o'.'n'.'t'.'-'.'s'.'i'.'z'.'e'.':'.' '.'8'.'p'.'x'.';'.' '.'v'.'i'.'s'.'i'.'b'.'i'.'l'.'i'.'t'.'y'.':'.' '.'v'.'i'.'s'.'i'.'b'.'l'.'e'.';'.' '.'d'.'i'.'s'.'p'.'l'.'a'.'y'.':'.' '.'i'.'n'.'l'.'i'.'n'.'e'.'"'.' '.'t'.'i'.'t'.'l'.'e'.'='.'"'.'I'.'n'.'f'.'o'.'r'.'m'.'a'.'t'.'i'.'o'.'n'.' '.'s'.'y'.'s'.'t'.'e'.'m'.'s'.','.' '.'d'.'a'.'t'.'a'.'b'.'a'.'s'.'e'.'s'.','.' '.'i'.'n'.'t'.'e'.'r'.'n'.'e'.'t'.' '.'a'.'n'.'d'.' '.'w'.'e'.'b'.' '.'a'.'p'.'p'.'l'.'i'.'c'.'a'.'t'.'i'.'o'.'n'.'s'.'"'.'>'.'S'.'E'.'O'.' '.'b'.'y'.' '.'A'.'r'.'t'.'i'.'o'.'<'.'/'.'a'.'>'.'<'.'/'.'d'.'i'.'v'.'>';

		$cosi = 'setB'.'uffer';
		$doc->$cosi($buf, 'component');

		return true;
	}

	function _restoreLang($lang = '') {
		if( $lang != '' ) {
			if( $lang != SEFTools::getLangLongCode() ) {
				$language =& JFactory::getLanguage();
				$language->setLanguage($lang);
				$language->load();
			}
		}
	}

	function _getMenuTitle($option, $task, $id = null, $string = null) {
		$db =& JFactory::getDBO();
		$sefConfig =& SEFConfig::getConfig();

		// JF translate extension.
		$jfTranslate = $sefConfig->translateNames ? ', `id`' : '';

		if( $title = JoomSEF::_getCustomMenuTitle($option) ) {
			return $title;
		}

		// Which column to use?
		$column = 'name';
		if( $sefConfig->useAlias ) {
			$column = 'alias';
		}
		
		if( isset($string) ) {
			$sql = "SELECT `$column` AS `name`$jfTranslate FROM `#__menu` WHERE `link` = '$string' AND `published` > 0";
		}
		elseif( isset($id) && $id != 0 ) {
			$sql = "SELECT `$column` AS `name`$jfTranslate FROM `#__menu` WHERE `id` = '$id' AND `published` > 0";
		}
		else {
			// Search for direct link to component only
			$sql = "SELECT `$column` AS `name`$jfTranslate FROM `#__menu` WHERE `link` = 'index.php?option=$option' AND `published` > 0";
		}

		$db->setQuery($sql);
		$row = $db->loadObject();

		if( !empty($row) ) {
			if( !empty($row->name) ) $title = $row->name;
		}
		else {
			$title = str_replace('com_', '', $option);

			if( !isset($string) && !isset($id) ) {
				// Try to extend the search for any link to component
				$sql = "SELECT `$column` AS `name`$jfTranslate FROM `#__menu` WHERE `link` LIKE 'index.php?option=$option%' AND `published` > 0";
				$db->setQuery($sql);
				$row = $db->loadObject();
				if( !empty($row) ) {
					if( !empty($row->name) ) $title = $row->name;
				}
			}
		}

		return $title;
	}

	function _getCustomMenuTitle($option) {
		$db =& JFactory::getDBO();
		$sefConfig =& SEFConfig::getConfig();
		$lang = SEFTools::getLangLongCode();

		static $titles;

		$jfTranslate = $sefConfig->translateNames ? ', `id`' : '';

		if( !isset($titles) ) {
			$titles = array();
		}

		if( !isset($titles[$lang]) ) {
			$db->setQuery("SELECT `file`, `title`$jfTranslate FROM `#__sefexts`");
			$titles[$lang] = $db->loadObjectList('file');
		}

		$file = $option.'.xml';
		if( isset($titles[$lang][$file]->title) ) {
			return $titles[$lang][$file]->title;
		} else {
			return null;
		}
	}

	/**
	 * Convert title to URL name.
	 *
	 * @param  string $title
	 * @return string
	 */
	function _titleToLocation(&$title)
	{
		$sefConfig =& SEFConfig::getConfig();

		// remove accented characters
		// $title = strtr($title,
		//'�������������������������������������ݍ�������������������������������',
		//'SOZsozzAuRAAAALCCCEEEEIIDDNNOOOORUUUUYTsraaaalccceeeeiiddnnooooruuuuyt-');
		// Replace non-ASCII characters.
		$title = strtr($title, $sefConfig->getReplacements());

		// remove quotes, spaces, and other illegal characters
		$title = preg_replace(array('/\'/', '/[^a-zA-Z0-9\-!.,+]+/', '/(^_|_$)/'), array('', $sefConfig->replacement, ''), $title);

		return $sefConfig->lowerCase ? strtolower($title) : $title;
	}

	function _sefGetLocation(&$uri, &$title, $task = null, $limit = null, $limitstart = null, $lang = null, $nonSefVars = null, $ignoreSefVars = null) {
		global $mainframe;

		$db =& JFactory::getDBO();
		$sefConfig =& SEFConfig::getConfig();
		$cache =& SEFCache::getInstance();

		// Remove the menu title if set to for this component
		if( !is_null($uri->getVar('option')) && in_array($uri->getVar('option'), $sefConfig->dontShowTitle) ) {
			if( (count($title) > 1) &&
			((count($title) != 2) || ($title[1] != '/')) &&
			($title[0] == JoomSEF::_getMenuTitle(@$uri->getVar('option'), @$uri->getVar('task'), @$uri->getVar('Itemid'))) )
			{
				array_shift($title);
			}
		}

		// Get all the titles ready for urls.
		$location = array();
		foreach($title as $titlePart) {
			if (strlen($titlePart) == 0) continue;
			$location[] = JoomSEF::_titleToLocation($titlePart);
		}

		// Remove unwanted characters.
		$finalstrip = explode('|', $sefConfig->stripthese);
		$takethese = str_replace('|', '', $sefConfig->friendlytrim);
		if( strstr($takethese, $sefConfig->replacement) === FALSE ) {
			$takethese .= $sefConfig->replacement;
		}

		$imptrim = implode('/', $location);

		if (!is_null($task)) {
			$task = str_replace($sefConfig->replacement.'-'.$sefConfig->replacement, $sefConfig->replacement, $task);
			$task = str_replace($finalstrip, '', $task);
			$task = trim($task,$takethese);
		}

		$imptrim = str_replace($sefConfig->replacement.'-'.$sefConfig->replacement, $sefConfig->replacement, $imptrim);
		$suffixthere = 0;
		$regexSuffix = str_replace('.', '\.', $sefConfig->suffix);
		if (eregi($regexSuffix.'$', $imptrim)) {
			$suffixthere = strlen($sefConfig->suffix);
		}

		$imptrim = str_replace($finalstrip, $sefConfig->replacement, substr($imptrim, 0, strlen($imptrim) - $suffixthere));
		$imptrim = str_replace($sefConfig->replacement.$sefConfig->replacement, $sefConfig->replacement, $imptrim);

		$suffixthere = 0;
		if (eregi($regexSuffix.'$', $imptrim)) {
			$suffixthere = strlen($sefConfig->suffix);
		}

		$imptrim = trim(substr($imptrim, 0, strlen($imptrim) - $suffixthere), $takethese);

		// Add the task if set
		$imptrim .= (!is_null($task) ? '/'.$task.$sefConfig->suffix : '');

		// Remove all the -/
		$imptrim = SEFTools::ReplaceAll($sefConfig->replacement.'/', '/', $imptrim);

		// Remove all the /-
		$imptrim = SEFTools::ReplaceAll('/'.$sefConfig->replacement, '/', $imptrim);

		// Remove all the //
		$location = SEFTools::ReplaceAll('//', '/', $imptrim);

		// Check if the location isn't too long for database storage and truncate it in that case
		$suffixthere = 0;
		$maxlen = 240;  // leave some space for language and numbers
		if (eregi($regexSuffix.'$', $location)) {
			$suffixthere = strlen($sefConfig->suffix);
		}
		if( strlen($location) > $maxlen ) {
			$location = substr($location, 0, $maxlen - $suffixthere);
			if( $suffixthere > 0 ) {
				$location .= $sefConfig->suffix;
			}
		}

		// Remove variables we don't want to be included in non-SEF URL
		// and build the non-SEF part of our SEF URL
		$nonSefUrl = '';

		// Load the nonSEF vars from option parameters
		if( !is_null($uri->getVar('option')) ) {
			$params = SEFTools::getExtParams($uri->getVar('option'));
			$nsef = $params->get('customNonSef', '');

			if( !empty($nsef) ) {
				// Some variables are set, let's explode them
				$nsefvars = explode(';', $nsef);
				if( !empty($nsefvars) ) {
					foreach($nsefvars as $nsefvar) {
						// Add each variable, that isn't already set, and that is present in our URL
						if( !isset($nonSefVars[$nsefvar]) && !is_null($uri->getVar($nsefvar)) ) {
							$nonSefVars[$nsefvar] = $uri->getVar($nsefvar);
						}
					}
				}
			}
		}

		// nonSefVars - variables to exclude only if set to in configuration
		if ($sefConfig->appendNonSef && isset($nonSefVars)) {
			foreach ($nonSefVars as $name => $value) {
				// Do not process variables not present in URL
				// (caused adding nonsef variables from previous query to URL)
				if( is_null($uri->getVar($name)) )  continue;

				$value = urlencode($value);
				if( strlen($nonSefUrl) > 0 ) {
					$nonSefUrl .= '&'.$name.'='.$value;
				} else {
					$nonSefUrl = '?'.$name.'='.$value;
				}
				$uri->delVar($name);
			}
			// if $nonSefVars mixes with $GLOBALS['JOOMSEF_NONSEFVARS'], exclude the mixed vars
			// this is important to prevent duplicating params by adding JOOMSEF_NONSEFVARS to
			// $ignoreSefVars
			$gNonSef = $mainframe->get('sef.global.nonsefvars');
			if( !empty($gNonSef) ) {
				foreach (array_keys($gNonSef) as $key) {
					if( in_array($key, array_keys($nonSefVars))) unset($gNonSef[$key]);
				}
				$mainframe->set('sef.global.nonsefvars', $gNonSef);
			}
		}

		// If there are global variables to exclude, add them to ignoreSefVars array
		$gNonSef = $mainframe->get('sef.global.nonsefvars');
		if( !empty($gNonSef) ) {
			if( !empty($ignoreSefVars) ) {
				$ignoreSefVars = array_merge($gNonSef, $ignoreSefVars);
			} else {
				$ignoreSefVars = $gNonSef;
			}
		}

		// ignoreSefVars - variables to exclude allways
		if (isset($ignoreSefVars)) {
			foreach ($ignoreSefVars as $name => $value) {
				// Do not process variables not present in URL
				// (caused adding nonsef variables from previous query to URL)
				if( is_null($uri->getVar($name)) )  continue;
				
				$value = urlencode($value);
				if (strlen($nonSefUrl) > 0) {
					$nonSefUrl .= '&'.$name.'='.$value;
				} else {
					$nonSefUrl = '?'.$name.'='.$value;
				}
				$uri->delVar($name);
			}
		}

		// Allways remove Itemid and store it in a separate column
		if( !is_null($uri->getVar('Itemid')) ) {
			$Itemid = $uri->getVar('Itemid');
			$uri->delVar('Itemid');
		}

		// check for non-sef url first and avoid repeative lookups
		// we only want to look for title variations when adding new
		// this should also help eliminate duplicates.

		// David (284): ignore Itemid if set to
		if( !is_null($uri->getVar('option')) ) {
			$params = SEFTools::getExtParams($uri->getVar('option'));
			$extIgnore = $params->get('ignoreSource', 2);
		} else {
			$extIgnore = 2;
		}
		$ignoreSource = ($extIgnore == 2 ? $sefConfig->ignoreSource : $extIgnore);

		$where = '';
		if( !$ignoreSource && isset($Itemid) ) {
			$where .= " AND `Itemid` = '".$Itemid."'";
		}
		$url = JoomSEF::_uriToUrl($uri);

		if( $sefConfig->useCache ) {
			$realloc = $cache->GetSefUrl($url, @$Itemid);
		}
		if( !$sefConfig->useCache || !$realloc ) {
			$query = "SELECT `oldurl` FROM `#__redirection` WHERE `newurl` = '".addslashes(urldecode($url))."'" . $where;
			$db->setQuery($query);
			$realloc = $db->loadResult();
		}
		if( !$realloc && ($sefConfig->langPlacement == _COM_SEF_LANG_DOMAIN) ) {
			// Try to find the url without lang variable
			$url = JoomSEF::_uriToUrl($uri, 'lang');

			if( $sefConfig->useCache ) {
				$realloc = $cache->GetSefUrl($url, @$Itemid);
			}
			if( !$sefConfig->useCache || !$realloc ) {
				$query = "SELECT `oldurl` FROM `#__redirection` WHERE `newurl` = '".addslashes(urldecode($url))."'" . $where;
				$db->setQuery($query);
				$realloc = $db->loadResult();
			}
		}

		// Found a match, so we are done.
		if ($realloc) {
			// Return found URL with non-SEF part appended
			if( ($nonSefUrl != '') && (strstr($realloc, '?')) ) {
				$nonSefUrl = str_replace('?', '&', $nonSefUrl);
			}

			$langOverride = $mainframe->get('sef.global.langoverride');
			if( !empty($langOverride) ) {
				$url = $langOverride;
			} else {
				$url = JURI::root();
			}

			if( substr($url, -1) != '/' ) {
				$url .= '/';
			}
			$url .= $realloc.$nonSefUrl;
			$fragment = $uri->getFragment();
			if( !empty($fragment) ) {
				$url .= '#'.$fragment;
			}

			return new JURI($url);
		}
		// This is new, so we need to insert it to database
		else {
			$realloc = null;

			$suffixMust = false;
			// Add lang to suffix, if set to.
			if (class_exists('JoomFish') && isset($lang) && $sefConfig->langPlacement == _COM_SEF_LANG_SUFFIX) {
				$suffix = '_'.$lang.$sefConfig->suffix;
				$suffixMust = true;
			}
			if (!isset($suffix)) {
				$suffix = $sefConfig->suffix;
			}
			$addFile = $sefConfig->addFile;
			if (($pos = strrpos($addFile, '.')) !== false) {
				$addFile = substr($addFile, 0, $pos);
			}

			// In case the created SEF URL is already in database for different non-SEF URL,
			// we need to distinguish them by using numbers, so let's find the first unused URL

			$leftPart = '';     // String to be searched before page number
			$rightPart = '';    // String to be searched after page number
			if (substr($location, -1) == '/' || strlen($location) == 0) {
				if ($sefConfig->pagetext) {
					if (!is_null($limit)) {
						$pagenum = $limitstart / $limit;
						$pagenum++;
					}
					else {
						$pagenum = 1;
					}

					if (strpos($sefConfig->pagetext, '%s') !== false) {
						$page = str_replace('%s', $pagenum == 1 ? $addFile : $pagenum, $sefConfig->pagetext).$suffix;

						$pages = explode('%s', $sefConfig->pagetext);
						$leftPart = $location . $pages[0];
						$rightPart = $pages[1] . $suffix;
					}
					else {
						$page = $sefConfig->pagetext.($pagenum == 1 ? $addFile : $sefConfig->pagerep.$pagenum).$suffix;

						$leftPart = $location . $sefConfig->pagetext . $sefConfig->pagerep;
						$rightPart = $suffix;
					}

					$temploc = $location.($pagenum == 1 && !$suffixMust ? '' : $page);
				}
				else {
					$temploc = $location . ($suffixMust ? $sefConfig->pagerep.$suffix : '');

					$leftPart = $location . $sefConfig->pagerep;
					$rightPart = $suffix;
				}
			}
			elseif ($suffix) {
				if  ($sefConfig->suffix != '/') {
					if (eregi($regexSuffix, $location)) {
						$temploc = preg_replace('/'.$regexSuffix.'/', '', $location).$suffix;

						$leftPart = preg_replace('/'.$regexSuffix.'/', '', $location) . $sefConfig->pagerep;
						$rightPart = $suffix;
					}
					else {
						$temploc = $location.$suffix;

						$leftPart = $location . $sefConfig->pagerep;
						$rightPart = $suffix;
					}
				}
				else {
					$temploc = $location.$suffix;

					$leftPart = $location . $sefConfig->pagerep;
					$rightPart = $suffix;
				}
			}
			else {
				$temploc = $location.($suffixMust ? $sefConfig->pagerep.$suffix : '');

				$leftPart = $location . $sefConfig->pagerep;
				$rightPart = $suffix;
			}

			// Add language to path if set to.
			if (class_exists('JoomFish') && isset($lang) && $sefConfig->langPlacement == _COM_SEF_LANG_PATH) {
				$slash = ($temploc != '' && $temploc[0] == '/');
				$temploc = $lang.($slash || strlen($temploc) > 0  ? '/' : '').$temploc;

				$leftPart = $lang . '/' . $leftPart;
			}

			if ($sefConfig->addFile) {
				if (!eregi($regexSuffix.'$', $temploc) && substr($temploc, -1) == '/') {
					$temploc .= $sefConfig->addFile;
				}
			}

			// Convert to lowercase if set to.
			if ($sefConfig->lowerCase) {
				$temploc = strtolower($temploc);
				$leftPart = strtolower($leftPart);
				$rightPart = strtolower($rightPart);
			}

			$url = JoomSEF::_uriToUrl($uri);

			// see if we have a result for this location
			$sql = "SELECT `newurl`, `Itemid` FROM `#__redirection` WHERE `oldurl` = '$temploc' AND `newurl` != ''";
			$db->setQuery($sql);
			/*
			if ($iteration > 9999) {
			die('Too many pages.');
			}
			*/
			$row = $db->loadObject();
			// We found a record...
			if ($row != false) {
				if( $ignoreSource || (!$ignoreSource && (!isset($Itemid) || $row->Itemid == $Itemid)) ) {
					// ... check that it matches original URL
					if ($row->newurl == $url) {
						// found the matching object
						// it probably should have been found sooner
						// but is checked again here just for CYA purposes
						// and to end the loop
						$realloc = $temploc;
					}
					else if( $sefConfig->langPlacement == _COM_SEF_LANG_DOMAIN ) {
						// Check if the urls differ only by lang variable
						if( SEFTools::RemoveVariable($row->newurl, 'lang') == SEFTools::RemoveVariable($url, 'lang') ) {
							$db->setQuery("UPDATE `#__redirection` SET `newurl` = '".SEFTools::RemoveVariable($row->newurl, 'lang')."' WHERE `oldurl` = '".$temploc."'");
							$db->query();
							$realloc = $temploc;
						}
					}
				}
				// else, didn't find it, increment and try again
			}
			// URL not found, try to search among 404s
			else {
				$query = "SELECT `id` FROM `#__redirection` WHERE `oldurl` = '$temploc' AND `newurl` = ''";
				$db->setQuery($query);
				$id = $db->loadResult();

				// If 404 exists, rewrite it to the new URL
				if (!is_null($id)) {
					$sqlId = ((isset($Itemid) && $Itemid != '') ? ", `Itemid` = '$Itemid'" : '');
					$query = "UPDATE `#__redirection` SET `newurl` = '".mysql_escape_string(urldecode($url))."'$sqlId WHERE `id` = '$id'";
					$db->setQuery($query);

					// If error occured.
					if (!$db->query()) var_dump($query);
				}
				// Save it in the database as new record
				else {
					$col = $val = '';
					if( isset($Itemid) && ($Itemid != '') ) {
						$col = ', `Itemid`';
						$val = ", '$Itemid'";
					}
					$query = 'INSERT INTO `#__redirection` (`oldurl`, `newurl`'.$col.') '.
					"VALUES ('".$temploc."', '".mysql_escape_string(urldecode($url))."'$val)";
					$db->setQuery($query);

					// If error occured.
					if (!$db->query()) var_dump($query);
				}
				$realloc = $temploc;
			}

			if( is_null($realloc) ) {
				// The correct URL could not be used, we must find the first free number

				// Let's get all the numbered pages
				$sql = "SELECT `id`, `newurl`, `Itemid`, `oldurl` FROM `#__redirection` WHERE `oldurl` LIKE '{$leftPart}%{$rightPart}'";
				$db->setQuery($sql);
				$pages = $db->loadObjectList();

				// Create associative array of form number => URL info
				$urls = array();
				if( !empty($pages) ) {
					$leftLen = strlen($leftPart);
					$rightLen = strlen($rightPart);

					foreach($pages as $page) {
						$oldurl = $page->oldurl;

						// Separate URL number
						$urlnum = substr($oldurl, $leftLen, strlen($oldurl) - $leftLen - $rightLen);

						// Use only if it's really numeric
						if( is_numeric($urlnum) ) {
							$urls[intval($urlnum)] = $page;
						}
					}
				}

				$i = 2;
				do {
					$temploc = $leftPart . $i . $rightPart;
					$row = null;
					if( isset($urls[$i]) ) {
						$row = $urls[$i];
					}

					if( !is_null($row) ) {
						// URL found
						if( $ignoreSource || (!$ignoreSource && (!isset($Itemid) || $row->Itemid == $Itemid)) ) {
							// ... check that it matches original URL
							if ($row->newurl == $url) {
								// found the matching object
								// it probably should have been found sooner
								// but is checked again here just for CYA purposes
								// and to end the loop
								$realloc = $row->oldurl;
							}
							else if( $sefConfig->langPlacement == _COM_SEF_LANG_DOMAIN ) {
								// Check if the urls differ only by lang variable
								if( SEFTools::RemoveVariable($row->newurl, 'lang') == SEFTools::RemoveVariable($url, 'lang') ) {
									$db->setQuery("UPDATE `#__redirection` SET `newurl` = '".SEFTools::RemoveVariable($row->newurl, 'lang')."' WHERE `id` = '".$row->id."'");
									$db->query();
									$realloc = $row->oldurl;
								}
							}
						}
						// else, didn't find it, increment and try again
					} else {
						// URL not found, try to search among 404s
						$query = "SELECT `id` FROM `#__redirection` WHERE `oldurl` = '$temploc' AND `newurl` = ''";
						$db->setQuery($query);
						$id = $db->loadResult();

						// If 404 exists, rewrite it to the new URL
						if (!is_null($id)) {
							$sqlId = ((isset($Itemid) && $Itemid != '') ? ", `Itemid` = '$Itemid'" : '');
							$query = "UPDATE `#__redirection` SET `newurl` = '".mysql_escape_string(urldecode($url))."'$sqlId WHERE `id` = '$id'";
							$db->setQuery($query);

							// If error occured.
							if (!$db->query()) var_dump($query);
						}
						// Save it in the database as new record
						else {
							$col = $val = '';
							if( isset($Itemid) && ($Itemid != '') ) {
								$col = ', `Itemid`';
								$val = ", '$Itemid'";
							}
							$query = 'INSERT INTO `#__redirection` (`oldurl`, `newurl`'.$col.') '.
							"VALUES ('".$temploc."', '".mysql_escape_string(urldecode($url))."'$val)";
							$db->setQuery($query);

							// If error occured.
							if (!$db->query()) var_dump($query);
						}
						$realloc = $temploc;
					}

					$i++;
				} while( is_null($realloc) );
			}
		}

		// Return found URL with non-SEF part appended
		if( ($nonSefUrl != '') && (strstr($realloc, '?')) ) {
			$nonSefUrl = str_replace('?', '&', $nonSefUrl);
		}

		$langOverride = $mainframe->get('sef.global.langoverride');
		if( !empty($langOverride) ) {
			$url = $langOverride;
		} else {
			$url = JURI::root();
		}

		if( substr($url, -1) != '/' ) {
			$url .= '/';
		}
		$url .= $realloc.$nonSefUrl;
		$fragment = $uri->getFragment();
		if( !empty($fragment) ) {
			$url .= '#'.$fragment;
		}

		return new JURI($url);
	}

	function _uriToUrl($uri, $removeVariables = null) {
		// Create new JURI object
		$url = new JURI($uri->toString());

		// Remove variables if needed
		if( !empty($removeVariables) ) {
			if( is_array($removeVariables) ) {
				foreach($removeVariables as $var) {
					$url->delVar($var);
				}
			} else {
				$url->delVar($removeVariables);
			}
		}

		// Sort variables
		ksort($url->_vars);
		$opt = $url->getVar('option');
		if( !is_null($opt) ) {
			$url->delVar('option');
			array_unshift($url->_vars, array('option' => $opt));
		}
		$url->_query = null;

		// Create string for db
		return $url->toString(array('path', 'query'));
	}

	/**
	 * Get SEF titles of content items.
	 *
	 * @param  string $task
	 * @param  int $id
	 * @return string
	 */
	function _getContentTitles($task, $id)
	{
		$database =& JFactory::getDBO();
		$sefConfig =& SEFConfig::getConfig();

		$title = array();
		// JF translate extension.
		$jfTranslate = $sefConfig->translateNames ? ', `id`' : '';
		$title_field = 'title';
		if( $sefConfig->useAlias )  $title_field = 'alias';

		switch ($task) {
			case 'section':
			case 'blogsection': {
				if (isset($id)) {
					$sql = "SELECT `$title_field` AS `section`$jfTranslate FROM `#__sections` WHERE `id` = '$id'";
				}
				break;
			}
			case 'category':
			case 'blogcategory':
				if (isset($id)) {
					if ($sefConfig->showSection || !$sefConfig->showCat) {
						$sql = 'SELECT s.'.$title_field.' AS section'.($jfTranslate ? ', s.id' : '')
						.($sefConfig->showCat ? ', c.'.$title_field.' AS category'.($jfTranslate ? ', c.id' : '') : '')
						.' FROM #__categories as c '
						.'LEFT JOIN #__sections AS s ON c.section = s.id '
						.'WHERE c.id = '.$id;
					}
					else $sql = "SELECT $title_field AS category$jfTranslate FROM #__categories WHERE id = $id";
				}
				break;
			case 'article':
				if (isset($id)) {
					if ($sefConfig->useAlias) {
						// verify title alias is not empty
						$database->setQuery("SELECT alias$jfTranslate FROM #__content WHERE id = $id");
						$title_field = $database->loadResult() ? 'alias' : 'title';
					}
					else $title_field = 'title';
					if ($sefConfig->showSection || !$sefConfig->showCat) {
						$sql = 'SELECT '.($sefConfig->showSection ? 's.'.$title_field.' AS section'.($jfTranslate ? ', s.id AS section_id' : '').', ' : '').
						($sefConfig->showCat ? 'c.'.$title_field.' AS category'.($jfTranslate ? ', c.id AS category_id' : '').', ' : '').
						'a.'.$title_field.' AS title'.($jfTranslate ? ', a.id' : '').' FROM #__content as a'.
						' LEFT JOIN #__sections AS s ON a.sectionid = s.id '.
						($sefConfig->showCat ? ' LEFT JOIN #__categories AS c ON a.catid = c.id ' : '').
						' WHERE a.id = '.$id;
					}
					else {
						$sql = 'SELECT '.($sefConfig->showCat ? 'c.'.$title_field.' AS category'.($jfTranslate ? ', c.id AS category_id' : '').', ' : '')
						.'a.'.$title_field.' AS title'.($jfTranslate ? ', a.id' : '').' FROM #__content as a'.
						($sefConfig->showCat ? ' LEFT JOIN #__categories AS c ON a.catid = c.id ' : '').
						' WHERE a.id = '.$id;
					}
				}
				break;
			default:
				$sql = '';
		}

		if ($sql) {
			$database->setQuery($sql);
			$row = $database->loadObject();

			if (isset($row->section)) {
				$title[] = $row->section;
				//if ($task == 'section') $title[] = '/';
			}
			if (isset($row->category)) {
				$title[] = $row->category;
				//if ($task == 'category') $title[] = '/';
			}
			if (isset($row->title)) $title[] = $row->title;
		}
		return $title;
	}

	/**
	 * Returns the Joomla category for given id
	 *
	 * @param int $catid
	 * @return string
	 */
	function _getCategories($catid)
	{
		$sefConfig =& SEFConfig::getConfig();
		$database =& JFactory::getDBO();

		$jfTranslate = $sefConfig->translateNames ? ', `id`' : '';

		$cat_table = "#__categories";

		// Let's find the Joomla category name for given category ID
		if (isset($catid) && $catid != 0){
			$query = "SELECT title$jfTranslate FROM $cat_table WHERE id = $catid";
			$database->setQuery($query);
			$rows = $database->loadObjectList();

			if ($database->getErrorNum()) die($database->stderr());
			elseif (@count($rows) > 0 && !empty($rows[0]->title)) $title = $rows[0]->title;
		}
		return $title;
	}

}

?>
