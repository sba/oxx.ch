<?php
$glb_kategorie = 'cronjob';
$glb_seite = 'reminder';
$glb_menu = 'start';

$glb_no_infos = true;
$glb_no_login = true;

$pfad = '/web-data/oxx.ch/http/wm2014/';
include($pfad.'lib/inc.init.php');

$benutzer = $cls_benutzer->get();
$spiele = get_spiel('', '', 0, false);

$datum_start = time();
$datum_ende = strtotime(date('Y-m-d', strtotime('+1 day', time())).' 23:59:59');
$datum_heute_ende = strtotime(date('Y-m-d', time()).' 23:59:59');

foreach($benutzer as $b){
	if($b['benutzer_quelle']!=$cfg_benutzer_quelle){
		continue;
	}
	
	$reminder = array(
		'heute' => array(),
		'morgen' => array(),
	);

	foreach($spiele as $spiel){
		$stadion_zeitzone_differenz = (!is_null($spiel['stadion_zeitzone_differenz'])) ? (int)$spiel['stadion_zeitzone_differenz'] : 0;
		$datum_spiel = strtotime($stadion_zeitzone_differenz+' '.(($stadion_zeitzone_differenz==1 || $stadion_zeitzone_differenz==-1) ? 'hour' : 'hours'), strtotime($spiel['spiel_datum']));

		if(!isset($b['benutzer_tips'][$spiel['spiel_id']]) && $datum_spiel>=$datum_start && $datum_spiel<=$datum_ende && $spiel['spiel_home_res']<0 && $spiel['spiel_away_res']<0 && file_exists($pfad.'layout/img/flags/'.$spiel['spiel_home_land_code'].'.gif') && file_exists($pfad.'layout/img/flags/'.$spiel['spiel_away_land_code'].'.gif')){
			$key = ($datum_spiel<=$datum_heute_ende) ? 'heute' : 'morgen';

			$reminder[$key][] = $spiel;
		}
	}

	$gruppenphase = strtotime($cfg_gruppenphase);
	$gruppenphase = ($gruppenphase>=$datum_start && $gruppenphase<=$datum_ende);

	if($gruppenphase){
		if($b['benutzer_tip_sieger_land_code']!='' && $b['benutzer_tip_scorer_spieler_id']>0 && $b['benutzer_tip_meiste_tore_land_code']!='' && $b['benutzer_tip_meiste_gegentore_land_code']!='' && $b['benutzer_tip_wenigste_tore_land_code']!='' && $b['benutzer_tip_wenigste_gegentore_land_code']!=''){
			$gruppenphase = false;
		}
	}

	if(count($reminder['heute'])>0 || count($reminder['morgen'])>0 || $gruppenphase){
		$data = array(
			'benutzer_benutzername' => $b['benutzer_benutzername'],
			'benutzer_sprache' => $b['benutzer_sprache'],
			'spiele' => $reminder,
			'gruppenphase' => $gruppenphase,
		);
		$flags = array();

		foreach($reminder as $typ => $sp){
			foreach($sp as $spiel){
				if(!in_array($spiel['spiel_home_land_code'], $flags)){
					$flags[] = $spiel['spiel_home_land_code'];
				}

				if(!in_array($spiel['spiel_away_land_code'], $flags)){
					$flags[] = $spiel['spiel_away_land_code'];
				}
			}
		}

		$betreff = (isset($lg_mails[$b['benutzer_sprache']]['reminder']['betreff'])) ? $lg_mails[$b['benutzer_sprache']]['reminder']['betreff'] : $lg_mails[$cfg_sprache_standard]['reminder']['betreff'];
		$text = $cls_mailer->get_mail_text('reminder', $data);
		$cls_mailer->send('', '', $b['benutzer_email'], $betreff, $text, true, $flags, $pfad);
	}
}
?>