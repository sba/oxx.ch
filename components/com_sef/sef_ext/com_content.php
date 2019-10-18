<?php
/**
 * Content SEF extension for Joomla!
 *
 * @author      $Author: David Jozefov $
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access.');

class SefExt_com_content extends SefExt
{
    function beforeCreate(&$uri) {
        $db =& JFactory::getDBO();

        $params = SEFTools::GetExtParams('com_content');

        // Compatibility mode
        $comp = $params->get('compatibility', '0');
        
        // Change task=view to view=article for old urls
        if( !is_null($uri->getVar('task')) && ($uri->getVar('task') == 'view') ) {
            if( $comp == '0' ) {
                $uri->delVar('task');
            }
            $uri->setVar('view', 'article');
        }
        
        // Add the task=view in compatibility mode
        if( $comp != '0' ) {
            if( is_null($uri->getVar('task')) && !is_null($uri->getVar('view')) && ($uri->getVar('view') == 'article') ) {
                $uri->setVar('task', 'view');
            }
        }

        // Remove the limitstart and limit variables if they point to the first page
        if( !is_null($uri->getVar('limitstart')) && ($uri->getVar('limitstart') == '0') ) {
            $uri->delVar('limitstart');
            $uri->delVar('limit');
        }

        // Try to guess the correct Itemid if set to
        if( $params->get('guessId', '0') != '0' ) {
            if( !is_null($uri->getVar('Itemid')) && !is_null($uri->getVar('id')) ) {
                global $mainframe;
                $i = $mainframe->getItemid($uri->getVar('id'));
                $uri->setVar('Itemid', $i);
            }
        }

        // Remove the part after ':' from variables
        if( !is_null($uri->getVar('id')) )       SEFTools::fixVariable($uri, 'id');
        if( !is_null($uri->getVar('catid')) )    SEFTools::fixVariable($uri, 'catid');

        // If catid not given, try to find it
        $catid = $uri->getVar('catid');
        if( !is_null($uri->getVar('view')) && ($uri->getVar('view') == 'article') && !is_null($uri->getVar('id')) && empty($catid) ) {
            $id = $uri->getVar('id');
            $query = "SELECT `catid` FROM `#__content` WHERE `id` = '{$id}'";
            $db->setQuery($query);
            $catid = $db->loadResult();

            if( !empty($catid) ) {
                $uri->setVar('catid', $catid);
            }
        }

        // Add the view variable if it's not set
        if( is_null($uri->getVar('view')) ) {
            if( is_null($uri->getVar('id')) ) {
                $uri->setVar('view', 'frontpage');
            } else {
                $uri->setVar('view', 'article');
            }
        }

        return;
    }

    function GoogleNews($title, $id) {
        $db =& JFactory::getDBO();
        $params = SEFTools::GetExtParams('com_content');

        $num = '';
        $add = $params->get('googlenewsnum', '0');

        if( $add == '1' ) {
            // Article ID
            $digits = trim($params->get('digits', '3'));
            if( !is_numeric($digits) ) {
                $digits = '3';
            }

            $num = sprintf('%0'.$digits.'d', $id);
        }
        else if( $add == '2' ) {
            // Publish date
            $query = "SELECT `publish_up` FROM `#__content` WHERE `id` = '$id'";
            $db->setQuery($query);
            $time = $db->loadResult();

            $time = strtotime($time);

            $date = $params->get('dateformat', 'ddmm');

            $search = array( 'dd', 'd', 'mm', 'm', 'yyyy', 'yy' );
            $replace = array( date('d', $time),
            date('j', $time),
            date('m', $time),
            date('n', $time),
            date('Y', $time),
            date('y', $time) );
            $num = str_replace($search, $replace, $date);
        }

        if( !empty($num) ) {
            $sefConfig =& SEFConfig::getConfig();
            $sep = $sefConfig->replacement;

            $where = $params->get('numberpos', '1');

            if( $where == '1' ) {
                $title = $title.$sep.$num;
            } else {
                $title = $num.$sep.$title;
            }
        }

        return $title;
    }

    function create(&$uri) {
        $sefConfig =& SEFConfig::getConfig();

        $params = SEFTools::GetExtParams('com_content');

        $vars = $uri->getQuery(true);
        extract($vars);

        // Do not SEF URLs with exturl variable
        //if( !empty($exturl) )   return $string;

        // Do not SEF edit urls
        if( isset($task) && ($task == 'edit') ) {
            return $uri;
        }

        // Set title.
        $title = array();

        switch (@$view) {
            case 'new':
            case 'edit': {
                /*
                $title[] = getMenuTitle($option, $task, $Itemid, $string);
                $title[] = 'new' . $sefConfig->suffix;
                */
                break;
            }
            /*
            case 'archivecategory':
            case 'archivesection': {
            if (eregi($task.".*id=".$id, $_SERVER['REQUEST_URI'])) break;
            }
            */
            default: {
                if( isset($format) ) {
                    if( $format == 'pdf') {
                        // Create PDF
                        $title = JoomSEF::_getContentTitles('article', $id);
                        if (count($title) === 0) $title[] = JoomSEF::_getMenuTitle(@$option, @$task, @$Itemid);

                        $title[] = JText::_('PDF');
                    } elseif( $format == 'feed' ) {
                        // Create feed
                        if( @$view == 'frontpage' ) {
                            $title[] = JText::_('Frontpage');
                        } else {
                            $title = JoomSEF::_getContentTitles('article', $id);
                        }
                        if (count($title) === 0) $title[] = JoomSEF::_getMenuTitle(@$option, @$task, @$Itemid);

                        if( !empty($type) ) $title[] = $type;
                    }
                } else {
                    if( isset($id) ) {
                        $title = JoomSEF::_getContentTitles(@$view, @$id);
                        if (count($title) === 0) $title[] = JoomSEF::_getMenuTitle(@$option, @$task, @$Itemid);

                        // Add Google News number if set to
                        if( (@$view == 'article') && ($params->get('googlenewsnum', '0') != '0') ) {
                            $i = count($title) - 1;
                            $title[$i] = $this->GoogleNews($title[$i], $id);
                        }
                    } else {
                        $title[] = JoomSEF::_getMenuTitle(@$option, @$task, @$Itemid);
                        //$title[] = JText::_('Submit');
                    }

                    if( isset($limitstart) && (!$sefConfig->appendNonSef || ($params->get('pagination', '0') == '0')) ) {
                        $title[] .= JText::_('Page') . '-' . ($limitstart+1);
                    }

                    if( isset($showall) && ($showall == 1) ) {
                        $title[] = JText::_('All Pages');
                    }

                    if( isset($print) && ($print == 1) ) {
                        // Print article
                        $title[] = JText::_('Print') . (!empty($page) ? '-'.($page+1) : '');
                    }
                }
            }
        }

        $newUri = $uri;
        if (count($title) > 0) {
            $nonSefVars = array();
            if( $sefConfig->appendNonSef && ($params->get('pagination', '0') != '0') ) {
                if( isset($limit) )         $nonSefVars['limit'] = $limit;
                if( isset($limitstart) )    $nonSefVars['limitstart'] = $limitstart;
            }

            $newUri = JoomSEF::_sefGetLocation($uri, $title, null, null, null, @$lang, $nonSefVars);
        }

        return $newUri;
    }
}
?>
