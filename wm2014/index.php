<?php
$glb_kategorie = 'start';
$glb_seite = 'start';
$glb_menu = 'start';

include('lib/inc.init.php');

$res_login = '';
if(isset($_POST['action']) && $_POST['action']=='login'){
	$data = array(
		'benutzer_benutzername' => addslashes($_POST['benutzer_benutzername']),
		'benutzer_kennwort' => $_POST['benutzer_kennwort'],
	);

	$res_login = $cls_session->login($data);

	if($res_login=='ok'){
		header('Location: index.php');
		exit();
	}
}

if(isset($_GET['a']) && $_GET['a']=='logout'){
	$cls_session->logout();

	header('Location: index.php');
	exit();
}

$bShowMoney = ($glb_eingeloggt && $cfg_einsatz['on'] && $_SESSION[$cfg_session]['login']['benutzer_einsatz']>=$cfg_einsatz['betrag']);
$pot = $cls_benutzer->calc_pot();

include('header.php');

?>
<script>
	$(document).ready(function(){
	 $('#benutzer_kennwort').keypress(function(e) {
		if(e.which == 13) {
			checkForm()
		}
	  })
	})
</script>
<h1><?=parse($lg_application_name)?></h1>
<?php

if($glb_db=='ok'){
	if($glb_eingeloggt){
		$benutzer = $cls_benutzer->get_rangliste($_SESSION[$cfg_session]['login']['benutzer_id']);
		$anzahl_spiele = get_spiele_anzahl();

		if(isset($_SESSION[$cfg_session]['temp']) && $_SESSION[$cfg_session]['temp']['action']=='reg'){
			$msg = $lg_registrieren_ok;

			msg($msg, 'erfolg', 0, 2);

			unset($_SESSION[$cfg_session]['temp']);
		}
		?>
		<div align="center">
			<table width="280" border="0" cellpadding="0" cellspacing="0" style="font-size: 16px;">
				<colgroup>
					<col width="100"/>
					<col width="90"/>
					<col width="90"/>
				</colgroup>
				<tr>
					<td align="left"><b><?=$lg_rang?>:</b></td>
					<td align="right"><b><?=$benutzer['benutzer_rang']?>.</b></td>
					<td align="right" style="font-size: 13px;">(<?=$benutzer['benutzer_rang_alt']?>.)</td>
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
				<tr>
					<td align="left"><b><?=$lg_punkte?>:</b></td>
					<td align="right"><b><?=$benutzer['benutzer_punkte']?></b></td>
					<td align="right" style="font-size: 13px;">(<?=$benutzer['benutzer_punkte_alt']?>)</td>
				</tr>
				<tr>
					<td colspan="3" height="5"></td>
				</tr>
				<?php
				if($bShowMoney){
					$anteil = (isset($cfg_einsatz['anteil'][($benutzer['benutzer_rang']-1)])) ? $cfg_einsatz['anteil'][($benutzer['benutzer_rang']-1)] : 0;
					$anteil_alt = (isset($cfg_einsatz['anteil'][($benutzer['benutzer_rang_alt']-1)])) ? $cfg_einsatz['anteil'][($benutzer['benutzer_rang_alt']-1)] : 0;
					$betrag_anteil = (($pot / 100) * $anteil);
					$betrag_anteil_alt = (($pot / 100) * $anteil_alt);
					?>
					<tr>
						<td colspan="3" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
					</tr>
					<tr>
						<td colspan="3" height="5"></td>
					</tr>
					<tr>
						<td align="left"><b><?=$lg_gewinn?>:</b></td>
						<td align="right"><b><?=int2money($betrag_anteil)?> CHF</b></td>
						<td align="right" style="font-size: 13px;">(<?=int2money($betrag_anteil_alt)?> CHF)</td>
					</tr>
					<tr>
						<td colspan="3" height="5"></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan="3" height="1" style="background-image: url(layout/img/ln/ln_rot.gif);"></td>
				</tr>
			</table><br/><br/>
		</div>

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<colgroup>
				<col/>
				<col width="40"/>
				<col width="70"/>
				<col width="60"/>
			</colgroup>
			<tr>
				<td>&nbsp;</td>
				<td align="right"><b><?=$lg_tip?></b></td>
				<td align="right"><b><?=$lg_resultat?></b></td>
				<td align="right"><b><?=$lg_punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td><?=$lg_liste_punkte_einmalig['15'][0]?></td>
				<td align="right">
					<?php
					if($_SESSION[$cfg_session]['login']['benutzer_tip_sieger_land_code']!=''){
						?>
						<?=$lg_ja?>
						<?php
					} else {
						?>
						<a href="tip.php"><?=$lg_nein?></a>
						<?php
					}
					?>
				</td>
				<td align="right">
					<?php
					$punkte = 0;

					if($glb_final_sieger!=''){
						$img = ($_SESSION[$cfg_session]['login']['benutzer_tip_sieger_land_code']==$glb_final_sieger) ? 'erfolg' : 'error';
						$punkte = ($img=='erfolg') ? 15 : 0;
						?>
						<img src="layout/img/<?=$img?>.png" border="0" alt=""/>
						<?php
					} else {
						?>
						-
						<?php
					}
					?>
				</td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td><?=$lg_liste_punkte_einmalig['15'][1]?></td>
				<td align="right">
					<?php
					if($_SESSION[$cfg_session]['login']['benutzer_tip_scorer_spieler_id']>0){
						?>
						<?=$lg_ja?>
						<?php
					} else {
						?>
						<a href="tip.php"><?=$lg_nein?></a>
						<?php
					}
					?>
				</td>
				<td align="right">
					<?php
					$punkte = 0;
					if(count($glb_schuetzen)>0){
						$img = (in_array($_SESSION[$cfg_session]['login']['benutzer_tip_scorer_spieler_id'], $glb_schuetzen)) ? 'erfolg' : 'error';
						$punkte = ($img=='erfolg') ? 15 : 0;
						?>
						<img src="layout/img/<?=$img?>.png" border="0" alt=""/>
						<?php
					} else {
						?>
						-
						<?php
					}
					?>
				</td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td><?=$lg_liste_punkte_einmalig['10'][0]?></td>
				<td align="right">
					<?php
					if($_SESSION[$cfg_session]['login']['benutzer_tip_meiste_tore_land_code']!=''){
						?>
						<?=$lg_ja?>
						<?php
					} else {
						?>
						<a href="tip.php"><?=$lg_nein?></a>
						<?php
					}
					?>
				</td>
				<td align="right">
					<?php
					$punkte = 0;
					if(count($glb_schuetzen)>0){
						$img = (in_array($_SESSION[$cfg_session]['login']['benutzer_tip_meiste_tore_land_code'], $glb_meiste_tore_land_codes)) ? 'erfolg' : 'error';
						$punkte = ($img=='erfolg') ? 10 : 0;
						?>
						<img src="layout/img/<?=$img?>.png" border="0" alt=""/>
						<?php
					} else {
						?>
						-
						<?php
					}
					?>
				</td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td><?=$lg_liste_punkte_einmalig['10'][1]?></td>
				<td align="right">
					<?php
					if($_SESSION[$cfg_session]['login']['benutzer_tip_meiste_gegentore_land_code']!=''){
						?>
						<?=$lg_ja?>
						<?php
					} else {
						?>
						<a href="tip.php"><?=$lg_nein?></a>
						<?php
					}
					?>
				</td>
				<td align="right">
					<?php
					$punkte = 0;
					if(count($glb_schuetzen)>0){
						$img = (in_array($_SESSION[$cfg_session]['login']['benutzer_tip_meiste_gegentore_land_code'], $glb_meiste_gegentore_land_codes)) ? 'erfolg' : 'error';
						$punkte = ($img=='erfolg') ? 10 : 0;
						?>
						<img src="layout/img/<?=$img?>.png" border="0" alt=""/>
						<?php
					} else {
						?>
						-
						<?php
					}
					?>
				</td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td><?=$lg_liste_punkte_einmalig['10'][2]?></td>
				<td align="right">
					<?php
					if($_SESSION[$cfg_session]['login']['benutzer_tip_wenigste_tore_land_code']!=''){
						?>
						<?=$lg_ja?>
						<?php
					} else {
						?>
						<a href="tip.php"><?=$lg_nein?></a>
						<?php
					}
					?>
				</td>
				<td align="right">
					<?php
					$punkte = 0;
					if(count($glb_schuetzen)>0){
						$img = (in_array($_SESSION[$cfg_session]['login']['benutzer_tip_wenigste_tore_land_code'], $glb_wenigste_tore_land_codes)) ? 'erfolg' : 'error';
						$punkte = ($img=='erfolg') ? 10 : 0;
						?>
						<img src="layout/img/<?=$img?>.png" border="0" alt=""/>
						<?php
					} else {
						?>
						-
						<?php
					}
					?>
				</td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td><?=$lg_liste_punkte_einmalig['10'][3]?></td>
				<td align="right">
					<?php
					if($_SESSION[$cfg_session]['login']['benutzer_tip_wenigste_gegentore_land_code']!=''){
						?>
						<?=$lg_ja?>
						<?php
					} else {
						?>
						<a href="tip.php"><?=$lg_nein?></a>
						<?php
					}
					?>
				</td>
				<td align="right">
					<?php
					$punkte = 0;
					if(count($glb_schuetzen)>0){
						$img = (in_array($_SESSION[$cfg_session]['login']['benutzer_tip_wenigste_gegentore_land_code'], $glb_wenigste_gegentore_land_codes)) ? 'erfolg' : 'error';
						$punkte = ($img=='erfolg') ? 10 : 0
						?>
						<img src="layout/img/<?=$img?>.png" border="0" alt=""/>
						<?php
					} else {
						?>
						-
						<?php
					}
					?>
				</td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
		</table><br/><br/>

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<colgroup>
				<col/>
				<col width="70"/>
				<col width="70"/>
				<col width="60"/>
			</colgroup>
			<tr>
				<td>&nbsp;</td>
				<td align="right"><b><?=$lg_moeglich?></b></td>
				<td align="right"><b><?=$lg_richtige?></b></td>
				<td align="right"><b><?=$lg_punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<?php
				$punkte = ($benutzer['benutzer_richtige_res'] * 5);
				?>
				<td><?=$lg_liste_punkte_pro_spiel['5'][0]?></td>
				<td align="right"><?=$anzahl_spiele['gespielt']?></td>
				<td align="right"><?=$benutzer['benutzer_richtige_res']?></td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<?php
				$punkte = ($benutzer['benutzer_richtige_ext'] * 5);
				?>
				<td><?=$lg_liste_punkte_pro_spiel['5'][1]?></td>
				<td align="right"><?=$anzahl_spiele['ext']?></td>
				<td align="right"><?=$benutzer['benutzer_richtige_ext']?></td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<?php
				$punkte = ($benutzer['benutzer_richtige_penalty'] * 5);
				?>
				<td><?=$lg_liste_punkte_pro_spiel['5'][2]?></td>
				<td align="right"><?=$anzahl_spiele['penalty']?></td>
				<td align="right"><?=$benutzer['benutzer_richtige_penalty']?></td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<?php
				$punkte = ($benutzer['benutzer_richtige_sieger'] * 3);
				?>
				<td><?=$lg_liste_punkte_pro_spiel['3'][0]?></td>
				<td align="right"><?=$anzahl_spiele['gespielt']?></td>
				<td align="right"><?=$benutzer['benutzer_richtige_sieger']?></td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<?php
				$punkte = ($benutzer['benutzer_richtige_anzahl_treffer'] * 2);
				?>
				<td><?=$lg_liste_punkte_pro_spiel['2'][0]?></td>
				<td align="right"><?=$anzahl_spiele['gespielt']?></td>
				<td align="right"><?=$benutzer['benutzer_richtige_anzahl_treffer']?></td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<?php /*
			<tr>
				<?php
				$punkte = ($benutzer['benutzer_richtige_gelb'] * 2);
				?>
				<td><?=$lg_liste_punkte_pro_spiel['2'][1]?></td>
				<td align="right"><?=$anzahl_spiele['gespielt']?></td>
				<td align="right"><?=$benutzer['benutzer_richtige_gelb']?></td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<?php
				$punkte = ($benutzer['benutzer_richtige_rot'] * 2);
				?>
				<td><?=$lg_liste_punkte_pro_spiel['2'][2]?></td>
				<td align="right"><?=$anzahl_spiele['gespielt']?></td>
				<td align="right"><?=$benutzer['benutzer_richtige_rot']?></td>
				<td align="right"><b><?=$punkte?></b></td>
			</tr>
			*/ ?>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td><?=$lg_liste_punkte_pro_spiel['1'][0]?></td>
				<td align="right"><?=($anzahl_spiele['gespielt'] * 2)?></td>
				<td align="right"><?=$benutzer['benutzer_richtige_anzahl_treffer_team']?></td>
				<td align="right"><b><?=$benutzer['benutzer_richtige_anzahl_treffer_team_punkte']?></b></td>
			</tr>
			<tr>
				<td colspan="4" height="5"></td>
			</tr>
			<tr>
				<td colspan="4" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
		</table>
		<?php
	} else {
		?>
		<script type="text/javascript">
			function checkForm(){
				var obj = document.frmLogin;
				var goOn = true;

				if(goOn && obj.benutzer_benutzername.value==''){
					obj.benutzer_benutzername.focus();
					goOn = false;
				}

				if(goOn && obj.benutzer_kennwort.value==''){
					obj.benutzer_kennwort.focus();
					goOn = false;
				}

				if(goOn){
					obj.spongebob.name = 'action';
					obj.submit();
				}
			}
		</script>

		<h2><?=$lg_einloggen?></h2>

		<?=$lg_login_info?><br/><br/>

		<?php
		if($res_login!=''){
			$msg = ($res_login=='inaktiv') ? $lg_login_error_inaktiv : (($res_login=='pw') ? $lg_login_error_kennwort : (($res_login=='benutzername') ? $lg_login_benutzername : $lg_login_error));
			msg($msg, 'error', 0, 1);
		}

		$wert_benutzer_benutzername = (isset($_POST['benutzer_benutzername'])) ? $_POST['benutzer_benutzername'] : '';
		$wert_benutzer_kennwort = (isset($_POST['benutzer_kennwort'])) ? $_POST['benutzer_kennwort'] : '';
		?>
		<form name="frmLogin" action="index.php" method="post">
			<input type="hidden" name="spongebob" value="login"/>

			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><b><label for="benutzer_benutzername"><?=$lg_benutzername?>:</label></b></td>
				</tr>
				<tr>
					<td><input type="text" name="benutzer_benutzername" id="benutzer_benutzername" class="input" style="width: 250px;" tabindex="1" value="<?=$wert_benutzer_benutzername?>"/></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b><label for="benutzer_kennwort"><?=$lg_kennwort?>:</label></b>&nbsp;&nbsp;&nbsp;<a href="pw.php" tabindex="4"><i style="font-size: 12px;">(<?=$lg_kennwort_vergessen?>)</i></a></td>
				</tr>
				<tr>
					<td><input type="password" name="benutzer_kennwort" id="benutzer_kennwort" class="input" style="width: 250px;" tabindex="2" value="<?=$wert_benutzer_kennwort?>"/></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
						<?php
						button($lg_login.' &raquo;', '', 'checkForm()', 'gruen', false, 3);
						?>
					</td>
				</tr>
			</table>
		</form><br/><br/>

		<?php
		if(!$cfg_einsatz['on'] || !$cfg_einsatz['restrict_registration']){
			?>
			<h2><?=$lg_registrieren?></h2>

			<?=$lg_registrieren_info?><br/><br/>

			<?php
			button($lg_registrieren.' &raquo;', 'reg.php', '', 'gruen', false, 5);
		}
	}
} else {
	$msg = $lg_offline;
	msg($msg, 'error', 0, 0);
}

include('footer.php');
?>