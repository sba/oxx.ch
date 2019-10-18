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
<form action="index.php" method="post" name="adminForm" id="adminForm">

<table>
    <tr>
        <td width="100%" valign="bottom">
        </td>
        <td nowrap="nowrap" align="right">
            <?php
            echo JText::_('ViewMode') . ':<br />' . $this->lists['viewmode'];
            ?>
        </td>
        <td nowrap="nowrap" align="right">
            <?php
            echo JText::_('Sort by') . ':<br />' . $this->lists['sortby'];
            ?>
        </td>
        <td nowrap="nowrap" align="right">
            <?php
            echo JText::_('Hits') . '<br />' . $this->lists['hitsCmp'] . $this->lists['hitsVal'];
            ?>
        </td>
        <?php if( $this->viewmode != 1 ) { ?>
        <td nowrap="nowrap" align="right">
            <?php
            echo JText::_('ItemID') . ':<br />' . $this->lists['itemid'];
            ?>
        </td>
        <?php } ?>
        <td nowrap="nowrap" align="right">
            <?php
            echo (($this->viewmode == 1) ? JText::_('Filter Urls') : JText::_('Filter SEF Urls')) . ':<br />' . $this->lists['filterSEF'];
            ?>
        </td>
        <?php if( $this->viewmode != 1 ) { ?>
        <td nowrap="nowrap" align="right">
            <?php
            echo JText::_('Filter Real Urls') . ':<br />' . $this->lists['filterReal'];
            ?>
        </td>
        <?php } ?>
        <td nowrap="nowrap" align="right">
            <?php
            echo JText::_('Component') . ':<br />' . $this->lists['comList'];
            ?>
        </td>
    </tr>
</table>

<table class="adminlist">
<thead>
    <tr>
        <th width="5">
            <?php echo JText::_('Num'); ?>
        </th>
        <th width="20">
            <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
        </th>
        <th class="title" width="40px">
            <?php echo JText::_('Hits'); ?>
        </th>
        <th class="title">
            <?php echo (($this->viewmode == 1) ? JText::_('Date Added') : JText::_('SEF Url')); ?>
        </th>
        <th class="title">
            <?php echo (($this->viewmode == 1) ? JText::_('Url') : JText::_('Real Url')); ?>
        </th>
    </tr>
</thead>
<tfoot>
    <tr>
        <td colspan="5">
            <?php echo $this->pagination->getListFooter(); ?>
        </td>
    </tr>
</tfoot>
<tbody>
    <?php
    $k = 0;
    //for ($i=0, $n=count( $rows ); $i < $n; $i++) {
    foreach (array_keys($this->items) as $i) {
        $row = &$this->items[$i];
        ?>
        <tr class="<?php echo 'row'. $k; ?>">
            <td align="center">
                <?php echo $this->pagination->getRowOffset($i); ?>
            </td>
            <td>
                <?php echo JHTML::_('grid.id', $i, $row->id ); ?>
            </td>
            <td>
                <?php echo $row->cpt; ?>
            </td>
            <td style="text-align:left;">
                <?php if( $this->viewmode == 1 ) {
                    echo $row->dateadd;
                } else { ?>
                    <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>', 'edit')">
                    <?php echo $row->oldurl;?>
                    </a>
                <?php } ?>
            </td>
            <td style="text-align:left;">
                <?php if( $this->viewmode == 1 ) { ?>
                    <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>', 'edit')">
                    <?php echo $row->oldurl; ?>
                    </a>
                <?php } else {
                    echo htmlentities( $row->newurl . ($row->Itemid == '' ? '' : (strpos($row->newurl, '?') ? '&' : '?' ) . 'Itemid='.$row->Itemid ) );
                } ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
</tbody>
</table>

<input type="hidden" name="option" value="com_sef" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="sefurls" />
</form>
