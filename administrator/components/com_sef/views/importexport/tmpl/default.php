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

<script language="javascript" type="text/javascript">
<!--
	function submitbuttonimport(pressbutton) {
		var form = document.adminForm;

		form.task.value = 'import';
		form.submit();
	}

//-->
</script>

<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm">

<fieldset class="adminform">
<legend><?php echo JText::_('Import URLs'); ?></legend>
<table class="adminform">
<tr>
    <th colspan="2"><?php echo JText::_( 'Import URLs' ); ?></th>
</tr>
<tr>
    <td width="120">
        <label for="install_package"><?php echo JText::_( 'Import File' ); ?>:</label>
    </td>
    <td>
        <input class="input_box" id="importfile" name="importfile" type="file" size="57" />
        <input class="button" type="button" value="<?php echo JText::_( 'Import Custom URLs' ); ?>" onclick="submitbuttonimport()" />
    </td>
</tr>
</table>
</fieldset>

<input type="hidden" name="option" value="com_sef" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="sefurls" />
</form>
