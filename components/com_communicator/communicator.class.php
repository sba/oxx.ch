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
 * @version $Id: communicator.class.php 361 2008-07-07 11:43:32Z Suami $
 */

defined('_JEXEC') or die('Restricted access');

/* Load the tooltip class */
JHTML::_('behavior.tooltip');

/**
*  Table Class
*
* Provides access to the mos_communicator table
*/
class josCommunicator extends JTable {
	/** @var int Unique id*/
	var $id=null;
	/** @var string */
	var $subject=null;
	/** @var string */
	var $headers=null;
	/** @var string */
	var $message=null;
	/** @var string */
	var $html_message=null;
	/** @var int */
	var $published=null;
	/** @var int */
	var $checked_out=null;
	/** @var datetime */
	var $checked_out_time=null;
	/** @var datetime */
	var $publish_up=null;
	/** @var datetime */
	var $publish_down=null;
	/** @var int */
	var $created=null;
	/** @var datetime */
	var $send=null;
	/** @var datetime */
	var $hits=null;
	/** @var int */
	var $access=null;
	/**
	* @param database A database connector object
	*/
	function __construct( &$database ) {
		global $cm_params;
		
		parent::__construct( '#__communicator', 'id', $database );
		
		if( !isset($GLOBALS['cm_params']) || !empty($_REQUEST['cm_params'])) {
			// load Communicator parameters
			$row =& JTable::getInstance( 'component' );
			$row->loadByOption( 'com_communicator' );
			jimport( 'joomla.application.component.helper' );
			$cm_params = JComponentHelper::getParams('com_communicator');
			$GLOBALS['cm_params'] = $cm_params;
			
			if( !$cm_params->get('newsletter_css', false)) {
				$database->setQuery('SELECT template FROM `#__templates_menu` WHERE client_id=0 ORDER BY menuid ASC LIMIT 0, 1');
				$cur_template = $database->loadResult();
				$template_css_file = JPATH_SITE."/templates/$cur_template/css/template_css.css";
				if( file_exists($template_css_file)) {
					
					$template_css = str_replace( "\r\n", "\n", file_get_contents( $template_css_file ));
					$txt = array();
					
					foreach (get_object_vars($cm_params->_registry['_default']['data']) as $k=>$v) {
						if ($k == 'newsletter_css') {
							$txt[] = "newsletter_css=".$template_css;
						}
						else $txt[] = "$k=$v";
					}
					
					$total = count( $txt );
					for( $i=0; $i < $total; $i++ ) {
						if ( strstr( $txt[$i], "\n" ) ) {
							$txt[$i] = str_replace( "\n", '<br />', $txt[$i] );
						}
					}
					
					$params = implode( "\n", $txt );
					$database->setQuery('UPDATE `#__components` SET params = \''.$params.'\' WHERE id = '.$row->id);
					$database->query();
				}
			}
		}
		
	}
	function check() {
		
		if( empty( $this->created ) ) {
			$this->created = date('Y-m-d H:i:s');
		}
		
		return true;
	}
}
/**
*  Table Class
*
* Provides access to the mos_communicator_subscribers table
*/
class josCommunicatorSubscribers extends JTable {
	/** @var int Unique id*/
	var $subscriber_id=null;
	/** @var string */
	var $subscriber_name=null;
	/** @var string */
	var $user_id=null;
	/** @var string */
	var $subscriber_email=null;
	/** @var int */
	var $confirmed=null;
	/** @var int */
	var $subscribe_date=null;
	/**
	* @param database A database connector object
	*/
	function __construct( &$database ) {
		parent::__construct( '#__communicator_subscribers', 'subscriber_id', $database );
	}
}
/**
 * Replaces all src attributes with a full URL to the live site
 * OR (when images are to be embedded) embeds the images into
 * the mail and changes the src attributes to a content id (=cid)
 * refrencing the encoded image in the mail body
 *
 * @param string $html_message
 * @param mosPHPMailer $mymail
 */
