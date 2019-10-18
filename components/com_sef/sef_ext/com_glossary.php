<?php
/**
 * Glossary SEF extension for Joomla!
 *
 * @author      $Author: David Jozefov $
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access.');

class SefExt_com_glossary extends SefExt
{
    var $params;
    
    // Returns term for given id
    function GetTerm($id) {
        $database =& JFactory::getDBO();

        $database->setQuery("SELECT `tterm`, `tletter` FROM `#__glossary` WHERE `id` = $id");
        $row = $database->loadObject();
        
        if( $this->params->get('letter', '0') != '0' ) {
            return array($row->tletter, $row->tterm);
        } else {
            return array($row->tterm);
        }
    }
    
    function GetLetter($term) {
        $database =& JFactory::getDBO();
        
        if( $this->params->get('letter', '0') != '0' ) {
            $database->setQuery("SELECT `tletter` FROM `#__glossary` WHERE `tterm` = '$term'");
            return $database->loadResult();
        } else {
            return null;
        }
    }

    function create(&$uri) {
        $sefConfig =& SEFConfig::getConfig();

        $this->params = SEFTools::getExtParams('com_glossary');
        
        // Extract variables
        $vars = $uri->getQuery(true);
        extract($vars);
        $title = array();

        $title[] = JoomSEF::_getMenuTitle(@$option, @$task, @$Itemid);

        if( isset($func) ) {
            switch($func) {
                case 'display':
                    if( isset($search) ) {
                        // We don't want to save every search URL in the database
                        return;
                    }
                    if( isset($letter) )  $title[] = $letter;
                    if( isset($page) && !$sefConfig->appendNonSef )  $title[] = $page;
                    break;

                case 'view':
                    if( isset($term) )  $title = array_merge( $title, array($this->GetLetter($term), $term) );
                    break;

                case 'comment':
                case 'delete':
                    $title = array_merge( $title, $this->GetTerm($id) );
                    $title[] = $func;
                    break;

                case 'submit':
                    if( isset($id) ) {
                        $title = array_merge( $title, $this->GetTerm($id) );
                        $title[] = 'edit';
                    }
                    else {
                        $title[] = $letter;
                        $title[] = 'submit';
                    }
                    break;
            }
        }

        $newUri = $uri;
        if (count($title) > 0) {
            $nonSefVars = array();
            if( isset($page) ) $nonSefVars['page'] = $page;
            
            $newUri = JoomSEF::_sefGetLocation($uri, $title, @$task, @$limit, @$limitstart, @$lang, $nonSefVars);
        }
        
        return $newUri;
    }
}
?>
