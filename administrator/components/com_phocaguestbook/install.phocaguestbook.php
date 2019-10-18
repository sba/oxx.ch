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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.filesystem.folder' );

function com_install()
{

	
	$message = '';
	
	$message .= '<p>Please select if you want to install or upgrade the Phoca Guestbook component.</p>';
	
/*	if (in_array(0, $error))
	{
		return false;
	}
	else
	{
		return true;
	}*/
	echo $message;
	echo '<center>';
	echo '<div style="padding:20px;border:1px solid#ff8000;background:#fff">';
	echo '<table border="0" cellpadding="20" cellspacing="20"><tr>';
	echo '<td align="center" valign="middle"><a href="index.php?option=com_phocaguestbook&amp;controller=phocaguestbookinstall&amp;task=install"><img src="components/com_phocaguestbook/assets/images/install.png" alt="Install" /></a></td>';
	echo '<td align="center" valign="middle"><a href="index.php?option=com_phocaguestbook&amp;controller=phocaguestbookinstall&amp;task=upgrade"><img src="components/com_phocaguestbook/assets/images/upgrade.png" alt="Upgrade" /></a></td>';
	echo '</tr></table>';
	echo '</div></center>';
	
	echo '<p>&nbsp;</p><p>&nbsp;</p>';
	echo '<div style="padding:20px;border:1px solid#0080c0;background:#fff">';
	echo '<p><img src="components/com_phocaguestbook/assets/images/logo.png" alt="www.phoca.cz" /></p>';
	echo '<center><a style="text-decoration:underline" href="http://www.phoca.cz/" target="_blank">www.phoca.cz</a></center>';
	echo '</div>';

}

?>