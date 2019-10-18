<?php
class benutzer {

	function get($benutzer_id = 0){
		global $cfg_db;

		$return = array();

		$where = ($benutzer_id!=0) ? "AND benutzer_id = ".$benutzer_id." " : "";
		$sql = "SELECT *
				  FROM ".$cfg_db['prefix']."benutzer
				 WHERE benutzer_aktiv = 1
				   ".$where."
			  ORDER BY benutzer_benutzername ASC,
					   benutzer_datum ASC;";
		$query = mysql_query($sql);

		while($benutzer = mysql_fetch_assoc($query)){
			$benutzer['benutzer_benutzername'] = stripslashes($benutzer['benutzer_benutzername']);

			$sql2 = "SELECT *
					   FROM ".$cfg_db['prefix']."tip
					  WHERE tip_benutzer_id = ".$benutzer['benutzer_id'].";";
			$query2 = mysql_query($sql2);

			$benutzer['benutzer_tips'] = array();
			while($tip = mysql_fetch_assoc($query2)){
				$benutzer['benutzer_tips'][$tip['tip_spiel_id']] = $tip;
			}

			$return[] = $benutzer;
		}

		if($benutzer_id!=0 && count($return)==1){
			$return = $return[0];
		}

		return $return;
	}

	function get_rangliste($benutzer_id = 0){
		global $cfg_db;
		global $glb_final_sieger;
		global $glb_schuetzen;
		global $glb_meiste_tore_land_codes;
		global $glb_meiste_gegentore_land_codes;
		global $glb_wenigste_tore_land_codes;
		global $glb_wenigste_gegentore_land_codes;

		$return = array();

		$benutzer = $this->get();
		if(count($benutzer)>0){
			$sql = "SELECT *
					  FROM ".$cfg_db['prefix']."spiel
					 WHERE spiel_home_res >= 0
					   AND spiel_away_res >= 0
				  ORDER BY spiel_datum ASC;";
			$query = mysql_query($sql);

			$letztes_datum_int = 0;
			$spiele = array();
			while($spiel = mysql_fetch_assoc($query)){
				if(strtotime($spiel['spiel_datum'])>$letztes_datum_int){
					$letztes_datum_int = strtotime($spiel['spiel_datum']);
				}

				$spiele[] = $spiel;
			}

			foreach($benutzer as $b){
				$b['benutzer_punkte'] = 0;
				$b['benutzer_richtige_res'] = 0;
				$b['benutzer_richtige_res_punkte'] = 0;
				$b['benutzer_richtige_ext'] = 0;
				$b['benutzer_richtige_ext_punkte'] = 0;
				$b['benutzer_richtige_penalty'] = 0;
				$b['benutzer_richtige_penalty_punkte'] = 0;
				$b['benutzer_richtige_sieger'] = 0;
				$b['benutzer_richtige_sieger_punkte'] = 0;
				$b['benutzer_richtige_anzahl_treffer'] = 0;
				$b['benutzer_richtige_anzahl_treffer_punkte'] = 0;
				$b['benutzer_richtige_gelb'] = 0;
				$b['benutzer_richtige_gelb_punkte'] = 0;
				$b['benutzer_richtige_rot'] = 0;
				$b['benutzer_richtige_rot_punkte'] = 0;
				$b['benutzer_richtige_anzahl_treffer_team'] = 0;
				$b['benutzer_richtige_anzahl_treffer_team_punkte'] = 0;

				$minus = 0;
				foreach($spiele as $spiel){
					$punkte_vorher = $b['benutzer_punkte'];

					$this->calc_spiel($b, $spiel);

					// einmalige Punkte, wenn Final bereits gespielt
					if($spiel['spiel_typ']=='F'){

						// Sieger
						if(!is_null($sieger_spiel_code) && $sieger_spiel_code!='' && $sieger_spiel_code!='none' && $sieger_spiel_code==$b['benutzer_tip_sieger_land_code']){
							$b['benutzer_punkte'] += 15;
						}

						$total_spiel_home = (($spiel['spiel_home_ext']>=0) ? $spiel['spiel_home_ext'] : $spiel['spiel_home_res']);
						$total_spiel_away = (($spiel['spiel_away_ext']>=0) ? $spiel['spiel_away_ext'] : $spiel['spiel_away_res']);

						$total_spiel_home_pen = ($total_spiel_home + (($spiel['spiel_home_penalty']>=0) ? $spiel['spiel_home_penalty'] : 0));
						$total_spiel_away_pen = ($total_spiel_away + (($spiel['spiel_away_penalty']>=0) ? $spiel['spiel_away_penalty'] : 0));

						$total_spiel = ($total_spiel_home + $total_spiel_away);
						$total_spiel_pen = ($total_spiel_home_pen + $total_spiel_away_pen);

						$sieger_spiel = ($total_spiel_home_pen>$total_spiel_away_pen) ? 'home' : (($total_spiel_home_pen<$total_spiel_away_pen) ? 'away' : 'none');
						$sieger_spiel_code = ($sieger_spiel=='home') ? $spiel['spiel_home_land_code'] : (($sieger_spiel=='away') ? $spiel['spiel_away_land_code'] : 'none');						
						
						// Torschützenkönig
						if(in_array($b['benutzer_tip_scorer_spieler_id'], $glb_schuetzen)){
							$b['benutzer_punkte'] += 15;
						}

						// Team mit den meisten Treffern
						if($b['benutzer_tip_meiste_tore_land_code']!='' && in_array($b['benutzer_tip_meiste_tore_land_code'], $glb_meiste_tore_land_codes)){
							$b['benutzer_punkte'] += 10;
						}

						// Team mit den meisten Gegentreffern
						if($b['benutzer_tip_meiste_gegentore_land_code']!='' && in_array($b['benutzer_tip_meiste_gegentore_land_code'], $glb_meiste_gegentore_land_codes)){
							$b['benutzer_punkte'] += 10;
						}

						// Team mit den wenigsten Treffern
						if($b['benutzer_tip_wenigste_tore_land_code']!='' && in_array($b['benutzer_tip_wenigste_tore_land_code'], $glb_wenigste_tore_land_codes)){
							$b['benutzer_punkte'] += 10;
						}

						// Team mit den wenigsten Gegentreffern
						if($b['benutzer_tip_wenigste_gegentore_land_code']!='' && in_array($b['benutzer_tip_wenigste_gegentore_land_code'], $glb_wenigste_gegentore_land_codes)){
							$b['benutzer_punkte'] += 10;
						}
					}

					$punkte_nachher = $b['benutzer_punkte'];
					if(strtotime($spiel['spiel_datum'])>=$letztes_datum_int){
						$minus = ($punkte_nachher - $punkte_vorher);
					}
				}

				unset($b['benutzer_kennwort']);
				unset($b['benutzer_aktiv']);
				unset($b['benutzer_tips']);
				unset($b['benutzer_tip_sieger_land_id']);
				unset($b['benutzer_tip_scorer_spieler_id']);
				unset($b['benutzer_tip_meiste_tore_land_code']);
				unset($b['benutzer_tip_meiste_gegentore_land_code']);
				unset($b['benutzer_tip_wenigste_tore_land_code']);
				unset($b['benutzer_tip_wenigste_gegentore_land_code']);

				$b['benutzer_sortierwert'] = date('YmdHis', strtotime($b['benutzer_datum']));

				$return['neu'][] = $b;

				$b['benutzer_punkte'] = ($b['benutzer_punkte'] - $minus);
				$return['alt'][] = $b;
			}

			$neu = $return['neu'];
			$alt = $return['alt'];

			// neue Rangliste sortieren
			$arr_benutzer_id = array();
			$arr_benutzer_benutzername = array();
			$arr_benutzer_datum = array();
			$arr_benutzer_punkte = array();
			$arr_benutzer_richtige_res = array();
			$arr_benutzer_richtige_ext = array();
			$arr_benutzer_richtige_penalty = array();
			$arr_benutzer_richtige_sieger = array();
			$arr_benutzer_richtige_anzahl_treffer = array();
			$arr_benutzer_richtige_gelb = array();
			$arr_benutzer_richtige_rot = array();
			$arr_benutzer_richtige_anzahl_treffer_team = array();
			$arr_benutzer_richtige_anzahl_treffer_team_punkte = array();
			$arr_sortierwert = array();
			foreach($neu as $key => $row){
				$arr_benutzer_id[$key] = $row['benutzer_id'];
				$arr_benutzer_benutzername[$key] = $row['benutzer_benutzername'];
				$arr_benutzer_datum[$key] = $row['benutzer_datum'];
				$arr_benutzer_punkte[$key] = $row['benutzer_punkte'];
				$arr_benutzer_richtige_res[$key] = $row['benutzer_richtige_res'];
				$arr_benutzer_richtige_ext[$key] = $row['benutzer_richtige_ext'];
				$arr_benutzer_richtige_penalty[$key] = $row['benutzer_richtige_penalty'];
				$arr_benutzer_richtige_sieger[$key] = $row['benutzer_richtige_sieger'];
				$arr_benutzer_richtige_anzahl_treffer[$key] = $row['benutzer_richtige_anzahl_treffer'];
				$arr_benutzer_richtige_gelb[$key] = $row['benutzer_richtige_gelb'];
				$arr_benutzer_richtige_rot[$key] = $row['benutzer_richtige_rot'];
				$arr_benutzer_richtige_anzahl_treffer_team[$key] = $row['benutzer_richtige_anzahl_treffer_team'];
				$arr_benutzer_richtige_anzahl_treffer_team_punkte[$key] = $row['benutzer_richtige_anzahl_treffer_team_punkte'];
				$arr_sortierwert[$key] = $row['benutzer_sortierwert'];
			}

			array_multisort($arr_benutzer_punkte, SORT_DESC, $arr_sortierwert, SORT_ASC, $arr_benutzer_benutzername, SORT_ASC, $arr_benutzer_datum, $arr_benutzer_id, $arr_benutzer_richtige_res, $arr_benutzer_richtige_ext, $arr_benutzer_richtige_penalty, $arr_benutzer_richtige_sieger, $arr_benutzer_richtige_anzahl_treffer, $arr_benutzer_richtige_gelb, $arr_benutzer_richtige_rot, $arr_benutzer_richtige_anzahl_treffer_team, $arr_benutzer_richtige_anzahl_treffer_team_punkte, $neu);

			$return['neu'] = $neu;

			// alte Rangliste sortieren
			$arr_benutzer_id = array();
			$arr_benutzer_benutzername = array();
			$arr_benutzer_datum = array();
			$arr_benutzer_punkte = array();
			$arr_benutzer_richtige_res = array();
			$arr_benutzer_richtige_ext = array();
			$arr_benutzer_richtige_penalty = array();
			$arr_benutzer_richtige_sieger = array();
			$arr_benutzer_richtige_anzahl_treffer = array();
			$arr_benutzer_richtige_gelb = array();
			$arr_benutzer_richtige_rot = array();
			$arr_benutzer_richtige_anzahl_treffer_team = array();
			$arr_benutzer_richtige_anzahl_treffer_team_punkte = array();
			$arr_sortierwert = array();
			foreach($alt as $key => $row){
				$arr_benutzer_id[$key] = $row['benutzer_id'];
				$arr_benutzer_benutzername[$key] = $row['benutzer_benutzername'];
				$arr_benutzer_datum[$key] = $row['benutzer_datum'];
				$arr_benutzer_punkte[$key] = $row['benutzer_punkte'];
				$arr_benutzer_richtige_res[$key] = $row['benutzer_richtige_res'];
				$arr_benutzer_richtige_ext[$key] = $row['benutzer_richtige_ext'];
				$arr_benutzer_richtige_penalty[$key] = $row['benutzer_richtige_penalty'];
				$arr_benutzer_richtige_sieger[$key] = $row['benutzer_richtige_sieger'];
				$arr_benutzer_richtige_anzahl_treffer[$key] = $row['benutzer_richtige_anzahl_treffer'];
				$arr_benutzer_richtige_gelb[$key] = $row['benutzer_richtige_gelb'];
				$arr_benutzer_richtige_rot[$key] = $row['benutzer_richtige_rot'];
				$arr_benutzer_richtige_anzahl_treffer_team[$key] = $row['benutzer_richtige_anzahl_treffer_team'];
				$arr_benutzer_richtige_anzahl_treffer_team_punkte[$key] = $row['benutzer_richtige_anzahl_treffer_team_punkte'];
				$arr_sortierwert[$key] = $row['benutzer_sortierwert'];
			}

			array_multisort($arr_benutzer_punkte, SORT_DESC, $arr_sortierwert, SORT_ASC, $arr_benutzer_benutzername, SORT_ASC, $arr_benutzer_datum, $arr_benutzer_id, $arr_benutzer_richtige_res, $arr_benutzer_richtige_ext, $arr_benutzer_richtige_penalty, $arr_benutzer_richtige_sieger, $arr_benutzer_richtige_anzahl_treffer, $arr_benutzer_richtige_gelb, $arr_benutzer_richtige_rot, $arr_benutzer_richtige_anzahl_treffer_team, $arr_benutzer_richtige_anzahl_treffer_team_punkte, $alt);

			$return['alt'] = array();
			foreach($alt as $index => $benutzer){
				$rang = ($index + 1);

				$arr = array(
					'benutzer_rang' => $rang,
					'benutzer_punkte' => $benutzer['benutzer_punkte'],
				);

				$return['alt'][$benutzer['benutzer_id']] = $arr;
			}


			// einzelner Benutzer
			if($benutzer_id){
				$rang = 0;
				$rang_alt = $return['alt'][$benutzer_id]['benutzer_rang'];
				$punkte_alt = $return['alt'][$benutzer_id]['benutzer_punkte'];
				$benutzer = array();

				foreach($return['neu'] as $index => $benutzer){
					if($benutzer['benutzer_id']==$benutzer_id){
						$rang = ($index + 1);
						break;
					}
				}

				$benutzer['benutzer_rang'] = $rang;
				$benutzer['benutzer_rang_alt'] = $rang_alt;
				$benutzer['benutzer_punkte_alt'] = $punkte_alt;
				$return = $benutzer;
			}
		}

		return $return;
	}
	
