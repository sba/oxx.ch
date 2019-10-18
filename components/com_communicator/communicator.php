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
 * @version $Id: communicator.php 361 2008-07-07 11:43:32Z Suami $
 */

// load the html drawing class, MUST include the option for components
require_once( JPATH_COMPONENT . '/communicator.html.php');
require_once( JPATH_COMPONENT. '/communicator.class.php');

// Load configuration in constructor
$database =& JFactory::getDBO();
$communicator = new josCommunicator($database);

$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_communicator/communicator.css');

/* END CONFIG */
$pop = JRequest::getVar( 'pop', 0 );
$access = !$mainframe->getCfg( 'shownoauth' );
$task = JRequest::getVar( 'task', "" );
$id = JRequest::getInt( 'id', 0 ) ;
$my = &JFactory::getUser();
$Itemid = JRequest::getInt('Itemid');
$subscriber = JRequest::getVar( 'subscriber', '' );
$limit 		= JRequest::getInt( 'limit', $mainframe->getCfg('list_limit') ) ;
$limitstart = JRequest::getInt( 'limitstart', 0 ) ;

$communicator_rights = Array();
// Editor usertype check
$communicator_rights['is_editor'] = $is_editor = (strtolower($my->usertype) == 'editor' || strtolower($my->usertype) == 'publisher' || strtolower($my->usertype) == 'administrator' || strtolower($my->usertype) == 'super administrator' );
// Sender usertype check
$communicator_rights['is_sender'] = (strtolower($my->usertype) == 'manager' || strtolower($my->usertype) == 'administrator' || strtolower($my->usertype) == 'super administrator' );
// Who can delete?
$communicator_rights['can_delete'] = (strtolower($my->usertype) == 'administrator' || strtolower($my->usertype) == 'super administrator' );

$GLOBALS['Itemid'] = JRequest::getInt( 'Itemid' );
if($GLOBALS['Itemid']== "" ) {
	$database->setQuery ( "SELECT id FROM #__menu WHERE link LIKE '%com_communicator%'" );
	 $myid = $database->loadObject();
	if( !empty($myid->id))
		$GLOBALS['Itemid'] = $myid->id;
	else
		$GLOBALS['Itemid'] = 1;
}

$database->setQuery ( "SELECT name FROM #__menu WHERE id='$Itemid'" );
$menuname = $database->loadResult();

HTML_communicator::header();

switch ($task) {
	case 'view':
		showItem( $id, $my->gid, $is_editor, $pop, $option );
		// showItem ( $id );
		break;
		
	case "edit":
		if( $communicator_rights['is_editor'] )
			editNewsletter( $id, $option );
		break;

	case "save":
		if( $communicator_rights['is_editor'] )
			saveNewsletter( $option );
		break;
	  
	case "cancel":
		if( $communicator_rights['is_editor'] )
			cancelNewsletter( $option );
		break;  
		
	case "sendNow":
		if( $communicator_rights['is_sender'] ) {
			HTML_communicator::send_bar( $id );
			cm_sendNewsletter( $id, $option );
		}
		break;

	case "sendMail":
		if( $communicator_rights['is_sender'] )
			cm_sendMail();
		break;

	case "remove":
		if( $communicator_rights['can_delete'] )
			removeNewsletter( $id, $option );
		break;

	case 'subscribe': 
		HTML_communicator::subscribe( $subscriber); 
		break;
		
	case 'unsubscribe';
		HTML_communicator::unsubscribe( $subscriber);
		break;

	case 'confirm': 
		confirmSubscriber( $subscriber ); 
		break;
		
	default:
		HTML_communicator::subscriber_bar();
		if( $communicator_rights['is_editor'] ) {
			HTML_communicator::new_bar();
		}
		listAll( $communicator_rights );
}

HTML_communicator::footer();

