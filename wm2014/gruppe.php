<?php
$glb_kategorie = 'gruppe';
$glb_seite = 'start';
$glb_menu = 'start';

$glb_no_infos = true;

include('lib/inc.init.php');

include('header.php');

?>
<h1><?=parse($lg_gruppen)?></h1>

<?php
$gruppe = (isset($_GET['g']) && $_GET['g']!='') ? $_GET['g'] : '';
$check_gruppe = ($gruppe!='' && check_gruppe($gruppe));
$gruppe = ($check_gruppe) ? $gruppe : '';

$gruppen = get_land('', $gruppe, true);

if($gruppe!=''){
	?>
	<a href="gruppe.php"><?=$lg_zeige_gruppe_alle?> &raquo;</a><br/><br/><br/>
	<?php
}
?>
<b><?=$lg_zeichen_punkte?></b> <?=$lg_punkte?>&nbsp;&nbsp;
<b><?=$lg_zeichen_spiele?></b> <?=$lg_zeichen_spiele_info?>&nbsp;&nbsp;
<b><?=$lg_zeichen_siege?></b> <?=$lg_zeichen_siege_info?>&nbsp;&nbsp;
<b><?=$lg_zeichen_unentschieden?></b> <?=$lg_zeichen_unentschieden_info?>&nbsp;&nbsp;
<b><?=$lg_zeichen_niederlagen?></b> <?=$lg_zeichen_niederlagen_info?>&nbsp;&nbsp;
<b><?=$lg_zeichen_differenz?></b> <?=$lg_zeichen_differenz_info?><br/><br/><br/>

<table width="508" border="0" cellpadding="0" cellspacing="0">
	<colgroup>
		<col width="40"/>
		<col width="28"/>
		<col/>
		<col width="40"/>
		<col width="40"/>
		<col width="40"/>
		<col width="40"/>
		<col width="40"/>
		<col width="40"/>
	</colgroup>
	<?php
	$counter = 0;
	foreach($gruppen as $g => $laender){
		if($counter>0){
			?>
			<tr>
				<td colspan="9" height="25"></td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td colspan="3"><b><?=$lg_gruppe?> <?=$g?></b></td>
			<td align="right"><b><?=$lg_zeichen_punkte?></b></td>
			<td align="right"><b><?=$lg_zeichen_spiele?></b></td>
			<td align="right"><b><?=$lg_zeichen_siege?></b></td>
			<td align="right"><b><?=$lg_zeichen_unentschieden?></b></td>
			<td align="right"><b><?=$lg_zeichen_niederlagen?></b></td>
			<td align="right"><b><?=$lg_zeichen_differenz?></b></td>
		</tr>
		<tr>
			<td colspan="9" height="5"></td>
		</tr>
		<tr>
			<td colspan="9" height="1" style="background-image: url(layout/img/ln/ln_rot.gif);"></td>
		</tr>
		<tr>
			<td colspan="9" height="5"></td>
		</tr>
		<?php

		foreach($laender as $index => $land){
			$rang = ($index + 1);
			?>
			<tr>
				<td><b><?=$rang?>.</b></td>
				<?php
				if(file_exists('layout/img/flags/'.$land['land_code'].'.gif')){
					?>
					<td align="center"><img src="layout/img/flags/<?=$land['land_code']?>.gif" alt="<?=$land['land_name']?>" title="<?=$land['land_name']?>"/></td>
					<td><?=$land['land_name']?></td>
					<?php
				} else {
					?>
					<td colspan="2"><?=$land['land_name']?></td>
					<?php
				}
				?>
				<td align="right"><b><?=$land['land_punkte']?></b></td>
				<td align="right"><?=$land['land_gespielt']?></td>
				<td align="right"><?=$land['land_siege']?></td>
				<td align="right"><?=$land['land_unentschieden']?></td>
				<td align="right"><?=$land['land_niederlagen']?></td>
				<td align="right"><?=$land['land_differenz']?></td>
			</tr>
			<tr>
				<td colspan="9" height="3"></td>
			</tr>
			<tr>
				<td colspan="9" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="9" height="3"></td>
			</tr>
			<?php
		}

		?>
		<tr>
			<td colspan="9" height="5"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="8">
				<?php
				$search = array('%GRUPPE%');
				$replace = array($g);
				?>
				<a href="spielplan.php?g=<?=$g?>"><?=parse($lg_zeige_spiele_gruppe, $search, $replace)?> &raquo;</a>
			</td>
		</tr>
		<?php

		$counter++;
	}
	?>
</table>
<?php

include('footer.php');
?>