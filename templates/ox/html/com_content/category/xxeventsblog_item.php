<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php $canEdit   = ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own')); ?>
<?php if ($this->item->state == 0) : ?>

<div class="system-unpublished">
<?php endif; ?>


<?php if ($this->item->readmore) { ?>
		<a href="<?php echo $this->item->readmore_link; ?>" title="<?php
			$event_css_suffix = '';
			if ($readmore = $this->item->params->get('readmore')) {
				echo $readmore;
			} else {
				echo JText::sprintf('Klicken f&uuml;r mehr Infos!');
			}?>">
<?php } else { 
			$event_css_suffix = '_noreadmore';
	}?>


<div class="event_blog_item_container<?=$event_css_suffix?>">

<?php
require_once 'phpthumb/createagendaimage.php';
$agendaimage_png = createAgendaImage($this->item->params->get( 'event_image' ));

if (!$agendaimage_png===false) { ?>
<div class="event_blog_graphic">
<img src="<?=$agendaimage_png;?>" width="135" border="0"/>
</div>
<?php } ?>
<? //$this->item->getItem($i, $this->params)?>
<div class="event_blog_text">

<?php if ($this->item->params->get('show_title') || $this->item->params->get('show_pdf_icon') || $this->item->params->get('show_print_icon') || $this->item->params->get('show_email_icon')) : ?>

	<span class="contentheading<?php echo $this->item->params->get( 'pageclass_sfx' ); ?>">
		<?php echo $this->escape($this->item->title); ?>
	</span>


	
	   <?php if ($canEdit) : ?>
	   <?php echo JHTML::_('icon.edit', $this->item, $this->item->params, $this->access); ?>
	   <?php endif; ?>

<?php endif; ?>
<?php  if (!$this->item->params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif; ?>
<?php echo $this->item->event->beforeDisplayContent; ?>
<br>


<?php 
$event_date=$this->item->params->get( 'event_date' ) ;
setlocale(LC_TIME, 'de_DE');
?><div class="event_blog_text_datum"><?php
echo strftime('%A, %d.%m.%Y', mktime(0,0,0,substr($event_date,5,2),substr($event_date,8,2),substr($event_date,0,4))) . '<br>';
?></div><?php
echo $this->item->text; 
?>

</div>
<br clear="all" />
</div>

<?php if ($this->item->readmore) : ?>			
		</a>
<?php endif; ?>

<br clear="all" />

<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>

<span class="article_separator">&nbsp;</span>
<?php echo $this->item->event->afterDisplayContent; ?>