	function calc_pot(){
		global $cfg_db;
	
		$return = 0;
		
		$sql = "SELECT SUM(benutzer_einsatz) AS total_einsatz
				  FROM ".$cfg_db['prefix']."benutzer;";
		$query = mysql_query($sql);
		
		$stat = mysql_fetch_assoc($query);
		$return = $stat['total_einsatz'];
		
		return $return;
	}

	function calc_spiel(&$b, $spiel){
		$punkte = 0;

		$total_spiel_home = (($spiel['spiel_home_ext']>=0) ? $spiel['spiel_home_ext'] : $spiel['spiel_home_res']);
		$total_spiel_away = (($spiel['spiel_away_ext']>=0) ? $spiel['spiel_away_ext'] : $spiel['spiel_away_res']);

		$total_spiel_home_pen = ($total_spiel_home + (($spiel['spiel_home_penalty']>=0) ? $spiel['spiel_home_penalty'] : 0));
		$total_spiel_away_pen = ($total_spiel_away + (($spiel['spiel_away_penalty']>=0) ? $spiel['spiel_away_penalty'] : 0));

		$total_spiel = ($total_spiel_home + $total_spiel_away);
		$total_spiel_pen = ($total_spiel_home_pen + $total_spiel_away_pen);

		$sieger_spiel = ($total_spiel_home_pen>$total_spiel_away_pen) ? 'home' : (($total_spiel_home_pen<$total_spiel_away_pen) ? 'away' : 'none');
		$sieger_spiel_code = ($sieger_spiel=='home') ? $spiel['spiel_home_land_code'] : (($sieger_spiel=='away') ? $spiel['spiel_away_land_code'] : 'none');

		if(isset($b['benutzer_tips'][$spiel['spiel_id']])){
			$tip = $b['benutzer_tips'][$spiel['spiel_id']];

			$total_tip_home = (($tip['tip_home_ext']>=0 && $spiel['spiel_home_ext']>=0) ? $tip['tip_home_ext'] : $tip['tip_home_res']);
			$total_tip_away = (($tip['tip_away_ext']>=0 && $spiel['spiel_away_ext']>=0) ? $tip['tip_away_ext'] : $tip['tip_away_res']);

			$total_tip_home_pen = ($total_tip_home + (($tip['tip_home_penalty']>=0 && $spiel['spiel_home_penalty']>=0) ? $tip['tip_home_penalty'] : 0));
			$total_tip_away_pen = ($total_tip_away + (($tip['tip_away_penalty']>=0 && $spiel['spiel_away_penalty']>=0) ? $tip['tip_away_penalty'] : 0));

			$total_tip = ($total_tip_home + $total_tip_away);
			$total_tip_pen = ($total_tip_home_pen + $total_tip_away_pen);

			$sieger_tip = ($total_tip_home_pen>$total_tip_away_pen) ? 'home' : (($total_tip_home_pen<$total_tip_away_pen) ? 'away' : 'none');

			// richtiges Resultat (ohne Verlängerung od. Elfmeterschiessen)
			if($tip['tip_home_res']==$spiel['spiel_home_res'] && $tip['tip_away_res']==$spiel['spiel_away_res']){
				$pt = 5;

				$punkte += $pt;
				$b['benutzer_punkte'] += $pt;
				$b['benutzer_richtige_res']++;
				$b['benutzer_richtige_res_punkte'] += $pt;
			}

			// richtiges Resultat (nach Verlängerung)
			if($spiel['spiel_home_ext']>=0 && $spiel['spiel_away_ext']>=0 && $tip['tip_home_ext']==$spiel['spiel_home_ext'] && $tip['tip_away_ext']==$spiel['spiel_away_ext']){
				$pt = 5;

				$punkte += $pt;
				$b['benutzer_punkte'] += $pt;
				$b['benutzer_richtige_ext']++;
				$b['benutzer_richtige_ext_punkte'] += $pt;
			}

			// richtiges Resultat (nach Elfmeterschiessen)
			if($spiel['spiel_home_penalty']>=0 && $spiel['spiel_away_penalty']>=0 && $tip['tip_home_penalty']==$spiel['spiel_home_penalty'] && $tip['tip_away_penalty']==$spiel['spiel_away_penalty']){
				$pt = 5;

				$punkte += $pt;
				$b['benutzer_punkte'] += $pt;
				$b['benutzer_richtige_penalty']++;
				$b['benutzer_richtige_penalty_punkte'] += $pt;
			}

			// falsches Endresultat, richtiger Spielausgang (Sieg / Niederlage / Unentschieden)
			if($tip['tip_home_res']!=$spiel['spiel_home_res'] || $tip['tip_away_res']!=$spiel['spiel_away_res'] || ($spiel['spiel_home_ext']>=0 && ($tip['tip_home_ext']!=$spiel['spiel_home_ext'] || $tip['tip_away_ext']!=$spiel['spiel_away_ext'])) || ($spiel['spiel_home_penalty']>=0 && ($tip['tip_home_penalty']!=$spiel['spiel_home_penalty'] || $tip['tip_away_penalty']!=$spiel['spiel_away_penalty']))){
				if($sieger_spiel==$sieger_tip){
					$pt = 3;

					$punkte += $pt;
					$b['benutzer_punkte'] += $pt;
					$b['benutzer_richtige_sieger']++;
					$b['benutzer_richtige_sieger_punkte'] += $pt;
				}
			}

			// Anzahl Treffer (Summe beider Teams, ohne Elfmeterschiessen)
			if($total_tip==$total_spiel){
				$pt = 2;

				$punkte += $pt;
				$b['benutzer_punkte'] += $pt;
				$b['benutzer_richtige_anzahl_treffer']++;
				$b['benutzer_richtige_anzahl_treffer_punkte'] += $pt;
			}

			// gelbe Karten
/*			if($tip['tip_gelb']==$spiel['spiel_gelb']){
				$pt = 2;

				$punkte += $pt;
				$b['benutzer_punkte'] += $pt;
				$b['benutzer_richtige_gelb']++;
				$b['benutzer_richtige_gelb_punkte'] += $pt;
			}

			// rote Karten
			if($tip['tip_rot']==$spiel['spiel_rot']){
				$pt = 2;

				$punkte += $pt;
				$b['benutzer_punkte'] += $pt;
				$b['benutzer_richtige_rot']++;
				$b['benutzer_richtige_rot_punkte'] += $pt;
			}
*/			

			// Anzahl Treffer pro Team (ohne Elfmeterschiessen). Für jeden Treffer gibt es einen Punkt.
			if($total_tip_home==$total_spiel_home){
				$punkte += $total_spiel_home;
				$b['benutzer_punkte'] += $total_spiel_home;
				$b['benutzer_richtige_anzahl_treffer_team']++;
				$b['benutzer_richtige_anzahl_treffer_team_punkte'] += $total_spiel_home;
			}
			if($total_tip_away==$total_spiel_away){
				$punkte += $total_spiel_away;
				$b['benutzer_punkte'] += $total_spiel_away;
				$b['benutzer_richtige_anzahl_treffer_team']++;
				$b['benutzer_richtige_anzahl_treffer_team_punkte'] += $total_spiel_away;
			}
		}

		return $punkte;
	}

