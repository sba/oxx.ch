﻿<?php // no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.utilities.date');
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocaguestbook.php' );
?><script language="javascript" type="text/javascript">
		function submitbutton() {
			var novalues='';
			var form = document.saveForm;
			/*var text = tinyMCE.getContent();*/
			var text = form.content.value 
			if (novalues!=''){}
			<?php
			if ($this->require['title']== 1) {?>
			else if ( form.title.value == "" ) {
				alert("<?php echo JText::_( 'Phoca Guestbook No Title', true); ?>");return false;} <?php }
			if ($this->require['username']== 1){?>
			else if( form.pgusername.value == "" ) {
				alert("<?php echo JText::_( 'Phoca Guestbook No Username', true); ?>");return false;}<?php }
			if ($this->require['email']== 1){?>
			else if( form.email.value == "" ) {
				alert("<?php echo JText::_( 'Phoca Guestbook No Email', true); ?>");return false;}<?php }
			if ($this->require['content']== 1){?>
			else if( text == "" ) {
				alert("<?php echo JText::_( 'Phoca Guestbook No Content', true); ?>");return false;}<?php } ?>
		}
		</script><?php
		
if ( $this->params->get( 'show_page_title' ) ) : ?>
	<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<?php echo $this->params->get( 'page_title' );?>
	</div>
<?php endif; ?>
<div class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php if ( @$this->image || @$this->guestbooks->description ) : ?>
	<div class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php
		if ( isset($this->image) ) :  echo $this->image; endif;
		echo $this->guestbooks->description;
	?>
	</div>
