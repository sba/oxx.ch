<?php
/**
 * Communicator file
 *
 * Based on Letterman module by Soeren Eberhardt modified by RolandD of www.csvimproved.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package Communicator
 * @author Granholm CMS
 * @link http://www.granholmcms.com
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2008 granholmcms.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version $Id: toolbar.communicator.html.php 301 2008-06-04 12:18:04Z Suami $
 */

defined('_JEXEC') or die('Restricted access');

class TOOLBAR_communicator {
	/**
	* Draws the menu for a New Contact
	*/
	function EDIT_MENU() {
		JToolBarHelper::save( 'save', JText::_('E_SAVE'));
		JToolBarHelper::spacer();
		JToolBarHelper::cancel( 'cancel', JText::_('E_CANCEL'));
		JToolBarHelper::spacer();
	}
	function COMPOSE_MENU() {
		JToolBarHelper::save( 'composeNow', JText::_( 'E_SAVE' ));
		JToolBarHelper::spacer();
		JToolBarHelper::cancel( 'cancel', JText::_('E_CANCEL'));
		JToolBarHelper::spacer();
	}
	function CONFIRM_COMPOSE_MENU() {
		JToolBarHelper::save( 'save', JText::_('E_SAVE'));
		JToolBarHelper::spacer();
		JToolBarHelper::cancel( 'compose', JText::_('E_CANCEL'));
		JToolBarHelper::spacer();
	}	
	function SEND_MENU() {
		JToolBarHelper::publish( "sendMail" );
		JToolBarHelper::cancel( 'cancel', JText::_('CMN_CANCEL'));
		JToolBarHelper::spacer();
	}

	function DEFAULT_MENU() {
		JToolBarHelper::publishList();
		JToolBarHelper::spacer();
		JToolBarHelper::unpublishList();
		JToolBarHelper::spacer();
		JToolBarHelper::divider();
		JToolBarHelper::spacer();
		JToolBarHelper::addNew( 'new', JText::_('CMN_NEW'));
		JToolBarHelper::spacer();
		JToolBarHelper::addNew( 'compose', JText::_('Compose Newsletter'));
		JToolBarHelper::spacer();
		JToolBarHelper::editList( 'edit',  JText::_('E_EDIT'));
		JToolBarHelper::spacer();
		JToolBarHelper::deleteList( '', 'remove', JText::_('E_REMOVE'));
		JToolBarHelper::spacer();
	}
	function SUBSCRIBE_MENU() {
		JToolBarHelper::addNew( "editSubscriber", JText::_('CM_NEW_SUBSCRIBER') );
		JToolBarHelper::editList( "editSubscriber", JText::_('CM_EDIT_SUBSCRIBER') );
		JToolBarHelper::deleteList( "", "deleteSubscriber", JText::_('E_REMOVE') );
		JToolBarHelper::custom( 'deletenotconfirmed', 'delete.png', 'delete.png', JText::_('Remove not confirmed'), false );
		JToolBarHelper::divider();
		JToolBarHelper::custom('assignUsers', 'user_f2.png',  '', JText::_('CM_ASSIGN_USERS'), false );
		
		if( function_exists('getmxrr')) {
			JToolBarHelper::divider();
			JToolBarHelper::custom('validateEmails', 'validate_f2.png',  '', JText::_('CM_VALIDATE_MX'), true );
		}
		
		JToolBarHelper::divider();
		JToolBarHelper::custom( 'importSubscribers', 'upload.png', 'upload_f2.png', JText::_('CM_IMPORT_USERS'), false );
		JToolBarHelper::custom( 'exportSubscribers', 'archive.png', 'archive_f2.png', JText::_('CM_EXPORT_USERS'), false );
	}
	function SUBSCRIBER_EDIT_MENU() {
		JToolBarHelper::save( "saveSubscriber", JText::_('E_SAVE') );
		JToolBarHelper::spacer();
		JToolBarHelper::cancel( "subscribers", JText::_('E_CANCEL') );
		JToolBarHelper::spacer();
	}
	function SUBSCRIBER_IMPORT_MENU() {
		JToolBarHelper::save( "importSubscribers", JText::_('E_SAVE') );
		JToolBarHelper::spacer();
		JToolBarHelper::cancel( "subscribers", JText::_('E_CANCEL') );
		JToolBarHelper::spacer();
	}
	function CONFIG_MENU() {
		JToolBarHelper::save('saveconfig', JText::_('E_SAVE'));
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('cancelconfig', JText::_('E_CANCEL'));
	}
}?>
