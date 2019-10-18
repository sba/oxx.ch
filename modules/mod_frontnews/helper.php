<?php
/**
 * Helper class for Frontpage-Article module
 * 
 * @package    OXX
 * @subpackage Modules
 * @license        GNU/GPL, see LICENSE.php
 * mod_frontnews is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
 
class modFnHelper
{
	/**
	 * Retrieves Frontpage-entries
	 *
	 * @param array $params An object containing the module parameters
	 * @access public
	 */    
	function getList(&$params)
	{
		global $mainframe;

		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$userId		= (int) $user->get('id');

		$count		= (int) $params->get('count', 2);
		$allowed_tags = $params->get('allowed_tags', '<i><b><strong>');
		
		$aid		= $user->get('aid', 0);

		$nullDate	= $db->getNullDate();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();

		$where		= 'a.state = 1'
			. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;

		// Content Items 
		$query = 'SELECT a.*, ' .
			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
			' FROM #__content AS a' .
			' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' .
			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
			' WHERE '. $where .' AND s.id > 0' .
			' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid .
			' AND f.content_id IS NOT NULL ' .
			' AND s.published = 1' .
			' AND cc.published = 1' .
			' ORDER BY f.ordering';
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();
		
		$i		= 0;
		$lists	= array();
		foreach ( $rows as $row )
		{
		
			// Get the page/component configuration and article parameters
			$row->params = clone($params);
			$aparams = new JParameter($row->attribs);

			$row->params->merge($aparams);


			if ($params->get( 'show_date' ) == '1') {
				$lists[$i]->date =  JHTML::_('date', $row->publish_up,$params->get( 'date_format' ), $offset) . '<br>';
			}
			
			if (strlen($row->fulltext)>0){
				if($row->access <= $aid)
				{
					$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
				} else {
					$lists[$i]->link = JRoute::_('index.php?option=com_user&view=login');
				}
				$lists[$i]->readmore = ($row->params->get( 'readmore'))?$row->params->get( 'readmore'):'weiterlesen...';
			}
			
			$lists[$i]->title = htmlspecialchars( $row->title );
			$lists[$i]->fulltext = htmlspecialchars( $row->fulltext );
			$lists[$i]->introtext = strip_tags( $row->introtext,$allowed_tags );
			$i++;
		}

		return $lists;
	}
}

/*
		// Build the link and text of the readmore button
		if (($item->params->get('show_readmore') && @ $item->readmore) || $item->params->get('link_titles'))
		{
			// checks if the item is a public or registered/special item
			if ($item->access <= $user->get('aid', 0))
			{
				$item->readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug, $item->sectionid));
				$item->readmore_register = false;
			}
			else
			{
				$item->readmore_link = JRoute::_("index.php?option=com_user&view=login");
				$item->readmore_register = true;
			}
		}*/
?>