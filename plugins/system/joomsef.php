<?php
/**
 * SEF component for Joomla! 1.5
 *
 * @author      ARTIO s.r.o.
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 * @version     3.1.0
 */

// Security check to ensure this file is being included by a parent file.
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgSystemJoomsef extends JPlugin
{
    function plgSystemJoomsef( &$subject ) {
        parent::__construct($subject);

        // load plugin parameters
        $this->_plugin = & JPluginHelper::getPlugin( 'system', 'joomsef' );
        $this->_params = new JParameter( $this->_plugin->params );
    }

    function onAfterInitialise() {
        global $mainframe;

        // Do not run plugin in administration area
        if( $mainframe->isAdmin()) {
            return true;
        }

        // Do not run plugin if SEF is disabled
        $config =& JFactory::getConfig();
        if( !$config->getValue('sef') ) {
            return true;
        }

        // check if joomsef is enabled
        require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sef'.DS.'classes'.DS.'config.php' );
        $sefConfig =& SEFConfig::getConfig();

        if( $sefConfig->enabled ) {
            $router =& $mainframe->getRouter();

            // Store the router for later use
            $mainframe->set('sef.global.jrouter', $router);

            require_once( JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef.router.php' );
            $router = new JRouterJoomsef();
        }

        return true;
    }

    function onAfterRoute() {
        global $mainframe;

        // Do not run plugin in administration area
        if( $mainframe->isAdmin()) {
            return true;
        }

        // Do not run plugin if SEF is disabled
        $config =& JFactory::getConfig();
        if( !$config->getValue('sef') ) {
            return true;
        }

        // check if joomsef is enabled
        require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sef'.DS.'classes'.DS.'config.php' );
        $sefConfig =& SEFConfig::getConfig();

        if( !$sefConfig->enabled ) {
            return true;
        }


        return true;
    }

    function onAfterDispatch() {
        global $mainframe;

        // Do not run plugin in administration area
        if( $mainframe->isAdmin()) {
            return true;
        }

        // Check if SEF and plugin are enabled
        if( !class_exists('JoomSEF') || !JoomSEF::enabled($this) ) {
            return true;
        }

        // Do not run plugin if metadata generation is disabled
        if( $this->_params->get('enable_metadata', '1') != '1' ) {
            return true;
        }

        // Generate page title
        $this->_checkSefTitle();

        // Generate page metadata
        $this->_generateMeta();

        return true;
    }

    function _generateMeta() {
        global $mainframe;

        $document = & JFactory::getDocument();

        $rewriteKeywords    = $this->_params->get('rewrite_keywords', '1');
        $rewriteDescription = $this->_params->get('rewrite_description', '1');

        $metadesc   = $mainframe->get('sef.meta.desc');
        $metakey    = $mainframe->get('sef.meta.key');
        $metalang   = $mainframe->get('sef.meta.lang');
        $metarobots = $mainframe->get('sef.meta.robots');
        $metagoogle = $mainframe->get('sef.meta.google');

        // Description metatag
        if( !empty($metadesc) ) {
            $oldDesc = $document->getDescription();
            $rewrite = (($rewriteDescription == '1') || ($oldDesc == ''));

            if( $rewrite ) {
                $document->setDescription($metadesc);
            } else {
                $document->setDescription($metadesc . ', ' . $oldDesc);
            }
        }

        // Keywords metatag
        if( !empty($metakey) ) {
            $oldKey = $document->getMetaData('keywords');
            $rewrite = (($rewriteKeywords == '1') || ($oldKey == ''));

            if( $rewrite ) {
                $document->setMetaData('keywords', $metakey);
            } else {
                $document->setMetaData('keywords', $metakey . ', ' . $oldKey);
            }
        }

        if( !empty($metalang) )     $document->setMetaData('lang', $metalang);
        if( !empty($metarobots) )   $document->setMetaData('robots', $metarobots);
        if( !empty($metagoogle) )   $document->setMetaData('google', $metagoogle);
    }

    function _checkSefTitle() {
        global $mainframe;

        $document = & JFactory::getDocument();
        $config = & JFactory::getConfig();

        $sitename = $config->getValue('sitename');
        $preferTitle = $this->_params->get('prefer_joomsef_title', '1');
        $useSitename = $this->_params->get('use_sitename', '2');
        $sitenameSep = ' '.trim($this->_params->get('sitename_sep', '-')).' ';
        $preventDupl = $this->_params->get('prevent_dupl', '1');

        if( $sitenameSep == '  ' ) {
            $sitenameSep = ' ';
        }

        // Page title
        $pageTitle = $mainframe->get('sef.meta.title');

        if( empty($pageTitle) ) {
            $pageTitle = $document->getTitle();

            // Dave: replaced regular expression as it was causing problems
            //       with site names like [ index-i.cz ] with str_replace
            /*$pageSep = '( - |'.$sitenameSep.')';
            if (preg_match('/('.$GLOBALS['mosConfig_sitename'].$pageSep.')?(.*)?/', $pageTitle, $matches) > 0) {
            $pageTitle = strtr($pageTitle, array($matches[1] => ''));
            }*/
            $pageTitle = str_replace(array($sitename.' - ', $sitename.$sitenameSep), array('', ''), $pageTitle);
        }

        if( $preferTitle ) {
            $pageTitle = trim($pageTitle);

            // Prevent name duplicity if set to
            if ($preventDupl && strcmp($pageTitle, trim($sitename)) == 0) {
                $pageTitle = '';
            }

            if (empty($pageTitle)) $sitenameSep = '';

            if ($useSitename == 1 && $sitename) {
                $pageTitle = $sitename.$sitenameSep.$pageTitle;
            }
            elseif ($useSitename == 2 && $sitename) {
                $pageTitle .= $sitenameSep.$sitename;
            }

            // set page title
            if ($pageTitle) {
                $document->setTitle($pageTitle);
                $document->setMetaData('title', $pageTitle);
            }
        }
    }
}

?>