<?php endif; ?>
</div>
<?php // <div style="clear:both"></div> ?>
<?php
// Create and correct Messages (Posts, Items)--------------------------------------------------------------------------
$msgpg = '';//Messages (Posts, Items)
foreach ($this->items as $key => $values)
{
	//Maximum of links in the message
	$rand 	= '%phoca' . mt_rand(0,1000) * time() . 'phoca%';
	$ahref_replace 		= "<a ".$rand."=";
	$ahref_search		= "/<a ".$rand."=/";
	$values->content	= preg_replace ("/<a href=/",				$ahref_replace,	$values->content, $this->config['maxurl']);
	$values->content	= preg_replace ("/<a href=.*?>(.*?)<\/a>/",	"$1",			$values->content);
	$values->content	= preg_replace ($ahref_search,				"<a href=",		$values->content);
	//Forbidden Word Filter
	foreach ($this->fwfa as $key2 => $values2)
	{
		if (function_exists('str_ireplace'))
		{
			$values->username 	= str_ireplace (trim($values2), '***', $values->username);
			$values->title		= str_ireplace (trim($values2), '***', $values->title);
			$values->content	= str_ireplace (trim($values2), '***', $values->content);
			$values->email		= str_ireplace (trim($values2), '***', $values->email);
		}
		else
		{		
			$values->username 	= str_replace (trim($values2), '***', $values->username);
			$values->title		= str_replace (trim($values2), '***', $values->title);
			$values->content	= str_replace (trim($values2), '***', $values->content);
			$values->email		= str_replace (trim($values2), '***', $values->email);
		}
	}
	//Forbidden Whole Word Filter
	foreach ($this->fwwfa as $key3 => $values3)
	{
		if ($values3 !='')
		{
			//$values3			= "/([\. ])".$values3."([\. ])/";
			$values3			= "/(^|[^a-zA-Z0-9_]){1}(".preg_quote(($values3),"/").")($|[^a-zA-Z0-9_]){1}/i";
			$values->username 	= preg_replace ($values3, "\\1***\\3", $values->username);// \\2
			$values->title		= preg_replace ($values3, "\\1***\\3", $values->title);
			$values->content	= preg_replace ($values3, "\\1***\\3", $values->content);
			$values->email		= preg_replace ($values3, "\\1***\\3", $values->email);
		}
	}
	//Hack, because Joomla add some bad code to src and href
	if (function_exists('str_ireplace'))
		{
			$values->content	= str_ireplace ('../plugins/editors/tinymce/', 'plugins/editors/tinymce/', $values->content);
		}
		else
		{		
			$values->content	= str_replace ('../plugins/editors/tinymce/', 'plugins/editors/tinymce/', $values->content);
		}
	$msgpg .= '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td nowrap="nowrap"><div style="padding:8px; float:left; color:#F59600"><b>';
	$sep = 0;
	if ($this->display['username'] != 0)//!!! username saved in database can be username or name
	{
		if ($values->username != '')
		{
			$msgpg .= PhocaguestbookHelper::wordDelete($values->username,25,'...');
			$sep = 1;
		}
	}
	if ($this->display['email'] != 0)
	{
		if ($values->email != '')
		{
			if ($sep == 1)
			{
				$msgpg .= ' ';
				$msgpg .= '( '. JHTML::_( 'email.cloak', PhocaguestbookHelper::wordDelete($values->email,35,'...') ).' )';
				$sep = 1;
			}
			else
			{
				$msgpg .= JHTML::_( 'email.cloak', PhocaguestbookHelper::wordDelete($values->email,35,'...') );
				$sep = 1;
			}
		}
	}
	if ($values->title != '')
	{
		if ($values->title != '')
		{
			if ($sep == 1) {$msgpg .= ': ';}
			$msgpg .= PhocaguestbookHelper::wordDelete($values->title,40,'...');
		}
	}
	
	
	
	// SECURITY
	// Open a tag protection
	$a_count 		= substr_count(strtolower($values->content), "<a");
	$a_end_count 	= substr_count(strtolower($values->content), "</a>");
	$quote_count	= substr_count(strtolower($values->content), "\"");
	
	if ($quote_count%2!=0)
	{
		$end_quote = "\""; // close the " if it is open
	}
	else
	{
		$end_quote = "";
	}
	
	if ($a_count > $a_end_count)
	{
		$end_a = "></a>"; // close the a tag if there is a open a tag
						  // in case <a href> ... <a href ></a>
						  // in case <a ... <a href >></a>
	}
	else
	{
		$end_a = "";
	}
	
	
	$msgpg .= '</b></div></td><td width="100%" style="width:100%; margin:10px 0px; background-image:url(templates/ox/images/punkte_bg.gif); background-repeat:repeat-x; background-position:0 20px;">&nbsp;</td><tr></table>';
	$msgpg .= '<div style="overflow:auto; border-left:5px solid #F59600; padding-left:5px;margin:10px;">' . stripslashes($values->content) . $end_quote .$end_a. '</div>';
	$msgpg .= '<p align="right" style="padding-right:10px;"><small style="color:#CDCDCD">' . JHTML::_('date',  $values->date, JText::_( 'DATE_FORMAT_LC2' ) ) . '</small>';
	
	if ($this->administrator != 0) {
		$msgpg.='<a href="'.JRoute::_('index.php?option=com_phocaguestbook&view=phocaguestbook&id='.$this->id.'&Itemid='.$this->itemid.'&controller=phocaguestbook&task=delete&mid='.$values->id.'&limitstart='.$this->pagination->limitstart).'" onclick="return confirm(\''.JText::_( 'Delete Message' ).'\');">'.JHTML::_('image', 'components/com_phocaguestbook/assets/images/icon-trash.gif', JText::_( 'Delete' )).'</a>';
		
		$msgpg.='<a href="'.JRoute::_('index.php?option=com_phocaguestbook&view=phocaguestbook&id='.$this->id.'&Itemid='.$this->itemid.'&controller=phocaguestbook&task=unpublish&mid='.$values->id.'&limitstart='.$this->pagination->limitstart).'">'.JHTML::_('image', 'components/com_phocaguestbook/assets/images/icon-unpublish.gif', JText::_( 'Unpublish' )).'</a>';
	}
	
	$msgpg.='</p>';
	
}
//--Messages (Posts, Items) --------------------------------------------------------------------------------
// Form 1
// Display Messages (Posts, Items) -------------------------------------------------------------------------
// Forms (If position = 1 --> Form is bottom, Messages top, if position = 0 --> Form is top, Messages bottom
if ($this->config['position'] == 1)
{
	echo $msgpg;
}
// Display or not to display the form - only for registered user, IP Ban------------------------------------
$show_form = 1;// Display it
if ($this->ipa == 0) 	{$show_form = 0;$ipa_msg = JText::_('Phoca Guestbook IP Ban No Access');}
else 					{$ipa_msg = '';} 
if ($this->reguser == 0){$show_form = 0;$reguser_msg = JText::_('Phoca Guestbook Reg User Only No Access');}
else 					{$reguser_msg = '';} 
if ($show_form == 1) 
{
	?>
	<h1 style="margin-top:-10px;">G&auml;stebuch</h1>
	<form action="<?php echo $this->action; ?>" method="post" name="saveForm" id="saveForm" onsubmit="return submitbutton();">
	<table border="0" width="500">
		<tr>
			<td>&nbsp;</td>
			<td colspan="4">
				<?php
				// Server side checking
				if ($this->title_msg_1 == 1)
				{
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook No Title' ).'</small><br />';
				}
				if ($this->title_msg_2 == 1)
				{
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook Bad Title' ).'</small><br />';
				}
				if ($this->username_msg_1 == 1)
				{
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook No Username' ).'</small><br />';
				}
				if ($this->username_msg_2 == 1)
				{
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook Bad Username' ).'</small><br />';
				}
				if ($this->username_msg_3 == 1)
				{
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook Username Exists' ).'</small><br />';
				}
				if ($this->email_msg_1 == 1)
				{
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook No Email' ).'</small><br />';
				}
				if ($this->email_msg_2 == 1)
				{
					//echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook Bad Email' ).'</small><br />';
					echo '<small style="color:#F59600;">Hallo! Dies ist sicher keine Emailadresse!</small><br />';
				}
				if ($this->email_msg_3 == 1)
				{
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook Email Exists' ).'</small><br />';
				}
				if ($this->content_msg_1 == 1)
				{	
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook No Content' ).'</small><br />';
				}
				if ($this->content_msg_2 == 1)
				{	
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook Bad Content' ).'</small><br />';
				}
				if ($this->ip_msg_1 == 1)
				{	
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook IP Ban' ).'</small><br />';
				}
				if ($this->reguser_msg_1 == 1)
				{	
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook Reg User Only' ).'</small><br />';
				}
				//-- Server side checking
				?>
			&nbsp;</td>
		</tr>
		
		
		<?php if ($this->display['formusername'])
		{
			?>
			<tr>
				<td> <b><?php echo JText::_('Name'); ?>: </b></td>
				<td colspan="4"><input type="text" name="pgusername" id="pgusername" value="<?php echo $this->formdata->username ?>" size="32" maxlength="100"  />
				<input type="hidden" name="title" id="title" value="<?php echo $this->formdata->title ?>" size="32" maxlength="200" /></td>
			</tr>
			<?php
		}
		?>
		
		<?php if ($this->display['formemail'])
		{
			?>
			<tr>
				<td><b><?php echo JText::_('Email'); ?>: </b></td>
				<td colspan="4"><input type="text" name="email" id="email" value="<?php echo $this->formdata->email ?>" size="32" maxlength="100" /></td>
			</tr>
		<tr>
			<td width="5"><b><?php echo JText::_('Title'); ?>:</b></td>
			<td colspan="4"><input type="text" name="title" id="title" value="<?php echo $this->formdata->title ?>" size="32" maxlength="200" style="width:380px;" /></td>
		</tr>
			<?php
		}
		?>
		
		<tr>
			<td><b><?php echo JText::_('Content'); ?>: </b></td>
			<td colspan="4"><?php //echo $this->editor; ?><textarea id="content" name="content"  rows="8" style="width:380px; height:120px;"><?=$_POST['content'];?></textarea> </td>
		</tr>
		
		<?php if ($this->enablecaptcha > 0)
		{
		?>
			<tr>
				<td width="5"><b><?php //echo JText::_('Image Verification'); ?> </b></td>		
				<td width="5" align="left" valign="middle"><?php
				//echo JHTML::_( 'image.site','index.php?option=com_phocaguestbook&amp;view=phocaguestbooki&amp;id='.$this->id.'&amp;Itemid='.$this->itemid.'&amp;phocasid='. md5(uniqid(time())), '', '','',JText::_('Captcha Image'), array('id' => 'phocacaptcha')); ?>
				
				<img src="<?php echo JRoute::_('index.php?option=com_phocaguestbook&view=phocaguestbooki&id='.$this->id.'&Itemid='.$this->itemid.'&phocasid='. md5(uniqid(time()))) ; ?>" alt="<?php JText::_('Captcha Image'); ?>" id="phocacaptcha" />				</td>
				
				<td width="5" align="left" valign="middle"><input type="text" id="captcha" name="captcha" size="6" maxlength="6"/></td>
				
				<td align="left" width="5" valign="middle"><a <?php //Remove because of IE6 - href="javascript:void(0)" onclick="javascript:reloadCaptcha();" ?> href="javascript:reloadCaptcha();" title="<?php echo JText::_('Reload Image'); ?>" ><?php echo JHTML::_( 'image.site', 'components/com_phocaguestbook/assets/images/icon-reload.gif', '', '','',JText::_('Reload Image')); ?></a></td>
				
				<td align="left" width="50%"><small style="color:#F59600;"><?php
				
				// Server side checking CAPTCHA 
				if ($this->captcha_msg)
				{
					echo '<small style="color:#F59600;">'.JText::_( 'Phoca Guestbook Wrong Captcha' ).'</small><br />';
				}
				//-- Server side checking CAPTCHA
				?></small>&nbsp;</td>
			</tr>
		
		<?php
		}
		?>
		
		<tr>
			<td>&nbsp;</td>
			<td colspan="4"><input class="button" type="submit" name="save" value="<?php echo JText::_('Submit'); ?>" /> &nbsp;<input class="button" type="reset" name="reset" value="<?php echo JText::_('Reset'); ?>" /></td>
		</tr>
	</table>
	<input type="hidden" name="cid" value="<?php echo $this->id ?>" />
	<input type="hidden" name="option" value="com_phocaguestbook" />
	<input type="hidden" name="view" value="phocaguestbook" />
	<input type="hidden" name="controller" value="phocaguestbook" />
	<input type="hidden" name="task" value="submit" />
	<input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
	</form>
	<?php
}//-- Display or not to display Form, Registered user only, IP Ban
else
{
	// Show messages (Only registered user, IP Ban)
	if ($ipa_msg !='') 		{echo '<p>'. $ipa_msg . '</p>';}
	if ($reguser_msg !='') 	{echo '<p>'. $reguser_msg . '</p>';}
}
//---------------------------------------------------------------------------------------------------------
//Forms (If position = 1 --> Form is bottom, Messages top, if position = 0 --> Form is top, Messages bottom
if ($this->config['position'] == 0)
{
	echo $msgpg;
}
//--Form 1
//Form 2
?>
<p>&nbsp;</p>
<form action="<?php echo $this->action; ?>" method="post" name="adminForm" id="adminForm">
<?php
if (count($this->items))
{
	?>
	<center>
	<?php
	/*if ($this->params->get('show_pagination_limit'))
	{
		?>
		<span style="margin:0 10px 0 10px">
			<?php
				echo JText::_('Display Num') .'&nbsp;';
				echo $this->pagination->getLimitBox();
			?>
		</span>
		<?php
	}*/
	
	if ($this->params->get('show_pagination'))
	{
		?>
		<span style="margin:0 10px 0 10px" class="sectiontablefooter<?php echo $this->params->get( 'pageclass_sfx' ); ?>" >
			<?php echo $this->pagination->getPagesLinks();?>
		</span>
		
		<span style="margin:0 10px 0 10px" class="pagecounter">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</span>
		<?php
	}
	?>
	</center>
	<?php
}
?>
</form>
