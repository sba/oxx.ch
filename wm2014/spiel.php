<?php
$glb_kategorie = 'spielplan';
$glb_seite = 'start';
$glb_menu = 'start';

$glb_no_infos = true;

include('lib/inc.init.php');

$spiel_id = (isset($_GET['s'])) ? intval($_GET['s']) : -1;
$spiel = get_spiel('', '', $spiel_id, true);

if(!$glb_admin || count($spiel)==0){
	header('Location: spielplan.php');
}

$res = '';
if(isset($_POST['action']) && $_POST['action']=='spiel'){
	$tore = array();

	foreach($_POST['tor_spieler_id'] as $index => $tor_spieler_id){
		$data = array(
			'tor_spiel_id' => $spiel_id,
			'tor_spieler_id' => $tor_spieler_id,
			'tor_minute' => ((isset($_POST['tor_minute'][$index])) ? $_POST['tor_minute'][$index] : 0),
			'tor_ext' => ((isset($_POST['tor_ext'][$index])) ? $_POST['tor_ext'][$index] : 0),
			'tor_own' => ((isset($_POST['tor_own'][$index])) ? $_POST['tor_own'][$index] : 0),
			'tor_penalty' => ((isset($_POST['tor_penalty'][$index])) ? $_POST['tor_penalty'][$index] : 0),
		);

		if($data['tor_spieler_id']!='' && $data['tor_minute']>=0){
			$tore[] = $data;
		}
	}

	$data = array(
		'spiel_id' => $spiel_id,
		'spiel_home_land_code' => $_POST['spiel_home_land_code'],
		'spiel_away_land_code' => $_POST['spiel_away_land_code'],
		'spiel_home_res' => (($_POST['spiel_home_res']!='') ? $_POST['spiel_home_res'] : -1),
		'spiel_away_res' => (($_POST['spiel_away_res']!='') ? $_POST['spiel_away_res'] : -1),
		'spiel_home_ext' => (($_POST['spiel_home_ext']!='') ? $_POST['spiel_home_ext'] : -1),
		'spiel_away_ext' => (($_POST['spiel_away_ext']!='') ? $_POST['spiel_away_ext'] : -1),
		'spiel_home_penalty' => (($_POST['spiel_home_penalty']!='') ? $_POST['spiel_home_penalty'] : -1),
		'spiel_away_penalty' => (($_POST['spiel_away_penalty']!='') ? $_POST['spiel_away_penalty'] : -1),
		'spiel_gelb' => (($_POST['spiel_gelb']!='') ? $_POST['spiel_gelb'] : -1),
		'spiel_rot' => (($_POST['spiel_rot']!='') ? $_POST['spiel_rot'] : -1),
		'tore' => $tore,
	);

	$res = update_spiel($data);

	if($res=='ok'){
		$_SESSION[$cfg_session]['temp'] = array(
			'action' => 'spiel',
			'res' => 'ok',
		);

		header('Location: spiel.php?s='.$spiel_id);
		exit();
	}
}

include('header.php');

$laender = get_land('', '', false, false, false);
foreach($lg_liste_land as $land_code => $land_name){
	$laender[] = array(
		'land_code' => $land_code,
		'land_name' => $land_name,
	);
}
$laender_assoc = array();
foreach($laender as $land){
	$laender_assoc[$land['land_code']] = $land;
}
$spieler = get_spieler(0, array($spiel['spiel_home_land_code'], $spiel['spiel_away_land_code']));

?>
<h1><?=parse($lg_spiel_edit)?>: <?=$spiel['spiel_id']?></h1>

<a href="spielplan.php">&laquo; <?=$lg_zurueck_spielplan?></a><br/><br/>

<?php
if(isset($_SESSION[$cfg_session]['temp']) && $_SESSION[$cfg_session]['temp']['action']=='spiel'){
	msg($lg_spiel_edit_ok, 'erfolg', 0, 1);

	unset($_SESSION[$cfg_session]['temp']);
} elseif($res!=''){
	msg($lg_spiel_edit_error, 'error', 0, 1);
}

