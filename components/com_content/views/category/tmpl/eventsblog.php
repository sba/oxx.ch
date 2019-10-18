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
<div id="event_blog">


<?php for ($i = $this->pagination->limitstart; $i < ($this->pagination->limitstart + $this->params->get('num_leading_articles')); $i++) : ?>
	<?php if ($i >= $this->total) : break; endif; ?>
	<div>
	<?php
		$this->item =& $this->getItem($i, $this->params);
		echo $this->loadTemplate('item');
	?>
	</div>
<?php endfor; ?>


</div>