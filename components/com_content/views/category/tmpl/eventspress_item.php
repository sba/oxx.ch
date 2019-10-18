<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

?><td><?php
echo $this->item->params->get('event_type');

?></td><td><?php
$event_date=$this->item->params->get( 'event_date' ) ;
$event_date2=$this->item->params->get( 'event_date2' ) ;
$event_days=$this->item->params->get( 'event_days' ) ;
setlocale(LC_TIME, 'de_DE');
/*echo strftime('%a, %d.%m.%y', mktime(0,0,0,substr($event_date,5,2),substr($event_date,8,2),substr($event_date,0,4)));

if($this->item->params->get( 'event_date2' )){
    echo '<br />'. $this->item->params->get( 'event_date2' );
}*/
echo $event_date;
?></td><td><?php
if ($event_days==""){
	if($event_date2!=""){
		echo "2";
	} else {
		echo "1";
	}
} else {
	echo $event_days;
}
?></td><td><?php

/*if ($this->item->readmore) { ?>
		<a href="<?php echo $this->item->readmore_link; ?>" title="<?php
			if ($readmore = $this->item->params->get('readmore')) {
				echo $readmore;
			} else {
				echo JText::sprintf('Klicken f&uuml;r mehr Infos!');
			}?>"><?php echo $this->escape($this->item->title); ?></a>
<?php } else { 
*/
			echo $this->escape($this->item->title);
//	}

/*?></td><td><?php
    
if ($this->item->params->get('event_intro')){
	echo $this->item->params->get('event_intro') .'<br>'; 
}
?></td><td><?php

echo $this->item->text; 
*/?></td>


