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
 * @version $Id: communicator.html.php 361 2008-07-07 11:43:32Z Suami $
 */

defined('_JEXEC') or die('Restricted access');

class HTML_communicator {

	function listAll ($menuname, &$rows, $communicator_rights, $pageNav ) {
	$Itemid = JRequest::getInt('Itemid');
	?>
	<form name="adminForm" action="index.php" method="post">
	
	<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center">
		<tr>
			<td colspan="4"><?php echo $menuname; ?></td>
			<td align="right" nowrap="nowrap">
				<?php
				echo '&nbsp;&nbsp;&nbsp;'. JText::_('Display #') .'&nbsp;';
				$link = 'index.php?option=com_communicator&amp;Itemid='. $Itemid;
				echo $pageNav->getLimitBox( $link );
				?>
			</td>
		</tr>
	<?php 
	if (count ($rows )) { ?>
		<tr>
		  <td colspan="2" class="sectiontableheader"><?php echo JText::_('Subject:'); ?></td>
		  <td class="sectiontableheader"><?php echo JText::_('Start Publishing:');?></td>
		  <td align="left" class="sectiontableheader"><?php echo JText::_('Hits');?></td>
		  <td style="width: 32px;" align="center" class="sectiontableheader"><?php echo JText::_('Delete');?></td>
		</tr>
<?php
		$k = 0;
		$tabclass = array("sectiontableentry1", "sectiontableentry2");
		foreach ($rows as $row) {
			$k = $k ? 0 : 1 ;
		?>
			<tr class="<?php echo $tabclass[$k]; ?>">
			  <td colspan="2">
			  <a href="<?php echo JRoute::_("index.php?option=com_communicator&task=view&Itemid=$Itemid&id=$row->id"); ?>"><?php echo $row->subject; ?></a>
		  <?php
		  if( $communicator_rights['is_editor'] ) { ?>
			  &nbsp;&nbsp;<a title="<?php echo JText::_('Edit') ?>" href="<?php echo JRoute::_("index.php?option=com_communicator&amp;task=edit&Itemid=$Itemid&id=$row->id"); ?>">
			  <img src="<?php echo JURI::base(); ?>images/M_images/edit.png" align="center" border="0" alt="" /></a>
		  <?php 
		  }
		  if( $communicator_rights['is_sender'] ) { ?>
			  &nbsp;&nbsp;<a title="<?php echo JText::_('Send') ?>" href="<?php echo JRoute::_("index.php?option=com_communicator&amp;task=sendNow&Itemid=$Itemid&id=$row->id"); ?>">
			  <img src="<?php echo JURI::base(); ?>media/com_communicator/images/mail_send.png" class="mail_send" alt="<?php echo JText::_('Send') ?>" /></a>
		  <?php 
		  }
		  ?>
			  </td>
			  <td><?php echo $row->send;?></td>
			  <td align="center"><?php echo $row->hits;?></td>
			  <td style="width: 32px;" align="center"><?php
		  if( $communicator_rights['can_delete'] ) { ?>
			  <form name="deleteForm<?php echo $row->id;?>" action="index.php?option=com_communicator" method="post">
			  <input type="hidden" name="id" value="<?php echo $row->id ?>" />
			  <input type="hidden" name="task" value="remove" />
			  <input type="hidden" name="Itemid" value="<?php echo $Itemid ?>" />
			  </form>
			  &nbsp;&nbsp;<a title="<?php echo JText::_('Delete') ?>" href="javascript: if( confirm('<?php echo JText::_('Are you sure you want to delete selected item?'); ?>')) { document.deleteForm<?php echo $row->id;?>.submit(); }">
			  <img src="<?php echo JUri::root(); ?>media/com_communicator/images/delete.png" align="center" border="0" height="22" width="22" alt="<?php echo JText::_('Delete') ?>" /></a>
			  
		  <?php 
		  } ?>&nbsp;</td>
			</tr>

		<?php  
		} ?>
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td align="center" colspan="5" class="sectiontablefooter">
			<?php
			$link = 'index.php?option=com_communicator&amp;Itemid='. $Itemid;
			echo $pageNav->getPagesLinks( $link );
			?>
			</td>
		</tr>
		<tr>
			<td colspan="5" align="center">
			<?php echo $pageNav->getPagesCounter(); ?>
			</td>
		</tr>
	<?php 
	} 
	else { ?>
		<tr><td colspan="5"><?php echo JText::_('No results were found'); ?></td></tr>
	<?php 
	} ?>
	</table>
	<input type="hidden" name="option" value="com_communicator" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
	</form>
	<?php

	}
function editNewsletter( &$row, &$publist, $option , $glist ) {
	global $mainframe;
	$savetext = '';
	$results = $mainframe->triggerEvent( 'onGetEditorContents', array( "html_message", "html_message" ) );
	$editor =& JFactory::getEditor();
	$Itemid = JRequest::getInt('Itemid');
	foreach ($results as $result) {
		if (trim($result)) {
			$savetext .= $result;
		}
	}
	// Add the Calendar includes to the document <head> section
	JHTML::_('behavior.calendar');
	  ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			try {
			document.adminForm.onsubmit();
			}
			catch(e){}
			if (form.subject.value == ""){
				alert( "Newsletter must have a subject" );
			} 
			else {
				<?php echo $savetext ?>
				submitform( pressbutton );
			}
		}
		</script>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td class="contentheading" ><?php echo $row->id ? JText::_('Edit') : JText::_('Add'); echo ": ".JText::_('Newsletter Item'); ?></td>
			<td>
				<a href="#" onclick="javascript: submitbutton('save')">
					<img name="new" src="<?php echo JURI::base(); ?>/administrator/images/save_f2.png" height="32" width="32" border="0" />
				<?php echo JText::_('Save'); ?>
				</a>
			</td>
			<td>&nbsp;</td>
			<td width="25%">
				<a href="#" onclick="javascript: submitbutton('cancel')">
					<img name="new" src="<?php echo JURI::base(); ?>/administrator/images/cancel_f2.png" height="32" width="32" border="0" />
					<?php echo JText::_('Cancel'); ?>
				</a>
			</td>
		</tr>
	</table>
		<br/><br/>
	<form action="index.php" method="post" name="adminForm">

		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
			<tr>
				<td width="200"><div style="font-weight:bold;text-align:right"><?php echo JText::_('Subject:') ?></div></td>
				<td><input class="inputbox" type="text" name="subject" size="25" value="<?php echo $row->subject; ?>" style="width:500px" ></td>
			</tr>

