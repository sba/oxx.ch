<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$canEdit	= ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
?>
<?php if ($this->params->get('show_page_title', 1) && $this->params->get('page_title') != $this->article->title) : ?>
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
<?php endif; ?>
<?php if ($canEdit || $this->params->get('show_title') || $this->params->get('show_pdf_icon') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon')) : ?>
<table class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<tr>
	<?php if ($this->params->get('event_intro')) { 
		$intotext =  $this->params->get('event_intro') .'<br>';
	} else {
		$intotext =  '';
	}?>

	<?php if ($this->params->get('show_title')) : ?>
	<td class="contentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>" width="100%">
	 <?php echo $intotext ?>
		<?php if ($this->params->get('link_titles') && $this->article->readmore_link != '') : ?>
		<a href="<?php echo $this->article->readmore_link; ?>" class="contentpagetitle<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
			<?php echo $this->escape($this->article->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->article->title); ?>
		<?php endif; ?>
	</td>
	<?php endif; ?>
	<?php if (!$this->print) : ?>
		<?php /*if ($this->params->get('show_pdf_icon')) : ?>
		<td align="right" width="100%" class="buttonheading">
		<?php echo JHTML::_('icon.pdf',  $this->article, $this->params, $this->access); ?>
		</td>
		<?php endif; */?>

		<?php if ( $this->params->get( 'show_print_icon' )) : ?>
		<td align="right" width="100%" class="buttonheading">
		<?php echo JHTML::_('icon.print_popup',  $this->article, $this->params, $this->access); ?>
		</td>
		<?php endif; ?>

		<?php /*if ($this->params->get('show_email_icon')) : ?>
		<td align="right" width="100%" class="buttonheading">
		<?php echo JHTML::_('icon.email',  $this->article, $this->params, $this->access); ?>
		</td>
		<?php endif; */?>
		<?php if ($canEdit) : ?>
		<td align="right" width="100%" class="buttonheading">
			<?php echo JHTML::_('icon.edit', $this->article, $this->params, $this->access); ?>
		</td>
		<?php endif; ?>
	<?php else : ?>
		<td align="right" width="100%" class="buttonheading">
		<?php echo JHTML::_('icon.print_screen',  $this->article, $this->params, $this->access); ?>
		</td>
	<?php endif; ?>
</tr>
</table>
<?php endif; ?>

<?php  if (!$this->params->get('show_intro')) :
	echo $this->article->event->afterDisplayTitle;
endif; ?>
<?php echo $this->article->event->beforeDisplayContent; ?>

<div class="event_preview_datum">
	<?php
	$event_date=$this->params->get('event_date') ."yy";
	setlocale(LC_TIME, 'de_DE');
	echo strftime('%A, %d.%m.%Y', mktime(0,0,0,substr($event_date,5,2),substr($event_date,8,2),substr($event_date,0,4))) . '<br>'; ?>
</div>

<?php if ($this->params->get('event_doors')){
	echo $this->params->get('event_doors') . '<br>';
}
?>

<table class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php if (($this->params->get('show_section') && $this->article->sectionid) || ($this->params->get('show_category') && $this->article->catid)) : ?>
<tr>
	<td>
		<?php /*if ($this->params->get('show_section') && $this->article->sectionid && isset($this->article->section)) : ?>
		<span>
			<?php if ($this->params->get('link_section')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->article->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->article->section; ?>
			<?php if ($this->params->get('link_section')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
				<?php if ($this->params->get('show_category')) : ?>
				<?php echo ' - '; ?>
			<?php endif; ?>
		</span>
		<?php endif; */?>
		<?php /*if ($this->params->get('show_category') && $this->article->catid) : ?>
		<span>
			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->article->catslug, $this->article->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->article->category; ?>
			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
		</span>
		<?php endif; */?>
	</td>
</tr>
<?php endif; ?>
<?php /*if (($this->params->get('show_author')) && ($this->article->author != "")) : ?>
<tr>
	<td valign="top">
		<span class="small">
			<?php JText::printf( 'Written by', ($this->article->created_by_alias ? $this->article->created_by_alias : $this->article->author) ); ?>
		</span>
		&nbsp;&nbsp;
	</td>
</tr>
<?php endif; */?>

<?php /*if ($this->params->get('show_create_date')) : ?>
<tr>
	<td valign="top" class="createdate">
		<?php echo JHTML::_('date', $this->article->created, JText::_('DATE_FORMAT_LC2')) ?>
	</td>
</tr>
<?php endif; */?>

<?php /*if ($this->params->get('show_url') && $this->article->urls) : ?>
<tr>
	<td valign="top">
		<a href="http://<?php echo $this->article->urls ; ?>" target="_blank">
			<?php echo $this->article->urls; ?></a>
	</td>
</tr>
<?php endif; */?>

<tr>
<td valign="top">
<?php if (isset ($this->article->toc)) : ?>
	<?php echo $this->article->toc; ?>
<?php endif; ?>
<?php echo $this->article->text; ?>
</td>
</tr>

<?php /*if ( intval($this->article->modified) !=0 && $this->params->get('show_modify_date')) : ?>
<tr>
	<td class="modifydate">
		<?php echo JText::_( 'Last Updated' ); ?> ( <?php echo JHTML::_('date', $this->article->modified, JText::_('DATE_FORMAT_LC2')); ?> )
	</td>
</tr>
<?php endif; */?>
</table>

<?php if ($this->params->get('event_petziid') || $this->params->get('event_galleryid') || ($this->params->get('event_bandwww1') && $this->params->get('event_bandwww1') != 'http://') || ($this->params->get('event_bandwww2') && $this->params->get('event_bandwww2') != 'http://') || ($this->params->get('event_bandwww3') && $this->params->get('event_bandwww3') != 'http://') || ($this->params->get('event_bandwww4') && $this->params->get('event_bandwww4') != 'http://')){?>
	<div class="event_linkbox">
	<?php if ($this->params->get('event_petziid')){?>
			<a class="event_linkbox_link" href="http://tickets.petzi.ch/detail_evenement.php?new_lang=de&id_evenement=<?=$this->params->get('event_petziid');?>" title="Vorverkauf m&ouml;glich - Ticket  bei Petzi kaufen">VVK Petzi</a> 
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
				<a class="event_linkbox_link" href="<?=$bandwww[0];?>"><?=$bandwww[1];?></a> 
			<?php }
		  } ?>
	<?php if ($this->params->get('event_galleryid')){?>
			<a class="event_linkbox_link" href="index.php?option=com_phocagallery&view=category&Itemid=53&id=<?=$this->params->get('event_galleryid');?>">Galerie</a> 
	<?php } ?>
	</div>
<?php } ?>

<span class="article_separator">&nbsp;</span>
<?php echo $this->article->event->afterDisplayContent; ?>
