<?php // no direct access
// $gb = array(username, date, title, content)
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<div class="box_container">
<div class="box_top"></div>
<div class="box_content">
<h2>Gästebuch</h2>
<em>Am <?=$gb['date'];?> schrieb </em><br />
<b><?=$gb['username'];?></b><br />
<?=str_replace(array('<br>', '<br />', '<br/>'), ' ', $gb['entry']);?><br /><br />
<a href="senf.html">Zum G&auml;stebuch ...</a>
</div>
<div class="box_bottom"></div>
</div>
<br />