			<tr>
				<td valign="top"><div style="font-weight:bold;text-align:right"><?php echo JText::_('Message (HTML-WYSIWYG)').": </div><br/>".JText::_('CM_NAME_TAG_USAGE') ?></td>
				<td><?php
				echo $editor->display( "html_message", str_replace('&','&amp;',$row->html_message), 500, 300, 70, 20 );
			  
				?>
				</td>
			</tr>

			<tr>
				<td valign="top"><div style="font-weight:bold;text-align:right"><?php echo JText::_('alternative Text Message').": </div><br/><br/>".JText::_('CM_NAME_TAG_USAGE') ?></td>
				<td><textarea name="message" cols="70" rows="20" style="width:500px; height:300px;"><?php echo str_replace('&','&amp;',$row->message); ?></textarea>
				</td>
			</tr>
			<tr>
				<td valign="top"><div style="font-weight:bold;text-align:right"><?php echo JText::_('Published') ?>:</div></td>
				<td>
					<?php echo $publist; ?>
				</td>
			</tr>
			<tr>
				<td><div style="font-weight:bold;text-align:right"><?php echo JText::_('State:'); ?></div></td>
				<td><?php 
				if ($row->published == "1") {
				  echo JText::_('Published');
				} 
				else {
				  echo JText::_('Unpublished');
				}
						?>
					</td>
			</tr>
			<tr>
				<td><div style="font-weight:bold;text-align:right"><?php echo JText::_('Access Level:'); ?></div></td>
				<td> <?php echo $glist; ?> </td>
			</tr>
			<tr>
				<td><div style="font-weight:bold;text-align:right"><?php echo JText::_('Start Publishing:'); ?></div></td>
				<td><?php echo JHTML::_('calendar', $row->publish_up, 'publish_up', 'publish_up', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?></td>
			</tr>
			<tr>
				<td><div style="font-weight:bold;text-align:right"><?php echo JText::_('Finish Publishing:'); ?></div></td>
				<td><?php echo JHTML::_('calendar', $row->publish_down, 'publish_down', 'publish_down', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?></td>
			</tr>
		</table>


	<?php if (!$row->id) { ?>
			<input type="hidden" name="created" value="<?php echo date('Y-m-d H:i:s'); ?>" />
	<?php }
		  else { ?>
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<?php }
	?>
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
			</form>
	<?php 
  }
  
	function showItem( $row ) {
	  
	  ?><div class="componentheading">Newsletter Item <a href="javascript: history.back()">&nbsp;<?php echo JText::_('Back'); ?></a></div>
	  <div align="right" class="createdate">Date: <?php echo $row->created ?></div>
	  <div><strong><?php echo $row->title; ?></strong></div><br/>
	  <div><?php echo $row->text; ?></div>
	<?php
	
	}
	
	function sendNewsletter( &$row, $option , $grouplist, $admin_email ) {
		global $cm_params;
		$Itemid = JRequest::getInt('Itemid');
	  ?> 
	  <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>		  
	  <script language="Javascript" src="includes/js/overlib_mini.js"></script>
	  <table cellpadding="4" cellspacing="0" border="0" width="100%">
		  <tr>
			<td class="contentheading" ><?php echo JText::_('Send Newsletter') ?></td>
		  </tr>
		</table>
	  <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="adminForm">

		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
			<tr>
				<td width="250"><strong><?php echo JText::_('Send to group') ?>:</strong></td>
				<td width="85%"><?php echo $grouplist; ?></td>
			</tr>
			<tr>
				<td width="250"><strong><?php echo JText::_('Only confirmed Accounts?') ?></strong></td>
				<td width="85%"><input type="checkbox" name="confirmed_accounts" value="1">
				<?php echo JHTML::_('tooltip', JText::_('Confirmed accounts tip') ) ?></td>
			</tr>
			<tr>
				<td><strong><?php echo JText::_('Mail from') ?>:</strong></td>
				<td><input class="inputbox" type="text" name="mailfrom" size="25" value="<?php echo $admin_email; ?>" style="width:200px" ></td>
			</tr>
			<tr>
				<td><strong><?php echo JText::_('Reply to') ?>:</strong></td>
				<td><input class="inputbox" type="text" name="replyto" size="25" value="<?php echo $admin_email; ?>" style="width:200px" ></td>
			</tr>
			<tr>
				<td width="250"><strong><?php echo JText::_('Disable timeout') ?>:</strong></td>
				<td width="85%"> <input type="checkbox" checked="checked" name="disable_timeout" value="1">
				<?php echo JHTML::_('tooltip', JText::_('Disable timeout tip') ) ?></td>
			</tr>
			<?php
			if( strstr( $row->html_message, '[NAME]') === false && strstr( $row->message, '[NAME]') === false) {
				$mails_per_pageload= $cm_params->get( 'normal_mails_per_pageload', 500 );
			}
			else {
				$mails_per_pageload = $cm_params->get('personalized_mails_per_pageload' , 100 );
			}
				?>
			<tr>
				<td style="text-align:right;" width="250"><label for="mails_per_pageload"><strong><?php echo JText::_('How many mails to send at once?') ?>:</strong></label></td>
				<td width="85%"><input type="text" id="mails_per_pageload" name="mails_per_pageload" value="<?php echo $mails_per_pageload ?>" /></td>
			</tr>
			
			<tr><td colspan="2"><hr/></td></tr>
			
			<tr>
				<td><strong><?php echo JText::_('Subject:') ?></strong></td>
				<td><?php echo $row->subject; ?></td>
			</tr>
			<tr>
				<td valign="top"><strong><?php echo JText::_('MESSAGE (HTML-WYSIWYG)') ?>:</strong></td>
				<td valign="top"><?php echo $row->html_message; ?></td>
			</tr>
			<tr>
				<td valign="top"><strong><?php echo JText::_('Message (HTML-source)') ?>:</strong></td>
				<td valign="top"><?php echo htmlspecialchars($row->html_message); ?></td>
			</tr>
			<tr>
				<td valign="top"><strong><?php echo JText::_('Alternative Text Message') ?>:</strong></td>
				<td valign="top"><?php echo htmlspecialchars($row->message); ?></td>
			</tr>
		</table>
		
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
		</form>
<?php
	}
	

	function sendMailInfo( $all_rows, $startfrom, $msg ) {
		$config =& Jfactory::getConfig(); 
		$mails_per_pageload  = JRequest::getInt( "mails_per_pageload", 100 );
		$option  = JRequest::getVar( "option", 'com_communicator' );
		$disable_timeout  = JRequest::getVar( "disable_timeout", '' );
		$id  = JRequest::getVar( "id", '' );
		$sendto = JRequest::getVar( "sendto", null );
		$mailfrom = JRequest::getVar( "mailfrom", $config->getValue('config.mailfrom') );
		$confirmed_accounts = JRequest::getVar( "confirmed_accounts", "0" );
		$replyto = JRequest::getVar( "replyto", false );
		
		echo '<h3>'.JText::_('Communicator Newsletter Send Log').'</h3>
		<pre>'. $msg .'</pre>
		<p><strong>'.sprintf( JText::_('%s of %s mails have been sent so far.'), $startfrom , $all_rows ).'<strong></p>
		<p>'.sprintf( JText::_('Click the button to send the next %s Mails.'), $mails_per_pageload ) .'</p>
		<br/>
		<br/>
		<form action="'. $_SERVER['PHP_SELF'] .'" method="post" name="adminForm">
		'.JText::_('Change the mails-per-step amount').': <input type="text" name="mails_per_pageload" value="'. $mails_per_pageload .'" size="4" />
		<br />
		<br />
			<input type="hidden" name="startfrom" value="'. $startfrom .'" />
			<input type="hidden" name="disable_timeout" value="'. $disable_timeout .'" />
			<input type="hidden" name="id" value="'. $id .'" />
			<input type="hidden" name="sendto" value="'. $sendto .'" />
			<input type="hidden" name="mailfrom" value="'. $mailfrom .'" />
			<input type="hidden" name="confirmed_accounts" value="'. $confirmed_accounts .'" />
			<input type="hidden" name="replyto" value="'. $replyto .'" />
			<input type="hidden" name="task" value="sendMail" />
			<input type="hidden" name="option" value="'. $option .'" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input style="font-weight:bold;" type="submit" name="send" value="&nbsp;&nbsp;&nbsp;&nbsp;'. JText::_('Send') .'&nbsp;&nbsp;&nbsp;&nbsp;" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" name="abort" value="'. JText::_('Cancel') .'" onclick="if( confirm( \''.JText::_('Do you really want to abort sending this newsletter?').'\' )) { document.location=\''. $_SERVER['PHP_SELF']  .'?option=com_communicator\'; }" />
		</form>
		<br/><br/>';
		
	}
	
	function subscribe(){
		global $mainframe;
	  $database =& JFactory::getDBO();
	  $Itemid = JRequest::getInt('Itemid');
	  $my = &JFactory::getUser(); 
	  $check = "SELECT id FROM #__users, #__communicator_subscribers WHERE user_id=id AND user_id='".$my->id."'";
	  $database->setQuery( $check );
	  $result = $database->loadObject();
	  
	  $name = addslashes(JRequest::getVar('subscriber_name','Subscriber' ));
	  $email = addslashes(JRequest::getVar('email'));
	  
	  if( !$result ) {
		if(!empty($email)){
			saveSubscriber( $name, $email);
		}
		else {
			//new subscriber
			HTML_communicator::showField( "subscribe");
		}
	  }
	  else {
		$mainframe->redirect( "index.php?option=com_communicator&Itemid=$Itemid" , JText::_('Youre already subscribed to our Newsletters.') );
	  }
	}
	
	function unsubscribe($subscriber){
	  global $mainframe;
	  $database =& JFactory::getDBO();
	  $Itemid = JRequest::getInt('Itemid');
	  $my = &JFactory::getUser();
	  $name = addslashes(JRequest::getVar('subscriber_name'));
	  $email = addslashes(JRequest::getVar('email'));
	  
	  if( !empty($email) ) {
		$check = "SELECT subscriber_name FROM #__communicator_subscribers WHERE subscriber_email='$email'";
		$database->setQuery( $check );
		$result = $database->loadObject();
		if( $result ) {
			//delete
			deleteSubscriber( $result->subscriber_name, $email );
		}
		else {
		  $mainframe->redirect( "index.php?option=com_communicator&Itemid=$Itemid" , JText::_('E-mail address cannot be found') );
		}
	  }
	  else{
		//new subscriber
		HTML_communicator::showField("unsubscribe", $subscriber);
	  }
	  
	}
	
	function showField( $action){
		global $letters;
		$database =& JFactory::getDBO();
		$Itemid = JRequest::getInt('Itemid');
		$my = &JFactory::getUser();
		
		if( $my->id ) {
		  if( $action == "subscribe") {
			$query="SELECT name as subscriber_name, email as subscriber_email FROM #__users WHERE id = '" . $my->id . "'";
			$database->setQuery($query);
			$subscriberdata = $database->loadObject();
		  }
		  elseif( $action == "unsubscribe") {
			$query="SELECT subscriber_name, subscriber_email FROM #__communicator_subscribers WHERE user_id=" . $my->id;
			$database->setQuery($query);
			$subscriberdata = $database->loadObject();
		  }
		}
		if( empty($subscriberdata)) {
		  $subscriberdata =& new stdClass();
		  $subscriberdata->subscriber_email = ""; 
		  $subscriberdata->subscriber_name = ""; 
		}
		
		$module = &JModuleHelper::getModule('communicatorsubscribe');
		if( is_object($module) && $module->id ) {
			$params = new JParameter($module->params, JPATH_SITE . '/modules/mod_communicatorsubscribe/mod_communicatorsubscribe.xml');
			$hide_name_field = $params->get( "hide_name_field", "0" );
		}
		else {
		  $hide_name_field = 0;
		}
		$action_lbl = ($action=="subscribe") ? JText::_('Subscribe') : JText::_('Unsubscribe');

		?>
		  <script type="text/javascript">
		  <!--
		  
		  function validate(){
			if(<?php if($hide_name_field=='0'){ ?>document.showField.subscriber_name.value == "" || <?php } ?>document.showField.email.value == ""){
			  alert('<?php echo JText::_('Please complete all the fields.') ?>');
			  return false;
			}
			else{
			  return true;
			}
		  }
		  
		  //-->
		  </script>
			<h1>OX Newsletter</h1>
            <form method="post" name="showField" action="<?php echo JRoute::_("index.php?option=com_communicator&amp;Itemid=$Itemid&amp;task=$action"); ?>">
			<table border="0" cellpadding="0" cellspacing="0" class="contentpane" width="100%">
			<tr>
			  <th align="left" colspan="2"><?php echo JText::_('Your Details:') ?></th>
			  <th width="40%"><br /><br /></th>
			</tr>
		<?php if($hide_name_field=='0'){ ?>
			<tr>
			  <td><?php echo JText::_('Name:') ?></td>
			  <td><input type="text" name="subscriber_name" size="32" class="inputbox" maxlength="64" value="<?php echo $subscriberdata->subscriber_name; ?>" <?php if ($action=='unsubscribe' && !empty($subscriberdata->subscriber_name)){echo 'readonly="readonly"';}; ?> /></td>
			  <td><br /><br /></td>
			</tr>
		<?php }
			else { ?>
			  <input type="hidden" name="subscriber_name" size="32" class="inputbox" maxlength="64" value="<?php echo $subscriberdata->subscriber_name; ?>" <?php if ($action=='unsubscribe' && !empty($subscriberdata->subscriber_name)){echo 'readonly="readonly"';}; ?>>
	  <?php } ?>
			<tr>
			  <td><?php echo JText::_('E-mail Address:') ?></td>
			  <td><input type="text" name="email" size="32" class="inputbox" maxlength="64" value="<?php echo $subscriberdata->subscriber_email; ?>" <?php if ($action=='unsubscribe' && !empty($subscriberdata->subscriber_name)){echo 'readonly="readonly"';}; ?>></td>
			  <td><br /><br /></td>
			</tr>
			<tr>
			  <td colspan="2"><br />
			  <input type="submit" name="submit" value="<?php echo $action_lbl; ?>" class="button" onclick="return validate();" /></td>
			  <td></td>
			</tr>
		  </table>
			 <?php
			// used for spoof hardening
			$validate = cm_SpoofValue(1);
			?>
			<input type="hidden" name="<?php echo $validate; ?>" value="1" />
			</form>
		<?php
	}
	
	function header() {
	
	}
	
	function footer() {
		
		//echo '<div align="center" class="small">Powered by <a href="http://www.granholmhosting.com" target="_blank">Communicator</a></div>';
	
	}
	
	function new_bar() {
	  $Itemid = JRequest::getInt('Itemid');
		require_once( JPATH_SITE."/includes/HTML_toolbar.php");
		?>
		<td width="25" align="right">
		<a href="<?php echo JRoute::_( "index.php?option=com_communicator&amp;task=edit&Itemid=$Itemid") ?>">
		<img name="new" src="<?php echo JURI::base(); ?>/administrator/images/new_f2.png" height="32" width="32" border="0" />
		<?php echo JText::_('New'); ?>
		</a>
		</td>
		<?php
	}
	
	function send_bar() {
		require_once( JPATH_SITE."/includes/HTML_toolbar.php");
		?>
		<script language="javascript" type="text/javascript">
		  function submitbutton(pressbutton) {
			  var form = document.adminForm;
			  if (pressbutton == 'cancel') {
				  submitform( pressbutton );
				  return;
			  }
			  // do field validation
			  if (getSelectedValue('adminForm','sendto') < 0){
				  alert( "Please select a group" );
			  } else if (confirm ("<?php echo JText::_('Are you sure you want to send the newsletter?\\nWarning: If you send mail to a large group of users this could take a while!') ?>")) {
				  submitform( 'sendMail' );
			  }
		  }
	  </script>	 
 
		<td width="50%" align="right">
		<a href="javascript:submitbutton('sendMail');">
		<img name="publish" src="<?php echo JURI::base(); ?>/administrator/images/publish_f2.png" align="center" height="32" width="32" border="0" />
		<br />
		<?php echo JText::_('Send'); ?>
		</a>&nbsp;
		</td>
		<td width="50%" align="left">&nbsp;
		<a href="javascript:submitbutton('cancel');">
		<img name="back" src="<?php echo JURI::base(); ?>/administrator/images/back_f2.png" align="center" height="32" width="32" border="0" />
		<br />
		<?php echo JText::_('Cancel'); ?>
		</a>
		</td>
		<?php
	}
	
	function subscriber_bar() {
	  $database =& JFactory::getDBO();
	  $Itemid = JRequest::getInt('Itemid');
	  $my = &JFactory::getUser();
	  $subscriber = null;
	  if( $my->id ) {
		  $database->setQuery('SELECT user_id FROM `#__communicator_subscribers` WHERE user_id='.$my->id .' OR subscriber_email =\''.$my->email.'\'');
		  $subscriber = $database->loadObject();
	  }
	  ?>
		<table align="center">
		  <tr>
		  <?php
		  if(!$subscriber) {
			?>
			<td>
				<img src="<?php echo JURI::base(); ?>media/com_communicator/images/subscribe.png" alt="<?php echo JText::_('Subscribe') ?>" align="center" border="0" />
				<a href="<?php echo JRoute::_( 'index.php?option=com_communicator&amp;task=subscribe&amp;Itemid='. $Itemid ) ?>" title="<?php echo JText::_('Subscribe') ?>">
			  &nbsp;<?php echo JText::_('Newsletter abonnieren') ?></a>
			</td>
			<?php
		  }
		  else {
			?>
			<td>&nbsp;&nbsp;
				<img src="<?php echo JURI::base(); ?>media/com_communicator/images/unsubscribe.png" alt="<?php echo JText::_('Unsubscribe') ?>" align="center" border="0" />
			  <a href="<?php echo JRoute::_( 'index.php?option=com_communicator&amp;task=unsubscribe&amp;Itemid='. $Itemid ) ?>" title="<?php echo JText::_('Unsubscribe') ?>">
			  &nbsp;<?php echo JText::_('Newsletter abmelden') ?></a>
			</td>
			<?php
		  }
		  ?>
		  </tr>
		</table>
<?php
	}
}
?>
