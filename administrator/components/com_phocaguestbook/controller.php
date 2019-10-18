<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Guestbook
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

jimport('joomla.application.component.controller');

// Submenu view
$view	= JRequest::getVar( 'view', '', '', 'string', JREQUEST_ALLOWRAW );
if ($view == 'phocaguestbookbs')
{
	JSubMenuHelper::addEntry(JText::_('Items'), 'index.php?option=com_phocaguestbook');
	JSubMenuHelper::addEntry(JText::_('Guestbooks'), 'index.php?option=com_phocaguestbook&view=phocaguestbookbs', true );
}
else
{
	JSubMenuHelper::addEntry(JText::_('Items'), 'index.php?option=com_phocaguestbook', true);
	JSubMenuHelper::addEntry(JText::_('Guestbooks'), 'index.php?option=com_phocaguestbook&view=phocaguestbookbs' );
}

class PhocaGuestbooksController extends JController
{
	function display()
	{
		parent::display();
	}
}
?>
