<?php
/**
 * Banners SEF extension for Joomla!
 *
 * @author      $Author: David Jozefov $
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access.');

class SefExt_com_banners extends SefExt
{
    function GetBannerName($id) {
        $database =& JFactory::getDBO();
        $sefConfig =& SEFConfig::getConfig();
        
        $jfTranslate = $sefConfig->translateNames ? ', `bid`' : '';
        $query = "SELECT `name`$jfTranslate FROM `#__banner` WHERE `bid` = '$id'";
        $database->setQuery($query);
        $row = $database->loadObject();
        
        return isset($row->name) ? $row->name : '';
    }
    
    function create(&$uri) {
        $sefConfig =& SEFConfig::getConfig();
        
        $vars = $uri->getQuery(true);
        extract($vars);
        
        //$title[] = 'banners';
        //$title[] = '/';
        //$title[] = $task.$bid.$sefConfig->suffix;
        $title[] = JoomSEF::_getMenuTitle(@$option, @$task, @$Itemid);
        
        switch(@$task) {
            case 'click':
                $title[] = $this->GetBannerName($bid);
                unset($task);
                break;
        }

        $newUri = $uri;
        if (count($title) > 0) $newUri = JoomSEF::_sefGetLocation($uri, $title, @$task, null, null, @$vars['lang']);
        
        return $newUri;
    }
}
?>
