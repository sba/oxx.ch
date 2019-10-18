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
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');


class PhocaGuestbookViewPhocaGuestbook extends JView
{
	function display($tpl = null)
	{
		
		global $mainframe;
	
		$pathway 	= & $mainframe->getPathway();
		$document	= & JFactory::getDocument();
		$uri 		= & JFactory::getURI();
		$user 		=& JFactory::getUser();
		
		$administrator = 0;
		if (strtolower($user->usertype) == strtolower('super administrator') || $user->usertype == strtolower('administrator')) {
			$administrator = 1;
		}

		$document->addCustomTag(PhocaguestbookHelper::setCaptchaReloadJS());
		$document->addCustomTag(PhocaguestbookHelper::setTinyMCEJS());
		$document->addCustomTag(PhocaguestbookHelper::displaySimpleTinyMCEJS());
		$document->addStyleSheet(JURI::base(true).'/components/com_phocaguestbook/assets/phocaguestbook.css');
		
		// Get the parameters of the active menu item
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();
		$params	= &$mainframe->getParams();
		
		//-----------------------------------------------------------------------------------------------
		// Fill the form in case, you get data from post (e.g. user send data, but with no valid captcha
		// We send him back to the form but without lossing data
		$post				= JRequest::get('post');
		$post['content']	= JRequest::getVar( 'content', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$cid				= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$id					= JRequest::getVar( 'id', '', 'get', 'string' );
		$post['catid'] 		= (int) $cid[0];
		
		if (isset($post['pgusername'])) { // if not there is other code to solve it - see below
			$post['username']	= $post['pgusername'];
		}
		
		// HTML Purifier
		require_once( JPATH_COMPONENT.DS.'assets'.DS.'library'.DS.'HTMLPurifier.auto.php' );
		$configP = HTMLPurifier_Config::createDefault();
		$configP->set('Core', 'TidyFormat', !empty($_REQUEST['tidy']));
		$configP->set('Core', 'DefinitionCache', null);
		$configP->set('HTML', 'Allowed', 'strong,em,p[style],span[style],img[src|width|height|alt|title],li,ul,ol,a[href],u,strike');
		$purifier = new HTMLPurifier($configP);
		$post['content'] = $purifier->purify($post['content']);
		// ------------
		
		//-----------------------------------------------------------------------------------------------
		// Add username and user e-mail if user is login
		
		$username_or_name = 0;
		if ($params->get( 'username_or_name' ) != ''){$username_or_name = $params->get( 'username_or_name' );}
		
		if ($username_or_name == 1)
		{
			if ($user->name && trim($user->name !=''))
			{
				$form_username = $user->name;
			}
			else
			{
				$form_username = JText::_('Guest');
			}
		}
		else
		{
			if ($user->username && trim($user->username !=''))
			{
				$form_username = $user->username;
			}
			else
			{
				$form_username = JText::_('Guest');
			}
		}
		
		if ($user->email && trim($user->email !=''))
		{
			$form_email = $user->email;
		}
		else
		{
			$form_email = '';
		}
		
		//-----------------------------------------------------------------------------------------------
		// !!!! Add content to the fields
		
		//-----------------------------------------------------------------------------------------------
		//Create new object, if user fill not all data, no redirection and he gets the data he added (he doesn't loss it)
		$formdata = new JObject();
		//Content
		if (isset($post['content']))	{$formdata->set('content', $post['content']);}
		else							{$formdata->set('content', '');}
		//Name !!! NAME OR USERNAME 
		if (isset($post['username']))	{$formdata->set('username', $post['username']);}
		else							{$formdata->set('username', $form_username);}
		//Email
		if (isset($post['email']))		{$formdata->set('email', $post['email']);}
		else							{$formdata->set('email', $form_email);}
		//Title
		if (isset($post['title']))		{$formdata->set('title', $post['title']);}
		else							{$formdata->set('title', '');}
		
		$editor = PhocaguestbookHelper::displayTextArea('content',  $formdata->content , '400px', '200px', '100', '100', false );
		
		
		//-----------------------------------------------------------------------------------------------
		// Get data - all items
		$items		= $this->get('data');
		$guestbooks	= $this->get('guestbook');
		// Define image tag attributes
		if (!empty ($guestbooks->image))
		{
			$attribs['align'] = $guestbooks->image_position;
			$attribs['hspace'] = '6';

			// Use the static HTML library to build the image tag
			$image = JHTML::_('image', 'images/stories/'.$guestbooks->image, JText::_('Phoca Guestbook'), $attribs);
		}
		
		//$total		= $this->get('total');
		$pagination	= &$this->get('pagination');
		
		//----------------------------------------------------------------------------------------------
		// Forbidden Word Filter
		$forbidden_word_filter			= trim( $params->get( 'forbidden_word_filter' ) );
		$forbidden_word_filter_array 	= explode( ';', $forbidden_word_filter );
		
		//----------------------------------------------------------------------------------------------
		// Forbidden Whole Word Filter
		$forbidden_whole_word_filter			= trim( $params->get( 'forbidden_whole_word_filter' ) );
		$forbidden_whole_word_filter_array 		= explode( ';', $forbidden_whole_word_filter );
		
		//----------------------------------------------------------------------------------------------
		//Add custom CSS V A L U E S
		if ($params->get( 'font_color' ) != '')			{$css['fontcolor'] = $params->get( 'font_color' );}
		else 											{$css['fontcolor'] = '#000000';}
		if ($params->get( 'second_font_color' ) != '')	{$css['secondfontcolor'] = $params->get( 'second_font_color' );}
		else 											{$css['secondfontcolor'] = '#dddddd';}
		if ($params->get( 'background_color' ) != '')	{$css['backgroundcolor'] = $params->get( 'background_color' );}
		else 											{$css['backgroundcolor'] = '#C8DFF9';}
		if ($params->get( 'border_color' ) != '')		{$css['bordercolor'] = $params->get( 'border_color' );}
		else 											{$css['bordercolor'] = '#E6E6E6';}
		
		//Add display values
		$display = '';
		$require = '';
		
		$display['username'] = 1;
		if ($params->get( 'display_name' ) != '')		{$display['username'] = $params->get( 'display_name' );}
		$display['email'] = 1;
		if ($params->get( 'display_email' ) != '')		{$display['email'] = $params->get( 'display_email' );}
		$display['formusername'] = 1;
		if ($params->get( 'display_name_form' ) != '')	{$display['formusername'] = $params->get( 'display_name_form' );}
		$display['formemail'] = 1;
		if ($params->get( 'display_email_form' ) != '')	{$display['formemail'] = $params->get( 'display_email_form' );}
		
		//Add requirement V A L U E S
		$require['title'] = 1;
		if ($params->get( 'require_title' ) != '')		{$require['title'] = $params->get( 'require_title' );}
		
		$require['username'] = 1;
		if ($params->get( 'require_username' ) != '')	{$require['username'] = $params->get( 'require_username' );}
		
		$require['email'] = 0;
		if ($params->get( 'require_email' ) != '')			{$require['email'] = $params->get( 'require_email' );}

		// if we disable email form field and name form field we cannot require these items
		if ($display['formusername'] == 0) 					{$require['username'] = 0;}
		if ($display['formemail'] == 0) 					{$require['email'] = 0;}
		
		$require['content'] = 1;
		if ($params->get( 'require_content' ) != '')		{$require['content'] = $params->get( 'require_content' );}
		
		$require['reguser'] = 0;
		if ($params->get( 'registered_users_only' ) != '')	{$require['reguser'] = $params->get( 'registered_users_only' );}
		
		
				
		//Select the position, add V A L U E S
		if ($params->get( 'form_position' ) != '')			{$config['position'] = $params->get( 'form_position' );}
		else 												{$config['position'] = 0;}

		if ($params->get( 'max_url' ) != '')				{$config['maxurl'] = $params->get( 'max_url' );}
		else 												{$config['maxurl'] = 5;}
		
		if ($params->get( 'enable_captcha' ) != '')			{$require['captcha'] = $params->get( 'enable_captcha' );}
		else 												{$require['captcha'] = 1;}
		
		
		//-----------------------------------------------------------------------------------------------
		// !!!! 1. Server Side Checking controll
		//-----------------------------------------------------------------------------------------------
		//Form Variables --------------------------------------------------------------------------------
		//captcha is wrong,we cannot redirect the page,we display message this way
		//DISPLAY MESSAGES WHICH YOU GET FROM CONTROLL FILE - (CONTROLLERS - phocaguestbook.php)
		$this->assignRef( 'captcha_msg', 		JRequest::getVar( 'captcha-msg', 0, 'get', 'int' ));
		$this->assignRef( 'title_msg_1',		JRequest::getVar( 'title-msg-1', 0, 'get', 'int' ));
		$this->assignRef( 'title_msg_2',		JRequest::getVar( 'title-msg-2', 0, 'get', 'int' ));
		$this->assignRef( 'username_msg_1',		JRequest::getVar( 'username-msg-1', 0, 'get', 'int' ));
		$this->assignRef( 'username_msg_2',		JRequest::getVar( 'username-msg-2', 0, 'get', 'int' ));
		$this->assignRef( 'username_msg_3',		JRequest::getVar( 'username-msg-3', 0, 'get', 'int' ));
		$this->assignRef( 'email_msg_1',		JRequest::getVar( 'email-msg-1', 0, 'get', 'int' ));
		$this->assignRef( 'email_msg_2',		JRequest::getVar( 'email-msg-2', 0, 'get', 'int' ));
		$this->assignRef( 'email_msg_3',		JRequest::getVar( 'email-msg-3', 0, 'get', 'int' ));
		$this->assignRef( 'content_msg_1',		JRequest::getVar( 'content-msg-1', 0, 'get', 'int' ));
		$this->assignRef( 'content_msg_2',		JRequest::getVar( 'content-msg-2', 0, 'get', 'int' ));
		$this->assignRef( 'ip_msg_1',			JRequest::getVar( 'ip-msg-1', 0, 'get', 'int' ));
		$this->assignRef( 'reguser_msg_1',		JRequest::getVar( 'reguser-msg-1', 0, 'get', 'int' ));
		//Form Variables --------------------------------------------------------------------------------
		
		//-----------------------------------------------------------------------------------------------
		// !!!! 2. Before Server Side Checking controll, don't show form (but there is a server side
		//         checking, it means, if the user hack the form which is not displayed to him
		//         there is a server checking controll too.
		//-----------------------------------------------------------------------------------------------
		//Don't show form, is IP Ban is wrong
		$ip_ban			= trim( $params->get( 'ip_ban' ) );
		$ip_ban_array	= explode( ';', $ip_ban );
		$ipa 			= 1;//display
		if (is_array($ip_ban_array))
		{
			foreach ($ip_ban_array as $value)
			{
				if ($_SERVER["REMOTE_ADDR"] == trim($value))
				{
					$ipa = 0;
				}
			}
		}
		//REGISTERED USER ONLY --------------------------------------------------------------------------
		if ($require['reguser'] == 1)
		{
			if ( $user->id > 0 )
			{
				$reguser = 1;
			}
			else
			{
				$reguser = 0;
			}
		}
		else
		{
			$reguser = 1;
		}
		
		//ENABLE OR DISABLE CAPTCHA ----------------------------------------------------------------
		if ($require['captcha'] < 1)// if captcha is disabled
		{
			$enablecaptcha = 0;
		}
		else
		{
			$enablecaptcha = 1;
		}

		
		//----------------------------------------------------------------------------------------------------
		//Variables	
		
		$this->assignRef( 'administrator' ,	$administrator);
		$this->assignRef( 'itemid' ,		$menu->id);
		$this->assignRef( 'id' ,			$id);
		$this->assignRef( 'formdata' ,		$formdata);//captcha is wrong, add the same values via POST into form as they were
		$this->assignRef( 'items' ,			$items);
		$this->assignRef( 'fwfa' ,			$forbidden_word_filter_array);
		$this->assignRef( 'fwwfa' ,			$forbidden_whole_word_filter_array);
		$this->assignRef( 'css',			$css);
		$this->assignRef( 'display', 		$display);
		$this->assignRef( 'require', 		$require);
		$this->assignRef( 'config', 		$config);
		$this->assignRef( 'guestbooks', 	$guestbooks);
		$this->assignRef( 'image', 			$image);
		$this->assignRef( 'params' ,		$params);
		$this->assignRef( 'editor' , 		$editor);
		$this->assignRef( 'pagination', 	$pagination);
		$this->assignRef( 'ipa' ,			$ipa);
		$this->assignRef( 'reguser' ,		$reguser);
		$this->assignRef( 'enablecaptcha' ,	$enablecaptcha);
		$this->assign('action',	$uri->toString());
		
		parent::display($tpl);
	}
}
?>
