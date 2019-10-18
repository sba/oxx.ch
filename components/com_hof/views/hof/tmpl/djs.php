<script type="text/javascript" src="/media/system/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
jQuery.noConflict();

jQuery(document).ready(function(){
	jQuery(".hof_group").click(function(){
		/*Alle zusammen animieren: jQuery(".hof_event", this).animate({width: "toggle"}, "slow");*/
		var $events = jQuery(".hof_event", this);
		var currentEvent = 0;
		function nextEvent(){
			  $events.eq(currentEvent).animate({width: "toggle"}, "fast", nextEvent); /*show anstatt toggle*/
			  ++currentEvent;
		}
		nextEvent();
	});    
});
</script>

<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<h1>Hall of Fame  - DJ's</h1>
<br />
<div style="display:block">
<?php echo $this->djs; ?>
</div>