function cm_replaceImagesInMailBody( $html_message, &$mymail ) {
	global $cm_params;
	
	$embed_images = $cm_params->get('embed_images', 1);
	
	// Handle <img />Images and embed ALL images
	$images = array();
	if (preg_match_all("/<img[^>]*>/", $html_message, $images) > 0) {
		$i = 0;
		foreach ($images as $image) {
			if ( is_array( $image ) ) {
				foreach( $image as $src) {
					preg_match("'src=\"[^\"]*\"'si", $src, $matches);
					$source = str_replace ("src=\"", "", $matches[0]);

					$source = str_replace ("\"", "", $source);
					
					if( $embed_images ) {
						$filename = basename( $source );
						// must be a remote Image or somethin with ../../../image.gif then
						if (!stristr($source, JURI::base())) {
	
							// must be a local image.
							// Attention! Now we guess it's located somewhere in the folder /images/ !!!
							if (!stristr($source, "http")) {
								// convert "media/mypicture.gif" to "/home/user/public_html/media/mypicture.gif"
								$tmp_source = JPATH_SITE."/$source";
								if( !file_exists($tmp_source)) {
									// IN /ADMINISTRATOR/ then!
									// convert "images/mypicture.gif" to "/home/user/public_html/administrator/images/mypicture.gif"					
									$tmp_source = JPATH_SITE."/administrator/$source";
									if( !file_exists($tmp_source)) {
										// leave the URL unchanged (we don't know where to find the image here!)
										continue;
									}
								}
								$source = $tmp_source;
							}
							else {
								// remote pictures are left unchanged
								continue;
							}
						}
						else {
							$source = str_replace( JURI::base(), JPATH_SITE, $source );
						}
						$pathinfo  = pathinfo( $filename );
						$cid = basename( $filename, ".".$pathinfo['extension'] );
						$size = @getimagesize( $source );
	
						switch($pathinfo['extension']) {
							case "jpg":
							case "jpeg":
							$mimetype = "image/jpeg"; break;
							case "png":
							$mimetype = "image/png"; break;
							case "gif":
							$mimetype = "image/gif"; break;
							case "swf":
							$mimetype = "image/swf"; break;
						}
						$mymail->AddEmbeddedImage( $source, $cid, $filename, "base64", $mimetype );
						$newtag = $size[3] ." src=\"cid:$cid\"";
						$html_message = str_replace( $matches[0], $newtag, $html_message );
					}
					else {
						if (!stristr($source, JPATH_SITE)) {
							if( substr($source, 0, 3) == '../') {
								$source = str_replace('../', '', $source);
							}
							// must be a remote Image or somethin with ../images/stories/image.jpg then
							$source = JPATH_SITE."/$source";
							$html_message = str_replace( $matches[0], "src=\"$source\"", $html_message );
						}
					}
				}
			}
		}
	}
	return $html_message;
}


/**
 * Sends out the mailing using mosPHPMailer
 *
 */
