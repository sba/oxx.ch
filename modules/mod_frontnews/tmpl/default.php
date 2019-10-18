<?php // no direct access
// $gb = array(username, date, title, content)
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<div class="box_container">
<div class="box_top"></div>
<div class="box_content">
<h2 class="frontnews">Saison 2008 / 2009</h2>

<?php foreach ($list as $item) :  ?>
	<h3 class="frontnews"><?php echo $item->title; ?></h3>
	<?php $item->date ?>
	<?php echo $item->introtext ?>
	<?php //ein br schreiben wenn nicht schon vorhanden
		if (!(substr(trim($item->introtext),-4)=='<br>' || substr(trim($item->introtext),-5)=='<br/>' || substr(trim($item->introtext),-6)== '<br />')) { ?>
	<br />
	<?php } ?>
	<?php if (isset( $item->readmore )){?>
		<a href="<?php echo $item->link ?>" class="frontnews"><?php echo $item->readmore  ?></a>
	<?php } ?>
	
<?php endforeach; ?>

</div>
<div class="box_bottom"></div>
</div>
<br />