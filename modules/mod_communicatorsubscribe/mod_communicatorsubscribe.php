<?php
/**
 * Communicator file
 *
 * This program is free software; under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @package Communicator
 * @subpackage Plugin
 * @author Granholm CMS
 * @link http://www.granholmcms.com
 * @copyright Copyright (C) 2008 granholmCMS.com
 * @version $Id: mod_communicatorsubscribe.php 318 2008-06-10 10:19:55Z Suami $
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Configuration
 * ------------------
 */
global $mainframe;

$database =& JFactory::getDBO();
$my =& JFactory::getUser();

if(!file_exists(JPATH_SITE . "/components/com_communicator/communicator.php")) {
	echo JText::_('This module requires the Communicator component');
}
else {

	require_once( JPATH_SITE . '/components/com_communicator/communicator.class.php');
	
	/**
	 * See if the user wants it horizontal or vertical 
	 * 0 = Vertical
	 * 1 = Horizontal
	 */
	$position = $params->get('position', 0);
	
	// The Text to be shown in front of the Subscribe Form
	$pretext = $params->get('pretext', JText::_('Keep yourself updated with our FREE newsletters now!') );
	$show_pretext = $params->get('show_pretext', 0);
	
	//1 to limit the number of characters of title, 0 to disable it
	$chars_limit = $params->get( 'chars_limit', 1);

	// used with character limits enabled. the value signifies the number of characters to display
	$chars = intval( $params->get( 'chars', 15) );

	//to hide the name field, set it to 1
	$hide_name_field = $params->get( 'hide_name_field', 0);
	$username = ( !empty( $my->name ) ) ? $my->name : $my->username;

	// GetItemid
	$query = "SELECT id"
	. "\n FROM #__menu"
	. "\n WHERE type = 'components'"
	. "\n AND published = 1"
	. "\n AND link = 'index.php?option=com_communicator'"
	;
	$database->setQuery( $query );
	$_Itemid = $database->loadResult();

	?>
	<script type="text/javascript" language="Javascript"><!--
	function changeTask() {
		var name = document.communicatorMod.subscriber_name.value;
		var email;
		var max_length = <?php echo $chars ?>;
		var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i

		if (filter.test(document.communicatorMod.email.value)) {
			email = document.communicatorMod.email.value;
			var a = true;
		} else {
			alert("<?php echo JText::_('Please enter a valid email address.',true); ?>");
			var a = true; return false;
		}
		try {
		<?php
		if( !empty($chars_limit) && $hide_name_field=="0" ) { ?>
			if(document.communicatorMod.subscriber_name.length > max_length) {
				alert("<?php echo JText::_('Please enter use a shorter Subscriber Name. Thanks.',true); ?>");
				return false;
			}
		<?php
		}
		if( $hide_name_field=="0" ) { ?>
			if(document.communicatorMod.subscriber_name.length < 1) {
				alert("<?php echo JText::_('Please enter a Subscriber Name. Thanks.',true); ?>");
				return false;
			}
			<?php
		}
		?>
		}
		catch(e) {}
		return true;
	} // -->
	</script>
	 
<div class="box_container">
<div class="box_top"></div>
<div class="box_content">
<h2>OX Newsletter</h2>
	
	<?php if( !empty( $pretext) && ($show_pretext == 1)) {
		echo '<p>'. $pretext .'</p>';
	}
	?>
	
	<form method="post" action="<?php echo JURI::base() ?>/index.php?option=com_communicator&amp;Itemid=<?php echo $_Itemid; ?>" name="communicatorMod">

	<?php
	if($hide_name_field == 1) { ?>
		<input type="hidden" name="subscriber_name" value="<?php echo !empty( $username) ? $username: "Subscriber"; ?>" />
	<?php
	}
	else { ?>
			<label for="subscriber_name"><?php echo JText::_('Name'); ?></label>
			<input type="text" id="subscriber_name" name="subscriber_name" class="inputbox" value="<?php echo $username; ?>" />
	<?php
	}
	?>
		<input style="width:144px; float:left;
" type="text" id="cm_email" name="email" class="inputbox" value="deine Email" onblur="if(this.value=='') this.value='deine Email';" onfocus="if(this.value=='deine Email') this.value='';"/>
	<?php
	if( $my->id ) {
		
		$q = "SELECT subscriber_id FROM `#__communicator_subscribers` WHERE user_id=".$my->id.' OR subscriber_email=\''.$my->email.'\'';
		$database->setQuery($q); $subscriber = $database->loadResult();
		
		if( empty($subscriber)) { ?>
			<input name="task" type="hidden" value="subscribe" />
			<input  type="image" src="templates/ox/images/pfeil.gif" class="button_pfeil"  onclick="return changeTask();"/>
			<?php
		}
		else {?>
				<input name="task" type="hidden" value="unsubscribe" />
				<input type="image" src="templates/ox/images/pfeil.gif" class="button_pfeil" onclick="return( confirm('<?php echo JText::_('Willst du dich wirklich von unserem Newsletter abmelden?'); ?>'));" onclick="return changeTask();" />
	
		<?php			
		}
	}
	else {?><input name="task" type="hidden" class="inputbox" id="cm_subscribe" value="subscribe" checked="checked"/>
			<input  type="image" src="templates/ox/images/pfeil.gif" class="button_pfeil"  onclick="return changeTask();"/>
			</form>
	<?php
	}
	?>
	 <input type="hidden" name="Itemid" value="<?php echo $_Itemid; ?>" />
	 <?php
		// used for spoof hardening
		$validate = cm_SpoofValue(1);
		?>
		<input type="hidden" name="<?php echo $validate; ?>" value="1" />
	 
<?php
	$my =& JFactory::getUser();
}
?>
<br />
</div>
<div class="box_bottom"></div>
</div>
<br />