function cm_sendMail() {

	global $mainframe, $cm_params;
	
	$database =& JFactory::getDBO();
	$user = &JFactory::getUser(); 
	
	$config =& Jfactory::getConfig(); 
	
	/*
	* because sending mail may take a long time, we want to disable timeout
	* unfortunately when you are running php in safe mode you cannot use set_time_limit(0)
	* that's why the disable-timout is optional.
	*/
	
	$mails_per_pageload  = JRequest::getInt( "mails_per_pageload", 100 );
	$startfrom  = JRequest::getInt( "startfrom", 0 );
	$option  = JRequest::getVar( "option", 'com_communicator' );
	$disable_timeout  = JRequest::getVar( "disable_timeout", '' );
	$id  = JRequest::getVar( "id", '' );
	$sendto = JRequest::getVar( "sendto", null );
	$mailfrom =JRequest::getVar( "mailfrom", $config->getValue('config.mailfrom'));
	$confirmed_accounts = JRequest::getVar( "confirmed_accounts", "0" );
	$replyto = JRequest::getVar( "replyto" );
	
	if ( $disable_timeout ) {
		@set_time_limit(0);
	}
	// Get default emailaddress
	$database->setQuery( "SELECT `email` FROM `#__users` WHERE usertype='superadministrator' LIMIT 0,1");
	$admin_email = $database->loadResult();
	echo $database->getErrorMsg();
	
	$mailfrom = $mailfrom ? $mailfrom : $admin_email;
	$replyto = $replyto ? $replyto : $admin_email;

	if ( $sendto===null) {
		$mainframe->redirect( $_SERVER['PHP_SELF'].'?option=com_communicatr&mosmsg='.CM_ERROR_NEWSLETTER_COULDNTBESENT );
	}
	// Get Itemid for Communicator
	$database->setQuery ( "SELECT `id` FROM `#__menu` WHERE link LIKE '%com_communicator%'" );
	$myid = $database->loadObject();
	if( !empty($myid->id)) {
		$Itemid = $myid->id;
	}
	else {
		$Itemid = 1;
	}

	// Get newsletter
	$database->setQuery( "SELECT subject, message, html_message FROM `#__communicator` WHERE id='$id'");
	$newsletter = $database->loadObject();

	// Build e-mail message format
	$subject = $newsletter->subject;
	$message = stripslashes($newsletter->message);
	$unsub_link = JURI::root()."index.php?option=$option&task=unsubscribe&Itemid=$Itemid";
	if( substr( $unsub_link, 0, 4 ) != "http" ) {
		$unsub_link = JURI::base() ."/". $unsub_link;
	}
	$unsub_link_html = "<a href=\"$unsub_link\">$unsub_link</a>";

	$footer_html = str_replace( "[UNLINK]", $unsub_link_html, JText::_('CM_NEWSLETTER_FOOTER') );
	$footer_html = str_replace( "[mosConfig_live_site]", "<a href=\"".JURI::root()."\">".JURI::root()."</a>", $footer_html );

	$footer_text = str_replace( "[UNLINK]", $unsub_link, JText::_('CM_NEWSLETTER_FOOTER') );
	$footer_text = str_replace( "[mosConfig_live_site]",JURI::root(), $footer_text );
	$footer_text = str_replace( "<br/>", "\n", $footer_text );
	$footer_text = str_replace( "<br />", "\n", $footer_text );
	
	// Prevent HTML tags in the text message
	$footer_text = strip_tags( $footer_text );
	
	$html_message = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
					  <html>
						  <head>
							  <title>".$mainframe->getCfg( 'sitename' )." :: $subject</title>
							  <base href=\"".JURI::root()."/\" />
							  <style type=\"text/css\">
							  ".strip_tags( $cm_params->get('newsletter_css'))."
							  </style>
						  </head>
						  <body>"
							. stripslashes($newsletter->html_message)
							. "<p>$footer_html</p>" 
							. '<p style="font-size:8px;color:grey;">'.JText::_('CMN_EMAIL').': [EMAIL]</p>'
							. "</body>
					  </html>";
	$html_message = str_replace( "../../..", JURI::root(), $html_message );
	
	// Create the PHPMailer Object ( we need that NOW! )
	$mymail = &JFactory::getMailer();
	$mymail->setSender(array($mailfrom, $config->getValue('config.fromname')));
	$mymail->setSubject($subject);
	$mymail->setBody($html_message);
	// Patch to get correct Line Endings
	switch( substr( strtoupper( PHP_OS ), 0, 3 ) ) {
		case "WIN":
			$mymail->LE = "\r\n";
			break;
		case "MAC": // fallthrough
		case "DAR": // Does PHP_OS return 'Macintosh' or 'Darwin' ?
			$mymail->LE = "\r";
		default: // change nothing
			break;
	}
	
	// $mymail->Encoding = 'base64';
	
	$mymail->addReplyTo( array($replyto, $config->getValue('config.fromname')) );
	
	$html_message = cm_replaceImagesInMailBody( $html_message, $mymail );
	
	// Handle Attachments
	$regex = '#\[ATTACHMENT filename="(.*?)"\]#si';
	$attachments = array();
	if (preg_match_all($regex, $message, $attachments) > 0) {
		
		foreach ($attachments[1] as $idx => $attachment ) {
			//$mymail->AddAttachment( JURI::base(true).substr($attachment, 1) );
			$mymail->AddAttachment( JPATH_SITE.$attachment );
		}
	}
	$message = preg_replace( $regex, '', $message );
	
	// Get all users email and group
	if( $sendto == "subscribers" ) {
		$q = "SELECT subscriber_name AS name, subscriber_email AS email FROM #__communicator_subscribers";
		if( $confirmed_accounts == "1" ) {
			$q .= " WHERE confirmed='1'";
		}
	}
	// Currenly supported: VirtueMart and mambo-phpShop customers
	elseif( $sendto == 'customers') {
		if( file_exists(JPATH_SITE.'/components/com_virtuemart/virtuemart_parser.php')) {
			$shop = "virtuemart";
			// the configuration file for the Shop
			//require_once( JPATH_SITE. "/administrator/components/com_$shop/$shop.cfg.php" );
			$q = 'SELECT user_email as email, CONCAT( first_name, \' \', last_name) as name FROM `#__{vm}_user_info` WHERE address_type=\'BT\'';
			
			// This is just needed for VirtueMart's table prefix vm
			require_once( CLASSPATH. 'ps_database.php' );
			$db = new ps_DB();
			$db->setQuery( $q );
			$q = $db->_sql;
		}
		elseif( file_exists(JPATH_SITE.'/components/com_phpshop/phpshop_parser.php')) {
			$shop = 'phpshop';
			// the configuration file for the Shop
			require_once( JPATH_SITE. "/administrator/components/com_$shop/$shop.cfg.php" );
			$q = 'SELECT email, CONCAT( first_name, \' \',  last_name) as name FROM `#__users` WHERE address_type=\'BT\'';			
		}
	}
	//Marlar's CB integration
	elseif( substr($sendto,0,3) == 'cb:' ) {
		$sendto=substr($sendto, 3);
		$database->setQuery("SHOW COLUMNS FROM `#__comprofiler` LIKE '$sendto'");
		if(is_null($database->loadRow())){
			  echo "<div style='font-size:15px; width:700px;border: 2px solid black; padding: 30px 10px; margin: 50px;'>I can't find the specified field '$sendto' in Community Builder to control email sending!<br><br>";
			  echo "Please go to Community Builder -&gt; Field Management and create a Check Box (single) named <b>$sendto</b></div>";
			  die;
		}
		$q = "SELECT CONCAT_WS(' ', firstname, middlename, lastname) AS name, email FROM `#__comprofiler` t1 INNER JOIN `#__users` t2 ON t1.user_id=t2.id WHERE $sendto=1";
		//Marlar's CB hack end
	}
	else {
		$query_appendix = ", #__core_acl_aro, #__core_acl_groups_aro_map WHERE #__core_acl_aro.value=#__users.id AND #__core_acl_groups_aro_map.aro_id = #__core_acl_aro.aro_id AND #__core_acl_groups_aro_map.group_id='$sendto'";
		$q = "SELECT #__users.name, email FROM #__users ";
		$q .= ($sendto !== '0') ? $query_appendix : "";
		
	}
	$database->setQuery( $q );
	$database->query();
	$all_rows = $database->getNumRows();
	
	echo $database->getErrorMsg();

	$q .= " LIMIT $startfrom, $mails_per_pageload";
	$database->setQuery( $q );
		
	// Now process all Recipients
	$i = 0;
	$errors = 0;
	
	// Send individual newsletters for each recipient
	$rows = $database->loadObjectList();
	foreach ($rows as $row) {
		if( empty($row->name) ) {
			$name = JText::_('CM_SUBSCRIBER');
		}
		else {
			$name = $row->name;
		}
		// Now let's update the HTML Mail Body
		// embed the subscriber / user name
		$mymail->Body = str_replace( "[NAME]", $name, $html_message);
		
		// embed the email address this newsletter is sent to
		$mymail->Body = str_replace( "[EMAIL]", $row->email, $mymail->Body);
		
		// Set alternative Body with Text Message
		$mymail->AltBody = str_replace( "[NAME]", $name, $message . $footer_text );

		$mymail->ClearAddresses();
		$mymail->AddAddress( $row->email, $row->name );

		//Send email
		if( $mymail->Send()) {
			$i++;
		}
		else {
			$i++; // Added to prevent double mailings when errors occur
			$errors++;
		}
	}
	
	$msg = '';
	if( $errors > 0) {
		$msg = $mymail->ErrorInfo." =&gt; $errors Errors
";
	}
	$database->setQuery( "UPDATE `#__communicator` SET send=NOW() WHERE id=$id" );
	$database->query();
	$msg .= str_replace( "{X}", $i-$errors, JText::_('CM_NEWSLETTER_SENDTO_X_USERS'));
	if( $startfrom+$i >= $all_rows  ) {
		$mainframe->redirect( $_SERVER['PHP_SELF']."?option=$option", $msg );
	}
	else {
		HTML_communicator::sendMailInfo( $all_rows, $startfrom+$mails_per_pageload, $msg );
	}

}

