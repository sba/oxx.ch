<?php
$glb_kategorie = 'tip';
$glb_seite = 'start';
$glb_menu = 'start';

include('lib/inc.init.php');

$benutzer_id = (isset($_GET['b']) && intval($_GET['b'])>0) ? $_GET['b'] : ((isset($_SESSION[$cfg_session]['login']['benutzer_id'])) ? $_SESSION[$cfg_session]['login']['benutzer_id'] : -1);

$benutzer = $cls_benutzer->get($benutzer_id);
if(count($benutzer)==0){
	header('Location: rangliste.php');
	exit();
}

$visiting = (!$glb_eingeloggt || $benutzer_id!=$_SESSION[$cfg_session]['login']['benutzer_id']);

if($visiting){
	$glb_kategorie = 'rangliste';
} else {
	if($cfg_einsatz['on'] && $_SESSION[$cfg_session]['login']['benutzer_einsatz']<$cfg_einsatz['betrag']){
		header('Location: rangliste.php');
		exit();
	}
}

$reload_tips = (!$visiting);
include('header.php');

$spiele = get_spiel();
$laender = get_land('', '', false, false, false);
$laender_assoc = array();
foreach($laender as $land){
	$laender_assoc[$land['land_code']] = $land;
}

?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.punkte').bt({
			contentSelector: '$(\'#div_punkte_tooltip_\'+$(this).attr(\'spielId\')).html()',
			fill: '#D81313',
			strokeWidth: 2,
			strokeStyle: '#5FA98E',
			cssStyles: {
				color: '#FFFFFF'
			},
			shrinkToFit: true,
			padding: 10,
			width: 500,
			cornerRadius: 10,
			spikeLength: 15,
			spikeGirth: 10,
			positions: ['left', 'right', 'bottom']
		});

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
<?php
if($cfg_mehrere_zeitzonen){
	?>
	<span id="zeitzoneUser"><?=parse($lg_spielzeit_zeitzone_user)?></span>
	<span id="zeitzoneLokal" style="display: none;"><?=parse($lg_spielzeit_zeitzone_lokal)?></span>
	(<a id="zeitzoneWechsler" href="javascript: void(0);" style="font-weight: normal;"><?=parse($lg_wechseln)?></a>)<br/><br/><br/>
	<?php
}

