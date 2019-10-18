<?php
/**
 * NewsFeeds SEF extension for Joomla!
 *
 * @author      $Author: David Jozefov $
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access.');

class SefExt_com_newsfeeds extends SefExt
{
    function beforeCreate(&$uri) {
        // Remove the part after ':' from variables
        if( !is_null($uri->getVar('id')) )       SEFTools::fixVariable($uri, 'id');
        if( !is_null($uri->getVar('catid')) )    SEFTools::fixVariable($uri, 'catid');

        return;
    }
    
    function create(&$uri) {
        $sefConfig =& SEFConfig::getConfig();
        $database =& JFactory::getDBO();
        
        $vars = $uri->getQuery(true);
        extract($vars);
        
        // JF translate extension.
        $jfTranslate = $sefConfig->translateNames ? ', `id`' : '';

        $title = array();
        
        $title[] = JoomSEF::_getMenuTitle($option, @$this_task);
        
        if( @$view == 'category' && isset($id) ) {
            $title[] = JoomSEF::_getCategories($id);
        }

        if (@$view == "newsfeed") {
            if( !empty($catid) )    $title[] = JoomSEF::_getCategories($catid);
            
            if( empty($feedid) )    $feedid = $id;
            
            $database->setQuery("SELECT `name`$jfTranslate FROM #__newsfeeds WHERE id = '$feedid'");
            $rows = $database->loadObjectList();

            if ($database->getErrorNum()) {
                die($database->stderr());
            }
            elseif (@count($rows) > 0 && !empty($rows[0]->name)) {
                $title[] = $rows[0]->name;
            }
        }

        $newUri = $uri;
        if (count($title) > 0) {
            $newUri = JoomSEF::_sefGetLocation($uri, $title, null, null, null, @$vars['lang']);
        }
        
        return $newUri;
    }
}
?>
