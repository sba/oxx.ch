<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );
 
class PhocaGuestbooksViewPhocaGuestbooks extends JView
{
	function display($tpl = null)
	{
		
		
		global $mainframe;
		$db			=& JFactory::getDBO();
		$uri		=& JFactory::getURI();
		$document	= & JFactory::getDocument();

		// Set toolbar items for the page
		JToolBarHelper::title(   JText::_( 'Phoca Guestbook Item' ), 'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList(  JText::_( 'WARNWANTDELLISTEDITEMS' ), 'remove', 'delete');
		JToolBarHelper::editListX();
		//JToolBarHelper::addNewX();
		
		JToolBarHelper::preferences('com_phocaguestbook', '360');
		JToolBarHelper::help( 'screen.phocaguestbook', true );

		//Filter
		$context			= 'com_phocaguestbook.phocaguestbook.list.';
		$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',				'word' );
	#	$filter_catid		= $mainframe->getUserStateFromRequest( $context.'filter_catid',		'filter_catid',		0,				'int' );
		$filter_guestbook	= $mainframe->getUserStateFromRequest( $context.'filter_guestbook',	'filter_guestbook',	0,				'int' );
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $mainframe->getUserStateFromRequest( $context.'search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		// Get data from the model
		$items		= & $this->get( 'Data');
		$total		= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );
		

		
	#	// build list of categories
	#	$javascript 	= 'onchange="document.adminForm.submit();"';
	#	$lists['catid'] = JHTML::_('list.category',  'filter_catid', $context, intval( $filter_catid ), $javascript );

		
		//build the list of guestbooks
		$query = 'SELECT a.title AS text, a.id AS value'
		. ' FROM #__phocaguestbook_books AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$guestbooks = $db->loadObjectList();
		array_unshift($guestbooks, JHTML::_('select.option', '0', '- '.JText::_('Select Guestbook').' -', 'value', 'text'));
		
		//list guestbook
		$lists['guestbook'] = JHTML::_( 'select.genericlist', $guestbooks, 'filter_guestbook',  'onchange="document.adminForm.submit();"', 'value', 'text', intval( $filter_guestbook ));
		
		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search']= $search;
		
			//Get version (check for update)
		$folder = JPATH_ADMINISTRATOR .DS. 'components'.DS.'com_phocaguestbook';
		if (JFolder::exists($folder)) {
			$xmlFilesInDir = JFolder::files($folder, '.xml$');
		} else {
			$folder = JPATH_SITE .DS. 'components'.DS.'com_phocaguestbook';
			if (JFolder::exists($folder)) {
				$xmlFilesInDir = JFolder::files($folder, '.xml$');
			} else {
				$xmlFilesInDir = null;
			}
		}

		$xml_items = '';
		if (count($xmlFilesInDir))
		{
			foreach ($xmlFilesInDir as $xmlfile)
			{
				if ($data = JApplicationHelper::parseXMLInstallFile($folder.DS.$xmlfile)) {
					foreach($data as $key => $value) {
						$xml_items[$key] = $value;
					}
				}
			}
		}

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('version',	$xml_items['version']);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>