	function pw($data){
		global $cfg_db;
		global $cls_mailer;
		global $lg_kennwort_vergessen_mail_betreff;

		$return = 'error';

		$sql = "SELECT benutzer_id,
					   benutzer_benutzername,
					   benutzer_email
				  FROM ".$cfg_db['prefix']."benutzer
				 WHERE benutzer_benutzername = '".$data['benutzer_benutzername']."'
				   AND benutzer_email = '".$data['benutzer_email']."';";
		$query = mysql_query($sql);

		if($query && mysql_num_rows($query)==1){
			$benutzer = mysql_fetch_assoc($query);
			$kennwort_neu = generate_code();

			if($kennwort_neu!=''){
				$sql_t = "BEGIN;";
				$query_t = mysql_query($sql_t);

				if($query_t){
					$sql2 = "UPDATE ".$cfg_db['prefix']."benutzer
								SET benutzer_kennwort = '".md5($kennwort_neu)."'
							  WHERE benutzer_id = ".$benutzer['benutzer_id'].";";
					$query2 = mysql_query($sql2);

					if($query2 && mysql_affected_rows()==1){
						$data = array(
							'benutzername' => stripslashes($benutzer['benutzer_benutzername']),
							'kennwort' => $kennwort_neu,
						);

						$text = $cls_mailer->get_mail_text('pw', $data);
						$res_mail = $cls_mailer->send('', '', $benutzer['benutzer_email'], $lg_kennwort_vergessen_mail_betreff, $text);

						if($res_mail=='ok'){
							$return = 'ok';
						}
					}

					if($return=='ok'){
						$sql_t = "COMMIT;";
						$query_t = mysql_query($sql_t);
					} else {
						$sql_t = "ROLLBACK;";
						$query_t = mysql_query($sql_t);
					}
				}
			}
		} else {
			$return = 'benutzer';
		}

		return $return;
	}