/**
 * Prints out a form to prepare the sending of a newsletter
 *
 * @param int $uid The Newsletter ID
 * @param string $option
 */
function cm_sendNewsletter ( $uid, $option ) {
	$database =& JFactory::getDBO();
	$my =& JFactory::getUser();
	$language =& Jfactory::getLanguage();
	$lang = $language->getBackwardLang();
	
	// Get default emailaddress
	$database->setQuery( "SELECT `email` FROM `#__users` WHERE usertype='superadministrator' OR gid=25 LIMIT 0,1");
	$row = $database->loadObjectList();
	$admin_email = $row[0]->email;

	$row = new josCommunicator( $database );
	// load the row from the db table
	$row->load( $uid );

	// get list of groups
	$groups = array( JHTML::_( 'select.option', "subscribers", '- All Subscribers -' ) );
	if( file_exists(JPATH_SITE.'/components/com_virtuemart/virtuemart_parser.php')) {
		$shop = "virtuemart";
	}
	elseif( file_exists(JPATH_SITE.'/components/com_phpshop/phpshop_parser.php')) {
		$shop = 'phpshop';
	}
	else {
		$shop = '';
	}
	if( $shop != '') {
		// the configuration file for the Shop
		// require_once( JPATH_SITE. "/administrator/components/com_$shop/$shop.cfg.php" );
		$groups[] = JHTML::_( 'select.option', '- - - - - - - -', '- - - - - - - -' );
		$groups[] = JHTML::_( 'select.option', "customers", '- '.JText::_('PHPSHOP_STATISTIC_CUSTOMERS').' ('.$shop.') -' );
	}
	// CB integration
	if( file_exists(JPATH_SITE.'/components/com_comprofiler/comprofiler.php')) {
		$database->setQuery("SELECT name FROM `#__comprofiler_fields` where type = 'checkbox' and published=1");
		$result = $database->loadResultArray();
		if(is_array($result)) {
			$groups[] = JHTML::_( 'select.option', '- - - - - - - -', '- - - - - - - -' );
			foreach($result as $r) {
				$groups = array_merge($groups, array(JHTML::_( 'select.option', "cb:$r", "- Community Builder, field $r -" )));
			}
		}
	}
	
	$groups[] = JHTML::_( 'select.option', '- - - - - - - -', '- - - - - - - -' );
	$groups[] = JHTML::_( 'select.option', 0, '- All User Groups -' );
	$groups[] = JHTML::_( 'select.option', '- - - - - - - -', '- - - - - - - -' );
	$database->setQuery( "SELECT id AS value, name AS text FROM #__core_acl_aro_groups WHERE id<>17 AND id NOT in(28,29,30) ORDER BY id" );
	$groups = array_merge( $groups, $database->loadObjectList() );
	
	
	// build the html select list
	$grouplist = JHTMLSelect::genericlist( $groups, 'sendto', 'class="inputbox" size="1"',
	'value', 'text', '-1' );
	// manually disable options. This is MISSING IN mosHTML::selectList (core team, read this!!)
	$grouplist = str_replace( '"- - - - - - - -"', '"- - - - - - - -" disabled="disabled"', $grouplist);
	
	HTML_communicator::sendNewsletter( $row, $option , $grouplist, $admin_email );
}

