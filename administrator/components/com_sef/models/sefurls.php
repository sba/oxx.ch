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

class SEFModelSEFUrls extends JModel
{
    /**
     * Constructor that retrieves variables from the request
     */
    function __construct()
    {
        parent::__construct();
        $this->_getVars();
    }

    function _getVars()
    {
        global $mainframe;

        $this->viewmode = $mainframe->getUserStateFromRequest('sef.sefurls.viewmode', 'viewmode', 0);
        $this->sortby = $mainframe->getUserStateFromRequest('sef.sefurls.sortby', 'sortby', 0);
        $this->filterComponent = $mainframe->getUserStateFromRequest("sef.sefurls.comFilter", 'comFilter', '');
        $this->filterSEF = $mainframe->getUserStateFromRequest("sef.sefurls.filterSEF", 'filterSEF', '');
        $this->filterReal = $mainframe->getUserStateFromRequest("sef.sefurls.filterReal", 'filterReal', '');
        $this->filterHitsCmp = $mainframe->getUserStateFromRequest("sef.sefurls.filterHitsCmp", 'filterHitsCmp', 0);
        $this->filterHitsVal = $mainframe->getUserStateFromRequest("sef.sefurls.filterHitsVal", 'filterHitsVal', '');
        $this->filterItemid = $mainframe->getUserStateFromRequest("sef.sefurls.filterItemId", 'filterItemid', '');

        $this->limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $this->limitstart	= $mainframe->getUserStateFromRequest('sef.sefurls.limitstart', 'limitstart', 0, 'int');

        // In case limit has been changed, adjust limitstart accordingly
        $this->limitstart = ( $this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0 );

        $total = $this->getTotal();
        if( ($this->limitstart + $this->limit - 1) > $total ) {
            $this->limitstart = max(($total - $this->limit + 1), 0);
        }
    }

    /**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
    function _buildQuery()
    {
        $limit = '';
        if( ($this->limit != 0) || ($this->limitstart != 0) ) {
            $limit = " LIMIT {$this->limitstart},{$this->limit}";
        }

        $query = "SELECT * FROM #__redirection WHERE ".$this->_getWhere()." ORDER BY ".$this->_getSort().$limit;

        return $query;
    }

    function _getSort()
    {
        if( !isset($this->_sort) ) {
            switch ($this->sortby) {
                case 1: $this->_sort =  "`oldurl` DESC";    break;
                case 2: $this->_sort =  "`newurl`";         break;
                case 3: $this->_sort =  "`newurl` DESC";    break;
                case 4: $this->_sort =  "`cpt`";            break;
                case 5: $this->_sort =  "`cpt` DESC";       break;
                default: $this->_sort = "`oldurl`";         break;
            }
        }

        return $this->_sort;
    }

    function _getWhere()
    {
        if( empty($this->_where) ) {
            // Filter ViewMode
            if ($this->viewmode == 1) {
                $where = "`dateadd` > '0000-00-00' AND `newurl` = '' ";
            } elseif ( $this->viewmode == 2 ) {
                $where = "`dateadd` > '0000-00-00' AND `newurl` != '' ";
            } elseif ( $this->viewmode == 0 ) {
                $where = "`dateadd` = '0000-00-00' ";
            } elseif ( $this->viewmode == 4 ) {
                $where = "(`newurl` != '') AND (`oldurl` = '' OR `newurl` LIKE '%option=com_frontpage%') ";
            } else {
                $where = "`newurl` != '' ";
            }

            // Filter URLs
            if ($this->filterComponent != '' && $this->viewmode != 1) {
                $where .= "AND (`newurl` LIKE '%option={$this->filterComponent}&%' OR `newurl` LIKE '%option={$this->filterComponent}') ";
            }
            if ($this->filterSEF != '') {
                $where .= "AND `oldurl` LIKE '%{$this->filterSEF}%' ";
            }
            if ($this->filterReal != '' && $this->viewmode != 1) {
                $where .= "AND `newurl` LIKE '%{$this->filterReal}%' ";
            }

            // Filter hits
            if( $this->filterHitsVal != '' ) {
                $cmp = ($this->filterHitsCmp == 0) ? '=' : (($this->filterHitsCmp == 1) ? '>' : '<');
                $where .= "AND `cpt` $cmp {$this->filterHitsVal} ";
            }

            // Filter Itemid
            if( $this->filterItemid != '' && $this->viewmode != 1 ) {
                $where .= "AND `Itemid` = {$this->filterItemid} ";
            }

            $this->_where = $where;
        }

        return $this->_where;
    }
    
    function _getWhereIds()
    {
        $ids = JRequest::getVar('cid', array(), 'post', 'array');
        
        $where = '';
        if( count($ids) > 0 ) {
            $where = '`id` IN (' . implode(', ', $ids) . ')';
        }
        
        return $where;
    }

    function getTotal()
    {
        if( !isset($this->_total) )
        {
            $this->_db->setQuery("SELECT COUNT(*) FROM `#__redirection` WHERE ".$this->_getWhere());
            $this->_total = $this->_db->loadResult();
        }

        return $this->_total;
    }

    /**
     * Retrieves the data
     */
    function getData()
    {
        // Lets load the data if it doesn't already exist
        if (empty( $this->_data ))
        {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList( $query );
        }

        return $this->_data;
    }

