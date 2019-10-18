<?php
$glb_kategorie = 'spielplan';
$glb_seite = 'start';
$glb_menu = 'start';

$glb_no_infos = true;

include('lib/inc.init.php');

include('header.php');

?>
<script type="text/javascript">
	$(document).ready(function(){
		if($('#next_spieltag').html()!=null && $('#next_spieltag').html()!=''){
			$.scrollTo('#spieltag_'+$('#next_spieltag').html(), 1000);
		}
		
		$('#zeitzoneWechsler').click(function(){
			$('#zeitzoneUser').toggle();
			$('#zeitzoneLokal').toggle();
			$('.zeitUser').toggle();
			$('.zeitLokal').toggle();
		});
	});
</script>

<h1><?=parse($lg_spielplan)?></h1>

<?php
if($cfg_mehrere_zeitzonen){
	?>
	<span id="zeitzoneUser"><?=parse($lg_spielzeit_zeitzone_user)?></span>
	<span id="zeitzoneLokal" style="display: none;"><?=parse($lg_spielzeit_zeitzone_lokal)?></span>
	(<a id="zeitzoneWechsler" href="javascript: void(0);" style="font-weight: normal;"><?=parse($lg_wechseln)?></a>)<br/><br/><br/>
	<?php
}

$gruppe = (isset($_GET['g']) && $_GET['g']!='') ? $_GET['g'] : '';
$check_gruppe = ($gruppe!='' && check_gruppe($gruppe));
$gruppe = ($check_gruppe) ? $gruppe : '';

$land_code = (isset($_GET['t']) && $_GET['t']!='') ? $_GET['t'] : '';
$check_land_code = ($land_code!='' && check_land_code($land_code));
$land_code = ($check_land_code) ? $land_code : '';

$spiele = get_spiel($gruppe, $land_code);