function cm_email_check($email){
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("[^@]{1,64}@[^@]{1,255}", $email)) {
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
			return false;
		}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
				return false;
			}
		}
	}
	return true;
}
function extended_email_check( $email ) {
	$config =& JFactory::getConfig();
	require_once( JPATH_SITE.'/administrator/components/com_communicator/includes/email_validation.php');
	$validator =& new email_validation_class();
	$localdata = parse_url( JURI::base() );
	$tmp = explode( '@', $config->getValue('config.mailfrom') );
	$mailuser = $tmp[0];
	$mailserver = $tmp[1];
	
	/* how many seconds to wait before each attempt to connect to the
	   destination e-mail server */
	$validator->timeout=10;

	/* how many seconds to wait for data exchanged with the server.
	   set to a non zero value if the data timeout will be different
		 than the connection timeout. */
	$validator->data_timeout=0;

	/* user part of the e-mail address of the sending user
	   (info@phpclasses.org in this example) */
	$validator->localuser=$mailuser;

	/* domain part of the e-mail address of the sending user */
	$validator->localhost=$mailserver;

	/* Set to 1 if you want to output of the dialog with the
	   destination mail server */
	$validator->debug=0;

	/* Set to 1 if you want the debug output to be formatted to be
	displayed properly in a HTML page. */
	$validator->html_debug=1;


	/* When it is not possible to resolve the e-mail address of
	   destination server (MX record) eventually because the domain is
	   invalid, this class tries to resolve the domain address (A
	   record). If it fails, usually the resolver library assumes that
	   could be because the specified domain is just the subdomain
	   part. So, it appends the local default domain and tries to
	   resolve the resulting domain. It may happen that the local DNS
	   has an * for the A record, so any sub-domain is resolved to some
	   local IP address. This  prevents the class from figuring if the
	   specified e-mail address domain is valid. To avoid this problem,
	   just specify in this variable the local address that the
	   resolver library would return with gethostbyname() function for
	   invalid global domains that would be confused with valid local
	   domains. Here it can be either the domain name or its IP address. */
	$validator->exclude_address="";
	
	$result = $validator->ValidateEmailBox($email);
	
	return $result;
}

function listAll( $communicator_rights )
{
	global $mainframe, $menuname;
	
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getInt('Itemid');
	$option = JRequest::getVar('option');
	$gid = JRequest::getVar('gid');
	$my =& JFactory::getUser();
	
	$limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
	$limitstart = $mainframe->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );
	$now = JHTML::_('date', 'now', '%Y-%m-%d %H:%M:%S' );

	$sql = "SELECT id, subject, send, hits FROM `#__communicator`"
	."\nWHERE ";
	if( !$communicator_rights['is_editor'] )
		$sql.="\npublished=1 AND";
	
	$sql .= "\naccess <= $my->gid ";
	if( !$communicator_rights['is_editor'] ) {
		$sql .= "\nAND (publish_up = '0000-00-00 00:00:00' OR publish_up <= '$now') ";
		$sql .= "\nAND (publish_down = '0000-00-00 00:00:00' OR publish_down >= '$now')";
	}
	
	$database->setQuery( $sql );
	$database->query();
	$num_rows = $database->getNumRows();
	
	 jimport('joomla.html.pagination');
	 $pageNav = new JPagination( $num_rows, $limitstart, $limit );
	
	$sql .= "\nORDER BY created DESC";
	$sql .= "\nLIMIT $limitstart, $limit";
	$database->setQuery( $sql );
	
	$newsletters = $database->loadObjectList();
	
	echo $database->getErrorMsg();
	HTML_communicator::listAll( $menuname , $newsletters, $communicator_rights, $pageNav );

}

