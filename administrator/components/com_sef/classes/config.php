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

define('_COM_SEF_LANG_PATH', 0);
define('_COM_SEF_LANG_SUFFIX', 1);
define('_COM_SEF_LANG_NONE', 2);
define('_COM_SEF_LANG_DOMAIN', 3);

class SEFConfig
{
    /**
     * Whether to always add language version.
     *
     * @var bool
     */
    var $alwaysUseLang = true;
    /* boolean, is JoomSEF enabled  */
    var $enabled = true;
    /* char,  Character to use for url replacement */
    var $replacement = "-";
    /* char,  Character to use for page spacer */
    var $pagerep = "-";
    /* strip these characters */
    var $stripthese = ",|~|!|@|%|^|*|(|)|+|<|>|:|;|{|}|[|]|---|--|..|.";
    /* string,  suffix for "files" */
    var $suffix = ".html";
    /* string,  file to display when there is none */
    var $addFile = "index.php";
    /* trims friendly characters from where they shouldn't be */
    var $friendlytrim = "-|.";
    /* string,  page text */
    var $pagetext = "%s";
    /**
     * Should lang be part of path or suffix?
     *
     * @var bool
     */
    var $langPlacement = _COM_SEF_LANG_PATH;
    /* boolean, convert url to lowercase */
    var $lowerCase = true;
    /* boolean, include the section name in url */
    var $showSection = false;
    /* boolean, exclude the category name in url */
    var $showCat = true;
    /* boolean, use the title_alias instead of the title */
    var $useAlias = false;
    /**
     * Should we extract Itemid from URL?
     *
     * @var bool
     */
    var $excludeSource = false;
    /**
     * Should we extract Itemid from URL?
     *
     * @var bool
     */
    var $reappendSource = false;
    /**
     * Should we ignore multiple Itemids for the same page in database?
     *
     * @var bool
     */
    var $ignoreSource = true;
    /**
     * Excludes often changing variables from SEF URL and
     * appends them as non-SEF query
     *
     * @var bool
     */
    var $appendNonSef = true;
    /**
     * Consider both URLs with/without / in  theend valid
     *
     * @var bool
     */
    var $transitSlash = true;
    /**
     * Whether to use cache
     *
     * @var bool
     */
    var $useCache = true;
    /**
     * Maximum count of URLs in cache
     *
     * @var int
     */
    var $cacheSize = 1000;
    /**
     * Minimum hits count that URLs must have to get into cache
     *
     * @var int
     */
    var $cacheMinHits = 10;
    /**
     * Translate titles in URLs using JoomFish
     *
     * @var bool
     */
    var $translateNames = true;
    /* int, id of #__content item to use for static page */
    var $page404 = 0;
    /**
     * Record 404 pages?
     *
     * @var bool
     */
    var $record404 = false;
    /**
     * If set to yes, the standard Joomla message will be also shown when 404
     *
     * @var boolean
     */
    var $showMessageOn404 = false;
    /**
     * Whether to set the ItemID variable when Default 404 Page is displayed
     *
     * @var boolean
     */
    var $use404itemid = false;
    /**
     * ItemID used for the Default 404 page
     *
     * @var int
     */
    var $itemid404 = 0;
    /**
     * Redirect nonSEF URLs to their SEF equivalents with 301 header?
     *
     * @var bool
     */
    var $nonSefRedirect = false;
    /**
     * Use Moved Permanently redirection table?
     *
     * @var bool
     */
    var $useMoved = true;
    /**
     * Use Moved Permanently redirection table?
     *
     * @var bool
     */
    var $useMovedAsk = true;
    /**
     * Definitions of replacement characters.
     *
     * @var string
     */
    var $replacements = "Á|A, Â|A, Å|A, Ă|A, Ä|A, À|A, Ć|C, Ç|C, Č|C, Ď|D, É|E, È|E, Ë|E, Ě|E, Ì|I, Í|I, Î|I, Ï|I, Ĺ|L, Ń|N, Ň|N, Ñ|N, Ò|O, Ó|O, Ô|O, Õ|O, Ö|O, Ŕ|R, Ř|R, Š|S, Ś|O, Ť|T, Ů|U, Ú|U, Ű|U, Ü|U, Ý|Y, Ž|Z, Ź|Z, á|a, â|a, å|a, ä|a, à|a, ć|c, ç|c, č|c, ď|d, đ|d, é|e, ę|e, ë|e, ě|e, è|e, ì|i, í|i, î|i, ï|i, ĺ|l, ń|n, ň|n, ñ|n, ò|o, ó|o, ô|o, ő|o, ö|o, š|s, ś|s, ř|r, ŕ|r, ť|t, ů|u, ú|u, ű|u, ü|u, ý|y, ž|z, ź|z, ˙|-, ß|ss, Ą|A, µ|u, Ą|A, µ|u, ą|a, Ą|A, ę|e, Ę|E, ś|s, Ś|S, ż|z, Ż|Z, ź|z, Ź|Z, ć|c, Ć|C, ł|l, Ł|L, ó|o, Ó|O, ń|n, Ń|N";
    /* Array, contains predefined components. */
    var $predefined = array('0' => "com_login",'1' => "com_newsfeeds",'2' => "com_sef",'3' => "com_weblinks",'4' => "com_joomfish");
    /* Array, contains components JoomSEF will ignore. */
    var $skip = array('com_poll');
    /* Array, contains components JoomSEF will not add to the DB.
    * default style URLs will be generated for these components instead
    */
    var $nocache = array();
    /* String, contains URL to upgrade package located on server */
    var $serverUpgradeURL = "http://www.artio.cz/updates/joomsef3/upgrade_af.zip";
    /* String, contains URL to new version file located on server */
    var $serverNewVersionURL = "http://www.artio.cz/updates/joomsef3/version";
    /* Array, contains domains for different languages */
    var $langDomain = array();
    /**
     * List of alternative acepted domains. (delimited by comma)
     * @var string
     */
    var $altDomain;
    /**
     * If set to yes, new SEF URLs won't be generated and only those already
     * in database will be used
     *
     * @var boolean
     */
    var $disableNewSEF = false;
    /**
     * Array of components we don't want the menu title to be added to URL
     *
     * @var array
     */
    var $dontShowTitle = array();
    /**
     * If set to yes, the sid variable won't be removed from SEF url
     *
     * @var boolean
     */
    var $dontRemoveSid = false;
    /**
     * If set to yes, the $_SERVER['QUERY_STRING'] will be set according to parsed variables
     *
     * @var boolean
     */
    var $setQueryString = false;
    /**
     * If set to yes, the $_SERVER['QUERY_STRING'] will be set according to parsed variables
     *
     * @var boolean
     */
    var $parseJoomlaSEO = true;

