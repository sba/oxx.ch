<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_media');
?>
<?php if ($this->params->get('show_page_title', 1)) : ?>
<div class="componentheading<?php echo $this->params->get('pageclass_sfx');?>">
	<?php echo $this->escape($this->params->get('page_title')); ?>
</div>
<?php endif; ?>
<br />
<table border="1" cellpadding="3" cellspacing="0" width="100%">

<?php for ($i = $this->pagination->limitstart; $i < ($this->pagination->limitstart + $this->params->get('num_leading_articles')); $i++) : ?>
	<?php if ($i >= $this->total) : break; endif; ?>
	<tr>
	<?php
		$this->item =& $this->getItem($i, $this->params);
		echo $this->loadTemplate('item');
	?>
	</tr>
<?php endfor; ?>

</table>
<br />
<br />
<?= date('d.m.Y H:i');?>
<br />