function showItem( $uid, $gid, $is_editor ) {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	$my = &JFactory::getUser();

	$now = JHTML::_('date', 'now', '%Y-%m-%d %H:%M:%S' );

	if ($is_editor) {
		$xwhere='';
	} 
	else {
		$xwhere = ""
		. "\n	AND published=1 "
		. "\n	AND (publish_up = '0000-00-00 00:00:00' OR publish_up <= '$now')"
		. "\n	AND (publish_down = '0000-00-00 00:00:00' OR publish_down >= '$now')"
		;
	}

	$sql = "SELECT id, subject AS title, send, created, hits, html_message AS text FROM #__communicator"
	."\nWHERE id=$uid $xwhere"
	."\nAND access <= $gid "
	."\nORDER BY created DESC";
	$database->setQuery( $sql );
	$row = null;
	if ($row = $database->loadObject()) {
	
		$item = new josCommunicator($database);
		$item->hit( $row->id );
		if( $my->id > 0) {
			$row->text = str_replace( "[NAME]", $my->name, $row->text );
		}
		HTML_communicator::showItem( $row, $gid );
		
	} 
	else {
		echo JText::_('You are not authorized to view this item');
		return;
	}
}
function saveSubscriber($name, $email){
	global $mainframe, $cm_params;
			
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getInt('Itemid');
	$my = &JFactory::getUser();
	$config =& Jfactory::getConfig();
	// Added to prevent spamming
	cm_SpoofCheck(NULL,1);
	
	$name = addslashes(strip_tags($name));
	$email = addslashes(strip_tags($email));
	
	$row = new josCommunicatorSubscribers( $database );
	
	if( $cm_params->get( 'extended_email_validation', '1') && function_exists('GetMXRR')) {
		if( !extended_email_check( $email ) ) {
			$mainframe->redirect( JRoute::_('index.php?option=com_communicator&amp;task=subscribe&amp;Itemid='.$Itemid), JText::_('Please provide a valid e-mail address.'));;
		}
	}
	else {
		if (!cm_email_check($email)) {
			$mainframe->redirect( JRoute::_('index.php?option=com_communicator&amp;task=subscribe&amp;Itemid='.$Itemid), JText::_('Please provide a valid e-mail address.'));
		}
	}
	// load the row from the db table
	$row->subscriber_id = "";
	$row->user_id = $my->id;
	$row->subscriber_name = $name;
	$row->subscriber_email = $email;
	$row->subscribe_date = date( "Y-m-d H:i:s" );
	
	if (!$row->store()) {
		echo "<script type=\"text/javascript\"> alert('".JText::_('Merci, aber du bist bereits angemeldet!')."'); window.history.go(-1); </script>\n";
	}
	else{
		$subscriberhash = md5($database->insertid());
		$subject = str_replace( "[mosConfig_live_site]", JURI::root(), JText::_('Your Newsletter Subscription at [mosConfig_live_site]'));
		$confirmlink = JURI::root()."index.php?option=com_communicator&task=confirm&subscriber=$subscriberhash";
		$content = str_replace( "[LINK]", $confirmlink, JText::_('CM_SUBSCRIBE_MESSAGE') );
		if( $my->id ) {
			$content = str_replace( "[NAME]", $my->name, $content );
		}
		else {
			$content = str_replace( "[NAME]", $name, $content );
		}
		$content = str_replace( "[mosConfig_live_site]", JURI::base(), $content );
		$send = false;
		if( !$send = JUtility::sendMail( $config->getValue('config.mailfrom'), $config->getValue('config.fromname'), $email, $subject, $content) ) {
		  echo '<script type="text/javascript">alert("'.JText::_('A subscribe message could not be sent:'). $send . '");</script>';
		}
		$mainframe->redirect( "index.php?option=com_communicator&Itemid=$Itemid", JText::_('Your email Address was added to our Newsletter.'));

	}
}

function deleteSubscriber($name, $email ){
	global $mainframe;
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getInt('Itemid');
	$my = &JFactory::getUser();
	$config =& Jfactory::getConfig();
	cm_SpoofCheck(NULL,1);
	if( $name != "" ) {
		$check = "SELECT user_id FROM #__communicator_subscribers WHERE subscriber_name = '" . $name . "' AND subscriber_email = '" . $email . "'";
	}
	else {
		$check = "SELECT user_id FROM #__communicator_subscribers WHERE subscriber_email = '" . $email . "'";
	}
	$database->setQuery( $check );
	$result = $database->loadObject();

	if( !$result ) {
		$mainframe->redirect( JRoute::_("index.php?option=com_communicator&Itemid=$Itemid"), JText::_('E-mail address cannot be found') );
	}
	else {
		if( $name != "" )
			$query = "DELETE FROM #__communicator_subscribers WHERE subscriber_name = '" . $name . "' AND subscriber_email = '" . $email . "'";
		else
			$query = "DELETE FROM #__communicator_subscribers WHERE subscriber_email = '" . $email . "'";
		$database->setQuery($query);
		$database->query();
		
		$subject = str_replace( "[mosConfig_live_site]", JURI::base(), JText::_('CM_UNSUBSCRIBE_SUBJECT') );
		$content = str_replace( "[NAME]", $name, JText::_('CM_UNSUBSCRIBE_MESSAGE') );
		$content = str_replace( "[mosConfig_live_site]", JURI::base(), $content );
		$send = false;
		if( !$send = JUtility::sendMail($config->getValue('config.mailfrom'), $config->getValue('config.fromname'), $email, $subject, $content) ) {
		  echo '<script type="text/javaScript">alert("'.JText::_('An unsubscribe message could not be sent:') . $send . '");</script>';
		}
		 
		$mainframe->redirect( "index.php?option=com_communicator&Itemid=$Itemid", JText::_('Your email Address was removed from our Newsletter'));
	}
}