    function SEFConfig ()
    {
        $sef_config_file = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_sef'.DS.'configuration.php';

        if (file_exists($sef_config_file)) {
            include ($sef_config_file);
        }

        if (isset($enabled))
        $this->enabled = $enabled;
        if (isset($replacement))
        $this->replacement = $replacement;
        if (isset($pagerep))
        $this->pagerep = $pagerep;
        if (isset($stripthese))
        $this->stripthese = $stripthese;
        if (isset($friendlytrim))
        $this->friendlytrim = $friendlytrim;
        if (isset($suffix))
        $this->suffix = $suffix;
        if (isset($addFile))
        $this->addFile = $addFile;
        if (isset($pagetext))
        $this->pagetext = $pagetext;
        if (isset($lowerCase))
        $this->lowerCase = $lowerCase;
        if (isset($showSection))
        $this->showSection = $showSection;
        if (isset($replacement))
        $this->useAlias = $useAlias;
        if (isset($page404))
        $this->page404 = $page404;
        if (isset($record404))
        $this->record404 = $record404;
        if (isset($showMessageOn404))
        $this->showMessageOn404 = $showMessageOn404;
        if (isset($use404itemid))
        $this->use404itemid = $use404itemid;
        if (isset($itemid404))
        $this->itemid404 = $itemid404;
        if (isset($predefined))
        $this->predefined = $predefined;
        if (isset($skip))
        $this->skip = $skip;
        if (isset($nocache))
        $this->nocache = $nocache;
        if (isset($showCat))
        $this->showCat = $showCat;
        if (isset($replacements))
        $this->replacements = $replacements;
        if (isset($langPlacement))
        $this->langPlacement = $langPlacement;
        if (isset($alwaysUseLang))
        $this->alwaysUseLang = $alwaysUseLang;
        if (isset($translateNames))
        $this->translateNames = $translateNames;
        if (isset($excludeSource))
        $this->excludeSource = $excludeSource;
        if (isset($reappendSource))
        $this->reappendSource = $reappendSource;
        if (isset($transitSlash))
        $this->transitSlash = $transitSlash;
        if (isset($appendNonSef))
        $this->appendNonSef = $appendNonSef;
        if (isset($langDomain))
        $this->langDomain = $langDomain;
        if (isset($altDomain))
        $this->altDomain = $altDomain;
        if (isset($ignoreSource))
        $this->ignoreSource = $ignoreSource;
        if (isset($useCache))
        $this->useCache = $useCache;
        if (isset($cacheSize))
        $this->cacheSize = $cacheSize;
        if (isset($cacheMinHits))
        $this->cacheMinHits = $cacheMinHits;
        if (isset($nonSefRedirect))
        $this->nonSefRedirect = $nonSefRedirect;
        if (isset($useMoved))
        $this->useMoved = $useMoved;
        if (isset($useMovedAsk))
        $this->useMovedAsk = $useMovedAsk;
        if (isset($disableNewSEF))
        $this->disableNewSEF = $disableNewSEF;
        if (isset($dontShowTitle))
        $this->dontShowTitle = $dontShowTitle;
        if (isset($dontRemoveSid))
        $this->dontRemoveSid = $dontRemoveSid;
        if (isset($setQueryString))
        $this->setQueryString = $setQueryString;
        if (isset($parseJoomlaSEO))
        $this->parseJoomlaSEO = $parseJoomlaSEO;
    }
    
