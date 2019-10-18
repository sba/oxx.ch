<?php
/**
 * SEF component for Joomla! 1.5
 *
 * @author      ARTIO s.r.o.
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 * @version     3.1.0
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class TableSEFUrl extends JTable
{
    /** @var int */
    var $id = null;
    /** @var int */
    var $cpt = null;
    /** @var string */
    var $oldurl = null;
    /** @var string */
    var $newurl = null;
    /** @var int */
    var $Itemid = null;
    /** @var string */
    var $metadesc = null;
    /** @var string */
    var $metakey = null;
    /** @var string */
    var $metatitle = null;
    /** @var string */
    var $metalang = null;
    /** @var string */
    var $metarobots = null;
    /** @var string */
    var $metagoogle = null;
    /** @var date */
    var $dateadd = null;

    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
    function TableSEFUrl(& $db) {
        parent::__construct('#__redirection', 'id', $db);
    }

    function check ()
    {
        //initialize
        $this->_error = null;
        $this->oldurl = trim($this->oldurl);
        $this->newurl = trim($this->newurl);
        $this->metadesc = trim($this->metadesc);
        $this->metakey = trim($this->metakey);
        // check for valid URLs
        if ($this->newurl == '') {
            $this->_error .= JText::_('You must provide a URL for the redirection.');
            return false;
        }
        if (eregi("^\/", $this->oldurl)) {
            $this->_error .= JText::_('There should be NO LEADING SLASH on the New SEF URL.');
        }
        if ((eregi("^index.php", $this->newurl)) === false) {
            $this->_error .= JText::_('The Old Non-SEF Url must begin with index.php');
        }
        if (is_null($this->_error)) {
            // check for existing URLS
            $this->_db->setQuery("SELECT id FROM #__redirection WHERE `oldurl` LIKE '" . $this->oldurl . "' AND `newurl` != ''");
            $xid = intval($this->_db->loadResult());
            if ($xid && $xid != intval($this->id)) {
                $this->_error = JText::_('This URL already exists in the database!');
                return false;
            }
            
            return true;
        } else {
            return false;
        }
    }
}
?>