    function getLists()
    {
        // Make the input boxes for hits filter
        $hitsCmp[] = JHTML::_('select.option', '0', '=');
        $hitsCmp[] = JHTML::_('select.option', '1', '>');
        $hitsCmp[] = JHTML::_('select.option', '2', '<');
        $lists['hitsCmp'] = JHTML::_('select.genericlist', $hitsCmp, 'filterHitsCmp', "class=\"inputbox\" onchange=\"document.adminForm.submit();\" size=\"1\"" , 'value', 'text', $this->filterHitsCmp);
        $lists['hitsVal'] = "<input type=\"text\" name=\"filterHitsVal\" value=\"{$this->filterHitsVal}\" size=\"5\" maxlength=\"10\" onchange=\"document.adminForm.submit();\" />";

        // Make the input box for Itemid filter
        $lists['itemid'] = "<input type=\"text\" name=\"filterItemid\" value=\"{$this->filterItemid}\" size=\"5\" maxlength=\"10\" onchange=\"document.adminForm.submit();\" />";

        // make the select list for the filter
        $viewmode[] = JHTML::_('select.option', '3', JText::_('Show All Redirects'));
        $viewmode[] = JHTML::_('select.option', '2', JText::_('Show Custom Redirects'));
        $viewmode[] = JHTML::_('select.option', '0', JText::_('Show SEF Urls'));
        $viewmode[] = JHTML::_('select.option', '4', JText::_('Show Links to Homepage'));
        $viewmode[] = JHTML::_('select.option', '1', JText::_('Show 404 Log'));
        $lists['viewmode'] = JHTML::_('select.genericlist', $viewmode, 'viewmode', "class=\"inputbox\" onchange=\"document.adminForm.submit();\" size=\"1\"" ,  'value', 'text', $this->viewmode);

        // make the select list for the filter
        $orderby[] = JHTML::_('select.option', '0', JText::_('SEF Url').' '.JText::_('(asc)'));
        $orderby[] = JHTML::_('select.option', '1', JText::_('SEF Url').' '.JText::_('(desc)'));
        if ($this->viewmode != 1) {
            $orderby[] = JHTML::_('select.option', '2', JText::_('Real Url').' '.JText::_('(asc)'));
            $orderby[] = JHTML::_('select.option', '3', JText::_('Real Url').' '.JText::_('(desc)'));
        }
        $orderby[] = JHTML::_('select.option', '4', JText::_('Hits').' '.JText::_('(asc)'));
        $orderby[] = JHTML::_('select.option', '5', JText::_('Hits').' '.JText::_('(desc)'));
        $lists['sortby'] = JHTML::_('select.genericlist', $orderby, 'sortby', "class=\"inputbox\" onchange=\"document.adminForm.submit();\" size=\"1\"" , 'value', 'text', $this->sortby);

        // make the select list for the component filter
        $comList[] = JHTML::_('select.option', '', JText::_('(All)'));
        $comList[] = JHTML::_('select.option', 'com_content', 'Content');
        $this->_db->setQuery("SELECT `name`,`option` FROM `#__components` WHERE `parent` = '0' ORDER BY `name`");
        $rows = $this->_db->loadObjectList();
        if ($this->_db->getErrorNum()) {
            echo $this->_db->stderr();
            return false;
        }
        foreach(array_keys($rows) as $i) {
            $row = &$rows[$i];
            $comList[] = JHTML::_('select.option',  $row->option, $row->name );
        }
        $lists['comList'] = JHTML::_( 'select.genericlist', $comList, 'comFilter', "class=\"inputbox\" onchange=\"document.adminForm.submit();\" size=\"1\"", 'value', 'text', $this->filterComponent);

        // make the filter text boxes
        $lists['filterSEF']  = "<input type=\"text\" name=\"filterSEF\" value=\"{$this->filterSEF}\" size=\"40\" maxlength=\"255\" onchange=\"document.adminForm.submit();\" title=\"".JText::_(_COM_SEF_FILTERSEF_HLP)."\" />";
        $lists['filterReal'] = "<input type=\"text\" name=\"filterReal\" value=\"{$this->filterReal}\" size=\"40\" maxlength=\"255\" onchange=\"document.adminForm.submit();\" title=\"".JText::_(_COM_SEF_FILTERREAL_HLP)."\" />";

        return $lists;
    }

    function getPagination()
    {
        jimport('joomla.html.pagination');
        $pagination = new JPagination($this->getTotal(), $this->limitstart, $this->limit);

        return $pagination;
    }

    function deleteFiltered()
    {
        $query = "DELETE FROM `#__redirection` WHERE ".$this->_getWhere();
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            $this->setError( $this->_db->getErrorMsg() );
            return false;
        }

