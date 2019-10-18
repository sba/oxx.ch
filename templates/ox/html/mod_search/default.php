<?php
// @version $Id: default.php 10381 2008-06-01 03:35:53Z pasamio $
defined('_JEXEC') or die('Restricted access');
?>
<div class="box_container">
<div class="box_top"></div>
<div class="box_content">
<h2>Suchen</h2>
<form action="index.php"  method="post" class="search<?php echo $params->get('moduleclass_sfx'); ?>">
	<?php
	$text = '...';
	echo '<input name="searchword" id="mod_search_searchword" maxlength="20" alt="'.$button_text.'" class="inputbox'.$moduleclass_sfx.'" style="width: 144px; float: left;" type="text" size="'.$width.'" value="'.$text.'"  onblur="if(this.value==\'\') this.value=\''.$text.'\';" onfocus="if(this.value==\''.$text.'\') this.value=\'\';" />';
	?>
	<input type="image" src="templates/ox/images/pfeil.gif" value="suchen" class="button_pfeil"/>
	<input type="hidden" name="option" value="com_search" />
	<input type="hidden" name="task"   value="search" />
</form>
<br />
</div>

<div class="box_bottom"></div>
</div>
<br />