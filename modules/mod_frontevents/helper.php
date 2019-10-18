<?php
/**
* @version		$Id: helper.php 10616 2008-08-06 11:06:39Z hackwar $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modfronteventsHelper
{
	function getList(&$params)
	{
		global $mainframe;

		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$userId		= (int) $user->get('id');
		$aid		= $user->get('aid', 0);

		
		$events_full  		= (int) $params->get( 'events_full', 2);
		$events_link 		= (int) $params->get( 'events_link', 4);
		$section_id 		= trim($params->get( 'section_id', 0));
		
		$azlimit = $events_full + $events_link;
		

		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access		= !$contentConfig->get('shownoauth');

		$nullDate	= $db->getNullDate();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();
        
		$where		= 'a.state = 1'
			. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;


		//Filter
		//special OX-filter to fetch multidays-events (with custom MySQL Function 'DIGITS') and show events until 02:00 in night
		$where .= ' AND ( DATE_ADD(SUBSTRING(a.attribs, 12,10), INTERVAL IF(INSTR(a.attribs,"event_days=")=0,1,DIGITS(SUBSTR(a.attribs,INSTR(a.attribs,"event_days=")+11,2)))-1 DAY) >= "'.date('Y-m-d',strtotime("-2 hours")).'" )';
		$where .= ' AND (s.id=' .  $section_id . ')';
		
		// Ordering
		$ordering		= ' SUBSTRING(a.attribs, 12,10)';
		$limit		 = ' LIMIT ' . $azlimit;

		// Content Items only
		$query = 'SELECT a.*, ' .
			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
			' FROM #__content AS a' .
			($show_front == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
			' WHERE '. $where .
			($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
			' ORDER BY '. $ordering .
			' ' . $limit;

		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();

		$i		= 0;
		$lists	= array();
		foreach ( $rows as $row )
		{
			$params = new JParameter($row->attribs);

			if ($i < $events_full){
				$lists['full'][$i]->id = $row->id;
				$lists['full'][$i]->readmore = strlen($row->fulltext);
				$lists['full'][$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
				$lists['full'][$i]->text = htmlspecialchars( $row->title );
				$lists['full'][$i]->date =  $params->get('event_date');
                $lists['full'][$i]->date2 =  $params->get('event_date2');
                $lists['full'][$i]->image =  $params->get('event_image');
				$lists['full'][$i]->prize =  $params->get('event_prize');
				$lists['full'][$i]->type =  $params->get('event_prize');
				$lists['full'][$i]->doors =  $params->get('event_doors');
				$lists['full'][$i]->event_intro =  $params->get('event_intro'); //Live in concert / OX at the movies presents / ...
				$lists['full'][$i]->introtext = $row->introtext;
			} else {
				$lists['link'][$i]->id = $row->id;
				$lists['link'][$i]->readmore = strlen($row->fulltext);
				$lists['link'][$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
				$lists['link'][$i]->text = htmlspecialchars( $row->title );
                $lists['link'][$i]->date =  $params->get('event_date');
                $lists['link'][$i]->date2 =  $params->get('event_date2');
			}
			
			$i++;
		}

		return $lists;
	}
}