/**
 * Equivalent to Joomla's josSpoofCheck function
 * @author Joomla core team
 *
 * @param boolean $header
 * @param unknown_type $alt
 */
function cm_SpoofCheck( $header=NULL, $alt=NULL ) {	
	$validate 	= JRequest::getVar(cm_SpoofValue($alt), 0 );
	echo $validate;
	// probably a spoofing attack
	/*if (!$validate) {
		header( 'HTTP/1.0 403 Forbidden' );
		JError( JText::_('NOT_AUTH') );
		return;
	}*/
	
	// First, make sure the form was posted from a browser.
	// For basic web-forms, we don't care about anything
	// other than requests from a browser:   
	if (!isset( $_SERVER['HTTP_USER_AGENT'] )) {
		header( 'HTTP/1.0 403 Forbidden' );
		JError( JText::_('NOT_AUTH') );
		return;
	}
	
	// Make sure the form was indeed POST'ed:
	//  (requires your html form to use: action="post")
	if (!$_SERVER['REQUEST_METHOD'] == 'POST' ) {
		header( 'HTTP/1.0 403 Forbidden' );
		JError( JText::_('NOT_AUTH') );
		return;
	}
	
	if ($header) {
	// Attempt to defend against header injections:
		$badStrings = array(
			'Content-Type:',
			'MIME-Version:',
			'Content-Transfer-Encoding:',
			'bcc:',
			'cc:'
		);
		
		// Loop through each POST'ed value and test if it contains
		// one of the $badStrings:
		foreach ($_POST as $k => $v){
			foreach ($badStrings as $v2) {
				if (strpos( $v, $v2 ) !== false) {
					header( "HTTP/1.0 403 Forbidden" );
					JError( JText::_('NOT_AUTH') );
					return;
				}
			}
		}   
		
		// Made it past spammer test, free up some memory
		// and continue rest of script:   
		unset($k, $v, $v2, $badStrings);
	}
}
/**
 * Equivalent to Joomla's josSpoofValue function
 *
 * @param boolean $alt
 * @return string Validation Hash
 */
function cm_SpoofValue($alt=NULL) {
	global $mainframe;
	
	if ($alt) {
		$random		= date( 'Ymd' );
	} else {		
		$random		= date( 'dmY' );
	}
	$validate 	= JUtility::getHash($mainframe->getCfg( 'db' ).$random);
	
	return $validate;
}

?>
