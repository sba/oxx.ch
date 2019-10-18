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
	function submitbutton3(pressbutton) {
		var form = document.adminForm;

		form.fromserver.value = '1';
		form.submit();
	}
	
	function submitbuttonext(extension) {
		var form = document.adminForm;

		form.fromserver.value = '1';
		form.ext.value = extension;
		form.submit();	    
	}

//-->
</script>

<fieldset class="adminform">
<legend><?php echo JText::_('JoomSEF'); ?></legend>
<table class="adminform">
<tr>
    <td width="20%"><strong><?php echo JText::_('Installed version').':'; ?></strong></td>
    <td><?php echo $this->oldVer; ?></td>
</tr>
<tr>
    <td><strong><?php echo JText::_('Newest version').':'; ?></strong></td>
    <td><?php echo $this->newVer; ?></td>
</tr>
</table>

<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm">
<table class="adminform">
<tr>
    <th colspan="2"><?php echo JText::_( 'Upload Package File' ); ?></th>
</tr>
<tr>
    <td width="120">
        <label for="install_package"><?php echo JText::_( 'Package File' ); ?>:</label>
    </td>
    <td>
        <input class="input_box" id="install_package" name="install_package" type="file" size="57" />
        <input class="button" type="submit" value="<?php echo JText::_( 'Upload File' ); ?> &amp; <?php echo JText::_( 'Install' ); ?>" />
    </td>
</tr>
</table>

<?php
if( (strnatcasecmp($this->newVer, $this->oldVer) > 0) ||
(strnatcasecmp($this->newVer, substr($this->oldVer, 0, strpos($this->oldVer, '-'))) == 0) ||
($this->newVer == "?.?.?") )
{
?>
    <table class="adminform">
        <tr>
            <th><?php echo JText::_('Upgrade From ARTIO Server'); ?></th>
        </tr>
        <tr>
            <td>
                   <?php
                   if( $this->newVer == '?.?.?' ) {
                       echo JText::_('Server not available.');
                   }
                   else
                   {
                       ?>
                       <input class="button" type="button" value="<?php echo JText::_('Upgrade From ARTIO Server'); ?>" onclick="submitbutton3()" />
                       <?php
                   }
                   ?>
            </td>
        </tr>
    </table>
<?php
} else {
?>
    <table class="adminform">
        <tr>
            <th><?php echo JText::_('Your JoomSEF is up to date.'); ?></th>
        </tr>
    </table>
<?php } ?>
</fieldset>

<fieldset class="adminform">
<legend><?php echo JText::_('SEF Extensions'); ?></legend>
<table class="adminform">
    <tr>
        <th><?php echo JText::_('SEF Extension'); ?></th>
        <th><?php echo JText::_('Installed version'); ?></th>
        <th><?php echo JText::_('Newest version'); ?></th>
        <th><?php echo JText::_('Upgrade'); ?></th>
    </tr>
    <?php
    $k = 0;
    if( (count($this->extensions) > 0) ) {
        foreach(array_keys($this->extensions) as $i) {
            $row = &$this->extensions[$i];
        ?>
        <tr class="<?php echo 'row'.$k; ?>">
            <td><?php echo $row->name; ?></td>
            <td><?php echo $row->old; ?></td>
            <td><?php echo $row->new; ?></td>
            <td>
            <?php
            if( (strnatcasecmp($row->new, $row->old) > 0) ||
                (strnatcasecmp($row->new, substr($row->old, 0, strpos($row->old, '-'))) == 0) )
            {
                ?>
                <input class="button" type="button" value="<?php echo JText::_('Upgrade'); ?>" onclick="submitbuttonext('<?php echo $i; ?>')" />
                <?php
            } else {
                echo JText::_('Not available');
            }
            ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
        }
    }
    ?>
</table>
</fieldset>

<input type="hidden" name="option" value="com_sef" />
<input type="hidden" name="task" value="doUpgrade" />
<input type="hidden" name="controller" value="" />
<input type="hidden" name="fromserver" value="0" />
<input type="hidden" name="ext" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>