    function saveConfig ($return_data = 0, $purge = '0')
    {
        $database =& JFactory::getDBO();
        $sef_config_file = JPATH_COMPONENT_ADMINISTRATOR.DS.'configuration.php';

        $config_data = '';
        if ($purge == '1') {
            // when the config changes, we automatically purge the cache before we save.
            $query = "DELETE FROM #__redirection WHERE `dateadd` = '0000-00-00'";
            $database->setQuery($query);
            if (! $database->query()) {
                die(basename(__FILE__) . "(line " . __LINE__ . ") : " . $database->stderr(1) . "<br />");
            }
        }
        //build the data file
        $config_data .= "&lt;?php\n";
        foreach ($this as $key => $value) {
            if ($key != '0') {
                $config_data .= "\$$key = ";
                switch (gettype($value)) {
                    case 'boolean':
                        {
                            $config_data .= ($value ? 'true' : 'false');
                            break;
                        }
                    case 'string':
                        {
                            // The only character that needs to be escaped is double quote (")
                            $config_data .= '"' . str_replace('"', '\"', stripslashes($value)) . '"';
                            break;
                        }
                    case 'integer':
                    case 'double':
                        {
                            $config_data .= strval($value);
                            break;
                        }
                    case 'array':
                        {
                            $datastring = '';
                            foreach ($value as $key2 => $data) {
                                $datastring .= '\'' . $key2 . '\' => "' . str_replace('"', '\"', stripslashes($data)) . '",';
                            }
                            $datastring = substr($datastring, 0, - 1);
                            $config_data .= "array($datastring)";
                            break;
                        }
                    default:
                        {
                            $config_data .= 'null';
                            break;
                        }
                }
            }
            $config_data .= ";\n";
        }
        $config_data .= '?>';
        if ($return_data == 1) {
            return $config_data;
        } else {
            // write to disk
            jimport( 'joomla.filesystem.file' );

            $trans_tbl = get_html_translation_table(HTML_ENTITIES);
            $trans_tbl = array_flip($trans_tbl);
            $config_data = strtr($config_data, $trans_tbl);
            $ret = JFile::write($sef_config_file, $config_data);

            return $ret;
        }
    }

    /**
     * Return array of URL characters to be replaced.
     *
     * @return array
     */
    function getReplacements ()
    {
        static $replacements;
        if (isset($replacements))
        return $replacements;
        $replacements = array();
        $items = explode(',', $this->replacements);
        foreach ($items as $item) {
            @list ($src, $dst) = explode('|', trim($item));
            $replacements[trim($src)] = trim($dst);
        }
        return $replacements;
    }

    function getAltDomain ()
    {
        return explode(',', $this->altDomain);
    }

    /**
     * Set config variables.
     *
     * @param string $var
     * @param mixed $val
     * @return bool
     */
    function set ($var, $val)
    {
        if (isset($this->$var)) {
            $this->$var = $val;
            return true;
        }
        return false;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    function version ()
    {
        return $this->$version;
    }

    function &getConfig() {
        static $instance;
        if( !isset($instance) ) {
            $instance = new SEFConfig();
        }
        return $instance;
    }
}

?>
