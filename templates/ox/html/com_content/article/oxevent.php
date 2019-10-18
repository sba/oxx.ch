<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$canEdit	= ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
?>

<?php if ($this->params->get('event_intro')) { ?>
		<div class="event_blog_text_intro"> 
		<?php
		echo  $this->params->get('event_intro') .'<br>'; ?>
		</div> 
		<?php
}?>


    <div style="float:left">
	    <h2>
	    <?php echo $this->escape($this->article->title); ?>
	    </h2>
    </div>

    
<div style="float:right">
	<?php if ( $this->params->get( 'show_print_icon' )) : ?>
	<?php echo JHTML::_('icon.print_popup',  $this->article, $this->params, $this->access); ?>
	<?php endif; ?>
	
	<?php if ($canEdit) : ?>
		<?php echo JHTML::_('icon.edit', $this->article, $this->params, $this->access); ?>
	<?php endif; ?>
</div>
<br clear="all">


<?php  if (!$this->params->get('show_intro')) :
	echo $this->article->event->afterDisplayTitle;
endif; ?>
<?php echo $this->article->event->beforeDisplayContent; ?>

<div class="event_blog_text_datum">
	<?php
	$event_date=$this->params->get('event_date') ."yy";
$str_event_date = strftime('%A, %d.%m.%Y', mktime(0,0,0,substr($event_date,5,2),substr($event_date,8,2),substr($event_date,0,4)));
	$str_event_date = str_replace(array("Monday", "Tuesday", "Wednesday", "Thursday","Friday","Saturday","Sunday"), array("Montag", "Dienstag", "Mittwoch", "Donnerstag","Freitag","Samstag","Sonntag"), $str_event_date);
	echo $str_event_date .'<br />';	
		
	global $mainframe;
	$document =& JFactory::getDocument();
	$document->setTitle( $document->getTitle() . ' - ' . strftime('%d.%m.%Y', mktime(0,0,0,substr($event_date,5,2),substr($event_date,8,2),substr($event_date,0,4))));


	
	if($this->params->get( 'event_date2' )){
	echo $this->params->get( 'event_date2' ) .'<br />';
	
}
	
	?>
    
    </div>


<?php if ($this->params->get('event_doors')){
	echo $this->params->get('event_doors') . '<br>';
}
?>

<br>

<?php /*if (isset ($this->article->toc)) : ?>
	<?php echo $this->article->toc; ?>
<?php endif; */?>


<?php //echo $this->article->text; ?>
<?php echo $this->article->introtext; ?>
<br />
<?php echo $this->article->fulltext; ?>


<?php
//display facebook likebutton
$url = str_replace( '//', '/', JURI::base().$_SERVER['REQUEST_URI']);        
$url = str_replace( 'http:/', 'http://', $url);
$url = urlencode($url);

$button = '<iframe src="http://www.facebook.com/plugins/like.php?href='.$url.'&amp;layout=button_count&amp;show_faces=false&amp;width=150&amp;action=like&amp;font=verdana&amp;colorscheme=dark&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:21px;" allowTransparency="true"></iframe>';    

if(preg_match('/<br \/>$/', $this->article->fulltext, $array)==0 ){?>
<br />
<br />
<?php } ?>
<?=$button?>
<br />


<?php if ($this->params->get('event_petziid') || $this->params->get('event_galleryid') || ($this->params->get('event_bandwww1') && $this->params->get('event_bandwww1') != 'http://') || ($this->params->get('event_bandwww2') && $this->params->get('event_bandwww2') != 'http://') || ($this->params->get('event_bandwww3') && $this->params->get('event_bandwww3') != 'http://') || ($this->params->get('event_bandwww4') && $this->params->get('event_bandwww4') != 'http://')){?>
	<div class="event_linkbox">
	<?php if ($this->params->get('event_petziid') && $this->params->get('event_date') >= date('Y-m-d')){?>
			<a class="event_linkbox_link" href="http://tickets.petzi.ch/detail_evenement.php?new_lang=de&id_evenement=<?=$this->params->get('event_petziid');?>" title="Vorverkauf m&ouml;glich - Jetzt Ticket bei Petzi kaufen!">VVK Petzi</a> 
	<?php } ?>
	<?php for ($i = 1; $i <= 4; $i++) {
			if ($this->params->get('event_bandwww'.$i) && $this->params->get('event_bandwww'.$i) != 'http://'){ 
				$bandwww = explode(',',$this->params->get('event_bandwww'.$i));
				if (count($bandwww)==1){
					// den Hostnamen aus URL holen
					preg_match('@^(?:http://)?([^/]+)@i',$bandwww[0], $treffer);
					$host = $treffer[1];

					// die letzten beiden Segmente aus Hostnamen holen
					preg_match('/[^.]+\.[^.]+$/', $host, $treffer);
					$bandwww[1] = $treffer[0];
				}
				?>
				<a class="event_linkbox_link" href="<?=$bandwww[0];?>" title="<?=$bandwww[0];?>"><?=$bandwww[1];?></a> 
			<?php }
		  } ?>
	<?php if ($this->params->get('event_galleryid')){?>
			<a class="event_linkbox_link" href="index.php?option=com_phocagallery&view=category&Itemid=53&id=<?=$this->params->get('event_galleryid');?>" title="Photo-Galerie anzeigen">Galerie</a> 
	<?php } ?>
	</div>
<?php } ?>

<span class="article_separator">&nbsp;</span>
<?php echo $this->article->event->afterDisplayContent; ?>