$wert_spiel_home_res = (isset($_POST['spiel_home_res']) && $_POST['spiel_home_res']!='') ? intval($_POST['spiel_home_res']) : (($spiel['spiel_home_res']>=0) ? $spiel['spiel_home_res'] : '');
$wert_spiel_away_res = (isset($_POST['spiel_away_res']) && $_POST['spiel_away_res']!='') ? intval($_POST['spiel_away_res']) : (($spiel['spiel_away_res']>=0) ? $spiel['spiel_away_res'] : '');
$wert_spiel_home_ext = (isset($_POST['spiel_home_ext']) && $_POST['spiel_home_ext']!='') ? intval($_POST['spiel_home_ext']) : (($spiel['spiel_home_ext']>=0) ? $spiel['spiel_home_ext'] : '');
$wert_spiel_away_ext = (isset($_POST['spiel_away_ext']) && $_POST['spiel_away_ext']!='') ? intval($_POST['spiel_away_ext']) : (($spiel['spiel_away_ext']>=0) ? $spiel['spiel_away_ext'] : '');
$wert_spiel_home_penalty = (isset($_POST['spiel_home_penalty']) && $_POST['spiel_home_penalty']!='') ? intval($_POST['spiel_home_penalty']) : (($spiel['spiel_home_penalty']>=0) ? $spiel['spiel_home_penalty'] : '');
$wert_spiel_away_penalty = (isset($_POST['spiel_away_penalty']) && $_POST['spiel_away_penalty']!='') ? intval($_POST['spiel_away_penalty']) : (($spiel['spiel_away_penalty']>=0) ? $spiel['spiel_away_penalty'] : '');
$wert_spiel_gelb = (isset($_POST['spiel_gelb']) && $_POST['spiel_gelb']!='') ? intval($_POST['spiel_gelb']) : (($spiel['spiel_gelb']>=0) ? $spiel['spiel_gelb'] : '');
$wert_spiel_rot = (isset($_POST['spiel_rot']) && $_POST['spiel_rot']!='') ? intval($_POST['spiel_rot']) : (($spiel['spiel_rot']>=0) ? $spiel['spiel_rot'] : '');
?>
<form name="frmSpiel" action="spiel.php?s=<?=$spiel['spiel_id']?>" method="post">
	<input type="hidden" name="action" value="spiel"/>
	<input type="hidden" name="spiel_id" value="<?=$spiel['spiel_id']?>"/>

	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<colgroup>
			<col/>
			<col width="15"/>
			<col/>
		</colgroup>
		<tr>
			<td align="right">
				<select name="spiel_home_land_code" id="spiel_home_land_code" class="input">
					<?php
					foreach($laender as $land){
						$sel = ($land['land_code']==$spiel['spiel_home_land_code']) ? ' selected="selected"' : '';
						?>
						<option value="<?=$land['land_code']?>"<?=$sel?>><?=$land['land_name']?></option>
						<?php
					}
					?>
				</select>
			</td>
			<td align="center">-</td>
			<td>
				<select name="spiel_away_land_code" id="spiel_away_land_code" class="input">
					<?php
					foreach($laender as $land){
						$sel = ($land['land_code']==$spiel['spiel_away_land_code']) ? ' selected="selected"' : '';
						?>
						<option value="<?=$land['land_code']?>"<?=$sel?>><?=$land['land_name']?></option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td align="right">
				<input type="text" name="spiel_home_res" id="spiel_home_res" class="input" style="width: 20px; text-align: right;" value="<?=$wert_spiel_home_res?>"/>
			</td>
			<td align="center">-</td>
			<td>
				<input type="text" name="spiel_away_res" id="spiel_away_res" class="input" style="width: 20px; text-align: right;" value="<?=$wert_spiel_away_res?>"/>
			</td>
		</tr>
		<?php
		if($spiel['spiel_typ']!='G'){
			?>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td align="right">
					<input type="text" name="spiel_home_ext" id="spiel_home_ext" class="input" style="width: 20px; text-align: right;" value="<?=$wert_spiel_home_ext?>"/>
				</td>
				<td align="center">-</td>
				<td>
					<input type="text" name="spiel_away_ext" id="spiel_away_ext" class="input" style="width: 20px; text-align: right;" value="<?=$wert_spiel_away_ext?>"/>&nbsp;&nbsp;&nbsp;
					<?=$lg_nach_ext_kurz?>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td align="right">
					<input type="text" name="spiel_home_penalty" id="spiel_home_penalty" class="input" style="width: 20px; text-align: right;" value="<?=$wert_spiel_home_penalty?>"/>
				</td>
				<td align="center">-</td>
				<td>
					<input type="text" name="spiel_away_penalty" id="spiel_away_penalty" class="input" style="width: 20px; text-align: right;" value="<?=$wert_spiel_away_penalty?>"/>&nbsp;&nbsp;&nbsp;
					<?=$lg_nach_penalty_kurz?>
				</td>
			</tr>
			<?php
		} else {
			?>
			<input type="hidden" name="spiel_home_ext" id="spiel_home_ext" value="-1"/>
			<input type="hidden" name="spiel_away_ext" id="spiel_away_ext" value="-1"/>
			<input type="hidden" name="spiel_home_penalty" id="spiel_home_penalty" value="-1"/>
			<input type="hidden" name="spiel_away_penalty" id="spiel_away_penalty" value="-1"/>
			<?php
		}
		?>
		<?php /*
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td align="right">
				<?=$lg_gelbe_karten?>
			</td>
			<td align="center">&nbsp;</td>
			<td>
				<input type="text" name="spiel_gelb" id="spiel_gelb" class="input" style="width: 20px; text-align: right;" value="<?=$wert_spiel_gelb?>"/>
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td align="right">
				<?=$lg_rote_karten?>
			</td>
			<td align="center">&nbsp;</td>
			<td>
				<input type="text" name="spiel_rot" id="spiel_rot" class="input" style="width: 20px; text-align: right;" value="<?=$wert_spiel_rot?>"/>
			</td>
		</tr>
		*/ ?>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" height="1" style="background-image: url(layout/img/ln/ln_rot.gif);"></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<colgroup>
						<col width="90"/>
						<col width="140"/>
						<col/>
					</colgroup>
					<tr>
						<td colspan="3"><b><?=$lg_tore?>:</b></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<?php
					for($a=0; $a<12; $a++){
						$wert_tor_minute = (isset($_POST['tor_minute'][$a])) ? $_POST['tor_minute'][$a] : ((isset($spiel['tore'][$a]['tor_minute'])) ? $spiel['tore'][$a]['tor_minute'] : '');
						$wert_tor_ext = ((isset($_POST['tor_ext'][$a])) || (!isset($_POST['action']) && isset($spiel['tore'][$a]['tor_ext']) && $spiel['tore'][$a]['tor_ext']==1)) ? ' checked="checked"' : '';
						$wert_tor_penalty = ((isset($_POST['tor_penalty'][$a])) || (!isset($_POST['action']) && isset($spiel['tore'][$a]['tor_penalty']) && $spiel['tore'][$a]['tor_penalty']==1)) ? ' checked="checked"' : '';
						$wert_tor_own = ((isset($_POST['tor_own'][$a])) || (!isset($_POST['action']) && isset($spiel['tore'][$a]['tor_own']) && $spiel['tore'][$a]['tor_own']==1)) ? ' checked="checked"' : '';
						?>
						<tr>
							<td><input type="text" name="tor_minute[<?=$a?>]" id="tor_minute_<?=$a?>" class="input" style="width: 30px; text-align: right;" value="<?=$wert_tor_minute?>"> min.</td>
							<td>
								<input type="checkbox" name="tor_ext[<?=$a?>]" id="tor_ext_<?=$a?>" value="1"<?=$wert_tor_ext?>/> <label for="tor_ext_<?=$a?>"><?=$lg_in_ext_kurz?></label>
								<input type="checkbox" name="tor_penalty[<?=$a?>]" id="tor_penalty_<?=$a?>" value="1"<?=$wert_tor_penalty?>/> <label for="tor_penalty_<?=$a?>"><?=$lg_zeichen_penalty?></label>
								<input type="checkbox" name="tor_own[<?=$a?>]" id="tor_own_<?=$a?>" value="1"<?=$wert_tor_own?>/> <label for="tor_own_<?=$a?>"><?=$lg_zeichen_own?></label>
							</td>
							<td>
								<select name="tor_spieler_id[<?=$a?>]" id="tor_spieler_id_<?=$a?>" class="input">
									<option value="">---</option>
									<?php
									$letztes_land = NULL;
									foreach($spieler as $s){
										$sel = ((isset($_POST['tor_spieler_id'][$a]) && $_POST['tor_spieler_id'][$a]==$s['spieler_id']) ||(!isset($_POST['action']) && isset($spiel['tore'][$a]['tor_spieler_id']) && $spiel['tore'][$a]['tor_spieler_id']==$s['spieler_id'])) ? ' selected="selected"' : '';
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
							<td colspan="3" height="5"></td>
						</tr>
						<tr>
							<td colspan="3" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
						</tr>
						<tr>
							<td colspan="3" height="5"></td>
						</tr>
						<?php
					}
					?>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" height="1" style="background-image: url(layout/img/ln/ln_rot.gif);"></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="right">
				<?php
				button($lg_speichern.' &raquo;', '', 'document.frmSpiel.submit()');
				?>
			</td>
		</tr>
	</table>
</form>
<?php

include('footer.php');
?>