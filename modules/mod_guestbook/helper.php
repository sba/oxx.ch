<?php
/**
 * Helper class for Gästebuch module
 * 
 * @package    OXX
 * @subpackage Modules
 * @license        GNU/GPL, see LICENSE.php
 * mod_guestbook is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
 
class modGBHelper
{
	/**
	 * Retrieves GB-entry
	 *
	 * @param array $params An object containing the module parameters
	 * @access public
	 */    
	function getGB( $params )
	{
		$db = &JFactory::getDBO();
		$db->setQuery('SELECT username, date, title, content FROM #__phocaguestbook_items WHERE published = 1 ORDER BY id DESC LIMIT 1' );

		// Use the loadResult() method to get only the first value from the first row of the result set.
		$row = $db->loadRow();
		
		$date = JHTML::_('date', $row[1], JText::_('DATE_FORMAT_LC4'));
		$entry = (strlen($row[2])==0)?'':$row[2] . ': ';
		$entry .= $row[3];
		if (strlen($entry)>250){
			$entry = substr($entry,0, 250) . "...";
		}
		$gb = array('date' => $date, 'username' => $row[0], 'entry' => $entry);
		
		return $gb;
	}
}
?>