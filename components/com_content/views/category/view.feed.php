<?php
/**
 * @version		$Id: view.feed.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Content component
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class ContentViewCategory extends JView
{
	function display()
	{
		global $mainframe;

		$doc     =& JFactory::getDocument();
		$params =& $mainframe->getParams();
		$feedEmail = (@$mainframe->getCfg('feed_email')) ? $mainframe->getCfg('feed_email') : 'author';
		$siteEmail = $mainframe->getCfg('mailfrom');

		// Get some data from the model
		JRequest::setVar('limit', $mainframe->getCfg('feed_limit'));
		$category	= & $this->get( 'Category' );
		$rows 		= & $this->get( 'Data' );

		$doc->link = JRoute::_(ContentHelperRoute::getCategoryRoute($category->id, $category->sectionid));

		foreach ( $rows as $row )
		{
			// strip html from feed item title
			$title = $this->escape( $row->title );
			$title = html_entity_decode( $title );

			// url link to article
			// & used instead of &amp; as this is converted by feed creator
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));

			// strip html from feed item description text
			$description	= ($params->get('feed_summary', 0) ? $row->introtext.$row->fulltext : $row->introtext);
			$author			= $row->created_by_alias ? $row->created_by_alias : $row->author;

			// load individual item creator class
			$item = new JFeedItem();
			$event_date = substr($row->attribs,11,10);
			$str_event_date = strftime('%A, %d.%m.%y', mktime(0,0,0,substr($event_date,5,2),substr($event_date,8,2),substr($event_date,0,4)));
			$str_event_date = str_replace(array("Monday", "Tuesday", "Wednesday", "Thursday","Friday","Saturday","Sunday"), array("Mo", "Di", "Mi", "Do","Fr","Sa","So"), $str_event_date);
			
			//$event_date = $_event_params->get( 'event_date' );
			$item->title 		= $str_event_date.' - '.$title;
			$item->link 		= $link;
			$item->description 	= $description;
			//$item->date			= $event_date;
			$item->category   	= $row->category;
			$item->author		= "OX. Kultur im Ochsen";
			/*if ($feedEmail == 'site') {
				$item->authorEmail = //$siteEmail;
			}
			else {
				$item->authorEmail = $row->author_email;

			}*/
			$item->authorEmail = "info@oxx.ch";
			// loads item info into rss array
			$doc->addItem( $item );
		}
	}
}
