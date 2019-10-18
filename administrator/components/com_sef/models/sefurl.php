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

class SEFModelSEFUrl extends JModel
{
    /**
     * Constructor that retrieves the ID from the request
     *
     * @access    public
     * @return    void
     */
    function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid',  0, '', 'array');
        $this->setId((int)$array[0]);
    }

    function setId($id)
    {
        // Set id and wipe data
        $this->_id      = $id;
        $this->_data    = null;
    }

    function &getData()
    {
        // Load the data
        if (empty( $this->_data )) {
            $query = "SELECT * FROM `#__redirection` WHERE `id` = '{$this->_id}'";
            $this->_db->setQuery( $query );
            $this->_data = $this->_db->loadObject();
        }
        if (!$this->_data) {
            $this->_data = new stdClass();
            $this->_data->id = 0;
            $this->_data->cpt = null;
            $this->_data->oldurl = null;
            $this->_data->newurl = null;
            $this->_data->Itemid = null;
            $this->_data->metadesc = null;
            $this->_data->metakey = null;
            $this->_data->metatitle = null;
            $this->_data->metalang = null;
            $this->_data->metarobots = null;
            $this->_data->metagoogle = null;
            $this->_data->dateadd = null;
        }

        return $this->_data;
    }

    function store()
    {
        $row =& $this->getTable();

        $data = JRequest::get( 'post' );

        // Bind the form fields to the table
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Make sure the record is valid
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Store the table to the database
        if (!$row->store()) {
            $this->setError( $row->getErrorMsg() );
            return false;
        }

        // Check if there's old url to save to Moved Permanently table
        $unchanged = JRequest::getVar('unchanged');
        if( !empty($unchanged) ) {
            $row =& $this->getTable('MovedUrl');
            $row->old = $unchanged;
            $row->new = JRequest::getVar('oldurl');

            // pre-save checks
            if (!$row->check()) {
                $this->setError( $row->getErrorMsg() );
                return false;
            }

            // save the changes
            if (!$row->store()) {
                $this->setError( $row->getErrorMsg() );
                return false;
            }
        }

        return true;
    }

    function delete()
    {
        $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

        $row =& $this->getTable();

        if( count($cids) )
        {
            $ids = implode(',', $cids);
            $query = "DELETE FROM `#__redirection` WHERE `id` IN ($ids)";
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError( $this->_db->getErrorMsg() );
                return false;
            }
        }
        return true;
    }

}
?>
