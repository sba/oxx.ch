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
defined('_JEXEC') or die('Restricted access.');

/**
 * Main class handling JoomSEF's cache
 *
 */
class SEFCache
{
    var $cacheUrl           = array();
    var $cacheItemid        = array();
    var $cacheHit           = array();
    var $cacheMetaTitle     = array();
    var $cacheMetaDesc      = array();
    var $cacheMetaKey       = array();
    var $cacheMetaLang      = array();
    var $cacheMetaRobots    = array();
    var $cacheMetaGoogle    = array();

    var $cacheLoaded = false;
    var $cacheFile = null;
    var $maxSize;
    var $minHits;

    /**
     * Sets the main variables and loads the cache from disk
     *
     * @param int $maxSize
     * @param int $minHits
     * @return sefCache
     */
    function sefCache($maxSize, $minHits)
    {
        $this->maxSize = $maxSize;
        $this->minHits = $minHits;
        $this->cacheFile = JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'cache'.DS.'cache.php';

        $this->loadCache();
    }

    function &getInstance() {
        static $instance;
        if( !isset($instance) ) {
            $sefConfig =& SEFConfig::getConfig();
            $instance = new sefCache($sefConfig->cacheSize, $sefConfig->cacheMinHits);
        }
        return $instance;
    }

    /**
     * Loads the cache from disk to memory
     *
     */
    function loadCache()
    {
        // Is cache already loaded?
        if ($this->cacheLoaded) return;

        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        if (JFile::exists($this->cacheFile)) {
            // Load the cache from disk
            include_once($this->cacheFile);
        }
        else {
            // Set the empty cache arrays
            if (!JFolder::exists(dirname($this->cacheFile))) {
                JFolder::create(dirname($this->cacheFile));
            }

            $url            = array();
            $hit            = array();
            $Itemids        = array();
            $metaTitles     = array();
            $metaDescs      = array();
            $metaKeys       = array();
            $metaLangs      = array();
            $metaRobots     = array();
            $metaGoogles    = array();
        }

        // Assign the arrays to cache variables
        $this->cacheUrl         = $url;
        $this->cacheItemid      = $Itemids;
        $this->cacheHit         = $hit;
        $this->cacheMetaTitle   = $metaTitles;
        $this->cacheMetaDesc    = $metaDescs;
        $this->cacheMetaKey     = $metaKeys;
        $this->cacheMetaLang    = $metaLangs;
        $this->cacheMetaRobots  = $metaRobots;
        $this->cacheMetaGoogle  = $metaGoogles;

        $this->cacheLoaded = true;
    }

    /**
     * Helper function for saving an array into includeable file
     * Returns the string ready for writing to disk
     *
     * @param array $array
     * @param string $arrayName
     * @return string
     */
    function saveArray(&$array, $arrayName)
    {
        $str = "\n";
        $str .= '$'.$arrayName.'=array();';
        if( count($array) > 0 ) {
            reset($array);
            foreach($array as $sef => $val) {
                $str .= "\n";
                $str .= '$'.$arrayName.'[\''.$sef.'\']=\''.str_replace(array('\\', '\''), array('\\\\', '\\\''), $val).'\';';
            }
        }

        return $str;
    }

