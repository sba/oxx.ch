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
$agendaimage_png = createAgendaImage($this->item->params->get( 'event_image' ), $this->item->id);

if (!$agendaimage_png===false) { ?>
	<div class="event_blog_graphic">
	<img src="<?=$agendaimage_png;?>" width="135" border="0"/>
	</div>
	<?php 
} ?>
<div class="event_blog_text">
<?php 
	if ($this->item->params->get('event_intro')){?>
		<div class="event_blog_text_intro">
		<?php
		echo $this->item->params->get('event_intro') .'<br>'; ?>
		</div>
		<?php
	}
?>
	<span class="contentheading<?php echo $this->item->params->get( 'pageclass_sfx' ); ?>">
		<?php echo $this->escape($this->item->title); ?>
	</span>
   <?php if ($canEdit) : ?>
   <?php echo JHTML::_('icon.edit', $this->item, $this->item->params, $this->access); ?>
   <?php endif; ?>
<?php  if (!$this->item->params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif; ?>
<?php echo $this->item->event->beforeDisplayContent; ?>
<br>
<?php 
$event_date=$this->item->params->get( 'event_date' ) ;
//echo substr($event_date,8,2) .".".substr($event_date,5,2) .".".substr($event_date,0,4) . '<br>';
?><div class="event_blog_text_datum"><?php
$str_event_date = strftime('%A, %d.%m.%Y', mktime(0,0,0,substr($event_date,5,2),substr($event_date,8,2),substr($event_date,0,4)));
$str_event_date = str_replace(array("Monday", "Tuesday", "Wednesday", "Thursday","Friday","Saturday","Sunday"), array("Montag", "Dienstag", "Mittwoch", "Donnerstag","Freitag","Samstag","Sonntag"), $str_event_date);
echo $str_event_date;

if($this->item->params->get( 'event_date2' )){
    $event_date2 = $this->item->params->get( 'event_date2' );
    if (!(substr($event_date2,0,6)=='<br />' || substr($event_date2,0,5)=='<br/>' || substr($event_date2,0,4)=='<br>')){
        echo '<br />';
    }
	echo $event_date2 .'<br />';
}
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



<?php 
//Add Items to Hall of Fame Table
if ($canEdit) { //only execute when user is logged in (increases preformance)
	$db = JFactory::getDBO();

	$content_id = $this->item->id;
	$catid = $this->item->catid;
	$event = $this->item->title;
	$event_date = $this->item->params->get( 'event_date' );
	$event_type = $this->item->params->get( 'event_type' );
	
	if ($this->item->params->get( 'event_events' )){ //add if band or moviename is set
		$bands = explode(',' , $this->item->params->get( 'event_events' ));
		$bands_together = $bands;
		
		foreach($bands as $band){
			$together_with = '';
			
			//build together with list
			if (count($bands)>1){
				foreach($bands_together as $band_together){
					if ($band != $band_together){
						$together_with .= trim($band_together) . ", ";
					}
				}
				$together_with = substr($together_with, 0, -2);
			}
			
			$band = $db->Quote(trim($band));
			$eventname = $db->Quote(trim($event));
			$together_with = $db->Quote($together_with);
			$query = "INSERT INTO #__hall_of_fame 
					  SET catid=$catid,
						  content_id=$content_id, 
						  date = '$event_date', 
						  type='$event_type', 
						  event=$eventname, 
						  bandname=$band, 
						  together_with=$together_with
					  ON DUPLICATE KEY UPDATE
						  catid=$catid,
						  type='$event_type', 
						  event=$eventname, 
						  bandname=$band, 
						  together_with=$together_with";
	//		echo $query;
			$db->setQuery($query);
			$db->query();
		}
	} else {
		//add other events without band-/moviename
		/*if ($this->item->params->get( 'event_typespecial' ) && $this->item->params->get( 'event_type' )=='Spezial'){
			$event_type .= ': '. $this->item->params->get( 'event_typespecial' );
		}*/
		$eventname = $db->Quote(trim($event));
		$together_with = $db->Quote($together_with);
		$query = "INSERT INTO #__hall_of_fame 
				  SET catid=$catid,
					  content_id=$content_id, 
					  date = '$event_date', 
					  type='$event_type', 
					  event=$eventname,
					  bandname='',
					  together_with=''
				  ON DUPLICATE KEY UPDATE
					  catid=$catid,
					  type='$event_type', 
					  event=$eventname";
		//echo $query;
		$db->setQuery($query);
		$db->query();
		
	}
}
?>
