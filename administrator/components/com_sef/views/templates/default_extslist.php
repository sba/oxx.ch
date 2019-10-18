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
<table class="adminheading">
    <tr>
        <td>
            <strong><?php echo JText::_('Installed SEF Extensions'); ?></strong>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo JText::_('Only those SEF extensions that can be uninstalled are displayed - some core extensions cannot be removed.'); ?>
        </td>
    </tr>
</table>

<table class="adminlist">
<thead>
    <tr>
        <th width="20%" class="title">
            <?php echo JText::_('SEF Extension'); ?>
        </th>
        <th width="10%">
            <?php echo JText::_('Author'); ?>
        </th>
        <th width="5%">
            <?php echo JText::_('Version'); ?>
        </th>
        <th width="10%">
            <?php echo JText::_('Date'); ?>
        </th>
        <th width="15%">
            <?php echo JText::_('Author Email'); ?>
        </th>
        <th width="15%">
            <?php echo JText::_('Author URL'); ?>
        </th>
    </tr>
</thead>
<tbody>
    <?php
    $k = 0;
    foreach (array_keys($this->extensions) as $i) {
        $row = &$this->extensions[$i];
        ?>
        <tr class="<?php echo 'row'. $k; ?>">
            <td>
                <input type="radio" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);">
                <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>', 'editext')">
                <?php echo $row->name; ?>
                </a>
            </td>
            <td>
                <?php echo @$row->author != '' ? $row->author : "&nbsp;"; ?>
            </td>
            <td align="center">
                <?php echo @$row->version != '' ? $row->version : "&nbsp;"; ?>
            </td>
            <td align="center">
                <?php echo @$row->creationdate != '' ? $row->creationdate : "&nbsp;"; ?>
            </td>
            <td>
                <?php echo @$row->authorEmail != '' ? $row->authorEmail : "&nbsp;"; ?>
            </td>
            <td>
                <?php echo @$row->authorUrl != "" ? "<a href=\"" .(substr( $row->authorUrl, 0, 7) == 'http://' ? $row->authorUrl : 'http://'.$row->authorUrl). "\" target=\"_blank\">".$row->authorUrl."</a>" : "&nbsp;";?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
</tbody>
</table>
