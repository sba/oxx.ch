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

require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_sef'.DS.'tables'.DS.'extension.php');
jimport( 'joomla.filesystem.file' );

class SEFTools
{
    function getSEFVersion()
    {
        static $version;

        if( !isset($version) ) {
            $xml =& JFactory::getXMLParser('Simple');

            $xmlFile = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sef'.DS.'sef.xml';

            if( JFile::exists($xmlFile) ) {
                if( $xml->loadFile($xmlFile) ) {
                    $root =& $xml->document;
                    $element =& $root->getElementByPath('version');
                    $version = $element ? $element->data() : '';
                }
            }
        }

        return $version;
    }

    function getExtVersion($extension)
    {
        $xml =& JFactory::getXMLParser('Simple');

        $xmlFile = JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS.$extension.'.xml';

        $version = null;
        if( JFile::exists($xmlFile) ) {
            if( $xml->loadFile($xmlFile) ) {
                $root =& $xml->document;
                $ver = $root->attributes('version');
                if( ($root->name() == 'install') && version_compare($ver, '1.5', '>=') && ($root->attributes('type') == 'sef_ext') ) {
                    $element =& $root->getElementByPath('version');
                    $version = $element ? $element->data() : '';
                }
            }
        }

        return $version;
    }

    /**
     * Returns extension name from its XML file.
     *
     * @return string
     */
    function getExtName($extension)
    {
        $xml =& JFactory::getXMLParser('Simple');

        $xmlFile = JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS.$extension.'.xml';

        $name = null;
        if( JFile::exists($xmlFile) ) {
            if( $xml->loadFile($xmlFile) ) {
                $root =& $xml->document;
                $ver = $root->attributes('version');
                if( ($root->name() == 'install') && version_compare($ver, '1.5', '>=') && ($root->attributes('type') == 'sef_ext') ) {
                    $element =& $root->getElementByPath('name');
                    $name = $element ? $element->data() : '';
                }
            }
        }

        return $name;
    }

    function getLangCode($langTag = null)
    {
        // Get current language tag
        if( is_null($langTag) ) {
            $lang =& JFactory::getLanguage();
            $langTag = $lang->getTag();
        }

        $jfm =& JoomFishManager::getInstance();
        $code = $jfm->getLanguageCode($langTag);

        return $code;
    }

    function getLangId($langTag = null)
    {
        // Get current language tag
        if( is_null($langTag) ) {
            $lang =& JFactory::getLanguage();
            $langTag = $lang->getTag();
        }

        $jfm =& JoomFishManager::getInstance();
        $id = $jfm->getLanguageID($langTag);

        return $id;
    }

    function getLangLongCode($langCode = null)
    {
        static $codes;

        // Get current language code
        if( is_null($langCode) ) {
            $lang =& JFactory::getLanguage();
            return $lang->getTag();
        }

        if( is_null($codes) ) {
            $codes = array();

            $langs = JoomFishManager::getLanguages(false);
            if( !empty($langs) ) {
                foreach($langs as $lang) {
                    $codes[$lang->shortcode] = $lang->code;
                }
            }
        }

        if( isset($codes[$langCode]) ) {
            return $codes[$langCode];
        }

        return null;
    }

    /** Returns JParameter object representing extension's parameters
     *
     * @param	string          Extension name
     * @return	JParameter      Extension's parameters
     */
    function getExtParams($option)
    {
        $db =& JFactory::getDBO();

        static $exts, $params;

        if( !isset($exts) ) {
            $query = "SELECT `file`, `params` FROM `#__sefexts`";
            $db->setQuery($query);
            $exts = $db->loadObjectList('file');
        }

        if( !isset($params) ) {
            $params = array();
        }
        if( !isset($params[$option]) ) {
            $path = JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef_ext'.DS.$option.'.xml';
            if (! file_exists($path) ) {
                $path = '';
            }
            $data = '';
            if( isset($exts[$option.'.xml']) ) {
                $data = $exts[$option.'.xml']->params;
            }
            $params[$option] = new JParameter($data, $path);
        }

        return $params[$option];
    }

    /** Returns the array of texts used by the extension for creating URLs
     *  in currently selected language (for JoomFish support)
     *
     * @param	string  Extension name
     * @return	array   Extension's texts
     */
    function getExtTexts ($option, $lang = '')
    {
        $database =& JFactory::getDBO();

        static $extTexts;

        if ($option == '') {
            return false;
        }

        // Set the language
        if ($lang == '') {
            $lang = SEFTools::getLangLongCode();
        }
        if (! isset($extTexts)) {
            $extTexts = array();
        }
        if (! isset($extTexts[$option])) {
            $extTexts[$option] = array();
        }
        if (! isset($extTexts[$option][$lang])) {
            $extTexts[$option][$lang] = array();
            // If lang is different than current language, change it
            if ($lang != SEFTools::getLangLongCode()) {
                $language =& JFactory::getLanguage();
                $oldLang = $language->setLanguage($lang);
                $language->load();
            }
            $query = "SELECT `id`, `name`, `value` FROM `#__sefexttexts` WHERE `extension` = '$option'";
            $database->setQuery($query);
            $texts = $database->loadObjectList();
            if (is_array($texts) && (count($texts) > 0)) {
                foreach (array_keys($texts) as $i) {
                    $name = $texts[$i]->name;
                    $value = $texts[$i]->value;
                    $extTexts[$option][$lang][$name] = $value;
                }
            }
            // Set the language back to previously selected one
            if (isset($oldLang) && ($oldLang != SEFTools::getLangLongCode())) {
                $language =& JFactory::getLanguage();
                $language->setLanguage($oldLang);
                $language->load();
            }
        }
        return $extTexts[$option][$lang];
    }

    function RemoveVariable ($url, $var, $value = '')
    {
        if ($value == '') {
            $newurl = eregi_replace("(&|\?)$var=[^&]*", '\\1', $url);
        } else {
            $trans = array('?' => '\\?', '.' => '\\.', '+' => '\\+', '*' => '\\*', '^' => '\\^', '$' => '\\$');
            $value = strtr($value, $trans);
            $newurl = eregi_replace("(&|\?)$var=$value(&|\$)", '\\1\\2', $url);
        }
        $newurl = trim($newurl, '&?');
        $trans = array('&&' => '&' , '?&' => '?');
        $newurl = strtr($newurl, $trans);
        return $newurl;
    }

    function fixVariable(&$uri, $varName)
    {
        $value = $uri->getVar($varName);
        if( !is_null($value) ) {
            $pos = strpos($value, ':');
            if( $pos !== false ) {
                $value = substr($value, 0, $pos);
                $uri->setVar($varName, $value);
            }
        }
    }

    function ReplaceAll($search, $replace, $subject)
    {
        while( strpos($subject, $search) !== false ) {
            $subject = str_replace($search, $replace, $subject);
        }

        return $subject;
    }
}

?>
