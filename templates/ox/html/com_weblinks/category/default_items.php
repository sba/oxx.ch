<?php // @version $Id: default_items.php 10381 2008-06-01 03:35:53Z pasamio $
defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">
	function tableOrdering(order, dir, task) {
		var form = document.adminForm;

		form.filter_order.value = order;
		form.filter_order_Dir.value = dir;
		document.adminForm.submit(task);
	}
</script>

<?php /*
<div class="display">
	<form action="<?php echo htmlspecialchars($this->action); ?>" method="post" name="adminForm">
		<?php echo JText :: _('Display Num'); ?>&nbsp;
		<?php echo $this->pagination->getLimitBox(); ?>
		<input type="hidden" name="filter_order" value="<?php echo $this->lists['order'] ?>" />
		<input type="hidden" name="filter_order_Dir" value="" />
	</form>
</div>
*/?>
<br />

<table class="weblinks" cellpadding="6px">

	<?php foreach ($this->items as $item) : ?>
	<tr class="sectiontableentry<?php echo $item->odd + 1; ?>">

		<td align="center" headers="num">
		<?php preg_match('@^(?:http://)?([^/]+)@i', $item->url, $treffer);
			$host = $treffer[1];
			preg_match('/[^.]+\.[^.]+$/', $host, $treffer);
			$domain = $treffer[0];
			$url = $item->url;
			/*if (substr($url, -1)=="/") {
				$url = substr($url,0,-1);
			}*/
			/*<a href="<?=$item->url?>"><img src="http://www.mythumbnail.net/webthumb.php?url=<?=$url ?>&x=150&y=100" /></a>*/
		?>
			
			<a href="<?=$item->url?>" target="_blank"><img src="http://www.thumbshots.de/cgi-bin/show.cgi?url=<?=$url ?>"/></a>
		</td>

		<td headers="title">
			<?php if ($item->image) :
				echo $item->image;
			endif;
			echo $item->link;
			if ($this->params->get('show_link_description')) : ?>
			<br />
			<?php echo nl2br($item->description);
			endif; ?>
		</td>

	</tr>
	<?php endforeach; ?>

</table>


<p class="counter">
	<?php echo $this->pagination->getPagesCounter(); ?>
</p>
<?php echo $this->pagination->getPagesLinks();
