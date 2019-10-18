<?php
/**
 * Weblinks SEF extension for Joomla!
 *
 * @author      $Author: David Jozefov $
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access.');

class SefExt_com_weblinks extends SefExt
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
        
        // JF translate extension.
        $jfTranslate = $sefConfig->translateNames ? ', `id`' : '';

        $vars = $uri->getQuery(true);
        extract($vars);

        $title = array();
        $title[] = JoomSEF::_getMenuTitle($option, @$this_task);

        if( @$view == 'category' ) {
            $title[] = JoomSEF::_getCategories($id);
        }
        elseif ((empty($this_task)) && (@$view == 'weblink')) {
            if( isset($catid) ) {
                $title[] = JoomSEF::_getCategories($catid);
            }

            if( !empty($id) ) {
                $database->setQuery("SELECT `title`$jfTranslate FROM #__weblinks WHERE id = '$id'");
                $rows = $database->loadObjectList();

                if ($database->getErrorNum()) die( $database->stderr());
                elseif (@count($rows) > 0 && !empty($rows[0]->title)) {
                    $title[] = $rows[0]->title;
                }
            } else {
                $title[] = JText::_('Submit');
            }
        }

        if (isset($task) && $task == 'new') {
            $title[] = 'new'.$sefConfig->suffix;
        }

        $newUri = $uri;
        if (count($title) > 0) $newUri = JoomSEF::_sefGetLocation($uri, $title, null, null, null, @$vars['lang']);

        return $newUri;
    }
}
?>