if(!$visiting){
	?>
	<script type="text/javascript">
		var arr_buttons = new Array();
		<?php
		$last_datum = '';
		$to_write = array();
		foreach($spiele as $index => $spiel){
			$land_home = (isset($laender_assoc[$spiel['spiel_home_land_code']])) ? $laender_assoc[$spiel['spiel_home_land_code']] : array();
			$land_away = (isset($laender_assoc[$spiel['spiel_away_land_code']])) ? $laender_assoc[$spiel['spiel_away_land_code']] : array();

			if(!isset($land_home['land_id']) || !isset($land_away['land_id']) || ($visiting && time()<strtotime($spiel['spiel_datum']))){
				continue;
			}

			$datum = date('Y-m-d', strtotime($spiel['spiel_datum']));
			if($last_datum!=$datum){
				$last_datum = $datum;

				$to_write[] = $index;
			}
		}
		foreach($to_write as $index => $val){
			?>
			arr_buttons[<?=$index?>] = <?=$val?>;
			<?php
		}
		?>

		function save(index){
			do_buttons(false, 0);

			last_button = index;

			var benutzer_tip_sieger_land_code = -1;
			var benutzer_tip_scorer_spieler_id = -1;
			var benutzer_tip_meiste_tore_land_code = -1;
			var benutzer_tip_meiste_gegentore_land_code = -1;
			var benutzer_tip_wenigste_tore_land_code = -1;
			var benutzer_tip_wenigste_gegentore_land_code = -1;
			if(document.getElementById('benutzer_tip_sieger_land_code') && !document.getElementById('benutzer_tip_sieger_land_code').disabled){
				benutzer_tip_sieger_land_code = document.getElementById('benutzer_tip_sieger_land_code').value;
				benutzer_tip_scorer_spieler_id = document.getElementById('benutzer_tip_scorer_spieler_id').value;
				benutzer_tip_meiste_tore_land_code = document.getElementById('benutzer_tip_meiste_tore_land_code').value;
				benutzer_tip_meiste_gegentore_land_code = document.getElementById('benutzer_tip_meiste_gegentore_land_code').value;
				benutzer_tip_wenigste_tore_land_code = document.getElementById('benutzer_tip_wenigste_tore_land_code').value;
				benutzer_tip_wenigste_gegentore_land_code = document.getElementById('benutzer_tip_wenigste_gegentore_land_code').value;
			}

			var tip_aktiv = document.getElementsByName('tip_aktiv[]');
			var tip_spiele = document.getElementsByName('tip_spiel[]');
			var tip_home_res = document.getElementsByName('tip_home_res[]');
			var tip_away_res = document.getElementsByName('tip_away_res[]');
			var tip_home_ext = document.getElementsByName('tip_home_ext[]');
			var tip_away_ext = document.getElementsByName('tip_away_ext[]');
			var tip_home_penalty = document.getElementsByName('tip_home_penalty[]');
			var tip_away_penalty = document.getElementsByName('tip_away_penalty[]');
			//var tip_gelb = document.getElementsByName('tip_gelb[]');
			//var tip_rot = document.getElementsByName('tip_rot[]');
			var tip_gelb = null;
			var tip_rot = null;
			

			var str_tip_spiele = '';
			var str_tip_home_res = '';
			var str_tip_away_res = '';
			var str_tip_home_ext = '';
			var str_tip_away_ext = '';
			var str_tip_home_penalty = '';
			var str_tip_away_penalty = '';
			var str_tip_gelb = '';
			var str_tip_rot = '';

			var counter = 0;
			for(var a=0; a<tip_spiele.length; a++){
				if(tip_aktiv[a].value==1){
					str_tip_spiele += ((counter>0) ? ',' : '')+tip_spiele[a].value;
					str_tip_home_res += ((counter>0) ? ',' : '')+tip_home_res[a].value;
					str_tip_away_res += ((counter>0) ? ',' : '')+tip_away_res[a].value;
					str_tip_home_ext += ((counter>0) ? ',' : '')+tip_home_ext[a].value;
					str_tip_away_ext += ((counter>0) ? ',' : '')+tip_away_ext[a].value;
					str_tip_home_penalty += ((counter>0) ? ',' : '')+tip_home_penalty[a].value;
					str_tip_away_penalty += ((counter>0) ? ',' : '')+tip_away_penalty[a].value;
					//str_tip_gelb += ((counter>0) ? ',' : '')+tip_gelb[a].value;
					//str_tip_rot += ((counter>0) ? ',' : '')+tip_rot[a].value;

					counter++;
				}
			}

			ajax_save_tips(<?=$_SESSION[$cfg_session]['login']['benutzer_id']?>, benutzer_tip_sieger_land_code, benutzer_tip_scorer_spieler_id, benutzer_tip_meiste_tore_land_code, benutzer_tip_meiste_gegentore_land_code, benutzer_tip_wenigste_tore_land_code, benutzer_tip_wenigste_gegentore_land_code, str_tip_spiele, str_tip_home_res, str_tip_away_res, str_tip_home_ext, str_tip_away_ext, str_tip_home_penalty, str_tip_away_penalty, str_tip_gelb, str_tip_rot);
		}

		var last_button = -1;

		function do_buttons(on, res){
			var display_buttons = (on) ? '' : 'none';
			var display_buttons_warten = (on) ? 'none' : '';

			for(var a=0; a<arr_buttons.length; a++){
				document.getElementById('div_button_'+arr_buttons[a]).style.display = display_buttons;
				document.getElementById('div_button_warten_'+arr_buttons[a]).style.display = display_buttons_warten;

				if(!on){
					document.getElementById('div_save_ok_'+arr_buttons[a]).style.display = 'none';
					document.getElementById('div_save_error_'+arr_buttons[a]).style.display = 'none';
				}
			}

			if(on){
				var div = (res==0) ? 'error' : 'ok';
				if(document.getElementById('div_save_'+div+'_'+last_button)){
					document.getElementById('div_save_'+div+'_'+last_button).style.display = '';
				}
			}
		}

		var start = <?=time()?>;
		var limite = new Array();
		var limite_gruppenphase = <?=strtotime($cfg_gruppenphase)?>;
		<?php
		$last = 0;
		$counter = 0;
		foreach($spiele as $spiel){
			$datum = strtotime($cfg_limit_spiel, strtotime($spiel['spiel_datum']));

			if($datum!=$last){
				$counter = 0;
				$last = $datum;
				?>
				limite[<?=$datum?>] = new Array();
				<?php
			} else {
				$counter++;
			}
			?>
			limite[<?=$datum?>][<?=$counter?>] = <?=$spiel['spiel_id']?>;
			<?php
		}
		?>

		function reload_tips(){
			start++;

			if(limite[start]){
				for(var a=0; a<limite[start].length; a++){
					var spiel_id =limite[start][a];

					document.getElementById('tip_spiel_'+spiel_id).disabled = true;
					document.getElementById('tip_home_res_'+spiel_id).disabled = true;
					document.getElementById('tip_away_res_'+spiel_id).disabled = true;
					document.getElementById('tip_home_ext_'+spiel_id).disabled = true;
					document.getElementById('tip_away_ext_'+spiel_id).disabled = true;
					document.getElementById('tip_home_penalty_'+spiel_id).disabled = true;
					document.getElementById('tip_away_penalty_'+spiel_id).disabled = true;
					document.getElementById('tip_gelb_'+spiel_id).disabled = true;
					document.getElementById('tip_rot_'+spiel_id).disabled = true;

					document.getElementById('tip_aktiv_'+spiel_id).value = 0;
				}
			}

			if(limite_gruppenphase==start){
				document.getElementById('benutzer_tip_sieger_land_code').disabled = true;
				document.getElementById('benutzer_tip_scorer_spieler_id').disabled = true;
				document.getElementById('benutzer_tip_meiste_tore_land_code').disabled = true;
				document.getElementById('benutzer_tip_meiste_gegentore_land_code').disabled = true;
				document.getElementById('benutzer_tip_wenigste_tore_land_code').disabled = true;
				document.getElementById('benutzer_tip_wenigste_gegentore_land_code').disabled = true;
			}

			setTimeout('reload_tips()', 1000);
		}

		var last_key = 0;
		function get_key(e){
			last_key = (e.keyCode) ? e.keyCode : e.charCode;
		}

		function parse_field(obj){
			if(last_key>=48 && last_key<=90){
				var new_string = '';

				for(var a=0; a<obj.value.length; a++){
					if(!isNaN(obj.value.charAt(a))){
						new_string += ""+obj.value.charAt(a);
					}
				}

				obj.value = new_string;
			}
		}
	</script>
	<?php
}

$titel = ($visiting) ? $benutzer['benutzer_benutzername'] : $lg_tips_abgeben;
?>

<h1><?=parse($titel)?></h1>

<?php
$spieler = get_spieler();

$gruppenphase = (time()<strtotime($cfg_gruppenphase));
$datum_bis_gruppenphase = date($lg_format_datum_mittel.' '.$lg_format_zeit_kurz, strtotime($cfg_gruppenphase));