        return true;
    }

    function export($where = '')
    {
        $config =& JFactory::getConfig();
        $dbprefix = $config->getValue('config.dbprefix');
        $sql_data = '';
        $filename = 'joomsef_custom_urls.sql';
        $fields = array( 'cpt', 'oldurl', 'newurl', 'Itemid', 'metadesc', 'metakey', 'metatitle', 'metalang', 'metarobots', 'metagoogle', 'dateadd' );

        $query = "SELECT * FROM `#__redirection`";
        if( !empty($where) ) {
            $query .= " WHERE " . $where;
        }
        $this->_db->setQuery( $query );
        $rows = $this->_db->loadObjectList();

        if( !empty($rows)  ) {
            foreach( $rows as $row ) {
                $values = array();
                foreach( $fields as $field ) {
                    if( isset($row->$field) ) {
                        $values[] = '\'' . $row->$field . '\'';
                    } else {
                        $values[] = '\'\'';
                    }
                }
                $sql_data .= "INSERT INTO `{$dbprefix}redirection` (".implode(', ', $fields).") VALUES (".implode(', ', $values).");\n";
            }
        } else {
            return false;
        }

        if( !headers_sent() ) {
            // Flush the output buffer
            while( ob_get_level() > 0 ) {
                ob_end_clean();
            }

            ob_start();
            header ('Expires: 0');
            header ('Last-Modified: '.gmdate ('D, d M Y H:i:s', time()) . ' GMT');
            header ('Pragma: public');
            header ('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header ('Accept-Ranges: bytes');
            header ('Content-Length: ' . strlen($sql_data));
            header ('Content-Type: Application/octet-stream');
            header ('Content-Disposition: attachment; filename="' . $filename . '"');
            header ('Connection: close');

            echo($sql_data);

            ob_end_flush();
            die();
            return true;
        } else {
            return false;
        }
    }

    function import()
    {
        // Get the uploaded file information
        $userfile = JRequest::getVar('importfile', null, 'files', 'array' );

        // Make sure that file uploads are enabled in php
        if (!(bool) ini_get('file_uploads')) {
            JError::raiseWarning(100, JText::_('Uploads not allowed.'));
            return false;
        }

        // If there is no uploaded file, we have a problem...
        if (!is_array($userfile) ) {
            JError::raiseWarning(100, JText::_('No file selected'));
            return false;
        }

        // Check if there was a problem uploading the file.
        if ( $userfile['error'] || $userfile['size'] < 1 )
        {
            JError::raiseWarning(100, JText::_('Error while uploading the file.'));
            return false;
        }

        // Build the appropriate paths
        $config =& JFactory::getConfig();
        $tmp_dest 	= $config->getValue('config.tmp_path').DS.$userfile['name'];
        $tmp_src	= $userfile['tmp_name'];

        // Move uploaded file
        jimport('joomla.filesystem.file');
        $uploaded = JFile::upload($tmp_src, $tmp_dest);

        // Load SQL
        $lines = file($tmp_dest);

        $result = true;
        $dbprefix = $config->getValue('config.dbprefix');
        $command = "INSERT INTO `{$dbprefix}redirection`";
        for ($i = 0, $n = count($lines); $i < $n; $i++) {
            // Trim line
            $line = trim($lines[$i]);
            // Ignore empty lines
            if (strlen($line) == 0) continue;

            // If the query continues at the next line.
            while (substr($line, -1) != ';' && $i + 1 < count($lines)) {
                $i++;
                $newLine = trim($lines[$i]);
                if (strlen($newLine) == 0) continue;
                $line .= ' '.$lines[$i];
            }

            if (preg_match('/^INSERT INTO `?(\w)+redirection`?/', $line) > 0) {
                // Fix for files exported from versions older than 1.3.0
                if (strstr($line, "redirection` VALUES") != false) {
                    $line = str_replace("redirection` VALUES", "redirection` (id, cpt, oldurl, newurl, dateadd) VALUES", $line);
                }

                // Fix for files exported from versions prior to 2.0.0
                if( stristr($line, "newurl, Itemid") == false ) {
                    $url = preg_replace('/.*VALUES\s*\(\'[^\']*\',\s*\'[^\']*\',\s*\'[^\']*\',\s*\'([^\']*)\'.*/', '$1', $line);
                    $itemid = preg_replace('/.*[&?]Itemid=([^&]*).*/', '$1', $url);
                    
                    $newurl = eregi_replace("Itemid=[^&]*", '', $url);
                    $newurl = trim($newurl, '&?');
                    $trans = array( '&&' => '&', '?&' => '?' );
                    $newurl = strtr($newurl, $trans);
                    
                    $line = str_replace('newurl,', 'newurl, Itemid,', $line);
                    $line = str_replace("'$url'", "'$newurl','$itemid'", $line);
                }

                $this->_db->setQuery($line);
                if (!$this->_db->query()) {
                    JError::raiseWarning( 100, JText::_('Error importing line') . ': ' . $line . '<br />' . $this->_db->getErrorMsg() );
                    $result = false;
                }
            }
            else {
                JError::raiseWarning(100, JText::_('Ignoring line') . ': ' . $line);
            }
        }

        JFile::delete($tmp_dest);
        
        return $result;
    }
}
?>
