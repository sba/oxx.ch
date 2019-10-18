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
 * @todo Fix mostoolbar
 * @package Communicator
 * @author Granholm CMS
 * @link http://www.granholmcms.com
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2008 granholmcms.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version $Id: toolbar.communicator.php 301 2008-06-04 12:18:04Z Suami $
 */

defined('_JEXEC') or die('Restricted access');

require_once( JApplicationHelper::getPath( 'toolbar_html' ) );

switch ( $task ) {

	case "new":
	case "edit":
		TOOLBAR_communicator::EDIT_MENU();
		break;
	case 'compose':
		TOOLBAR_communicator::COMPOSE_MENU();
		break;
	case 'composeNow':
		TOOLBAR_communicator::CONFIRM_COMPOSE_MENU();
		break;
	case "sendNow":
		TOOLBAR_communicator::SEND_MENU();
		break;
	
	case "importSubscribers": 
		TOOLBAR_communicator::SUBSCRIBER_IMPORT_MENU();
		break;
	
	case "subscribers":
	case "deleteSubscriber":
		TOOLBAR_communicator::SUBSCRIBE_MENU();
		break;
	case "assignUsers":
	case "editSubscriber":
		TOOLBAR_communicator::SUBSCRIBER_EDIT_MENU();
		break;
	case 'config':
		TOOLBAR_communicator::CONFIG_MENU();
		break;
	default:
		TOOLBAR_communicator::DEFAULT_MENU();
		break;
}
?>
