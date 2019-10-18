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

class SefExt {
    function beforeCreate(&$uri) {
        return;
    }

    function afterCreate(&$uri) {
        return;
    }

    function getSefUrlFromDatabase(&$uri) {
        $db =& JFactory::getDBO();
        $sefConfig =& SEFConfig::getConfig();

        // David (284): ignore Itemid if set to
        $where = '';

        // Get the extension's ignoreSource parameter
        $option = $uri->getVar('option');
        if( !is_null($option) ) {
            $params = SEFTools::getExtParams($option);
            $extIgnore = $params->get('ignoreSource', 2);
        } else {
            $extIgnore = 2;
        }
        $ignoreSource = ($extIgnore == 2 ? $sefConfig->ignoreSource : $extIgnore);
        if( !$ignoreSource && !is_null($uri->getVar('Itemid')) ) {
            $where = " AND `Itemid` = '".$uri->getVar('Itemid')."'";
        }

        $query = "SELECT `oldurl` FROM `#__redirection` WHERE `newurl` = '".addslashes(urldecode(JoomSEF::_uriToUrl($uri, 'Itemid')))."'" . $where;
        $db->setQuery($query);
        $result = $db->loadresult();

        return !empty($result) ? $result : false;
    }

    function create(&$uri) {
        $vars = $uri->getQuery(true);
        extract($vars);
        
        $title = array();
        $title[] = JoomSEF::_getMenuTitle(@$option, null, @$Itemid);

        $newUri = $uri;
        if (count($title) > 0) {
            $newUri = JoomSEF::_sefGetLocation($uri, $title, null, null, null, @$lang);
        }
        
        return $newUri;
    }

    function revert($route) {
        $db =& JFactory::getDBO();
        $sefConfig =& SEFConfig::getConfig();
        $cache =& SEFCache::getInstance();
        $vars = array();

        // Try to use cache
        if ($sefConfig->useCache) {
            $row = $cache->getNonSefUrl($route);
        }
        else {
            $row = null;
        }
        if ($row) {
            // Cache worked
            $fromCache = true;
        }
        else {
            // URL isn't in cache or cache disabled
            $fromCache = false;
            
            if( $sefConfig->transitSlash ) {
                $route = rtrim($route, '/');
                $where = "(`oldurl` = '$route') OR (`oldurl` = '".$route.'/'."')";
            } else {
                $where = "`oldurl` = '$route'";
            }
            $sql = "SELECT * FROM #__redirection WHERE ($where) AND (`newurl` != '') LIMIT 1";
            $db->setQuery($sql);
            $row = $db->loadObject();
        }

        if ($row) {
            // Use the already created URL
            $string = $row->newurl;
            if( isset($row->Itemid) && ($row->Itemid != '') ) {
                $string .= (strpos($string, '?') ? '&' : '?') . 'Itemid=' . $row->Itemid;
            }

            $where = '';
            if( !empty($row->id) ) {
                $where = " WHERE `id` = '{$row->id}'";
            } else {
                $where = " WHERE `oldurl` = '{$row->oldurl}' AND `newurl` != ''";
            }
            
            // update the count
            $db->setQuery("UPDATE #__redirection SET cpt=(cpt+1)".$where);
            $db->query();
            $string = str_replace( '&amp;', '&', $string );
            $QUERY_STRING = str_replace('index.php?', '', $string);
            parse_str($QUERY_STRING, $vars);
            if( $sefConfig->setQueryString ) {
                $_SERVER['QUERY_STRING'] = $QUERY_STRING;
            }

            // Prepare the meta tags array for MetaBot
            global $mainframe;
            if (!empty($row->metatitle))  $mainframe->set('sef.meta.title',  $row->metatitle );
            if (!empty($row->metadesc))   $mainframe->set('sef.meta.desc',   $row->metadesc );
            if (!empty($row->metakey))    $mainframe->set('sef.meta.key',    $row->metakey );
            if (!empty($row->metalang))   $mainframe->set('sef.meta.lang',   $row->metalang );
            if (!empty($row->metarobots)) $mainframe->set('sef.meta.robots', $row->metarobots );
            if (!empty($row->metagoogle)) $mainframe->set('sef.meta.google', $row->metagoogle );

            // If cache is enabled but URL isn't in cache yet, add it
            if ($sefConfig->useCache && !$fromCache) {
                $cache->addUrl($row->newurl, $row->oldurl, $row->cpt + 1, $row->Itemid, $row->metatitle, $row->metadesc, $row->metakey, $row->metalang, $row->metarobots, $row->metagoogle);
            }
        } elseif( $sefConfig->useMoved ) {
            // URL not found, let's try the Moved Permanently table
            $db->setQuery("SELECT * FROM `#__sefmoved` WHERE `old` = '$route'");
            $row = $db->loadObject();

            if($row) {
                // URL found, let's update the lastHit in table and redirect
                $db->setQuery("UPDATE `#__sefmoved` SET `lastHit` = NOW() WHERE `id` = '$row->id'");
                $db->query();

                $root = JURI::root();
                $f = $l = '';
                if (!headers_sent($f, $l)) {
                    // Let's build absolute URL from our link
                    if( strstr($row->new, $root) === false ) {
                        $url = $root;
                        if( substr($url, -1) != '/' )           $url .= '/';
                        if( substr($row->new, 0, 1) == '/' )    $row->new = substr($row->new, 1);
                        $url .= $row->new;
                    } else {
                        $url = $row->new;
                    }

                    // Use the link to redirect
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: ".$url);
                    header("Connection: close");
                    exit();
                } else {
                    JoomSEF::_headers_sent_error($f, $l, __FILE__, __LINE__);
                }
            }
        }

        return $vars;
    }
}

?>
