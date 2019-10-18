<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

if(!isset($_GET['searchword'])){ //damit suchresultat nicht unter events angezeigt wird (auf homepage)
if(count($list)>0){
?>
	<div id="event_blog">
	<?php foreach ($list['full'] as $item) :  ?>
		<?php
		require_once 'phpthumb/createagendaimage.php';
		$agendaimage_png = createAgendaImage($item->image, $item->id);
		?>
			<?php if($item->readmore != 0) {?>
				<a href="<?php echo $item->link; ?>" class="frontevents">
			<?php }?>
				<div class="event_blog_item_container<?= ($item->readmore == 0)?'_noreadmore':'';?>">
				<div class="event_blog_graphic"><img src="<?=$agendaimage_png;?>" width="135" border="0" /></div>
					<div class="event_blog_text">
						<?php 
						if (strlen($item->event_intro)>0){?>
							<div class="event_blog_text_intro">
							<?php
							echo $item->event_intro .'<br>'; ?>
							</div>
						<?php
						}
						?>
						<span class="contentheading<?php echo $params->get('moduleclass_sfx'); ?>">
							<?php echo $item->text; ?>
						</span>
						<?php 
						$event_date=$item->date;
						?><div class="event_blog_text_datum"><?php
						$str_event_date = strftime('%A, %d.%m.%Y', mktime(0,0,0,substr($event_date,5,2),substr($event_date,8,2),substr($event_date,0,4)));
						$str_event_date = str_replace(array("Monday", "Tuesday", "Wednesday", "Thursday","Friday","Saturday","Sunday"), array("Montag", "Dienstag", "Mittwoch", "Donnerstag","Freitag","Samstag","Sonntag"), $str_event_date);
						echo $str_event_date;
						$event_date2=$item->date2;
						if($event_date2!=''){
							if (!(substr($event_date2,0,6)=='<br />' || substr($event_date2,0,5)=='<br/>' || substr($event_date2,0,4)=='<br>')){
								echo '<br />';
							}
							echo $event_date2;
						}
						?>
						<br />
						</div>
					 <?= $item->introtext;?>
					</div>
					<br clear="all" />
				</div>
			<?php if($item->readmore != 0) {?>
				</a>
			<?php }?>	
			<br clear="all" />
			<span class="article_separator">&nbsp;</span>
	<?php endforeach; ?>
	</div>

	<?php if (count($list['link'])>0){?>
		<div id="event_blog_linkitems_front">
		<ul class="frontevents">
		<?php foreach ($list['link'] as $item) :  ?>
				<li class="frontevents">
					<?php if($item->readmore != 0) {?>
						<?php $event_date2=$item->date2;
						if ($event_date2!=''){
							$event_date_ts = mktime(0,0,0,substr($event_date,5,2),substr($event_date,8,2),substr($event_date,0,4));
							$str_event_date = strftime('%A, %d.%m.%y', $event_date_ts);
							$str_event_date = str_replace(array("Monday", "Tuesday", "Wednesday", "Thursday","Friday","Saturday","Sunday"), array("Mo", "Di", "Mi", "Do","Fr","Sa","So"), $str_event_date);
							$title = str_event_date . ' - ' .$event_date2;
						} else {
							$title = '';
						}
						?>
						<a href="<?php echo $item->link; ?>" class="frontevents" title="<?php echo $event_date2?>">
					<?php }?>
					<strong>
					<?php 
						$event_date=$item->date;
						setlocale(LC_TIME, 'de_DE');
						$event_date_ts = mktime(0,0,0,substr($event_date,5,2),substr($event_date,8,2),substr($event_date,0,4));
						$str_event_date = strftime('%A, %d.%m.%y', $event_date_ts);
						$str_event_date = str_replace(array("Monday", "Tuesday", "Wednesday", "Thursday","Friday","Saturday","Sunday"), array("Mo", "Di", "Mi", "Do","Fr","Sa","So"), $str_event_date);
						echo $str_event_date;										
						echo ': ';
						echo $item->text; 
						?>
					</strong>
					<?php if($item->readmore != 0) {?>
						</a>
					<?php }?>
				</li>
		<?php endforeach; ?>
		</ul>
		</div>
	<?php } 
	}
}?>
