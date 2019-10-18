<?php
/**
 * SEF component for Joomla! 1.5
 *
 * @author      ARTIO s.r.o.
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 * @version     3.1.0
 */

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Direct access to this location is not allowed.');

require_once( JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'joomsef.php' );
require_once( JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef.cache.php' );
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_sef'.DS.'classes'.DS.'seftools.php' );
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_sef'.DS.'assets'.DS.'strings.php' );
require_once( JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef.ext.php' );

class JRouterJoomsef extends JRouter
{
    /**
	 * Class constructor
	 *
	 * @access public
	 */
    function __construct($options = array()) {
        //parent::__construct($options);
    }

    function &_createURI($url) {
        // Create full URL if we are only appending variables to it
        if(substr($url, 0, 1) == '&')
        {
            $vars = array();
            parse_str($url, $vars);

            $vars = array_merge($this->getVars(), $vars);

            foreach($vars as $key => $var)
            {
                if($var == "") {
                    unset($vars[$key]);
                }
            }

            $url = 'index.php?'.JURI::buildQuery($vars);
        }

        // Decompose link into url component parts
        $uri = new JURI($url);

        return $uri;
    }

    function &build($url) {
        $uri = $this->_createURI($url);

        // Set URI defaults
        $menu =& JSite::getMenu();

        // Get the itemid from the URI
        $itemid = $uri->getVar('Itemid');

        if(is_null($itemid))
        {
            if($option = $uri->getVar('option'))
            {
                $item  = $menu->getItem($this->getVar('Itemid'));
                if(isset($item) && $item->component == $option) {
                    $uri->setVar('Itemid', $item->id);
                }
            }
            else
            {
                if($option = $this->getVar('option')) {
                    $uri->setVar('option', $option);
                }

                if($itemid = $this->getVar('Itemid')) {
                    $uri->setVar('Itemid', $itemid);
                }
            }
        }

        // If there is no option specified, try to get the query from menu item
        if( is_null($uri->getVar('option')) )
        {
            if( !is_null($uri->getVar('Itemid')) ) {
                $item = $menu->getItem($uri->getVar('Itemid'));
                if( is_object($item) ) {
                    foreach($item->query as $k => $v) {
                        $uri->setVar($k, $v);
                    }
                }
            }
        }

        JoomSEF::build($uri);

        return $uri;
    }

    function parse(&$uri) {
        global $mainframe;
        $mainframe->set('sef.global.meta', '3536c734403eaaa5699708eda7b52b1b');

        $vars   = array();

        $vars = JoomSEF::parse($uri);

        //return $vars;

        $menu =& JSite::getMenu(true);

        //Handle an empty URL (special case)
        if(empty($vars['Itemid']) && empty($vars['option']))
        {
            $item = $menu->getDefault();
            if(!is_object($item)) return $vars; // No default item set

            //Set the information in the request
            $vars = $item->query;

            //Get the itemid
            $vars['Itemid'] = $item->id;

            // Set the active menu item
            $menu->setActive($vars['Itemid']);

            return $vars;
        }

        // Get the item id, if it hasn't been set force it to null
        if( empty($vars['Itemid']) ) {
            $vars['Itemid'] = JRequest::getInt('Itemid', null);
        }

        // Get the variables from the uri
        $this->setVars($vars);

        // No option? Get the full information from the itemid
        if( empty($vars['option']) )
        {
            $item = $menu->getItem($this->getVar('Itemid'));
            if(!is_object($item)) return $vars; // No default item set

            $vars = $vars + $item->query;
        }

        // Set the active menu item
        $menu->setActive($this->getVar('Itemid'));

        return $vars;
    }
}

?>