<?php
/**
 * SEF component for Joomla! 1.5
 *
 * @author      ARTIO s.r.o.
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 * @version     3.1.0
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
<table class="adminform">
    <tr>
        <th colspan="4"><?php echo JText::_('ARTIO JoomSEF Support');?></th>
    </tr>
    <tr>
        <td>
        <ol><?php echo JText::_('<b>Help is available via following channels:</b>');?><br />
        <li><a href="http://www.artio.net/en/joomsef/artio-joomsef-3-x" target="_blank"><?php echo JText::_('Official Product Page');?></a></li>
        <li><a href="http://www.artio.net/en/support-forums" target="_blank"><?php echo JText::_('ARTIO Support Forums');?></a></li>
        <li><a href="http://www.artio.net/en/web-support/" target="_blank"><?php echo JText::_('ARTIO Helpdesk (payed)');?></a></li>
        </ol>
    </tr>
</table>
</div>

<input type="hidden" name="option" value="com_sef" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="" />
</form>
