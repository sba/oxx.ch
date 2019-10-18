<?php
$glb_kategorie = 'rangliste';
$glb_seite = 'start';
$glb_menu = 'start';

include('lib/inc.init.php');

include('header.php');

?>
<h1><?=parse($lg_rangliste)?></h1>

<?php
$bShowMoney = ($glb_eingeloggt && $cfg_einsatz['on'] && $_SESSION[$cfg_session]['login']['benutzer_einsatz']>=$cfg_einsatz['betrag']);
$pot = $cls_benutzer->calc_pot();
/*if($bShowMoney){
	?>
	<div style="margin-bottom: 20px; padding: 20px;">
		<div style="width: 80px; margin-right: 20px; float: left;"><img src="layout/img/money_large.png" style="width: 80px;"/></div>
		<div style="margin-top: 15px; font-size: 40px;"><?=int2money($pot)?> CHF</div>
		<br style="clear: both;"/>
	</div>
	<?php
}*/
$rangliste = $cls_benutzer->get_rangliste();
?>
<?=$lg_rangliste_benutzername?><br/>
<?=$lg_punktegleichheit?><br/><br/><br/>

<style type="text/css">
	#ranking .top3 b {
		color: white;
	}
</style>

<?php
if(isset($rangliste['neu']) && count($rangliste['neu'])>0){
	?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" id="ranking">
		<colgroup>
			<col width="5"/>
			<col width="35"/>
			<col width="35"/>
			<col width="25"/>
			<col width="30"/>
			<col/>
			<?php
			if($bShowMoney){
				?>
				<col/>
				<?php
			}
			?>
			<col width="60"/>
			<col width="40"/>
			<col width="5"/>
		</colgroup>
		<?php
		foreach($rangliste['neu'] as $index => $benutzer){
			$rang = ($index + 1);
			$rang_alt = (isset($rangliste['alt'][$benutzer['benutzer_id']]['benutzer_rang'])) ? $rangliste['alt'][$benutzer['benutzer_id']]['benutzer_rang'] : 0;
			$punkte_alt = (isset($rangliste['alt'][$benutzer['benutzer_id']]['benutzer_punkte'])) ? $rangliste['alt'][$benutzer['benutzer_id']]['benutzer_punkte'] : 0;

			$style = '';
			if($benutzer['benutzer_punkte']>0 || 1==1){
				if($rang==1){
					$style = ' style="font-size: 23px; background-color: #D9A441; color: #000;" class="top3"';
				} elseif($rang==2){
					$style = ' style="font-size: 19px; background-color: #A8A8A8; color: #000;" class="top3"';
				} elseif($rang==3){
					$style = ' style="font-size: 15px; background-color: #965A38; color: #000;" class="top3"';
				} elseif(($rang==4 || $rang==5) && $bShowMoney){
					$style = ' style="background-color: #D8BCFF;"';
				}
			}

			$pos = ($rang<$rang_alt) ? 'up' : (($rang>$rang_alt) ? 'down' : 'right');
			$colspanLarge = ($bShowMoney) ? 10 : 9;
			?>
			<tr<?=$style?>>
				<td colspan="<?=$colspanLarge?>" height="5"></td>
			</tr>
			<tr<?=$style?>>
				<td></td>
				<td><b><?=$rang?>.</b></td>
				<td style="font-size: 13px;">
					<?php
					if($rang_alt>0){
						?>
						(<?=$rang_alt?>.)
						<?php
					} else {
						?>
						&nbsp;
						<?php
					}
					?>
				</td>
				<td align="center">
					<?php
					if($rang_alt>0){
						?>
						<img src="layout/img/<?=$pos?>.png" alt=""/>
						<?php
					} else {
						?>
						&nbsp;
						<?php
					}
					?>
				</td>
				<?php
				$benutzer_land = ($benutzer['benutzer_land_code']!='' && file_exists('layout/img/flags/'.$benutzer['benutzer_land_code'].'.gif'));
				$colspan = ($benutzer_land) ? '' : ' colspan="2"';
				if($benutzer_land){
					$land = get_land($benutzer['benutzer_land_code'], '', false, false, true);
					$land_name = (isset($land['land_name'])) ? $land['land_name'] : '';
					?>
					<td align="center"><img src="layout/img/flags/<?=$benutzer['benutzer_land_code']?>.gif" alt="<?=$land_name?>" title="<?=$land_name?>"/></td>
					<?php
				}

				$geschlecht_bild = ($benutzer['benutzer_geschlecht']==1) ? 'male' : 'female';
				$geschlecht_alt = ($benutzer['benutzer_geschlecht']==1) ? $lg_maennlich : $lg_weiblich;
				?>
				<td<?=$colspan?> style="padding-left: 5px;">
					<a href="tip.php?b=<?=$benutzer['benutzer_id']?>"><b><?=$benutzer['benutzer_benutzername']?></b></a>
					<img src="layout/img/<?=$geschlecht_bild?>.png" width="15" height="15" alt="<?=$geschlecht_alt?>" title="<?=$geschlecht_alt?>"/>
				</td>
				<?php
				if($bShowMoney){
					$anteil = (isset($cfg_einsatz['anteil'][$index])) ? $cfg_einsatz['anteil'][$index] : 0;
					$betrag_anteil = (($pot / 100) * $anteil);
					?>
					<td align="right"><b><span title="<?=$anteil?>%" style="cursor: pointer;"><?=int2money($betrag_anteil)?> CHF</span></b></td>
					<?php
				}
				?>
				<td align="right"><b><?=$benutzer['benutzer_punkte']?></b></td>
				<td align="right" style="font-size: 13px;">(<?=$punkte_alt?>)</td>
				<td></td>
			</tr>
			<tr<?=$style?>>
				<td colspan="<?=$colspanLarge?>" height="5"></td>
			</tr>
			<tr>
				<td colspan="<?=$colspanLarge?>" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
} else {
	$msg = $lg_keine_benutzer;
	msg($msg, 'error', 0, 0);
}

include('footer.php');
?>