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

<div class="col width-60">
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'Extension Details' ); ?></legend>
        
        <table class="admintable">
            <tr>
                <td class="key">
                    <?php echo JText::_('Name'); ?>:
                </td>
                <td>
                    <?php echo $this->extension->name; ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo JText::_('Version'); ?>:
                </td>
                <td>
                    <?php echo $this->extension->version; ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo JText::_('Description'); ?>:
                </td>
                <td>
                    <?php echo $this->extension->description; ?>
                </td>
            </tr>
        </table>
    </fieldset>
</div>

<div class="col width-40">
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'Parameters' ); ?></legend>
        
        <?php
        echo $this->extension->params->render();
        ?>
    </fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_sef" />
<input type="hidden" name="controller" value="extension" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="file" value="<?php echo $this->extension->file; ?>" />
<input type="hidden" name="redirto" value="<?php echo $this->redirto; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
