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

class SEFModelExtensions extends JModel
{
    function __construct()
    {
        parent::__construct();
    }

    function getExtensions()
    {
        if( !isset($this->_extensions) ) {
            $path = JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef_ext';
            $xmlfiles = JFolder::files($path, '.xml$');

            $exts = array();
            if( is_array($xmlfiles) && (count($xmlfiles) > 0) ) {
                foreach($xmlfiles as $file) {
                    $manifest = $this->_isManifest($path.DS.$file);
                    if( !is_null($manifest) ) {
                        $ext = new stdClass();
                        $ext->id = $file;

                        $root =& $manifest->document;

                        $element            = &$root->getElementByPath('name');
                        $ext->name          = $element ? $element->data() : '';

                        $element 			 = &$root->getElementByPath('creationdate');
                        $ext->creationdate   = $element ? $element->data() : '';

                        $element 			= &$root->getElementByPath('author');
                        $ext->author		= $element ? $element->data() : '';

                        $element 			= &$root->getElementByPath('copyright');
                        $ext->copyright	    = $element ? $element->data() : '';

                        $element 			= &$root->getElementByPath('authoremail');
                        $ext->authorEmail	= $element ? $element->data() : '';

                        $element 			= &$root->getElementByPath('authorurl');
                        $ext->authorUrl	    = $element ? $element->data() : '';

                        $element 			= &$root->getElementByPath('version');
                        $ext->version		= $element ? $element->data() : '';

                        $exts[] = $ext;
                    }
                }
            }

            $this->_extensions = $exts;
        }

        return $this->_extensions;
    }

    function &_isManifest($file)
    {
        // Initialize variables
        $null	= null;
        $xml	=& JFactory::getXMLParser('Simple');

        // If we cannot load the xml file return null
        if (!$xml->loadFile($file)) {
            // Free up xml parser memory and return null
            unset ($xml);
            return $null;
        }

        /*
         * Check for a valid XML root tag.
         */
        $root =& $xml->document;
        if( !is_object($root) ||
            ($root->name() != 'install') ||
            version_compare($root->attributes('version'), '1.5', '<') ||
            ($root->attributes('type') != 'sef_ext') )
        {
            // Free up xml parser memory and return null
            unset ($xml);
            return $null;
        }

        // Valid manifest file return the object
        return $xml;
    }

}
?>