if($visiting){
	?>
	<a href="rangliste.php">&laquo; <?=$lg_zurueck_rangliste?></a><br/><br/>
	<?php
}

if($gruppenphase && !$visiting){
	?>
	<b><i><?=$lg_bis?> <?=parse($datum_bis_gruppenphase)?>:</i></b><br/><br/>
	<?php
}

$count = 0;
foreach($spiele as $index => $spiel){
	if(!$visiting || time()>=strtotime($spiel['spiel_datum']) || $glb_admin){
		$count++;
	}
}

if($count>0){
	?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<colgroup>
			<col width="250"/>
			<col/>
		</colgroup>
		<?php
		if($gruppenphase && !$visiting){
			?>
			<tr>
				<td><label for="benutzer_tip_sieger_land_code"><b><?=$lg_liste_punkte_einmalig['15'][0]?>:</b></label></td>
				<td><label for="benutzer_tip_scorer_spieler_id"><b><?=$lg_liste_punkte_einmalig['15'][1]?>:</b></label></td>
			</tr>
			<tr>
				<td>
					<select name="benutzer_tip_sieger_land_code" id="benutzer_tip_sieger_land_code" class="input">
						<option value="">---</option>
						<?php
						foreach($laender as $land){
							$sel = ($land['land_code']==$benutzer['benutzer_tip_sieger_land_code']) ? ' selected="selected"' : '';
							?>
							<option value="<?=$land['land_code']?>"<?=$sel?>><?=$land['land_name']?></option>
							<?php
						}
						?>
					</select>
				</td>
				<td>
					<select name="benutzer_tip_scorer_spieler_id" id="benutzer_tip_scorer_spieler_id" class="input">
						<option value="0">---</option>
						<?php
						$letztes_land = NULL;
						foreach($spieler as $s){
							$sel = ($s['spieler_id']==$benutzer['benutzer_tip_scorer_spieler_id']) ? ' selected="selected"' : '';
							$spieler_nr = (!is_null($s['spieler_nr'])) ? $s['spieler_nr'].' - ' : '';
							if($letztes_land!=$s['spieler_land_code']){
								if(!is_null($letztes_land)){
									?>
									</optgroup>
									<?php
								}
								?>
								<optgroup label="<?=$laender_assoc[$s['spieler_land_code']]['land_name']?>">
								<?php
								$letztes_land = $s['spieler_land_code'];
							}
							?>
							<option value="<?=$s['spieler_id']?>"<?=$sel?>><?=$spieler_nr?><?=$s['spieler_name']?> (<?=$lg_liste_position[$s['spieler_position']]?>)</option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td><label for="benutzer_tip_meiste_tore_land_code"><b><?=$lg_liste_punkte_einmalig['10'][0]?>:</b></label></td>
				<td><label for="benutzer_tip_meiste_gegentore_land_code"><b><?=$lg_liste_punkte_einmalig['10'][1]?>:</b></label></td>
			</tr>
			<tr>
				<td>
					<select name="benutzer_tip_meiste_tore_land_code" id="benutzer_tip_meiste_tore_land_code" class="input">
						<option value="">---</option>
						<?php
						foreach($laender as $land){
							$sel = ($land['land_code']==$benutzer['benutzer_tip_meiste_tore_land_code']) ? ' selected="selected"' : '';
							?>
							<option value="<?=$land['land_code']?>"<?=$sel?>><?=$land['land_name']?></option>
							<?php
						}
						?>
					</select>
				</td>
				<td>
					<select name="benutzer_tip_meiste_gegentore_land_code" id="benutzer_tip_meiste_gegentore_land_code" class="input">
						<option value="">---</option>
						<?php
						foreach($laender as $land){
							$sel = ($land['land_code']==$benutzer['benutzer_tip_meiste_gegentore_land_code']) ? ' selected="selected"' : '';
							?>
							<option value="<?=$land['land_code']?>"<?=$sel?>><?=$land['land_name']?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td><label for="benutzer_tip_wenigste_tore_land_code"><b><?=$lg_liste_punkte_einmalig['10'][2]?>:</b></label></td>
				<td><label for="benutzer_tip_wenigste_gegentore_land_code"><b><?=$lg_liste_punkte_einmalig['10'][3]?>:</b></label></td>
			</tr>
			<tr>
				<td>
					<select name="benutzer_tip_wenigste_tore_land_code" id="benutzer_tip_wenigste_tore_land_code" class="input">
						<option value="">---</option>
						<?php
						foreach($laender as $land){
							$sel = ($land['land_code']==$benutzer['benutzer_tip_wenigste_tore_land_code']) ? ' selected="selected"' : '';
							?>
							<option value="<?=$land['land_code']?>"<?=$sel?>><?=$land['land_name']?></option>
							<?php
						}
						?>
					</select>
				</td>
				<td>
					<select name="benutzer_tip_wenigste_gegentore_land_code" id="benutzer_tip_wenigste_gegentore_land_code" class="input">
						<option value="">---</option>
						<?php
						foreach($laender as $land){
							$sel = ($land['land_code']==$benutzer['benutzer_tip_wenigste_gegentore_land_code']) ? ' selected="selected"' : '';
							?>
							<option value="<?=$land['land_code']?>"<?=$sel?>><?=$land['land_name']?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<?php
		} elseif(!$visiting || time()>=strtotime($cfg_gruppenphase) || ($visiting && $glb_admin)){
			?>
			<tr>
				<td><b><?=$lg_liste_punkte_einmalig['15'][0]?>:</b></td>
				<td><b><?=$lg_liste_punkte_einmalig['15'][1]?>:</b></td>
			</tr>
			<tr>
				<td valign="top">
					<?php
					$land_tmp = ($glb_final_sieger!='' && isset($laender_assoc[$glb_final_sieger])) ? $laender_assoc[$glb_final_sieger] : '';
					$str = ($glb_final_sieger!='' && isset($land_tmp['land_name'])) ? $land_tmp['land_name'] : $lg_keine_angabe_kurz;

					$tip = '<i>'.$lg_kein_tip.'</i>';
					if($benutzer['benutzer_tip_sieger_land_code']!=''){
						$land = $laender_assoc[$benutzer['benutzer_tip_sieger_land_code']];

						if(isset($land['land_name'])){
							$tip = $lg_tip.': '.$land['land_name'];
						}
					}
					?>
					<b><?=$str?></b><br/>
					<b><i class="markiert"><?=$tip?></i></b>
				</td>
				<td valign="top">
					<?php
					$str = '';
					foreach($glb_schuetzen as $spieler_id){
						$spieler_tmp = ($spieler_id>0) ? get_spieler($spieler_id) : '';
						$spieler_nr = (!is_null($spieler_tmp['spieler_nr'])) ? ' - '.$spieler_tmp['spieler_nr'] : '';
						$str .= ($spieler_id>0 && isset($spieler_tmp['spieler_name'])) ? (($str!='') ? ', ' : '').$spieler_tmp['spieler_name'].' ('.$spieler_tmp['spieler_land_code'].$spieler_nr.' - '.$lg_liste_position[$spieler_tmp['spieler_position']].')' : '';
					}
					$str = ($str=='') ? $lg_keine_angabe_kurz : $str;

					$tip = '<i>'.$lg_kein_tip.'</i>';
					if($benutzer['benutzer_tip_scorer_spieler_id']>0){
						$spieler = get_spieler($benutzer['benutzer_tip_scorer_spieler_id']);

						if(isset($spieler['spieler_name'])){
							$spieler_nr = (!is_null($spieler['spieler_nr'])) ? ' - '.$spieler['spieler_nr'] : '';
							$tip = $lg_tip.': '.$spieler['spieler_name'].' ('.$spieler['spieler_land_code'].$spieler_nr.')';
						}
					}
					?>
					<b><?=$str?></b><br/>
					<b><i class="markiert"><?=$tip?></i></b>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td><b><?=$lg_liste_punkte_einmalig['10'][0]?>:</b></td>
				<td><b><?=$lg_liste_punkte_einmalig['10'][1]?>:</b></td>
			</tr>
			<tr>
				<td valign="top">
					<?php
					$str = '';
					foreach($glb_meiste_tore_land_codes as $land_code){
						$land_tmp = ($land_code!='' && isset($laender_assoc[$land_code])) ? $laender_assoc[$land_code] : '';
						$str .= ($land_code!='' && isset($land_tmp['land_name'])) ? (($str!='') ? ', ' : '').$land_tmp['land_name'] : '';
					}
					$str = ($str=='') ? $lg_keine_angabe_kurz : $str;

					$tip = '<i>'.$lg_kein_tip.'</i>';
					if($benutzer['benutzer_tip_meiste_tore_land_code']!=''){
						$land = $laender_assoc[$benutzer['benutzer_tip_meiste_tore_land_code']];

						if(isset($land['land_name'])){
							$tip = $lg_tip.': '.$land['land_name'];
						}
					}
					?>
					<b><?=$str?></b><br/>
					<b><i class="markiert"><?=$tip?></i></b>
				</td>
				<td valign="top">
					<?php
					$str = '';
					foreach($glb_meiste_gegentore_land_codes as $land_code){
						$land_tmp = ($land_code!='' && isset($laender_assoc[$land_code])) ? $laender_assoc[$land_code] : '';
						$str .= ($land_code!='' && isset($land_tmp['land_name'])) ? (($str!='') ? ', ' : '').$land_tmp['land_name'] : '';
					}
					$str = ($str=='') ? $lg_keine_angabe_kurz : $str;

					$tip = '<i>'.$lg_kein_tip.'</i>';
					if($benutzer['benutzer_tip_meiste_gegentore_land_code']!=''){
						$land = $laender_assoc[$benutzer['benutzer_tip_meiste_gegentore_land_code']];

						if(isset($land['land_name'])){
							$tip = $lg_tip.': '.$land['land_name'];
						}
					}
					?>
					<b><?=$str?></b><br/>
					<b><i class="markiert"><?=$tip?></i></b>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td><b><?=$lg_liste_punkte_einmalig['10'][2]?>:</b></td>
				<td><b><?=$lg_liste_punkte_einmalig['10'][3]?>:</b></td>
			</tr>
			<tr>
				<td valign="top">
					<?php
					$str = '';
					foreach($glb_wenigste_tore_land_codes as $land_code){
						$land_tmp = ($land_code!='' && isset($laender_assoc[$land_code])) ? $laender_assoc[$land_code] : '';
						$str .= ($land_code!='' && isset($land_tmp['land_name'])) ? (($str!='') ? ', ' : '').$land_tmp['land_name'] : '';
					}
					$str = ($str=='') ? $lg_keine_angabe_kurz : $str;

					$tip = '<i>'.$lg_kein_tip.'</i>';
					if($benutzer['benutzer_tip_wenigste_tore_land_code']!=''){
						$land = $laender_assoc[$benutzer['benutzer_tip_wenigste_tore_land_code']];

						if(isset($land['land_name'])){
							$tip = $lg_tip.': '.$land['land_name'];
						}
					}
					?>
					<b><?=$str?></b><br/>
					<b><i class="markiert"><?=$tip?></i></b>
				</td>
				<td valign="top">
					<?php
					$str = '';
					foreach($glb_wenigste_gegentore_land_codes as $land_code){
						$land_tmp = ($land_code!='' && isset($laender_assoc[$land_code])) ? $laender_assoc[$land_code] : '';
						$str .= ($land_code!='' && isset($land_tmp['land_name'])) ? (($str!='') ? ', ' : '').$land_tmp['land_name'] : '';
					}
					$str = ($str=='') ? $lg_keine_angabe_kurz : $str;

					$tip = '<i>'.$lg_kein_tip.'</i>';
					if($benutzer['benutzer_tip_wenigste_gegentore_land_code']!=''){
						$land = $laender_assoc[$benutzer['benutzer_tip_wenigste_gegentore_land_code']];

						if(isset($land['land_name'])){
							$tip = $lg_tip.': '.$land['land_name'];
						}
					}
					?>
					<b><?=$str?></b><br/>
					<b><i class="markiert"><?=$tip?></i></b>
				</td>
			</tr>
			<?php
		}
		?>
	</table><br/>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<colgroup>
			<col width="30"/>
			<col width="110"/>
			<col width="45"/>
			<col width="160"/>
			<col width="28"/>
			<col width="15"/>
			<col width="28"/>
			<col/>
			<col width="85"/>
		</colgroup>
		<?php
		$next_spieltag = '';

		$last_datum = '';
		foreach($spiele as $index => $spiel){
			$tip = (isset($benutzer['benutzer_tips'][$spiel['spiel_id']])) ? $benutzer['benutzer_tips'][$spiel['spiel_id']] : array();
			$land_home = (isset($laender_assoc[$spiel['spiel_home_land_code']])) ? $laender_assoc[$spiel['spiel_home_land_code']] : array();
			$land_away = (isset($laender_assoc[$spiel['spiel_away_land_code']])) ? $laender_assoc[$spiel['spiel_away_land_code']] : array();

			if(!isset($land_home['land_id']) || !isset($land_away['land_id']) || ($visiting && time()<strtotime($spiel['spiel_datum']) && !$glb_admin)){
				continue;
			}

			$limit = strtotime($cfg_limit_spiel, strtotime($spiel['spiel_datum']));
			$gespielt = (($spiel['spiel_home_res']>=0 && $spiel['spiel_away_res']>=0) || time()>=$limit);

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
						<td colspan="9" height="25"></td>
					</tr>
					<?php
				}

				if(!$visiting){
					?>
					<tr>
						<td colspan="9" align="right">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<colgroup>
									<col/>
									<col width="140"/>
								</colgroup>
								<tr>
									<td align="right">
										<div name="div_save_ok" id="div_save_ok_<?=$index?>" align="right" style="display: none;">
											<?php
											$msg = $lg_tips_save_ok;
											msg($msg, 'erfolg', 0, 0, true);
											?>
										</div>
										<div name="div_save_error" id="div_save_error_<?=$index?>" align="right" style="display: none;">
											<?php
											$msg = $lg_tips_save_error;
											msg($msg, 'error', 0, 0, true);
											?>
										</div>
									</td>
									<td align="right">
										<div name="div_button" id="div_button_<?=$index?>">
											<?php
											button($lg_tips_speichern.' &raquo;', '', 'save('.$index.')', 'gruen', false, 0, 0, 120);
											?>
										</div>
										<div name="div_button_warten" id="div_button_warten_<?=$index?>" style="display: none;">
											<?php
											button($lg_warten, '', '', 'gruen', true, 0, 0, 120);
											?>
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan="9">
						<div id="spieltag_<?=$datum?>"></div>
						<b><?=$str?></b>
					</td>
				</tr>
				<tr>
					<td colspan="9" height="3"></td>
				</tr>
				<tr>
					<td colspan="9" height="1" style="background-image: url(layout/img/ln/ln_rot.gif);"></td>
				</tr>
				<tr>
					<td colspan="9" height="3"></td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="8" class="stadion"><b><?=$spiel['stadion_ort']?></b> - <?=$spiel['stadion_name']?></td>
			</tr>
			<tr>
				<td colspan="9" height="5"></td>
			</tr>
			<tr>
				<td><?=$spiel['spiel_id']?></td>
				<td>
					<?php
					if($spiel['spiel_typ']=='G'){
						?>
						<?=$lg_gruppe_kurz?> <?=$spiel['spiel_gruppe']?>
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
					<td align="right"><?=$spiel['spiel_home_land_name']?></td>
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
					<td><?=$spiel['spiel_away_land_name']?></td>
					<?php
				} else {
					?>
					<td colspan="2"><?=$spiel['spiel_away_land_name']?></td>
					<?php
				}
				?>
				<td align="right">
					<?php
					if(($gespielt || $visiting) && $spiel['spiel_home_res']>=0){
						$benutzer['benutzer_punkte'] = 0;
						$benutzer['benutzer_richtige_res'] = 0;
						$benutzer['benutzer_richtige_res_punkte'] = 0;
						$benutzer['benutzer_richtige_ext'] = 0;
						$benutzer['benutzer_richtige_ext_punkte'] = 0;
						$benutzer['benutzer_richtige_penalty'] = 0;
						$benutzer['benutzer_richtige_penalty_punkte'] = 0;
						$benutzer['benutzer_richtige_sieger'] = 0;
						$benutzer['benutzer_richtige_sieger_punkte'] = 0;
						$benutzer['benutzer_richtige_anzahl_treffer'] = 0;
						$benutzer['benutzer_richtige_anzahl_treffer_punkte'] = 0;
						$benutzer['benutzer_richtige_gelb'] = 0;
						$benutzer['benutzer_richtige_gelb_punkte'] = 0;
						$benutzer['benutzer_richtige_rot'] = 0;
						$benutzer['benutzer_richtige_rot_punkte'] = 0;
						$benutzer['benutzer_richtige_anzahl_treffer_team'] = 0;
						$benutzer['benutzer_richtige_anzahl_treffer_team_punkte'] = 0;

						$punkte = $cls_benutzer->calc_spiel($benutzer, $spiel);
						$punkte_string = ($punkte==1) ? $lg_punkt : $lg_punkte;

						if($punkte>0){
							?>
							<span class="punkte" style="cursor: help;" spielId="<?=$spiel['spiel_id']?>"><b><?=$punkte?> <?=$punkte_string?></b> <img src="layout/img/icon_info.png" width="16" height="16" border="0" align="top" alt="" title=""/></span>

							<div id="div_punkte_tooltip_<?=$spiel['spiel_id']?>" style="display: none;">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<colgroup>
										<col width="25"/>
										<col/>
									</colgroup>
									<?php
									if($benutzer['benutzer_richtige_res_punkte']>0){
										?>
										<tr>
											<td valign="top"><?=$benutzer['benutzer_richtige_res_punkte']?></td>
											<td valign="top"><?=$lg_liste_punkte_pro_spiel['5'][0]?></td>
										</tr>
										<?php
									}
									if($benutzer['benutzer_richtige_ext_punkte']>0){
										?>
										<tr>
											<td valign="top"><?=$benutzer['benutzer_richtige_ext_punkte']?></td>
											<td valign="top"><?=$lg_liste_punkte_pro_spiel['5'][1]?></td>
										</tr>
										<?php
									}
									if($benutzer['benutzer_richtige_penalty_punkte']>0){
										?>
										<tr>
											<td valign="top"><?=$benutzer['benutzer_richtige_penalty_punkte']?></td>
											<td valign="top"><?=$lg_liste_punkte_pro_spiel['5'][2]?></td>
										</tr>
										<?php
									}
									if($benutzer['benutzer_richtige_sieger_punkte']>0){
										?>
										<tr>
											<td valign="top"><?=$benutzer['benutzer_richtige_sieger_punkte']?></td>
											<td valign="top"><?=$lg_liste_punkte_pro_spiel['3'][0]?></td>
										</tr>
										<?php
									}
									if($benutzer['benutzer_richtige_anzahl_treffer_punkte']>0){
										?>
										<tr>
											<td valign="top"><?=$benutzer['benutzer_richtige_anzahl_treffer_punkte']?></td>
											<td valign="top"><?=$lg_liste_punkte_pro_spiel['2'][0]?></td>
										</tr>
										<?php
									}
									/*if($benutzer['benutzer_richtige_gelb_punkte']>0){
										?>
										<tr>
											<td valign="top"><?=$benutzer['benutzer_richtige_gelb_punkte']?></td>
											<td valign="top"><?=$lg_liste_punkte_pro_spiel['2'][1]?></td>
										</tr>
										<?php
									}
									if($benutzer['benutzer_richtige_rot_punkte']>0){
										?>
										<tr>
											<td valign="top"><?=$benutzer['benutzer_richtige_rot_punkte']?></td>
											<td valign="top"><?=$lg_liste_punkte_pro_spiel['2'][2]?></td>
										</tr>
										<?php
									}*/
									if($benutzer['benutzer_richtige_anzahl_treffer_team_punkte']>0){
										?>
										<tr>
											<td valign="top"><?=$benutzer['benutzer_richtige_anzahl_treffer_team_punkte']?></td>
											<td valign="top"><?=$lg_liste_punkte_pro_spiel['1'][0]?></td>
										</tr>
										<?php
									}
									?>
								</table>
							</div>
							<?php
						} else {
							?>
							<b><?=$punkte?> <?=$punkte_string?></b>
							<?php
						}
					} else {
						?>
						&nbsp;
						<?php
					}
					?>
				</td>
			</tr>
			<tr>
				<td colspan="9" height="5"></td>
			</tr>
			<?php
			if(!$gespielt && !$visiting){
				$datum_bis = date($lg_format_datum_mittel.' '.$lg_format_zeit_kurz, strtotime($cfg_limit_spiel, strtotime($spiel['spiel_datum'])));
				$datum_bis_lokal = date($lg_format_datum_mittel.' '.$lg_format_zeit_kurz, strtotime($stadion_zeitzone_differenz.' '.(($stadion_zeitzone_differenz==1 || $stadion_zeitzone_differenz==-1) ? 'hour' : 'hours'), strtotime($datum_bis)));
				?>
				<tr>
					<td colspan="6">&nbsp;</td>
					<td colspan="3"><b><i><?=$lg_bis?> <span class="zeitUser"><?=parse($datum_bis)?></span><span class="zeitLokal" style="display: none;"><?=parse($datum_bis_lokal)?></span></i></b></td>
				</tr>
				<tr>
					<td colspan="9" height="5"></td>
				</tr>
				<?php
			}

			$wert_tip_home_res = (isset($tip['tip_home_res']) && intval($tip['tip_home_res'])>=0) ? intval($tip['tip_home_res']) : '';
			$wert_tip_away_res = (isset($tip['tip_away_res']) && intval($tip['tip_away_res'])>=0) ? intval($tip['tip_away_res']) : '';
			$wert_tip_home_ext = (isset($tip['tip_home_ext']) && intval($tip['tip_home_ext'])>=0) ? intval($tip['tip_home_ext']) : '';
			$wert_tip_away_ext = (isset($tip['tip_away_ext']) && intval($tip['tip_away_ext'])>=0) ? intval($tip['tip_away_ext']) : '';
			$wert_tip_home_penalty = (isset($tip['tip_home_penalty']) && intval($tip['tip_home_penalty'])>=0) ? intval($tip['tip_home_penalty']) : '';
			$wert_tip_away_penalty = (isset($tip['tip_away_penalty']) && intval($tip['tip_away_penalty'])>=0) ? intval($tip['tip_away_penalty']) : '';
			$wert_tip_gelb = (isset($tip['tip_gelb']) && intval($tip['tip_gelb'])>=0) ? intval($tip['tip_gelb']) : '';
			$wert_tip_rot = (isset($tip['tip_rot']) && intval($tip['tip_rot'])>=0) ? intval($tip['tip_rot']) : '';

			$wert2_tip_home_res = (isset($tip['tip_home_res']) && intval($tip['tip_home_res'])>=0) ? intval($tip['tip_home_res']) : -1;
			$wert2_tip_away_res = (isset($tip['tip_away_res']) && intval($tip['tip_away_res'])>=0) ? intval($tip['tip_away_res']) : -1;
			$wert2_tip_home_ext = (isset($tip['tip_home_ext']) && intval($tip['tip_home_ext'])>=0) ? intval($tip['tip_home_ext']) : -1;
			$wert2_tip_away_ext = (isset($tip['tip_away_ext']) && intval($tip['tip_away_ext'])>=0) ? intval($tip['tip_away_ext']) : -1;
			$wert2_tip_home_penalty = (isset($tip['tip_home_penalty']) && intval($tip['tip_home_penalty'])>=0) ? intval($tip['tip_home_penalty']) : -1;
			$wert2_tip_away_penalty = (isset($tip['tip_away_penalty']) && intval($tip['tip_away_penalty'])>=0) ? intval($tip['tip_away_penalty']) : -1;
			$wert2_tip_gelb = (isset($tip['tip_gelb']) && intval($tip['tip_gelb'])>=0) ? intval($tip['tip_gelb']) : -1;
			$wert2_tip_rot = (isset($tip['tip_rot']) && intval($tip['tip_rot'])>=0) ? intval($tip['tip_rot']) : -1;

			if($gespielt || $visiting){
				$tip_res = ($wert2_tip_home_res>=0 && $wert2_tip_away_res>=0) ? $lg_tip.': '.$wert2_tip_home_res.' - '.$wert2_tip_away_res : $lg_kein_tip;
				?>
				<tr>
					<td colspan="3">&nbsp;</td>
					<?php
					if($spiel['spiel_home_res']>=0 && $spiel['spiel_away_res']>=0){
						?>
						<td colspan="2" align="right" valign="middle"><b><?=$spiel['spiel_home_res']?></b></td>
						<td align="center" valign="middle">-</td>
						<td valign="middle"><b><?=$spiel['spiel_away_res']?></b></td>
						<?php
					} else {
						?>
						<td>&nbsp;</td>
						<td colspan="3" align="center" valign="middle"><?=$lg_keine_angabe_kurz?></td>
						<?php
					}
					?>
					<td valign="middle"><i><b class="markiert"><?=$tip_res?></b></i></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="9" height="5"></td>
				</tr>
				<?php
				if($spiel['spiel_typ']!='G'){
					$tip_ext = ($wert2_tip_home_ext>=0 && $wert2_tip_away_ext>=0) ? $lg_tip.': '.$wert2_tip_home_ext.' - '.$wert2_tip_away_ext : $lg_kein_tip;
					?>
					<tr>
						<td colspan="3">&nbsp;</td>
						<?php
						if($spiel['spiel_home_ext']>=0 && $spiel['spiel_away_ext']>=0){
							?>
							<td align="right"><?=$lg_nach_ext_kurz?></td>
							<td align="right" valign="middle"><b><?=$spiel['spiel_home_ext']?></b></td>
							<td align="center" valign="middle">-</td>
							<td valign="middle"><b><?=$spiel['spiel_away_ext']?></b></td>
							<?php
						} else {
							?>
							<td>&nbsp;</td>
							<td colspan="3" align="center" valign="middle"><?=$lg_keine_ext_kurz?></td>
							<?php
						}
						?>
						<td valign="middle"><i><b class="markiert"><?=$tip_ext?></b></i></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="9" height="5"></td>
					</tr>
					<?php
					$tip_penalty = ($wert2_tip_home_penalty>=0 && $wert2_tip_away_penalty>=0) ? $lg_tip.': '.$wert2_tip_home_penalty.' - '.$wert2_tip_away_penalty : $lg_kein_tip;
					?>
					<tr>
						<td colspan="3">&nbsp;</td>
						<?php
						if($spiel['spiel_home_penalty']>=0 && $spiel['spiel_away_penalty']>=0){
							?>
							<td align="right"><?=$lg_nach_penalty_kurz?></td>
							<td align="right" valign="middle"><b><?=$spiel['spiel_home_penalty']?></b></td>
							<td align="center" valign="middle">-</td>
							<td valign="middle"><b><?=$spiel['spiel_away_penalty']?></b></td>
							<?php
						} else {
							?>
							<td>&nbsp;</td>
							<td colspan="3" align="center" valign="middle"><?=$lg_kein_penalty_kurz?></td>
							<?php
						}
						?>
						<td valign="middle"><i><b class="markiert"><?=$tip_penalty?></b></i></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="9" height="5"></td>
					</tr>
					<?php
				}
				/*
				$tip_gelb = ($wert2_tip_gelb>=0) ? $lg_tip.': '.$wert2_tip_gelb : $lg_kein_tip;
				$wert_gelb = ($spiel['spiel_gelb']>=0) ? $spiel['spiel_gelb'] : $lg_keine_angabe_kurz;
				?>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td colspan="2" align="right" valign="middle"><b><?=$lg_gelbe_karten?>:</b></td>
					<td align="center" valign="middle">&nbsp;</td>
					<td valign="middle"><b><?=$wert_gelb?></b></td>
					<td valign="middle"><i><b class="markiert"><?=$tip_gelb?></b></i></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="9" height="5"></td>
				</tr>
				<?php
				$tip_rot = ($wert2_tip_rot>=0) ? $lg_tip.': '.$wert2_tip_rot : $lg_kein_tip;
				$wert_rot = ($spiel['spiel_rot']>=0) ? $spiel['spiel_rot'] : $lg_keine_angabe_kurz;
				?>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td colspan="2" align="right" valign="middle"><b><?=$lg_rote_karten?>:</b></td>
					<td align="center" valign="middle">&nbsp;</td>
					<td valign="middle"><b><?=$wert_rot?></b></td>
					<td valign="middle"><i><b class="markiert"><?=$tip_rot?></b></i></td>
					<td>&nbsp;</td>
				</tr>
				<?php*/
			} else {
				?>
				<input type="hidden" name="tip_spiel[]" id="tip_spiel_<?=$spiel['spiel_id']?>" value="<?=$spiel['spiel_id']?>"/>
				<input type="hidden" name="tip_aktiv[]" id="tip_aktiv_<?=$spiel['spiel_id']?>" value="1"/>

				<tr>
					<td colspan="3">&nbsp;</td>
					<td colspan="2" align="right" valign="middle"><input type="text" name="tip_home_res[]" id="tip_home_res_<?=$spiel['spiel_id']?>" class="input" style="width: 20px; text-align: right;" onkeyup="get_key(event); parse_field(this);" value="<?=$wert_tip_home_res?>"/></td>
					<td align="center" valign="middle">-</td>
					<td colspan="3" valign="middle"><input type="text" name="tip_away_res[]" id="tip_away_res_<?=$spiel['spiel_id']?>" class="input" style="width: 20px;" onkeyup="get_key(event); parse_field(this);" value="<?=$wert_tip_away_res?>"/></td>
				</tr>
				<tr>
					<td colspan="9" height="5"></td>
				</tr>
				<?php
				if($spiel['spiel_typ']!='G'){
					?>
					<tr>
						<td colspan="3">&nbsp;</td>
						<td colspan="2" align="right" valign="middle">
							<?=$lg_nach_ext_kurz?>
							<input type="text" name="tip_home_ext[]" id="tip_home_ext_<?=$spiel['spiel_id']?>" class="input" style="width: 20px; text-align: right;" onkeyup="get_key(event); parse_field(this);" value="<?=$wert_tip_home_ext?>"/>
						</td>
						<td align="center" valign="middle">-</td>
						<td colspan="3" valign="middle"><input type="text" name="tip_away_ext[]" id="tip_away_ext_<?=$spiel['spiel_id']?>" class="input" style="width: 20px;" onkeyup="get_key(event); parse_field(this);" value="<?=$wert_tip_away_ext?>"/></td>
					</tr>
					<tr>
						<td colspan="9" height="5"></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
						<td colspan="2" align="right" valign="middle">
							<?=$lg_nach_penalty_kurz?>
							<input type="text" name="tip_home_penalty[]" id="tip_home_penalty_<?=$spiel['spiel_id']?>" class="input" style="width: 20px; text-align: right;" onkeyup="get_key(event); parse_field(this);" value="<?=$wert_tip_home_penalty?>"/>
						</td>
						<td align="center" valign="middle">-</td>
						<td colspan="3" valign="middle"><input type="text" name="tip_away_penalty[]" id="tip_away_penalty_<?=$spiel['spiel_id']?>" class="input" style="width: 20px;" onkeyup="get_key(event); parse_field(this);" value="<?=$wert_tip_away_penalty?>"/></td>
					</tr>
					<tr>
						<td colspan="9" height="5"></td>
					</tr>
					<?php
				} else {
					?>
					<input type="hidden" name="tip_home_ext[]" id="tip_home_ext_<?=$spiel['spiel_id']?>" value=""/>
					<input type="hidden" name="tip_away_ext[]" id="tip_away_ext_<?=$spiel['spiel_id']?>" value=""/>
					<input type="hidden" name="tip_home_penalty[]" id="tip_home_penalty_<?=$spiel['spiel_id']?>" value=""/>
					<input type="hidden" name="tip_away_penalty[]" id="tip_away_penalty_<?=$spiel['spiel_id']?>" value=""/>
					<?php
				}
				/*
				?>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td colspan="2" align="right" valign="middle"><input type="text" name="tip_gelb[]" id="tip_gelb_<?=$spiel['spiel_id']?>" class="input" style="width: 20px; text-align: right;" onkeyup="get_key(event); parse_field(this);" value="<?=$wert_tip_gelb?>"/></td>
					<td align="center" valign="middle">&nbsp;</td>
					<td colspan="3" valign="middle"><?=$lg_gelbe_karten?></td>
				</tr>
				<tr>
					<td colspan="9" height="5"></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td colspan="2" align="right" valign="middle"><input type="text" name="tip_rot[]" id="tip_rot_<?=$spiel['spiel_id']?>" class="input" style="width: 20px; text-align: right;" onkeyup="get_key(event); parse_field(this);" value="<?=$wert_tip_rot?>"/></td>
					<td align="center" valign="middle">&nbsp;</td>
					<td colspan="3" valign="middle"><?=$lg_rote_karten?></td>
				</tr>
				<?php
				*/
			}
			?>
			<tr>
				<td colspan="9" height="5"></td>
			</tr>
			<tr>
				<td colspan="9" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="9" height="5"></td>
			</tr>
			<?php
		}
		?>
	</table>

	<span id="next_spieltag"><?=$next_spieltag?></span>
	<?php
} else {
	$msg = $lg_keine_tips;
	msg($msg, 'error', 0, 0);
}

include('footer.php');
?>