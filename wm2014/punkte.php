<?php
$glb_kategorie = 'punkte';
$glb_seite = 'start';
$glb_menu = 'start';

$glb_no_infos = true;

include('lib/inc.init.php');

include('header.php');

?>
<h1><?=parse($lg_regeln)?></h1>

<?php
$search = array(
	'%GRUPPENPHASE%',
);
$replace = array(
	date($lg_format_datum_lang.' '.$lg_format_zeit_lang, strtotime($cfg_gruppenphase)),
);
?>
<?=parse($lg_regeln_text, $search, $replace)?><br/><br/><br/>

<h1><?=parse($lg_punktvergabe)?></h1>

<?=parse($lg_punktegleichheit)?><br/><br/><br/>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<colgroup>
		<col/>
		<col width="75"/>
	</colgroup>
	<tr>
		<td colspan="2"><b><?=$lg_punkte_einmalig?>:</b></td>
	</tr>
	<tr>
		<td colspan="2" height="3"></td>
	</tr>
	<tr>
		<td colspan="2" height="1" style="background-image: url(layout/img/ln/ln_rot.gif);"></td>
	</tr>
	<tr>
		<td colspan="2" height="3"></td>
	</tr>
	<?php
	foreach($lg_liste_punkte_einmalig as $punkte => $aktionen){
		foreach($aktionen as $index => $aktion){
			$str = ((double)$punkte==1) ? $lg_punkt : $lg_punkte;
			?>
			<tr>
				<td valign="top"><?=$aktion?></td>
				<td align="right" valign="top"><b><?=$punkte?> <?=$str?></b></td>
			</tr>
			<tr>
				<td colspan="2" height="2"></td>
			</tr>
			<tr>
				<td colspan="2" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="2" height="2"></td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td colspan="2" height="20"></td>
		</tr>
		<?php
	}
	?>
</table><br/>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<colgroup>
		<col/>
		<col width="75"/>
	</colgroup>
	<tr>
		<td colspan="2"><b><?=$lg_punkte_spiel?>:</b></td>
	</tr>
	<tr>
		<td colspan="2" height="3"></td>
	</tr>
	<tr>
		<td colspan="2" height="1" style="background-image: url(layout/img/ln/ln_rot.gif);"></td>
	</tr>
	<tr>
		<td colspan="2" height="3"></td>
	</tr>
	<?php
	foreach($lg_liste_punkte_pro_spiel as $punkte => $aktionen){
		foreach($aktionen as $index => $aktion){
			$str = ((double)$punkte==1) ? $lg_punkt : $lg_punkte;
			?>
			<tr>
				<td valign="top"><?=$aktion?></td>
				<td align="right" valign="top"><b><?=$punkte?> <?=$str?></b></td>
			</tr>
			<tr>
				<td colspan="2" height="2"></td>
			</tr>
			<tr>
				<td colspan="2" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="2" height="2"></td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td colspan="2" height="20"></td>
		</tr>
		<?php
	}
	?>
</table>
<?php

include('footer.php');
?>