    /**
     * Saves the cache arrays to disk
     *
     */
    function saveCache()
    {
        // Security check
        $cache = '<?php
defined(\'_JEXEC\') or die(\'Direct access to this location is not allowed.\');';

        // Add all the arrays
        $cache .= $this->saveArray($this->cacheUrl, 'url');
        $cache .= $this->saveArray($this->cacheItemid, 'Itemids');
        $cache .= $this->saveArray($this->cacheHit, 'hit');
        $cache .= $this->saveArray($this->cacheMetaTitle, 'metaTitles');
        $cache .= $this->saveArray($this->cacheMetaDesc, 'metaDescs');
        $cache .= $this->saveArray($this->cacheMetaKey, 'metaKeys');
        $cache .= $this->saveArray($this->cacheMetaLang, 'metaLangs');
        $cache .= $this->saveArray($this->cacheMetaRobots, 'metaRobots');
        $cache .= $this->saveArray($this->cacheMetaGoogle, 'metaGoogles');

        $cache .= "\n?>";

        // Write the cache to disk
        $cachefile = fopen($this->cacheFile, 'wb');
        if ($cachefile) {
            if( flock($cachefile, LOCK_EX) ) {
                fwrite($cachefile, $cache);
                flock($cachefile, LOCK_UN);
            }
            fclose($cachefile);
        }
    }

    /**
     * Tries to find a nonSEF URL corresponding with given SEF URL
     * If updateHits is set the function will increase the cached URL hit count
     *
     * @param string $sef
     * @param boolean $updateHits
     * @return object
     */
    function getNonSefUrl($sef, $updateHits = true)
    {
        // Load the cache if needed
        if (!$this->cacheLoaded) $this->loadCache();

        $sefConfig =& SEFConfig::getConfig();

        // If we are tolerant for trailing slash
        if( $sefConfig->transitSlash ) {
            // Remove trailing slash
            $sef = rtrim($sef, '/');
            if( !isset($this->cacheUrl[$sef]) ) {
                // If there isn't URL without trailing slash, add the slash
                $sef .= '/';
            }
        }
        
        // Does the item exist in cache?
        if (isset($this->cacheUrl[$sef])) {
            // Create the object to be returned
            $row = new stdClass();
            $row->oldurl     = $sef;
            $row->newurl     = $this->cacheUrl[$sef];
            $row->cpt        = $this->cacheHit[$sef];
            $row->Itemid     = (isset($this->cacheItemid[$sef])     ? $this->cacheItemid[$sef] : '' );
            $row->metatitle  = (isset($this->cacheMetaTitle[$sef])  ? $this->cacheMetaTitle[$sef] : '' );
            $row->metadesc   = (isset($this->cacheMetaDesc[$sef])   ? $this->cacheMetaDesc[$sef] : '' );
            $row->metakey    = (isset($this->cacheMetaKey[$sef])    ? $this->cacheMetaKey[$sef] : '' );
            $row->metalang   = (isset($this->cacheMetaLang[$sef])   ? $this->cacheMetaLang[$sef] : '' );
            $row->metarobots = (isset($this->cacheMetaRobots[$sef]) ? $this->cacheMetaRobots[$sef] : '' );
            $row->metagoogle = (isset($this->cacheMetaGoogle[$sef]) ? $this->cacheMetaGoogle[$sef] : '' );

            // Update hits if set to
            if($updateHits) {
                $this->cacheHit[$sef]++;
                $this->saveCache();
            }

            return $row;
        } else {
            // Cache record not found
            return false;
        }
    }

    /**
     * Tries to find a SEF URL corresponding with given nonSEF URL
     *
     * @param string $nonsef
     * @param string $Itemid
     * @return string
     */
    function getSefUrl($nonsef, $Itemid = null)
    {
        $sefConfig =& SEFConfig::getConfig();

        // Load the cache if needed
        if( !$this->cacheLoaded )   $this->LoadCache();

        // Check if non-sef url doesn't contain Itemid
        $vars = array();
        parse_str(str_replace('index.php?', '', $nonsef), $vars);
        if( is_null($Itemid) && strpos($nonsef, 'Itemid=') ) {
            if( isset($vars['Itemid']) )    $Itemid = $vars['Itemid'];
            $nonsef = SEFTools::RemoveVariable($nonsef, 'Itemid');
        }

        // Get the ignoreSource parameter
        if( isset($vars['option']) ) {
            $params = SEFTools::getExtParams($vars['option']);
            $extIgnore = $params->get('ignoreSource', 2);
        } else {
            $extIgnore = 2;
        }
        $ignoreSource = ($extIgnore == 2 ? $sefConfig->ignoreSource : $extIgnore);

        if( $ignoreSource || is_null($Itemid) ) {
            // Search without Itemid
            if (($key = array_search($nonsef, $this->cacheUrl)) === FALSE ) {
                return false;
            } else {
                return $key;
            }
        }
        else {
            // Search with Itemid
            // Get all sef urls matching non-sef url
            $keys = array_keys($this->cacheUrl, $nonsef);
            if (count($keys) > 0) {
                // Try to find the key with corresponding Itemid
                foreach ($keys as $key) {
                    if (isset($this->cacheItemid[$key]) && ($this->cacheItemid[$key] == $Itemid)) {
                        return $key;
                    }
                }
            }
            return false;
        }
    }

    /**
     * Adds the URL to cache
     *
     * @param string $nonsef
     * @param string $sef
     * @param int $hits
     * @param string $Itemid
     * @param string $metatitle
     * @param string $metadesc
     * @param string $metakey
     * @param string $metalang
     * @param string $metarobots
     * @param string $metagoogle
     */
    function addUrl($nonsef, $sef, $hits, $Itemid = '', $metatitle = '', $metadesc = '', $metakey = '', $metalang = '', $metarobots = '', $metagoogle = '')
    {
        // Check if URL's hits count is enough to be stored
        if ($hits < $this->minHits)    return;

        // Check the cache size
        if (count($this->cacheUrl) < $this->maxSize) {
            // OK, we can add the URL to the end
            $this->cacheUrl[$sef] = $nonsef;
            $this->cacheHit[$sef] = $hits;
            if ($Itemid     != '')     $this->cacheItemid[$sef] = $Itemid;
            if ($metatitle  != '')  $this->cacheMetaTitle[$sef] = $metatitle;
            if ($metadesc   != '')   $this->cacheMetaDesc[$sef] = $metadesc;
            if ($metakey    != '')    $this->cacheMetaKey[$sef] = $metakey;
            if ($metalang   != '')   $this->cacheMetaLang[$sef] = $metalang;
            if ($metarobots != '') $this->cacheMetaRobots[$sef] = $metarobots;
            if ($metagoogle != '') $this->cacheMetaGoogle[$sef] = $metagoogle;

            // Sort the cache by hit count
            asort($this->cacheHit, SORT_NUMERIC);

            // Save the cache to disk
            $this->saveCache();
        }
        else {
            // Get the URL with minimum hits count
            reset($this->cacheHit);
            list($key, $value) = each($this->cacheHit);

            // Check if new URL is more often used
            if ($hits > $value) {
                // It is, let's change it
                unset($this->cacheHit[$key]);
                unset($this->cacheUrl[$key]);
                unset($this->cacheItemid[$key]);
                unset($this->cacheMetaTitle[$sef]);
                unset($this->cacheMetaDesc[$sef]);
                unset($this->cacheMetaKey[$sef]);
                unset($this->cacheMetaLang[$sef]);
                unset($this->cacheMetaRobots[$sef]);
                unset($this->cacheMetaGoogle[$sef]);

                $this->cacheUrl[$sef] = $nonsef;
                $this->cacheHit[$sef] = $hits;
                if ($Itemid != '')     $this->cacheItemid[$sef]      = $Itemid;
                if ($metatitle != '')  $this->cacheMetaTitle[$sef]   = $metatitle;
                if ($metadesc != '')   $this->cacheMetaDesc[$sef]    = $metadesc;
                if ($metakey != '')    $this->cacheMetaKey[$sef]     = $metakey;
                if ($metalang != '')   $this->cacheMetaLang[$sef]    = $metalang;
                if ($metarobots != '') $this->cacheMetaRobots[$sef]  = $metarobots;
                if ($metagoogle != '') $this->cacheMetaGoogle[$sef]  = $metagoogle;

                asort($this->cacheHit, SORT_NUMERIC);

                $this->saveCache();
            }
        }
    }
}
?>
