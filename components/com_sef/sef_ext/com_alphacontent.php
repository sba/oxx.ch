<?php
/**
 * AlphaContent SEF extension for Joomla!
 *
 * @author      $Author: David Jozefov $
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access.');

class SefExt_com_alphacontent extends SefExt
{
    function create(&$uri) {
        $sefConfig =& SEFConfig::getConfig();
        $database =& JFactory::getDBO();

        // Use this to get variables from the original Joomla! URL, such as $task, $page, $id, $catID, ...
        $vars = $uri->getQuery(true);
        extract($vars);
        $title = array();

        $title[] = JoomSEF::_getMenuTitle(@$option, @$task, @$Itemid);

        if (isset($section)/* && $sefConfig->showSection*/) {
            $sql = "SELECT title, id FROM #__sections WHERE id=".$section;
            $database->setQuery($sql);
            if (($section_name = $database->loadResult())) {
                $title[] = $section_name; //section name
            } else {
                if( $section == '0' ) {
                    $title[] = JText::_('No section');
                } else {
                    if( strpos($section, 'com_') === 0 ) {
                        $title[] = substr($section, 4);
                    } else {
                        $title[] = $section;
                    }
                }
            }
            unset($vars['section']);
        }

        if (isset($cat)/* && $sefConfig->showCat*/) {
            $sql = "SELECT title, id FROM #__categories WHERE id=".$cat;
            $database->setQuery($sql);
            if ($cat_name = $database->loadResult()) {
                $title[] = $cat_name; //category name
            }
            unset($vars['cat']);
        }

        if (isset($id)) {
            $sql = "SELECT title, id FROM #__content WHERE id = $id";
            $database->setQuery($sql);
            if ($cTitle = $database->loadResult()) {
                $title[] = $cTitle; //item title
            }
            unset($vars['id']);
            if (@$task == 'view') unset($task);
        }

        // Add letter name.
        if (isset($alpha)) {
            $title[] = $alpha;
        }

        $newUri = $uri;
        if (count($title) > 0) {
            $ignoreVars = array();
            if( isset($vars['sort']) )          $ignoreVars['sort'] = $vars['sort'];
            
            $nonSefVars = array();
            if( isset($vars['limit']) )         $nonSefVars['limit'] = $vars['limit'];
            if( isset($vars['limitstart']) )    $nonSefVars['limitstart'] = $vars['limitstart'];
            
            $newUri = JoomSEF::_sefGetLocation($uri, $title, @$task, @$limit, @$limitstart, @$lang, $nonSefVars, $ignoreVars);
        }
        
        return $newUri;
    }
}
?>