function confirmSubscriber( $subscriber ){
	global $mainframe;
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getInt('Itemid');
	$subscriber = addslashes(strip_tags($subscriber));
	
	$database->setQuery( "SELECT confirmed FROM #__communicator_subscribers WHERE md5(subscriber_id) = '" . $subscriber . "'" );
	$result = $database->loadObject();
	
	if( $result ) {
		$query = "UPDATE #__communicator_subscribers SET confirmed = 1 WHERE md5(subscriber_id) = '" . $subscriber . "'";
		$database->setQuery($query);
		$database->query();
		$mainframe->redirect( "index.php?option=com_communicator&Itemid=$Itemid", JText::_('Your account has been successfully confirmed'));
	}
	else {
		$mainframe->redirect( "index.php?option=com_communicator&Itemid=$Itemid", JText::_('The Account associated with your Confirmation Link was not found.'));
	}
}

function editNewsletter( $uid, $option ) {
	global $mainframe;
	$database =& JFactory::getDBO();
	$my = &JFactory::getUser();
	$Itemid =& JRequest::getInt('Itemid');

	$row = new josCommunicator( $database );
	// load the row from the db table
	$row->load( $uid );
	
	if( !empty($row->checked_out)) {
		if( $row->checked_out != $my->id )
			$mainframe->redirect( "index.php?option=$option&Itemid=$Itemid", JText::_('You are not authorized to view this item') );
	}
	
	if ($uid) {
		$row->checkout( $my->id );
	} else {
		// initialise new record
		$row->published = 0;
	}

	// make the select list for the image positions
	$yesno[] = JHTML::_( 'select.option', '0', 'No' );
	$yesno[] = JHTML::_( 'select.option', '1', 'Yes' );

	// build the html select list
	$publist = JHTMLSelect::genericlist( $yesno, 'published', 'class="inputbox" size="2"',
	'value', 'text', $row->published );
	
	// get list of groups
	$database->setQuery( "SELECT id AS value, name AS text FROM #__groups ORDER BY id" );
	$groups = $database->loadObjectList();	if (!($orders = $database->loadObjectList())) {
		echo $database->stderr();
		return false;
	}

	// build the html select list
	$glist = JHTMLSelect::genericlist( $groups, 'access', 'class="inputbox" size="1"',
	'value', 'text', intval( $row->access ) );


	HTML_communicator::editNewsletter( $row, $publist, $option , $glist );
}

function saveNewsletter( $option ) {
	global $mainframe;
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getInt('Itemid');
	
	$row = new josCommunicator( $database );
	
	if (!$row->bind( $_POST )) {
		echo "<script type=\"text/javascript\"> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if (!$row->check()) {
		echo "<script type=\"text/javascript\"> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->store()) {
		echo "<script type=\"text/javascript\"> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();

	$mainframe->redirect( "index.php?option=$option&Itemid=$Itemid", JText::_('Item successfully saved.') );
}
/**
* Cancels an edit operation
* @param string The current url option
*/
function cancelNewsletter( $option ) {
	global $mainframe;
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getVar('Itemid');
	$row = new josCommunicator( $database );
	$row->bind( $_POST );
	$row->checkin();
	$mainframe->redirect( "index.php?option=$option&Itemid=$Itemid" );
}

/**
* Deletes one or more records
* @param array An array of unique category id numbers
* @param string The current url option
*/
function removeNewsletter( $id, $option ) {
	global $mainframe;
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getVar('Itemid');

	$item = new josCommunicator( $database );
	if (!$item->delete( $id )) {
		echo "<script type=\"text/javascript\"> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	}
	else {
		$mainframe->redirect( "index.php?option=$option&Itemid=$Itemid" );
	}
}

?>