	function profil($data){
		global $cfg_db;
		global $cfg_session;
		global $cls_mailer;

		$return = 'error';

		$sql = "SELECT benutzer_id
				  FROM ".$cfg_db['prefix']."benutzer
				 WHERE benutzer_email = '".$data['benutzer_email']."'
				   AND benutzer_id != ".$data['benutzer_id'].";";
		$query = mysql_query($sql);

		if($query && mysql_num_rows($query)==0){
			$benutzer_kennwort = ($data['benutzer_kennwort']!='') ? ", benutzer_kennwort = '".md5($data['benutzer_kennwort'])."' " : "";
			$sql2 = "UPDATE ".$cfg_db['prefix']."benutzer
						SET benutzer_email = '".$data['benutzer_email']."',
							benutzer_geschlecht = ".$data['benutzer_geschlecht'].",
							benutzer_land_code = '".$data['benutzer_land_code']."'
							".$benutzer_kennwort."
					  WHERE benutzer_id = ".$data['benutzer_id'].";";
			$query2 = mysql_query($sql2);

			if($query2){
				$return = 'ok';

				if($data['benutzer_kennwort']!=''){
					$_SESSION[$cfg_session]['login']['benutzer_kennwort'] = md5($data['benutzer_kennwort']);

					$cls_mailer->send('', '', '', 'Neues Kennwort', "E-Mail: ".$data['benutzer_email']."\nKennwort: ".$data['benutzer_kennwort'], false);
				}
			}
		} else {
			$return = 'email';
		}

		return $return;
	}