if($gruppe!='' || $land_code!=''){
	?>
	<a href="spielplan.php"><?=$lg_zeige_spiele_alle?> &raquo;</a><br/><br/>
	<?php
	if($gruppe!=''){
		$search = array('%GRUPPE%');
		$replace = array($gruppe);
		?>
		<a href="gruppe.php?g=<?=$gruppe?>"><?=parse($lg_zeige_gruppe, $search, $replace)?> &raquo;</a><br/><br/>
		<?php
	}
	?>
	<br/>
	<?php
}
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<colgroup>
		<?php
		if($glb_admin){
			?>
			<col width="30"/>
			<?php
		}
		?>
		<col width="30"/>
		<col width="110"/>
		<col width="45"/>
		<col width="160"/>
		<col width="28"/>
		<col width="15"/>
		<col width="28"/>
		<col/>
	</colgroup>
	<?php
	$next_spieltag = '';

	$colspan = ($glb_admin) ? 9 : 8;
	$colspan2 = ($glb_admin) ? 5 : 4;
	$colspan3 = ($glb_admin) ? 4 : 3;
	$colspan4 = ($glb_admin) ? 7 : 6;
	$last_datum = '';
	foreach($spiele as $index => $spiel){
		$zeit_string = date($lg_format_zeit_kurz, strtotime($spiel['spiel_datum']));
		$stadion_zeitzone_differenz = (!is_null($spiel['stadion_zeitzone_differenz'])) ? (int)$spiel['stadion_zeitzone_differenz'] : 0;
		$zeit_lokal_string = date($lg_format_zeit_kurz, strtotime($stadion_zeitzone_differenz+' '.(($stadion_zeitzone_differenz==1 || $stadion_zeitzone_differenz==-1) ? 'hour' : 'hours'), strtotime($spiel['spiel_datum'])));

		$datum = date('Y-m-d', strtotime($spiel['spiel_datum']));

		if(strtotime($datum)<=time()){
			$next_spieltag = $datum;
		}

		if($last_datum!=$datum){
			$last_datum = $datum;

			$str = parse(date($lg_format_datum_lang, strtotime($datum)));
			if($index>0){
				?>
				<tr>
					<td colspan="<?=$colspan?>" height="25"></td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td colspan="<?=$colspan?>">
					<div id="spieltag_<?=$datum?>"></div>
					<b><?=$str?></b>
				</td>
			</tr>
			<tr>
				<td colspan="<?=$colspan?>" height="3"></td>
			</tr>
			<tr>
				<td colspan="<?=$colspan?>" height="1" style="background-image: url(layout/img/ln/ln_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="<?=$colspan?>" height="3"></td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td colspan="2">&nbsp;</td>
			<td colspan="<?=$colspan4?>" class="stadion"><b><?=$spiel['stadion_ort']?></b> - <?=$spiel['stadion_name']?></td>
		</tr>
		<tr>
			<td colspan="<?=$colspan?>" height="5"></td>
		</tr>
		<tr>
			<?php
			if($glb_admin){
				?>
				<td><a href="spiel.php?s=<?=$spiel['spiel_id']?>"><img src="layout/img/edit.gif" width="13" height="16" border="0" alt=""/></a></td>
				<?php
			}
			?>
			<td><?=$spiel['spiel_id']?></td>
			<td>
				<?php
				if($spiel['spiel_typ']=='G'){
					?>
					<a href="spielplan.php?g=<?=$spiel['spiel_gruppe']?>"><?=$lg_gruppe_kurz?> <?=$spiel['spiel_gruppe']?></a>
					<?php
				} else {
					?>
					<?=$lg_liste_final[$spiel['spiel_typ']]?>
					<?php
				}
				?>
			</td>
			<td>
				<span class="zeitUser"><?=$zeit_string?></span>
				<span class="zeitLokal" style="display: none;"><?=$zeit_lokal_string?></span>
			</td>
			<?php
			if(file_exists('layout/img/flags/'.$spiel['spiel_home_land_code'].'.gif')){
				?>
				<td align="right"><a href="spielplan.php?t=<?=$spiel['spiel_home_land_code']?>"><?=$spiel['spiel_home_land_name']?></a></td>
				<td align="right">
					<img src="layout/img/flags/<?=$spiel['spiel_home_land_code']?>.gif" alt="<?=$spiel['spiel_home_land_name']?>" title="<?=$spiel['spiel_home_land_name']?>"/>
				</td>
				<?php
			} else {
				?>
				<td colspan="2" align="right"><?=$spiel['spiel_home_land_name']?></td>
				<?php
			}
			?>
			<td align="center">-</td>
			<?php
			if(file_exists('layout/img/flags/'.$spiel['spiel_away_land_code'].'.gif')){
				?>
				<td>
					<img src="layout/img/flags/<?=$spiel['spiel_away_land_code']?>.gif" alt="<?=$spiel['spiel_away_land_name']?>" title="<?=$spiel['spiel_away_land_name']?>"/>
				</td>
				<td><a href="spielplan.php?t=<?=$spiel['spiel_away_land_code']?>"><?=$spiel['spiel_away_land_name']?></a></td>
				<?php
			} else {
				?>
				<td colspan="2"><?=$spiel['spiel_away_land_name']?></td>
				<?php
			}
			?>
		</tr>
		<?php
		if($spiel['spiel_home_res']>=0 && $spiel['spiel_away_res']>=0){
			$string_tore = '';

			foreach($spiel['tore'] as $index => $tor){
				if($tor['tor_ext']==0){
					if($tor['tor_own']==0 && isset($spiel['tore'][($index + 1)]) && $spiel['tore'][($index + 1)]['spieler_id']==$tor['spieler_id'] && $spiel['tore'][($index + 1)]['tor_ext']==0 && $spiel['tore'][($index + 1)]['tor_own']==0){
						$string_tore .= (($string_tore!='') ? ', ' : '').$tor['tor_minute'].$lg_zeichen_minute.(($tor['tor_penalty']==1) ? ' ('.$lg_zeichen_penalty.')' : '');
					} else {
						$land_code = ($tor['tor_own']==0) ? $tor['spieler_land_code'] : (($spiel['spiel_home_land_code']==$tor['spieler_land_code']) ? $spiel['spiel_away_land_code'] : $spiel['spiel_home_land_code']);
						$string_tore .= (($string_tore!='') ? ', ' : '').$tor['tor_minute'].$lg_zeichen_minute.' '.(($tor['tor_penalty']==1) ? ' ('.$lg_zeichen_penalty.') ' : (($tor['tor_own']==1) ? ' ('.$lg_zeichen_own.') ' : '')).$tor['spieler_name'].' ('.$land_code.')';
					}
				}
			}
			$string_tore = ($string_tore=='') ? '&nbsp;' : $string_tore;
			?>
			<tr>
				<td colspan="<?=$colspan?>" height="5"></td>
			</tr>
			<tr>
				<td colspan="<?=$colspan2?>">&nbsp;</td>
				<td align="right" valign="top"><b><?=$spiel['spiel_home_res']?></b></td>
				<td align="center" valign="top">-</td>
				<td valign="top"><b><?=$spiel['spiel_away_res']?></b></td>
				<td valign="top"><?=$string_tore?></td>
			</tr>
			<?php
		}
		if($spiel['spiel_home_ext']>=0 && $spiel['spiel_away_ext']>=0){
			$string_tore = '';
			foreach($spiel['tore'] as $index => $tor){
				if($tor['tor_ext']==1){
					if($tor['tor_own']==0 && isset($spiel['tore'][($index + 1)]) && $spiel['tore'][($index + 1)]['spieler_id']==$tor['spieler_id'] && $spiel['tore'][($index + 1)]['tor_ext']==1 && $spiel['tore'][($index + 1)]['tor_own']==0){
						$string_tore .= (($string_tore!='') ? ', ' : '').$tor['tor_minute'].$lg_zeichen_minute.(($tor['tor_penalty']==1) ? ' ('.$lg_zeichen_penalty.')' : '');
					} else {
						$land_code = ($tor['tor_own']==0) ? $tor['spieler_land_code'] : (($spiel['spiel_home_land_code']==$tor['spieler_land_code']) ? $spiel['spiel_away_land_code'] : $spiel['spiel_home_land_code']);
						$string_tore .= (($string_tore!='') ? ', ' : '').$tor['tor_minute'].$lg_zeichen_minute.' '.(($tor['tor_penalty']==1) ? ' ('.$lg_zeichen_penalty.') ' : (($tor['tor_own']==1) ? ' ('.$lg_zeichen_own.') ' : '')).$tor['spieler_name'].' ('.$land_code.')';
					}
				}
			}
			$string_tore = ($string_tore=='') ? '&nbsp;' : ', '.$string_tore;
			?>
			<tr>
				<td colspan="<?=$colspan?>" height="5"></td>
			</tr>
			<tr>
				<td colspan="<?=$colspan2?>">&nbsp;</td>
				<td align="right" valign="top"><b><?=$spiel['spiel_home_ext']?></b></td>
				<td align="center" valign="top">-</td>
				<td valign="top"><b><?=$spiel['spiel_away_ext']?></b></td>
				<td valign="top"><i><?=$lg_nach_ext_kurz?></i><?=$string_tore?></td>
			</tr>
			<?php
		}
		if($spiel['spiel_home_penalty']>=0 && $spiel['spiel_away_penalty']>=0){
			?>
			<tr>
				<td colspan="<?=$colspan?>" height="5"></td>
			</tr>
			<tr>
				<td colspan="<?=$colspan2?>">&nbsp;</td>
				<td align="right"><b><?=$spiel['spiel_home_penalty']?></b></td>
				<td align="center">-</td>
				<td><b><?=$spiel['spiel_away_penalty']?></b></td>
				<td><i><?=$lg_nach_penalty_kurz?></i></td>
			</tr>
			<?php
		}
		/*
		if($spiel['spiel_gelb']>=0){
			?>
			<tr>
				<td colspan="<?=$colspan?>" height="5"></td>
			</tr>
			<tr>
				<td colspan="<?=$colspan3?>">&nbsp;</td>
				<td colspan="2" align="right" valign="middle"><b><?=$lg_gelbe_karten?>:</b></td>
				<td align="center" valign="middle">&nbsp;</td>
				<td colspan="2"><b><?=$spiel['spiel_gelb']?></b></td>
			</tr>
			<?php
		}
		if($spiel['spiel_rot']>=0){
			?>
			<tr>
				<td colspan="<?=$colspan?>" height="5"></td>
			</tr>
			<tr>
				<td colspan="<?=$colspan3?>">&nbsp;</td>
				<td colspan="2" align="right" valign="middle"><b><?=$lg_rote_karten?>:</b></td>
				<td align="center" valign="middle">&nbsp;</td>
				<td colspan="2"><b><?=$spiel['spiel_rot']?></b></td>
			</tr>
			<?php
		}*/
		?>
		<tr>
			<td colspan="<?=$colspan?>" height="5"></td>
		</tr>
		<tr>
			<td colspan="<?=$colspan?>" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
		</tr>
		<tr>
			<td colspan="<?=$colspan?>" height="5"></td>
		</tr>
		<?php
	}
	?>
</table>

<!--<span id="next_spieltag"><?=$next_spieltag?></span>-->
<?php

include('footer.php');
?>