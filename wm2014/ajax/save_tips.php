<?php
$glb_kategorie = 'ajax';
$glb_seite = 'save';
$glb_menu = 'start';

$pfad = '../';
include('../lib/inc.init.php');

if($glb_eingeloggt && (!$cfg_einsatz['on'] || $_SESSION[$cfg_session]['login']['benutzer_einsatz']>=$cfg_einsatz['betrag'])){
	$alles_ok = 1;
	
	if(isset($_GET['benutzer_id'], $_GET['benutzer_tip_sieger_land_code'], $_GET['benutzer_tip_scorer_spieler_id'], $_GET['benutzer_tip_meiste_tore_land_code'], $_GET['benutzer_tip_meiste_gegentore_land_code'], $_GET['benutzer_tip_wenigste_tore_land_code'], $_GET['benutzer_tip_wenigste_gegentore_land_code'], $_GET['tip_spiele'], $_GET['tip_home_res'], $_GET['tip_away_res'], $_GET['tip_home_ext'], $_GET['tip_away_ext'], $_GET['tip_home_penalty'], $_GET['tip_away_penalty'], $_GET['tip_gelb'], $_GET['tip_rot']) && $_GET['benutzer_id']>0){
		$now_b = $_GET['benutzer_id'].$_GET['benutzer_tip_sieger_land_code'].$_GET['benutzer_tip_scorer_spieler_id'].$_GET['benutzer_tip_meiste_tore_land_code'].$_GET['benutzer_tip_meiste_gegentore_land_code'].$_GET['benutzer_tip_wenigste_tore_land_code'].$_GET['benutzer_tip_wenigste_gegentore_land_code'];
		$last_b = (isset($_SESSION[$cfg_session]['temp']['last_b'])) ? $_SESSION[$cfg_session]['temp']['last_b'] : '';
		
		if($now_b!=$last_b && $_GET['benutzer_tip_sieger_land_code']!=-1){
			$_SESSION[$cfg_session]['temp']['last_b'] = $now_b;
			
			$sql = "UPDATE ".$cfg_db['prefix']."benutzer 
					   SET benutzer_tip_sieger_land_code = '".$_GET['benutzer_tip_sieger_land_code']."', 
						   benutzer_tip_scorer_spieler_id = ".$_GET['benutzer_tip_scorer_spieler_id'].", 
						   benutzer_tip_meiste_tore_land_code = '".$_GET['benutzer_tip_meiste_tore_land_code']."', 
						   benutzer_tip_meiste_gegentore_land_code = '".$_GET['benutzer_tip_meiste_gegentore_land_code']."', 
						   benutzer_tip_wenigste_tore_land_code = '".$_GET['benutzer_tip_wenigste_tore_land_code']."', 
						   benutzer_tip_wenigste_gegentore_land_code = '".$_GET['benutzer_tip_wenigste_gegentore_land_code']."'
					 WHERE benutzer_id = ".$_GET['benutzer_id'].";";
			$query = mysql_query($sql);
			
			if(!$query){
				$alles_ok = 0;
			}
		}
		
		
		$now = $_GET['benutzer_id'].$_GET['tip_spiele'].$_GET['tip_home_res'].$_GET['tip_away_res'].$_GET['tip_home_ext'].$_GET['tip_away_ext'].$_GET['tip_home_penalty'].$_GET['tip_away_penalty'].$_GET['tip_gelb'].$_GET['tip_rot'];
		$last = (isset($_SESSION[$cfg_session]['temp']['last'])) ? $_SESSION[$cfg_session]['temp']['last'] : '';
		
		if($now!=$last){
			$_SESSION[$cfg_session]['temp']['last'] = $now;
			
			$benutzer_id = $_GET['benutzer_id'];
			$tip_spiele = explode(',', $_GET['tip_spiele']);
			$tip_home_res = explode(',', $_GET['tip_home_res']);
			$tip_away_res = explode(',', $_GET['tip_away_res']);
			$tip_home_ext = explode(',', $_GET['tip_home_ext']);
			$tip_away_ext = explode(',', $_GET['tip_away_ext']);
			$tip_home_penalty = explode(',', $_GET['tip_home_penalty']);
			$tip_away_penalty = explode(',', $_GET['tip_away_penalty']);
			$tip_gelb = explode(',', $_GET['tip_gelb']);
			$tip_rot = explode(',', $_GET['tip_rot']);
			
			foreach($tip_spiele as $index => $spiel_id){
				$spiel = get_spiel('', '', $spiel_id, false);
				
				$limit = (count($spiel)>0) ? strtotime($cfg_limit_spiel, strtotime($spiel['spiel_datum'])) : 0;
				
				if(is_numeric($spiel_id) && count($spiel)>0 && time()<$limit){
					$sql = "DELETE FROM ".$cfg_db['prefix']."tip 
								  WHERE tip_benutzer_id = ".$benutzer_id." 
									AND tip_spiel_id = ".$spiel_id.";";
					$query = mysql_query($sql);
					
					$val_tip_home_res = (isset($tip_home_res[$index]) && is_numeric($tip_home_res[$index]) && $tip_home_res[$index]>=0) ? $tip_home_res[$index] : -1;
					$val_tip_away_res = (isset($tip_away_res[$index]) && is_numeric($tip_away_res[$index]) && $tip_away_res[$index]>=0) ? $tip_away_res[$index] : -1;
					$val_tip_home_ext = (isset($tip_home_ext[$index]) && is_numeric($tip_home_ext[$index]) && $tip_home_ext[$index]>=0) ? $tip_home_ext[$index] : -1;
					$val_tip_away_ext = (isset($tip_away_ext[$index]) && is_numeric($tip_away_ext[$index]) && $tip_away_ext[$index]>=0) ? $tip_away_ext[$index] : -1;
					$val_tip_home_penalty = (isset($tip_home_penalty[$index]) && is_numeric($tip_home_penalty[$index]) && $tip_home_penalty[$index]>=0) ? $tip_home_penalty[$index] : -1;
					$val_tip_away_penalty = (isset($tip_away_penalty[$index]) && is_numeric($tip_away_penalty[$index]) && $tip_away_penalty[$index]>=0) ? $tip_away_penalty[$index] : -1;
					$val_tip_gelb = (isset($tip_gelb[$index]) && is_numeric($tip_gelb[$index]) && $tip_gelb[$index]>=0) ? $tip_gelb[$index] : -1;
					$val_tip_rot = (isset($tip_rot[$index]) && is_numeric($tip_rot[$index]) && $tip_rot[$index]>=0) ? $tip_rot[$index] : -1;
					
					$alle_inaktiv = ($val_tip_home_res==-1 && $val_tip_away_res==-1 && $val_tip_home_ext==-1 && $val_tip_away_ext==-1 && $val_tip_home_penalty==-1 && $val_tip_away_penalty==-1 && $val_tip_gelb==-1 && $val_tip_rot==-1);
					
					if($query && !$alle_inaktiv){
						$sql2 = "INSERT INTO ".$cfg_db['prefix']."tip 
										 SET tip_benutzer_id = ".$benutzer_id.", 
											 tip_spiel_id = ".$spiel_id.", 
											 tip_home_res = ".$val_tip_home_res.", 
											 tip_away_res = ".$val_tip_away_res.", 
											 tip_home_ext = ".$val_tip_home_ext.", 
											 tip_away_ext = ".$val_tip_away_ext.", 
											 tip_home_penalty = ".$val_tip_home_penalty.", 
											 tip_away_penalty = ".$val_tip_away_penalty.", 
											 tip_gelb = ".$val_tip_gelb.", 
											 tip_rot = ".$val_tip_rot.";";
						$query2 = mysql_query($sql2);
						
						if(!$query2){
							$alles_ok = 0;
						}
					}
				}
			}
		}
	}
	
	echo $alles_ok;
}
?>