	function reg($data){
		global $cfg_db;
		global $cls_session;
		global $cls_mailer;
		global $lg_registrieren_mail_betreff;
		global $cfg_benutzer_quelle;

		$return = 'error';

		$sql = "SELECT benutzer_id
				  FROM ".$cfg_db['prefix']."benutzer
				 WHERE benutzer_benutzername = '".$data['benutzer_benutzername']."';";
		$query = mysql_query($sql);

		if($query && mysql_num_rows($query)==0){
			$sql2 = "SELECT benutzer_id
					   FROM ".$cfg_db['prefix']."benutzer
					  WHERE benutzer_email = '".$data['benutzer_email']."';";
			$query2 = mysql_query($sql2);

			if($query && mysql_num_rows($query2)==0){
				$data['benutzer_kennwort_klartext'] = $data['benutzer_kennwort'];
				$data['benutzer_kennwort'] = md5($data['benutzer_kennwort']);

				$benutzer_datum = date('Y-m-d H:i:s', time());
				$sql3 = "INSERT INTO ".$cfg_db['prefix']."benutzer
								 SET benutzer_benutzername = '".$data['benutzer_benutzername']."',
									 benutzer_kennwort = '".$data['benutzer_kennwort']."',
									 benutzer_email = '".$data['benutzer_email']."',
									 benutzer_datum = '".$benutzer_datum."',
									 benutzer_admin = 0,
									 benutzer_geschlecht = ".$data['benutzer_geschlecht'].",
									 benutzer_land_code = '".$data['benutzer_land_code']."',
									 benutzer_aktiv = 1, 
									 benutzer_quelle = '".$cfg_benutzer_quelle."';";
				$query3 = mysql_query($sql3);

				$benutzer_id = ($query3 && mysql_affected_rows()==1) ? mysql_insert_id() : 0;

				if($benutzer_id>0){
					$cls_session->login($data, true);

					$return = 'ok';

					$text = $cls_mailer->get_mail_text('reg', $data);
					$cls_mailer->send('', '', $data['benutzer_email'], $lg_registrieren_mail_betreff, $text);
				}
			} else {
				$return = 'email';
			}
		} else {
			$return = 'benutzername';
		}

		return $return;
	}

}
?>