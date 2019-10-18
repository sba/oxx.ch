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
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class SEFModelConfig extends JModel
{
    function __construct()
    {
        parent::__construct();
    }

    function &getLists()
    {
        $db =& JFactory::getDBO();
        $sefConfig = SEFConfig::getConfig();

        $std_opt = 'class="inputbox" size="2"';

        $lists['enabled']        = JHTML::_('select.booleanlist', 'enabled',         $std_opt, $sefConfig->enabled);
        $lists['lowerCase']      = JHTML::_('select.booleanlist', 'lowerCase',       $std_opt, $sefConfig->lowerCase);
        $lists['showSection']    = JHTML::_('select.booleanlist', 'showSection',     $std_opt, $sefConfig->showSection);
        $lists['showCat']        = JHTML::_('select.booleanlist', 'showCat',         $std_opt, $sefConfig->showCat);
        $lists['disableNewSEF']  = JHTML::_('select.booleanlist', 'disableNewSEF',   $std_opt, $sefConfig->disableNewSEF);
        $lists['dontRemoveSid']  = JHTML::_('select.booleanlist', 'dontRemoveSid',   $std_opt, $sefConfig->dontRemoveSid);
        $lists['setQueryString'] = JHTML::_('select.booleanlist', 'setQueryString',   $std_opt, $sefConfig->setQueryString);
        $lists['parseJoomlaSEO'] = JHTML::_('select.booleanlist', 'parseJoomlaSEO',   $std_opt, $sefConfig->parseJoomlaSEO);

        // lang placement
        $langPlacement[] = JHTML::_('select.option', _COM_SEF_LANG_PATH,   JText::_('include in path'));
        $langPlacement[] = JHTML::_('select.option', _COM_SEF_LANG_SUFFIX, JText::_('add as suffix'));
        // TODO: $langPlacement[] = JHTML::_('select.option', _COM_SEF_LANG_DOMAIN, JText::_('use different domains'));
        $langPlacement[] = JHTML::_('select.option', _COM_SEF_LANG_NONE,   JText::_('do not add'));
        $lists['langPlacement'] = JHTML::_('select.radiolist', $langPlacement, 'langPlacement', $std_opt, 'value', 'text', $sefConfig->langPlacement);

/* TODO: lang domains
        // language domains
        $db->setQuery("SELECT `id`, `code`, `name` FROM `#__languages` WHERE `active`='1' ORDER BY `ordering`");
        $langs = $db->loadObjectList();
        if( @count(@$langs) ) {
            foreach($langs as $lang) {
                $langlist[] = '<td>'.$lang->name.':</td><td><input type="text" name="langDomain['.$lang->code.']" size="45" class="inputbox" value="'.(isset($sefConfig->langDomain[$lang->code]) ? $sefConfig->langDomain[$lang->code] : trim(JURI::root(), '/')).'" /></td>';
            }
            $lists['langDomains'] = '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>'. implode('</tr><tr>', $langlist) .'</tr></table>';
        }
*/
        $lists['record404']      = JHTML::_('select.booleanlist', 'record404',      $std_opt, $sefConfig->record404);
        $lists['msg404']         = JHTML::_('select.booleanlist', 'showMessageOn404',      $std_opt, $sefConfig->showMessageOn404);
        $lists['use404itemid']   = JHTML::_('select.booleanlist', 'use404itemid',      $std_opt, $sefConfig->use404itemid);
        $lists['nonSefRedirect'] = JHTML::_('select.booleanlist', 'nonSefRedirect', $std_opt, $sefConfig->nonSefRedirect);
        $lists['useMoved']       = JHTML::_('select.booleanlist', 'useMoved',       $std_opt, $sefConfig->useMoved);
        $lists['useMovedAsk']    = JHTML::_('select.booleanlist', 'useMovedAsk',    $std_opt, $sefConfig->useMovedAsk);
        $lists['alwaysUseLang']  = JHTML::_('select.booleanlist', 'alwaysUseLang',  $std_opt, $sefConfig->alwaysUseLang);
        $lists['translateNames'] = JHTML::_('select.booleanlist', 'translateNames', $std_opt, $sefConfig->translateNames);
        $lists['excludeSource']  = JHTML::_('select.booleanlist', 'excludeSource',  $std_opt, $sefConfig->excludeSource);
        $lists['reappendSource'] = JHTML::_('select.booleanlist', 'reappendSource', $std_opt, $sefConfig->reappendSource);
        $lists['ignoreSource']   = JHTML::_('select.booleanlist', 'ignoreSource',   $std_opt, $sefConfig->ignoreSource);
        $lists['appendNonSef']   = JHTML::_('select.booleanlist', 'appendNonSef',   $std_opt, $sefConfig->appendNonSef);
        $lists['transitSlash']   = JHTML::_('select.booleanlist', 'transitSlash',   $std_opt, $sefConfig->transitSlash);
        $lists['useCache']       = JHTML::_('select.booleanlist', 'useCache',       $std_opt, $sefConfig->useCache);
        $lists['cacheSize']      = '<input type="text" name="cacheSize" size="10" class="inputbox" value="'.$sefConfig->cacheSize.'" />';
        $lists['cacheMinHits']      = '<input type="text" name="cacheMinHits" size="10" class="inputbox" value="'.$sefConfig->cacheMinHits.'" />';

        $aliases[] = JHTML::_('select.option', '0', JText::_('Full Title'));
        $aliases[] = JHTML::_('select.option', '1', JText::_('Title Alias'));
        $lists['useAlias'] = JHTML::_('select.radiolist', $aliases, 'useAlias', $std_opt, 'value', 'text', $sefConfig->useAlias);

        // get a list of the static content items for 404 page
        $query = "SELECT id, title"
        ."\n FROM #__content"
        ."\n WHERE sectionid = 0 AND title != '404'"
        ."\n AND catid = 0"
        ."\n ORDER BY ordering"
        ;

        $db->setQuery( $query );
        $items = $db->loadObjectList();

        $options = array(JHTML::_('select.option', 0, '('.JText::_('Default 404 Page').')'));
        $options[] = JHTML::_('select.option', 9999999, '('.JText::_('Front Page').')');

        // assemble menu items to the array
        foreach ( $items as $item ) {
            $options[] = JHTML::_('select.option', $item->id, $item->title);
        }

        $lists['page404'] = JHTML::_('select.genericlist', $options, 'page404', 'class="inputbox" size="1"', 'value', 'text', $sefConfig->page404 );

        // Get the menu selection list
        $selections = JHTML::_('menu.linkoptions');
        $lists['itemid404'] = JHTML::_('select.genericlist', $selections, 'itemid404', 'class="inputbox" size="15"', 'value', 'text', $sefConfig->itemid404 );
        
        $sql="SELECT `id`, `introtext` FROM `#__content` WHERE `title` = '404'";
        $row = null;
        $db->setQuery($sql);
        $row = $db->loadObject();

        $lists['txt404'] = isset($row->introtext) ? $row->introtext : JText::_('<h1>404: Not Found</h1><h4>Sorry, but the content you requested could not be found</h4>');

        // get list of installed components for advanced config
        $installed_components = $undefined_components = array();
        $sql = 'SELECT SUBSTRING(link,8) AS name FROM #__components WHERE CHAR_LENGTH(link) > 0 ORDER BY name';
        $db->setQuery($sql);
        $installed_components = $db->loadResultArray();
        $undefined_components= array_values(array_diff($installed_components,array_intersect($sefConfig->predefined, $installed_components)));

        // build mode list and create the list
        $mode = array();
        $mode[] = JHTML::_('select.option', 0, JText::_('(use default handler)'));
        $mode[] = JHTML::_('select.option', 1, JText::_('nocache'));
        $mode[] = JHTML::_('select.option', 2, JText::_('skip'));

        $db->setQuery("SELECT file, title FROM #__sefexts");
        $titles = $db->loadAssocList('file');

        while (list($index, $name) = each($undefined_components)) {
            // List of handlers
            $selectedmode = ((in_array($name, $sefConfig->nocache)) * 1) + ((in_array($name, $sefConfig->skip)) * 2);
            $lists['adv_config'][$name] = JHTML::_('select.genericlist', $mode, $name, 'class="inputbox" size="1"', 'value', 'text', $selectedmode);

            // List of titles
            $title = isset($titles[$name.'.xml']['title']) ? $titles[$name.'.xml']['title'] : '';
            $lists['titles'][$name] = '<input type="text" name="title['.$name.']" size="40" class="inputbox" value="'.$title.'" />';

            // List of menu title checkboxes
            $checked = in_array($name, $sefConfig->dontShowTitle);
            $lists['dontshow'][$name] = '<input type="checkbox" name="dontshow['.$name.']" class="inputbox" '.($checked ? 'checked="checked" ' : '').'/>';
        }

        $this->_lists = $lists;

        return $this->_lists;
    }

    /**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
    function store()
    {
        $db =& JFactory::getDBO();
        $sefConfig =& SEFConfig::getConfig();
        $sef_config_file = JPATH_COMPONENT . DS . 'configuration.php';

        //set skip and nocache arrays
        $sefConfig->skip = array();
        $sefConfig->nocache = array();
        foreach($_POST as $key => $value) {
            $sefConfig->set($key, $value);
            $this->advancedConfig($key, $value);
        }

        // Save extensions not to show the menu titles in URL
        $sefConfig->dontShowTitle = array();
        if( isset($_POST['dontshow']) && is_array($_POST['dontshow']) ) {
            foreach(array_keys($_POST['dontshow']) as $name) {
                array_push($sefConfig->dontShowTitle, $name);
            }
        }

        $sql = 'SELECT id  FROM #__content WHERE `title` = "404"';
        $db->setQuery( $sql );

        $introtext = (get_magic_quotes_gpc() ? $_POST['introtext'] : addslashes($_POST['introtext']));
        if ($id = $db->loadResult()){
            $sql = 'UPDATE #__content SET introtext="'.$introtext.'",  modified ="'.date("Y-m-d H:i:s").'" WHERE `id` = "'.$id.'";';
        }
        else {
            $sql='SELECT MAX(id)  FROM #__content';
            $db->setQuery($sql);
            if ($max = $db->loadResult()) {
                $max++;
                $sql = 'INSERT INTO #__content (id, title, alias, introtext, `fulltext`, state, sectionid, mask, catid, created, created_by, created_by_alias, modified, modified_by, checked_out, checked_out_time, publish_up, publish_down, images, urls, attribs, version, parentid, ordering, metakey, metadesc, access, hits) '.
                'VALUES( "'.$max.'", "404", "404", "'.$introtext.'", "", "1", "0", "0", "0", "2004-11-11 12:44:38", "62", "", "'.date("Y-m-d H:i:s").'", "0", "62", "2004-11-11 12:45:09", "2004-10-17 00:00:00", "0000-00-00 00:00:00", "", "", "menu_image=-1\nitem_title=0\npageclass_sfx=\nback_button=\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0", "1", "0", "0", "", "", "0", "750");';
            }
        }

        $db->setQuery( $sql );
        if (!$db->query()) {
            echo "<script> alert('".addslashes($db->getErrorMsg())."'); window.history.go(-1); </script>\n";
            exit();
        }

        // Save the extension titles
        if( isset($_POST['title']) && is_array($_POST['title']) ) {
            foreach($_POST['title'] as $name => $title) {
                $file = $name.'.xml';
                $db->setQuery("SELECT file, title FROM #__sefexts WHERE file = '$file'");
                $row = $db->loadObject();

                if(!$row) {
                    $db->setQuery("INSERT INTO #__sefexts (file, title) VALUES ('$file', '$title')");
                    if(!$db->query()) {
                        echo "<script> alert('".addslashes($db->getErrorMsg())."'); window.history.go(-1); </script>\n";
                        exit();
                    }
                }
                elseif( $row->title != $title ) {
                    $db->setQuery("UPDATE #__sefexts SET title = '$title' WHERE file = '$file'");
                    if(!$db->query()) {
                        echo "<script> alert('".addslashes($db->getErrorMsg())."'); window.history.go(-1); </script>\n";
                        exit();
                    }
                }
            }
        }

        $purge = JRequest::getVar('purge', '0', 'POST');
        $config_written = $sefConfig->saveConfig(0, $purge);

        if( $config_written != 0 ) {
            return true;
        } else {
            return false;
        }
    }

    function advancedConfig($key,$value)
    {
        $sefConfig =& SEFConfig::getConfig();

        if ((strpos($key, 'com_')) !== false) {
            switch ($value) {
                case 1: {
                    array_push($sefConfig->nocache, $key);
                    break;
                }
                case 2: {
                    array_push($sefConfig->skip, $key);
                    break;
                }
            }
        }

    }

}
?>
